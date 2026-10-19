<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Handles every "Get Your Quote" / contact form on the site:
 * saves the request, emails the sales team and shows the thank-you page.
 */
class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Bots fill the hidden "website" field; pretend it worked.
        if (filled($request->input('website'))) {
            return $this->thankYou($request);
        }

        // Some forms ask for first and last name separately
        if (filled($request->input('last_name'))) {
            $request->merge(['name' => trim($request->input('name').' '.$request->input('last_name'))]);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'required_without:phone', 'email', 'max:150'],
            'phone' => ['nullable', 'required_without:email', 'string', 'max:50'],
            'company' => ['nullable', 'string', 'max:150'],
            'service' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:3000'],
        ], [
            'email.required_without' => 'Please give us an email or a phone number.',
            'phone.required_without' => 'Please give us a phone number or an email.',
        ]);

        $lead = Lead::create($data + [
            'page' => substr((string) $request->headers->get('referer'), 0, 255),
            'ip' => $request->ip(),
        ]);

        try {
            Mail::raw($this->emailBody($lead), function ($mail) use ($lead) {
                $mail->to(config('site.lead_email'))
                    ->subject('New demo request: '.$lead->name.($lead->company ? ' ('.$lead->company.')' : ''));
                if ($lead->email) {
                    $mail->replyTo($lead->email, $lead->name);
                }
            });
        } catch (\Throwable $e) {
            // The lead is already saved; a mail problem must not lose it or show an error.
            Log::error('Lead email failed', ['lead' => $lead->id, 'error' => $e->getMessage()]);
        }

        return $this->thankYou($request);
    }

    private function thankYou(Request $request): RedirectResponse
    {
        // RedirectResponse directly: redirect() would drop the trailing slash
        return new RedirectResponse($request->getSchemeAndHttpHost().$request->getBaseUrl().'/thank-you/', 303);
    }

    private function emailBody(Lead $lead): string
    {
        return implode("\n", [
            'New request from the website',
            '',
            'Name:    '.$lead->name,
            'Email:   '.($lead->email ?: '-'),
            'Phone:   '.($lead->phone ?: '-'),
            'Company: '.($lead->company ?: '-'),
            'Service: '.($lead->service ?: '-'),
            '',
            'Message:',
            $lead->message ?: '-',
            '',
            'Page: '.($lead->page ?: '-'),
            'Received: '.$lead->created_at->format('d M Y H:i'),
        ]);
    }
}

<?php

namespace App\Mcp\Tools;

use App\Mcp\ToolSet;
use App\Models\Lead;

/** Enquiries from the website forms: read-only. */
class LeadTools extends ToolSet
{
    public function tools(): array
    {
        return [
            $this->read('list_enquiries', 'Enquiries sent through the website forms (hero demo form, quick demo forms, quote/contact forms, call-back card, Setup Builder), newest first. Read-only: enquiries cannot be changed or deleted here.', [
                'form' => self::e('Only one kind', ['setup_builder', 'demo', 'all']),
                'since' => self::s('Only after this date (YYYY-MM-DD)'),
                'search' => self::s('Text in name, company, service or message'),
                'limit' => self::i('Default 50, max 200'),
            ], [], fn ($a) => $this->list($a)),
        ];
    }

    private function list(array $a): array
    {
        $q = Lead::query()
            ->when(($a['form'] ?? 'all') === 'setup_builder', fn ($q) => $q->where('service', 'like', 'Setup builder:%'))
            ->when(($a['form'] ?? 'all') === 'demo', fn ($q) => $q->where(fn ($q) => $q->whereNull('service')->orWhere('service', 'not like', 'Setup builder:%')))
            ->when($a['since'] ?? null, fn ($q, $v) => $q->where('created_at', '>=', $v))
            ->when($a['search'] ?? null, fn ($q, $v) => $q->where(fn ($q) => $q->where('name', 'like', "%$v%")->orWhere('company', 'like', "%$v%")->orWhere('service', 'like', "%$v%")->orWhere('message', 'like', "%$v%")));
        $total = (clone $q)->count();

        return [
            'total' => $total,
            'enquiries' => $q->latest('id')->limit(min(200, (int) ($a['limit'] ?? 50)))->get()->map(fn ($l) => [
                'id' => $l->id, 'received' => $l->created_at?->toIso8601String(), 'name' => $l->name, 'email' => $l->email, 'phone' => $l->phone,
                'company' => $l->company, 'service' => $l->service, 'message' => $l->message, 'page' => $l->page,
            ])->all(),
        ];
    }
}

@extends('layouts.app')

@section('title', 'Support Policy | MultiTech POS')
@section('description', 'MultiTech POS support policy: support channels, response times and what is covered for UAE businesses using our POS and ERP software.')
@section('og_type', 'article')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Support Policy', 'url' => $current]]])

<article class="page-article">
    <h1 class="page-article__title">Support Policy</h1>
    <div class="page-article__content prose">
        <p><strong>Last Updated: June 24, 2026</strong></p>
        <h2><strong>Introduction</strong></h2>
        <p>At MultiTech POS, we know your business depends on your point-of-sale system running smoothly — every single day. Whether you’re running a pharmacy, restaurant, salon, gold shop, tailor shop, retail store, or distribution business, our support team is here to keep your operations running without interruption.</p>
        <p>This Support Policy explains how to reach us, what’s covered under standard support, and what you can expect in terms of response times.</p>
        <h2><strong>Scope of Support</strong></h2>
        <p>Our support team assists with:</p>
        <ul>
        <li>POS software functionality and configuration</li>
        <li>Inventory, billing, and sales module questions</li>
        <li>Employee management and user access setup</li>
        <li>Reports, reconciliation, and end-of-day closing issues</li>
        <li>Printer, barcode scanner, and peripheral connectivity (software-side)</li>
        <li>Software updates and version-related issues</li>
        <li>Account, licensing, and subscription questions</li>
        <li>Industry-specific module support (e.g., salon booking, gold shop tracking, tailor order management, van sales routing, laundry and restaurant management)</li></ul>
        <p><strong>Not covered under standard support:</strong></p>
        <ul>
        <li>Hardware failure or replacement (printers, scanners, POS terminals, cash drawers) — we can advise but repairs/replacement are the customer’s responsibility unless covered under a separate AMC</li>
        <li>Internet/network connectivity issues at the customer’s location</li>
        <li>Custom feature development or third-party integrations not included in the original scope</li>
        <li>Data loss caused by unauthorized software modifications or use of unlicensed/pirated versions</li>
        <li>On-site visits (available as a separate paid service)</li></ul>
        <h2><strong>Support Channels</strong></h2>
        <figure><table>
        <tbody>
        <tr>
        <td><strong>Channel</strong></td><td><strong>Best For</strong></td><td><strong>Availability</strong></td></tr>
        <tr>
        <td>Phone: +971 58 897 9925</td><td>Urgent issues, system down, time-sensitive problems</td><td>Sat–Thu, 9:00 AM–6:00 PM (GST); Friday closed</td></tr>
        <tr>
        <td>WhatsApp:<a href="https://api.whatsapp.com/send?phone=971588979925"> +971 58 897 9925</a></td><td>Quick questions, screenshots, fast troubleshooting</td><td>Sat–Thu, 9:00 AM–6:00 PM (GST)</td></tr>
        <tr>
        <td>Email: support@multitechpos.com</td><td>Detailed issues, billing, documentation requests</td><td>Response within 1 business day</td></tr>
        <tr>
        <td>Online Form:<a href="{{ $base }}/contact/"> multitechpos.com/contact</a></td><td>Quote requests, demo requests, general inquiries</td><td>24/7 submission, reviewed during business hours</td></tr>
        </tbody>
        </table>
        </figure>
        <p>For critical system-down issues outside business hours, please send a WhatsApp message and our team will respond at the earliest opportunity.</p>
        <h2><strong>Severity Levels &amp; Response Times</strong></h2>
        <figure><table>
        <tbody>
        <tr>
        <td><strong>Severity</strong></td><td><strong>Examples</strong></td><td><strong>Target Response Time</strong></td></tr>
        <tr>
        <td><strong>Critical</strong></td><td>POS system down, unable to process sales, data not syncing</td><td>Within 1–2 hours (during business hours)</td></tr>
        <tr>
        <td><strong>High</strong></td><td>Module malfunction affecting daily operations (e.g., billing errors, stock discrepancies)</td><td>Within the same business day</td></tr>
        <tr>
        <td><strong>Medium</strong></td><td>Report inaccuracies, minor configuration issues</td><td>Within 1 business day</td></tr>
        <tr>
        <td><strong>Low</strong></td><td>General how-to questions, feature requests</td><td>Within 2 business days</td></tr>
        </tbody>
        </table>
        </figure>
        <p>Resolution times depend on issue complexity, but we’ll keep you updated at every stage.</p>
        <h2><strong>Support Plans</strong></h2>
        <figure><table>
        <tbody>
        <tr>
        <td><strong>Plan</strong></td><td><strong>Coverage</strong></td></tr>
        <tr>
        <td><strong>Standard Support</strong></td><td>Included with active subscription/license — phone, WhatsApp, and email support during business hours, software updates, and bug fixes</td></tr>
        <tr>
        <td><strong>Annual Maintenance Contract (AMC)</strong></td><td>Extended coverage including on-site visits, hardware troubleshooting, and preventive maintenance — billed separately</td></tr>
        <tr>
        <td><strong>Custom Development</strong></td><td>New features, integrations, or modifications outside the original scope are quoted and billed separately</td></tr>
        </tbody>
        </table>
        </figure>
        <h2><strong>Customer Responsibilities</strong></h2>
        <p>To help us resolve issues quickly, customers are asked to:</p>
        <ul>
        <li>Keep subscription/license payments current</li>
        <li>Use only licensed, up-to-date versions of the software</li>
        <li>Provide accurate details when reporting an issue (screenshots, error messages, steps taken)</li>
        <li>Ensure basic hardware (printers, scanners, terminals) is properly connected and powered</li>
        <li>Maintain a stable internet connection where required for cloud-based features</li>
        <li>Grant remote access (e.g., via TeamViewer/AnyDesk) when needed for troubleshooting</li></ul>
        <h2><strong>Exclusions</strong></h2>
        <p>MultiTech POS support does not cover:</p>
        <ul>
        <li>Issues arising from unauthorized modifications, third-party plugins, or unlicensed software copies</li>
        <li>Data loss due to customer-side hardware failure, theft, or lack of backups</li>
        <li>Problems caused by incompatible or outdated hardware not recommended by MultiTech POS</li>
        <li>Support requests for accounts with overdue or suspended subscriptions</li></ul>
        <h2><strong>Escalation Process</strong></h2>
        <p>If your issue isn’t resolved through standard support:</p>
        <ol>
        <li>Request escalation with your support representative via WhatsApp or email at support@multitechpos.com</li>
        <li>Your case will be reviewed by a senior support lead within 1 business day</li>
        <li>For unresolved critical issues, you may request direct contact with our Operations team for further assistance</li></ol>
        <h2><strong>Maintenance &amp; Updates</strong></h2>
        <p>Planned system maintenance or software updates will be communicated in advance via WhatsApp, email, or an in-app notice. We aim to schedule updates during low-business hours to minimize disruption to your operations.</p>
        <h2><strong>Policy Updates</strong></h2>
        <p>This Support Policy may be revised periodically to reflect changes in our services. The “Last Updated” date above reflects the most recent revision. Continued use of MultiTech POS constitutes acceptance of the current policy.</p>
        <h2><strong>Contact Us</strong></h2>
        <p><strong>MultiTech POS</strong> Address: 16 Al Isayfir 2 St, Musaffah, M33, Abu Dhabi, UAE Phone:<a href="https://claude.ai/chat/37b3c114-f9b0-49c8-bfb9-d6caebc3f7a5"> +971 58 897 9925</a> WhatsApp:<a href="https://api.whatsapp.com/send?phone=971588979925"> +971 58 897 9925</a> Email: support@multitechpos.com Website:<a href="{{ $base }}/contact/"> multitechpos.com/contact</a></p>
    </div>
</article>
@endsection

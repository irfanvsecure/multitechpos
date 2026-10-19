@extends('layouts.app')

@section('title', 'Privacy Policy | MultiTech POS')
@section('description', 'How MultiTech POS collects, uses and protects personal information when you use multitechpos.com and our POS and ERP software.')
@section('og_type', 'article')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Privacy Policy', 'url' => $current]]])

<article class="page-article">
    <h1 class="page-article__title">Privacy Policy</h1>
    <div class="page-article__content prose">
        <p><strong>Last Updated: June 22, 2026</strong></p>
        <p>MultiTech POS (“MultiTech,” “we,” “us,” or “our”) operates the website {{ $base }}/ and provides point-of-sale (POS) software and ERP systems for retail, restaurant, supermarket, pharmacy, salon, and other businesses across the UAE and Middle East, including industry-specific solutions (RestroPro, SalonPro, LaundryPro, TailorPro) and supporting hardware.</p>
        <p>This Privacy Policy explains how we collect, use, disclose, and protect information when you visit our website, request a demo, contact our team, or use our POS/ERP software. By using our website or services, you agree to the practices described in this policy.</p>
        <h2><strong>1. Information We Collect</strong></h2>
        <h3><strong>a) Information You Provide to Us</strong></h3>
        <ul>
        <li><strong>Demo and enquiry requests</strong>: name, business name, phone number, email address, and any details submitted via our “Request Free Demo” / “Contact” forms, WhatsApp, or phone calls.</li>
        <li><strong>Customer/account information</strong>: if you become a client, we collect business registration details, billing information, contact details, and software licensing/activation information.</li>
        <li><strong>Support communications</strong>: details you provide when contacting our support team, including issue descriptions and screenshots.</li></ul>
        <h3><strong>b) Information Collected Through Use of the Software</strong></h3>
        <p>Our POS/ERP software is used by businesses to manage their own sales, inventory, staff, and customers. As part of this functionality, the software may process:</p>
        <ul>
        <li>Sales transactions, VAT/FTA-compliant invoices, inventory, and supplier records entered by the business using the software.</li>
        <li>Customer records that our business clients (the “Account Holder”) choose to store within the POS system, such as customer names, phone numbers, and loyalty/purchase history, for the purpose of operating their own point-of-sale system.</li>
        <li>Employee management data entered by the Account Holder, such as staff names, roles, access levels, and attendance/payroll records.</li></ul>
        <p><strong>Important note on roles</strong>: Where our software is used by a business to store its own customers’ or employees’ data, that business acts as the <strong>data controller</strong> for that information, and MultiTech acts as a <strong>data processor/service provider</strong>, processing such data only to provide the software’s functionality, as instructed by the Account Holder. If you are an end-customer of a business using MultiTech POS, please refer to that business’s own privacy policy.</p>
        <h3><strong>c) Information Collected Automatically</strong></h3>
        <ul>
        <li><strong>Website usage data</strong>: IP address, browser type, device type, pages visited, and referring pages, collected through standard web server logs and analytics tools.</li>
        <li><strong>Cookies</strong>: used to remember preferences, understand site usage, and improve performance (see Section 5).</li></ul>
        <h2><strong>2. How We Use Your Information</strong></h2>
        <p>We use the information we collect to:</p>
        <ul>
        <li>Respond to demo requests, enquiries, and support tickets.</li>
        <li>Provide, install, activate, and maintain our POS/ERP software and related hardware.</li>
        <li>Generate VAT-compliant invoicing and reporting in line with UAE FTA requirements.</li>
        <li>Communicate with you about updates, billing, or service-related notices.</li>
        <li>Improve our website, software, and customer support.</li>
        <li>Send marketing communications about our products, where you have not opted out.</li>
        <li>Comply with legal and regulatory obligations in the UAE.</li></ul>
        <p>We do not sell your personal information to third parties.</p>
        <h2><strong>3. How We Share Your Information</strong></h2>
        <p>We may share information with:</p>
        <ul>
        <li><strong>Service providers</strong>: hosting providers, payment processors, SMS/WhatsApp gateway providers (for receipts, OTPs, or customer notifications within the software), and IT support contractors, under confidentiality obligations.</li>
        <li><strong>Hardware and installation partners</strong>: where necessary to deliver, install, or service POS hardware at your premises.</li>
        <li><strong>Legal and regulatory authorities</strong>: including the UAE Federal Tax Authority (FTA), where required for VAT compliance or by law.</li>
        <li><strong>Business transfers</strong>: in the event of a merger, acquisition, or sale of assets, subject to confidentiality protections.</li></ul>
        <p>We do not share your information with third parties for their own independent marketing purposes without your consent.</p>
        <h2><strong>4. Data Security</strong></h2>
        <p>We implement reasonable administrative, technical, and physical safeguards to protect information from unauthorized access, alteration, disclosure, or destruction, including access controls and restricted access to customer and business data. However, no method of transmission or storage is 100% secure, and we cannot guarantee absolute security.</p>
        <p>Account Holders using MultiTech POS software are responsible for maintaining the security of their own login credentials and configurable staff access levels.</p>
        <h2><strong>5. Cookies and Tracking Technologies</strong></h2>
        <p>Our website uses cookies to remember your preferences, analyze website traffic, and improve site functionality. You can control or disable cookies through your browser settings. Disabling cookies may affect certain website features but will not affect your ability to use MultiTech POS software.</p>
        <h2><strong>6. Data Retention</strong></h2>
        <p>We retain personal information for as long as necessary to:</p>
        <ul>
        <li>Provide ongoing services and support to you.</li>
        <li>Comply with UAE tax, accounting, and regulatory record-keeping obligations (including FTA requirements).</li>
        <li>Resolve disputes and enforce our agreements.</li></ul>
        <p>Business and transaction data stored within MultiTech POS software by an Account Holder is retained according to that business’s own data retention practices and the software’s storage configuration.</p>
        <p><strong>7. Your Rights</strong></p>
        <p>Depending on applicable law, you may have the right to:</p>
        <ul>
        <li>Request access to the personal information we hold about you.</li>
        <li>Request correction of inaccurate or incomplete information.</li>
        <li>Request deletion of your personal information, subject to legal/regulatory record-keeping obligations.</li>
        <li>Withdraw consent to marketing communications at any time.</li></ul>
        <p>To exercise any of these rights, please contact us using the details in Section 10. If you are an end-customer of a business using MultiTech POS, please contact that business directly, as they control their own customer records.</p>
        <h2><strong>8. Children’s Privacy</strong></h2>
        <p>Our website and software are intended for business use and are not directed at children under 18. We do not knowingly collect personal information from children. If you believe a child’s information has been collected, please contact us so we can remove it.</p>
        <p><strong>9. Changes to This Privacy Policy</strong></p>
        <p>We may update this Privacy Policy from time to time to reflect changes in our practices, software features, or legal requirements. The “Last Updated” date above indicates the most recent revision. Continued use of our website or software after changes are posted constitutes your acceptance of the revised policy.</p>
        <h2><strong>10. Contact Us</strong></h2>
        <p>If you have questions, concerns, or requests regarding this Privacy Policy or how your information is handled, please contact us:</p>
        <p><strong>MultiTech POS</strong> 16 Al Isayfir 2 St, Musaffah, M33, Abu Dhabi, UAE Phone/WhatsApp: +971 58 897 9925 Email: support@multitechpos.com Website: {{ $base }}/</p>
    </div>
</article>
@endsection

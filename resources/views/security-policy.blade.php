@extends('layouts.app')

@section('title', 'Security Policy | MultiTech POS')
@section('description', 'How MultiTech POS protects customer data in our POS and ERP software: access controls, encryption, backups and incident response.')
@section('og_type', 'article')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Security Policy', 'url' => $current]]])

<article class="page-article">
    <h1 class="page-article__title">Security Policy</h1>
    <div class="page-article__content prose">
        <p><strong>MultiTech POS</strong> <em>Last Updated: June 2026</em></p>
        <h2><strong>1. Our Commitment to Security</strong></h2>
        <p>At MultiTech POS, we provide point-of-sale and ERP software solutions to businesses across pharmacy, restaurant, retail, salon, gold shop, tailoring, distribution, and other industries throughout the UAE. We understand that our customers rely on our platform to manage sensitive business operations, including sales transactions, inventory, employee data, and financial records. Protecting this information is a core part of how we build and operate our software.</p>
        <p>This Security Policy describes the measures we take to safeguard customer data and the practices we expect from our team, partners, and users. It applies to our website (multitechpos.com), our POS/ERP software and associated mobile and desktop applications, our internal systems, employees, contractors, and any third parties who process data on our behalf.</p>
        <h2><strong>2. Data We Protect</strong></h2>
        <p>In delivering our POS and ERP services, we may collect and process:</p>
        <ul>
        <li>Business account and merchant registration details</li>
        <li>Sales, transaction, and inventory data</li>
        <li>Employee and payroll-related information entered into the system</li>
        <li>Customer data entered by merchants (e.g., loyalty or contact details)</li>
        <li>Billing and subscription payment information</li>
        <li>Website usage and analytics data</li></ul>
        <p>We collect only the data necessary to operate our platform and do not sell merchant or customer data to third parties.</p>
        <h2><strong>3. Data Protection Measures</strong></h2>
        <ul>
        <li><strong>Encryption:</strong> Data transmitted between your device and our servers is encrypted using industry-standard TLS/SSL protocols. Sensitive data stored within our systems, including financial and transactional records, is protected using encryption at rest where applicable.</li>
        <li><strong>Access Controls:</strong> Access to merchant data is restricted to authorized personnel based on role and necessity. Within the MultiTech POS platform itself, business owners can configure role-based access for their own staff (e.g., cashier vs. manager permissions).</li>
        <li><strong>Authentication:</strong> We enforce strong password requirements for account access, and multi-factor authentication is used to protect access to critical internal systems.</li>
        <li><strong>Regular Backups:</strong> Merchant data is backed up on a regular schedule to protect against data loss and support business continuity, with secure recovery procedures in place.</li></ul>
        <h2><strong>4. Infrastructure &amp; Application Security</strong></h2>
        <ul>
        <li>Our servers and infrastructure are protected by firewalls and continuously monitored for unauthorized access attempts.</li>
        <li>Software, plugins, and server components are kept up to date with security patches to address known vulnerabilities.</li>
        <li>We employ safeguards against Distributed Denial of Service (DDoS) attacks and other common web-based threats.</li>
        <li>Our applications undergo periodic internal security review as part of our development lifecycle, including testing of new POS modules before release.</li>
        <li>Database access from our applications follows secure connection practices, with credentials and API keys managed separately from application code.</li></ul>
        <h2><strong>5. Incident Response</strong></h2>
        <p>In the event of a suspected or confirmed security incident affecting merchant or customer data, MultiTech POS will:</p>
        <ol>
        <li>Promptly investigate and contain the incident.</li>
        <li>Assess the scope and impact of any data exposure.</li>
        <li>Notify affected customers without undue delay, along with guidance on any steps they should take.</li>
        <li>Cooperate with relevant authorities where required by law.</li>
        <li>Implement corrective measures to prevent recurrence.</li></ol>
        <h2><strong>6. Third-Party &amp; Vendor Security</strong></h2>
        <p>MultiTech POS may work with select third-party providers, such as cloud hosting providers, payment gateway processors, and SMS/communication services, to deliver platform functionality. We assess these providers for appropriate security practices and limit the data shared with them to what is necessary for them to perform their function. Payment card data, where applicable, is handled in line with industry practices for secure payment processing.</p>
        <h2><strong>7. Vulnerability Disclosure</strong></h2>
        <p>We welcome reports from security researchers, partners, and customers who identify potential vulnerabilities in our website or software. If you believe you have discovered a security issue, please contact us at:</p>
        <p><strong>Email:</strong> security@multitechpos.com</p>
        <p>Please include as much detail as possible, such as the affected module and steps to reproduce the issue. We ask that you avoid public disclosure until we have had a reasonable opportunity to investigate and resolve the matter. We are committed to acknowledging valid reports promptly and addressing them in good faith.</p>
        <h2><strong>8. Your Responsibilities</strong></h2>
        <p>As a business using MultiTech POS, we recommend you:</p>
        <ul>
        <li>Use a strong, unique password for your MultiTech POS account and change it periodically.</li>
        <li>Configure appropriate staff-level permissions within the system rather than sharing admin credentials broadly.</li>
        <li>Log out of POS terminals and admin dashboards when unattended, especially on shared devices.</li>
        <li>Keep your devices and local network secured, as POS hardware is often deployed in public-facing retail environments.</li>
        <li>Report any suspicious account activity or unauthorized access attempts to our support team immediately.</li></ul>
        <h2><strong>9. Compliance</strong></h2>
        <p>MultiTech POS operates in accordance with applicable UAE data protection regulations and strives to align our data handling practices with recognized international data protection principles. Where our platform supports VAT-compliant invoicing and reporting features, these are designed to assist merchants with their own regulatory obligations under UAE Federal Tax Authority requirements.</p>
        <p>This policy does not constitute legal advice and does not guarantee specific regulatory certifications. Merchants with industry-specific compliance requirements (e.g., pharmacy or healthcare-related regulations) should consult their own legal or compliance advisors regarding their use of our platform.</p>
        <h2><strong>10. Policy Updates</strong></h2>
        <p>We may revise this Security Policy periodically to reflect changes in our practices, technology, or applicable legal requirements. The “Last Updated” date at the top of this page reflects the most recent revision. We encourage merchants to review this page from time to time.</p>
        <h2><strong>11. Contact Us</strong></h2>
        <p>If you have questions or concerns about this Security Policy or our data security practices, please contact us:</p>
        <p><strong>MultiTech POS</strong> Email: security@multitechpos.com Website: {{ $base }}</p>
    </div>
</article>
@endsection

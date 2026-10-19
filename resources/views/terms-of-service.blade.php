@extends('layouts.app')

@section('title', 'Terms of Service | MultiTech POS')
@section('description', 'The terms that govern your use of multitechpos.com and MultiTech POS software and services.')
@section('og_type', 'article')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Terms of Service', 'url' => $current]]])

<article class="page-article">
    <h1 class="page-article__title">Terms of Service</h1>
    <div class="page-article__content prose">
        <p><strong>Effective Date:</strong> 6/24/2026 <strong>Last Updated:</strong> 6/24/2026</p>
        <h2><strong>1. Acceptance of Terms</strong></h2>
        <p>These Terms of Service (“Terms”) govern your access to and use of the website located at multitechpos.com (the “Site”) and the MultiTech POS software-as-a-service platform, including all related modules, mobile applications, and support services (collectively, the “Service”), provided by MultiTech POS (“Company,” “we,” “us,” or “our”).</p>
        <p>By accessing the Site, signing up for a free trial, or subscribing to the Service, you (“Customer,” “you,” or “your”) agree to be bound by these Terms and our Privacy Policy. If you do not agree, you must not access or use the Service. If you are entering into these Terms on behalf of a business or other legal entity, you represent that you have the authority to bind that entity.</p>
        <h2><strong>2. Description of Service</strong></h2>
        <p>MultiTech POS provides a cloud-based point-of-sale and business management platform designed for retail stores, restaurants, salons and spas, pharmacies, gold shops, tailoring businesses, distribution operations, and other verticals. Features may include, depending on your subscription plan: sales and inventory management, employee management, accounting and invoicing, van sales, customer loyalty tools, and reporting/analytics.</p>
        <p>We reserve the right to modify, add, or remove features at our discretion, including in response to evolving business or regulatory requirements, provided that we will not materially reduce core functionality of a paid plan during an active subscription term without reasonable notice.</p>
        <h2><strong>3. Eligibility and Account Registration</strong></h2>
        <p>You must be at least 18 years old and have the authority to enter into a binding agreement on behalf of yourself or your business to use the Service. When you register for an account, you agree to:</p>
        <ul>
        <li>Provide accurate, current, and complete business and contact information</li>
        <li>Maintain the confidentiality of your login credentials</li>
        <li>Be responsible for all activity that occurs under your account</li>
        <li>Notify us immediately of any unauthorized access or security breach</li></ul>
        <p>You are responsible for managing user permissions for any employees or staff you grant access to your account, and for ensuring their compliance with these Terms.</p>
        <h2><strong>4. Free Trial</strong></h2>
        <p>We may offer a free trial period for new customers. At the end of the trial period, your account will automatically convert to a paid subscription unless you cancel before the trial expires, or unless otherwise stated at the time of sign-up. We reserve the right to modify or discontinue the free trial offer, limit trial eligibility, or end a trial early in cases of suspected abuse or fraud, at our discretion.</p>
        <p>Data entered during the trial period will be retained if you convert to a paid subscription, but may be deleted if you do not convert within [Insert grace period, e.g., 30 days] of trial expiration.</p>
        <h2><strong>5. Subscription Plans, Fees, and Payment</strong></h2>
        <p><strong>5.1 Subscription Plans.</strong> The Service is offered under various subscription tiers, with features and pricing as described on the Site or in your service agreement. We reserve the right to change pricing for future billing cycles with at least thirty (30) days’ notice.</p>
        <p><strong>5.2 Billing.</strong> Subscription fees are billed in advance on a recurring basis (monthly or annually, as selected) via the payment method on file. By providing payment information, you authorize us to charge all applicable fees to that payment method.</p>
        <p><strong>5.3 Taxes.</strong> All fees are exclusive of applicable VAT or other taxes, which will be added to your invoice as required by UAE law.</p>
        <p><strong>5.4 Non-Payment.</strong> Failure to pay subscription fees on time may result in suspension or restriction of access to the Service. We may also restrict access to your data (without deleting it) until outstanding amounts are settled, subject to the data retention terms in Section 11.</p>
        <p><strong>5.5 Refunds.</strong> Except as required by law or expressly stated in a separate refund policy, subscription fees are non-refundable, including for partial billing periods.</p>
        <p><strong>5.6 Cancellation.</strong> You may cancel your subscription at any time through your account settings or by contacting support. Cancellation will take effect at the end of your current billing cycle; we do not provide prorated refunds for early cancellation.</p>
        <h2><strong>6. Customer Data and Responsibilities</strong></h2>
        <p><strong>6.1 Your Data.</strong> You retain ownership of all business, transaction, customer, and inventory data you input into the Service (“Customer Data”). You are solely responsible for the accuracy, legality, and completeness of Customer Data, including compliance with applicable tax, consumer protection, and data protection laws relevant to your business and customers.</p>
        <p><strong>6.2 Backups.</strong> While we maintain reasonable backup and data protection practices, you are responsible for maintaining your own backups of critical business data where feasible.</p>
        <p><strong>6.3 Acceptable Use.</strong> You agree not to use the Service to:</p>
        <ul>
        <li>Violate any applicable law or regulation, including UAE consumer protection and tax laws</li>
        <li>Process fraudulent transactions or evade taxes</li>
        <li>Upload malicious code, attempt unauthorized access, or interfere with the Service’s operation or other users’ access</li>
        <li>Reverse-engineer, decompile, or attempt to extract the source code of the Service, except as permitted by law</li>
        <li>Resell, sublicense, or provide third-party access to the Service without our written consent</li></ul>
        <h2><strong>7. Intellectual Property</strong></h2>
        <p>The Service, including all software, designs, logos, trademarks, and underlying technology, is owned by MultiTech POS or its licensors and is protected by applicable intellectual property laws. Subject to your compliance with these Terms, we grant you a limited, non-exclusive, non-transferable license to access and use the Service for your internal business operations during your subscription term. No other rights are granted.</p>
        <h2><strong>8. Third-Party Integrations</strong></h2>
        <p>The Service may integrate with third-party payment processors, accounting tools, e-commerce platforms, or government tax systems (e.g., FBR/PRA-style integrations where applicable). We are not responsible for the performance, availability, or security practices of third-party services, and your use of such integrations is subject to the third party’s own terms.</p>
        <h2><strong>9. Service Availability and Support</strong></h2>
        <p>We will use commercially reasonable efforts to maintain the availability and performance of the Service, but we do not guarantee uninterrupted or error-free operation. Scheduled maintenance, updates, or factors outside our control (including internet service provider issues or force majeure events) may result in temporary downtime.</p>
        <p>Support is provided according to the terms of your subscription plan. Specific service-level commitments, if any, will be set out in a separate Service Level Agreement (SLA) where applicable.</p>
        <h2><strong>10. Disclaimers and Limitation of Liability</strong></h2>
        <p><strong>10.1 Disclaimer.</strong> THE SERVICE IS PROVIDED “AS IS” AND “AS AVAILABLE,” WITHOUT WARRANTIES OF ANY KIND, WHETHER EXPRESS OR IMPLIED, INCLUDING WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, OR NON-INFRINGEMENT.</p>
        <p><strong>10.2 Limitation of Liability.</strong> To the maximum extent permitted by applicable law, MultiTech POS’s total liability arising out of or related to the Service shall not exceed the total fees paid by you in the twelve (12) months preceding the claim. We shall not be liable for indirect, incidental, special, consequential, or punitive damages, including loss of profits, revenue, data, or business opportunities, even if advised of the possibility of such damages.</p>
        <p><strong>10.3 Exceptions.</strong> Nothing in these Terms limits liability for fraud, willful misconduct, or any liability that cannot be excluded or limited under UAE law.</p>
        <h2><strong>11. Data Retention and Deletion</strong></h2>
        <p>Upon cancellation or termination of your subscription, we will retain your Customer Data for a limited period (typically [Insert period, e.g., 60 days]) to allow for reactivation or data export, after which it may be permanently deleted in accordance with our data retention policy. You are responsible for exporting any data you wish to retain prior to cancellation.</p>
        <h2><strong>12. Termination</strong></h2>
        <p>We may suspend or terminate your access to the Service for breach of these Terms, non-payment, suspected fraudulent activity, or extended inactivity, with notice where reasonably practicable. You may terminate your account at any time as described in Section 5.6. Provisions that by their nature should survive termination (including Sections 6, 7, 10, and 14) will remain in effect.</p>
        <h2><strong>13. Indemnification</strong></h2>
        <p>You agree to indemnify, defend, and hold harmless MultiTech POS, its officers, employees, and affiliates from any claims, damages, liabilities, and expenses (including reasonable legal fees) arising from: (a) your breach of these Terms; (b) your violation of applicable law; (c) Customer Data you input into the Service; or (d) disputes between you and your own customers or employees.</p>
        <h2><strong>14. Governing Law and Dispute Resolution</strong></h2>
        <p>These Terms are governed by the laws of the United Arab Emirates, as applied in the Emirate of Abu Dhabi, without regard to conflict of laws principles.</p>
        <p>Any dispute arising under or in connection with these Terms shall first be subject to good-faith negotiation between the parties. If unresolved within thirty (30) days, the dispute shall be referred to and finally resolved by arbitration in accordance with the rules of the Abu Dhabi Commercial Conciliation and Arbitration Centre (or the Abu Dhabi International Arbitration Centre, as applicable), seated in Abu Dhabi, UAE. Notwithstanding the foregoing, either party may seek injunctive or interim relief from the competent courts of Abu Dhabi where necessary to prevent irreparable harm.</p>
        <h2><strong>15. Changes to These Terms</strong></h2>
        <p>We may update these Terms from time to time. The “Last Updated” date will reflect the most recent revision. Material changes will be communicated to active subscribers via email or in-app notice. Continued use of the Service after changes take effect constitutes acceptance of the revised Terms.</p>
        <h2><strong>16. Severability</strong></h2>
        <p>If any provision of these Terms is found unenforceable, that provision will be limited or removed to the minimum extent necessary, and the remaining provisions will remain in full force and effect.</p>
        <h2><strong>17. Entire Agreement</strong></h2>
        <p>These Terms, together with any applicable order form, SLA, or service agreement, constitute the entire agreement between you and MultiTech POS regarding the Service and supersede all prior agreements or understandings, written or oral.</p>
        <h2><strong>18. Contact Information</strong></h2>
        <p>If you have questions about these Terms, please contact us at:</p>
        <p><strong>MultiTech POS</strong> Website: multitechpos.com Email: support@multitechpos.com Phone: +971 58 897 9925 Address:</p>
    </div>
</article>
@endsection

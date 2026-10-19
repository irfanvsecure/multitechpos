@extends('layouts.app')

@section('title', 'Cookie Policy | MultiTech POS')
@section('description', 'How MultiTech POS uses cookies and similar technologies on multitechpos.com, and how you can manage your cookie preferences.')
@section('og_type', 'article')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Cookie Policy', 'url' => $current]]])

<article class="page-article">
    <h1 class="page-article__title">Cookie Policy</h1>
    <div class="page-article__content prose">
        <p><strong>MultiTech POS</strong> <strong>Effective Date: June 22, 2026</strong> <strong>Last Updated: June 22, 2026</strong></p>
        <h2><strong>1. Introduction</strong></h2>
        <p>This Cookie Policy explains how MultiTech POS (“we,” “us,” or “our”) uses cookies and similar tracking technologies when you visit our website at {{ $base }}/ (the “Site”). This policy explains what these technologies are, why we use them, and the choices you have to control them.</p>
        <p>By continuing to browse or use our Site, you agree to our use of cookies as described in this Cookie Policy, unless you have adjusted your preferences through our cookie consent tool or your browser settings.</p>
        <h2><strong>2. What Are Cookies?</strong></h2>
        <p>Cookies are small text files placed on your computer, smartphone, or other device when you visit a website. They are widely used to make websites function properly, improve user experience, and provide useful information to website owners.</p>
        <p>Cookies set by the website owner (in this case, MultiTech POS) are called “first-party cookies.” Cookies set by parties other than the website owner are called “third-party cookies.” Third-party cookies enable third-party features or functionality to be provided on or through the website, such as analytics, live chat, and advertising.</p>
        <p>In addition to cookies, we may use similar tracking technologies such as web beacons, pixel tags, and local storage objects to collect information about how visitors use our Site.</p>
        <h2><strong>3. Why We Use Cookies</strong></h2>
        <p>We use cookies for several reasons. Some are required for technical reasons in order for our Site to operate, and we refer to these as “essential” or “strictly necessary” cookies. Others enable us to track and understand how visitors explore our POS software pages — including our pharmacy POS, restaurant POS, retail, salon, gold shop, tailor, distribution, bike shop, fashion, gift store, and van sales solutions — so we can improve content, demo requests, and overall site performance. Third parties may also serve cookies through our Site for analytics, advertising, and chat-support purposes.</p>
        <h2><strong>4. Types of Cookies We Use</strong></h2>
        <h3><strong>4.1 Strictly Necessary Cookies</strong></h3>
        <p>These cookies are essential for the Site to function properly and cannot be switched off in our systems. They are usually set in response to actions you take, such as setting privacy preferences, logging into a client/demo account, requesting a free trial, or submitting a contact or demo-request form. You can set your browser to block these cookies, but parts of the Site may not work as intended.</p>
        <p>Examples of use: session management, security and fraud prevention, load balancing, and maintaining form submission state for demo and trial requests.</p>
        <h3><strong>4.2 Performance and Analytics Cookies</strong></h3>
        <p>These cookies allow us to count visits and traffic sources so we can measure and improve Site performance. They help us understand which industry solution pages (pharmacy, restaurant, enterprise, salon, gold shop, tailor, distribution, bike shop, fashion, employee management, gift store, van sales) are most visited, and how visitors navigate between them, so we can improve content and user experience.</p>
        <p>Examples of providers: Google Analytics, Microsoft Clarity, and similar analytics services.</p>
        <h3><strong>4.3 Functional Cookies</strong></h3>
        <p>These cookies enable enhanced functionality and personalization, such as remembering your preferred industry/vertical selection, language settings, or pricing plan view. They may also support live chat widgets and demo-booking tools embedded on our Site. If you do not allow these cookies, some or all of these services may not function properly.</p>
        <h3><strong>4.4 Targeting and Advertising Cookies</strong></h3>
        <p>These cookies may be set through our Site by our advertising partners to build a profile of your interests and display relevant ads about MultiTech POS on other websites. They do not directly store personal information but work by uniquely identifying your browser and device.</p>
        <p>Examples of providers: Google Ads, Meta (Facebook/Instagram) Pixel, LinkedIn Insight Tag, and similar advertising networks.</p>
        <h3><strong>4.5 Social Media Cookies</strong></h3>
        <p>These cookies are set by social media services we have added to the Site to let you share our content with your network. They can track your browser across other sites and build a profile of your interests, which may affect the ads and content you see elsewhere.</p>
        <h2><strong>5. Specific Cookies We May Use</strong></h2>
        <figure><table>
        <tbody>
        <tr>
        <td><strong>Cookie Category</strong></td><td><strong>Purpose</strong></td><td><strong>Typical Duration</strong></td></tr>
        <tr>
        <td>Session/security cookies</td><td>Maintain user session, secure form/demo submissions</td><td>Session</td></tr>
        <tr>
        <td>_ga, _gid, _gat</td><td>Google Analytics — distinguishes users, tracks session activity</td><td>Up to 2 years</td></tr>
        <tr>
        <td>Google Ads conversion cookies</td><td>Measure ad campaign and demo-request conversion effectiveness</td><td>Up to 90 days</td></tr>
        <tr>
        <td>Meta Pixel (_fbp)</td><td>Track conversions from Facebook/Instagram ad campaigns</td><td>Up to 90 days</td></tr>
        <tr>
        <td>Live chat cookies</td><td>Maintain chat widget sessions for sales/support inquiries</td><td>Session to 30 days</td></tr>
        <tr>
        <td>Preference cookies</td><td>Remember selected industry vertical, language, region</td><td>Up to 1 year</td></tr>
        </tbody>
        </table>
        </figure>
        <p>Specific cookie names and durations may change as we update the tools and services used on our Site. We recommend reviewing this policy periodically for the most current information.</p>
        <h2><strong>6. Third-Party Cookies</strong></h2>
        <p>Some cookies are placed by third-party services embedded in our pages, including analytics tools, advertising networks, and live chat/demo-scheduling widgets. We do not control the operation of these third-party cookies. Please refer to the relevant third party’s own privacy and cookie policy for further details:</p>
        <ul>
        <li>Google Privacy Policy: https://policies.google.com/privacy</li>
        <li>Meta Privacy Policy: https://www.facebook.com/privacy/policy</li>
        <li>LinkedIn Privacy Policy: https://www.linkedin.com/legal/privacy-policy</li></ul>
        <h2><strong>7. How to Control Cookies</strong></h2>
        <p>You have several options to control or limit how we and our third-party partners use cookies:</p>
        <p><strong>Cookie Consent Tool:</strong> When you first visit our Site, you may see a cookie banner allowing you to accept or reject non-essential cookies. You can change your preferences at any time via the cookie settings link, typically found in the website footer.</p>
        <p><strong>Browser Settings:</strong> Most browsers let you control cookies through their settings. You can set your browser to refuse cookies, delete existing cookies, or notify you when cookies are being sent. Check your browser’s “Help” menu for details, or visit:</p>
        <ul>
        <li>Google Chrome: chrome://settings/cookies</li>
        <li>Mozilla Firefox: about:preferences#privacy</li>
        <li>Safari: Preferences &gt; Privacy</li>
        <li>Microsoft Edge: edge://settings/privacy</li></ul>
        <p><strong>Opt-Out Tools:</strong> You may opt out of certain third-party advertising cookies through industry tools such as the Digital Advertising Alliance (www.aboutads.info/choices) or the Network Advertising Initiative (www.networkadvertising.org/choices).</p>
        <p>Please note that if you disable or refuse cookies, some parts of the Site may become inaccessible or not function properly, including demo-request forms, live chat, and trial account login.</p>
        <h2><strong>8. Do Not Track Signals</strong></h2>
        <p>Some browsers offer a “Do Not Track” (DNT) feature. Because there is not yet an accepted industry standard for responding to DNT signals, our Site does not currently respond to DNT browser signals.</p>
        <h2><strong>9. Changes to This Cookie Policy</strong></h2>
        <p>We may update this Cookie Policy from time to time to reflect changes in technology, legislation, our operations, or for other legitimate reasons. We encourage you to review this page periodically. The “Last Updated” date at the top of this policy indicates when it was most recently revised.</p>
        <h2><strong>10. Contact Us</strong></h2>
        <p>If you have any questions about our use of cookies or this Cookie Policy, please contact us at:</p>
        <p><strong>MultiTech POS</strong> Website: {{ $base }}/ Email: info@multitechpos.com</p>
        <p><em>This Cookie Policy should be read alongside our Privacy Policy and Terms of Service, which govern the broader collection and use of your personal information.</em></p>
    </div>
</article>
@endsection

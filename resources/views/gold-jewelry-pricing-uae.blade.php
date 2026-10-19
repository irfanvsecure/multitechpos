@extends('layouts.app')

@section('title', 'Gold Jewelry Pricing UAE: Gold Rate, Making Charges & VAT')
@section('description', 'Gold jewelry pricing in the UAE explained: daily gold rate, making charges, 5% VAT and reverse charge rules, plus a POS setup checklist for jewelry shops.')
@section('og_image', $base . '/wp-content/uploads/2026/10/gold-jewelry-pricing-uae.jpg')
@section('og_type', 'article')
@section('body_class', 'page-post')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Blog', 'url' => '/our-blogs/'], ['name' => 'Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT', 'url' => $current]]])
@include('partials.schema-article', ['headline' => 'Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT', 'image' => '/wp-content/uploads/2026/10/gold-jewelry-pricing-uae.jpg', 'published' => '2026-10-14', 'author' => 'multitech_admin'])

<article class="post">
    <header class="post__header">
        <h1>Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT</h1>
        <div class="post__meta">By <a href="{{ $base }}/author/multitech_admin/">multitech_admin</a> / October 14, 2026</div>
    </header>
    <div class="post__content prose">
        <figure><img src="{{ $base }}/wp-content/uploads/2026/10/gold-jewelry-pricing-uae.webp" width="1280" height="1024" alt="Gold jewelry pricing in the UAE: gold rings and necklace priced by weight, karat and making charge"></figure>
        <p>Gold jewelry pricing in the UAE comes down to three numbers: the item’s gold weight multiplied by today’s rate for its karat, the making charge, and 5% VAT on the total for a retail buyer. Your POS should hold all three, apply them the same way every time, and print them clearly on the invoice.</p>
        <p>That sounds simple until the rate moves twice before lunch, a customer wants to lock a price for a custom bangle, or a trade buyer hands you a reverse charge declaration. This guide shows how to set up pricing so the counter, the invoice and the books always agree. If you are still choosing software, see how a <a href="{{ $base }}/jewelry-pos-system-in-uae/">point of sale built for jewelry shops in the UAE</a> handles weight and purity.</p>
        <h4>Key takeaways</h4>
        <ul>
        <li>Price = net gold weight x today’s karat rate + making charge + stones, then 5% VAT for retail buyers.</li>
        <li>Store the rate used on every invoice line. It is your audit trail when the rate changes during the day.</li>
        <li>The reverse charge is for VAT registered trade buyers with a written declaration, never for walk-in customers.</li>
        <li>Write down one rate locking rule for deposits and custom orders, and let the POS enforce it.</li></ul>
        <h4>In this guide</h4>
        <ol>
        <li><a href="#pricing-formula">The gold jewelry pricing formula</a></li>
        <li><a href="#daily-gold-rate">Handling the daily gold rate</a></li>
        <li><a href="#making-charges">Making charges: per gram, percentage or flat</a></li>
        <li><a href="#vat-on-gold">VAT on gold jewelry: retail and trade</a></li>
        <li><a href="#rate-locking">Rate locking for deposits and custom orders</a></li>
        <li><a href="#setup-checklist">A POS setup checklist for gold pricing</a></li>
        <li><a href="#gold-jewelry-pricing-uae-faq">Frequently asked questions</a></li></ol>
        <h2>The gold jewelry pricing formula</h2>
        <p>For a standard piece, the selling price is net gold weight times the karat rate, plus the making charge, plus any stone value. VAT is then added for a retail sale.</p>
        <p>Here is an illustrative example with made-up numbers. A 22K ring weighs 10 grams. Assume a rate of AED 400 per gram for 22K and a making charge of AED 40 per gram. The gold value is AED 4,000. Making adds AED 400. The subtotal is AED 4,400, VAT at 5% is AED 220, and the customer pays AED 4,620.</p>
        <p>Two details trip shops up. First, net weight. If a piece has stones, the stone weight must come off before the gold is priced, or you are charging gold rates for diamonds. Second, karat. A 21K and a 22K chain can look identical on the tray, so the item record must carry its karat.</p>
        <h2>Handling the daily gold rate</h2>
        <p>The gold rate is the input that changes most, so it should be entered once and used everywhere. Your tags should show weight and karat, not a fixed price.</p>
        <p>Retail rates in the UAE follow the international bullion market, and <a href="https://gulfnews.com/business/retail/buying-gold-in-the-uae-how-making-charges-affect-your-profits-when-selling-1.500694154" target="_blank" rel="noopener">Gulf News reports</a> that some shop displays are refreshed several times a day.</p>
        <p>A practical setup looks like this. A manager enters the new rate per karat (24K, 22K, 21K, 18K) with a time stamp, and only managers can change it. Every item priced after that moment uses the new figure. Every invoice keeps the rate it was sold at.</p>
        <strong>Quick check:</strong> Pull any invoice from last week. Can you see the gold rate, net weight, karat and making charge used on each line? If you would have to rebuild the price from memory, your setup needs work before the next customer dispute.<h2>Making charges: per gram, percentage or flat</h2>
        <p>Making charges are the fee for craftsmanship, and they are usually set per gram, as a percentage of the gold value, or as a flat amount per piece. Each method behaves differently when the rate moves.</p>
        <p>A percentage charge rises with the gold price. A per gram charge stays the same in dirhams. A flat fee suits light, intricate pieces.</p>
        <p>The POS should store the method on the item or category, and allow making charge discounts only down to a set floor without a manager PIN.</p>
        <h3>Price every piece the same way, every time</h3>
        <p>Send us a quick message and see how MultiTech tracks gold by weight and purity at the jewelry counter.</p>
        <a href="https://wa.me/971588979925" target="_blank" rel="noopener">Chat With Us on WhatsApp</a><h2>VAT on gold jewelry: retail and trade</h2>
        <p>For a normal retail sale, charge 5% VAT on the full price, including the making charge. Trade sales between registered dealers can fall under the domestic reverse charge, but only when strict conditions are met.</p>
        <p>The Federal Tax Authority’s public clarification <a href="https://tax.gov.ae/Datafolder/Files/Pdf/2025/VATP043-Precious-Metals-and-Precious-Stones-01-05-2025.pdf" target="_blank" rel="noopener">VATP043 on precious metals and precious stones</a> explains the current rules. Since 26 February 2025, Cabinet Decision No. 127 of 2024 extends the reverse charge beyond gold and diamonds to silver, platinum, palladium, pearls, rubies, sapphires and emeralds, and to jewelry where those materials are worth more than the other components.</p>
        <p>The reverse charge applies only if the buyer is VAT registered in the UAE, intends to resell or manufacture, and gives the supplier a written declaration before the supply. The supplier should check the buyer’s TRN and keep evidence. If the declaration is missing, the supplier charges VAT normally.</p>
        <p>Making charges need care. Under the same guidance, a single price for the goods and the making service can be treated as one supply. Separate prices mean the making service is taxed in the normal way. Confirm anything beyond routine retail with your tax adviser.</p>
        <p>In POS terms, you want two invoice templates: a standard retail tax invoice, and a trade invoice that records the buyer’s TRN, stores the declaration and states that the reverse charge applies. Our guide to <a href="{{ $base }}/what-makes-a-tax-invoice-valid/">what a valid UAE tax invoice must show</a> covers the general fields.</p>
        <h4>Retail sale vs trade sale: what the POS must capture</h4>
        <table>
        <thead>
        <tr>
        <th>Item</th><th>Retail customer</th><th>VAT registered trade buyer</th></tr>
        </thead>
        <tbody>
        <tr>
        <td>VAT on invoice</td><td>5% charged by the shop</td><td>Reverse charge if all conditions are met</td></tr>
        <tr>
        <td>Buyer TRN</td><td>Not needed</td><td>Required and verified</td></tr>
        <tr>
        <td>Written declaration</td><td>Not needed</td><td>Collected before the supply and stored</td></tr>
        <tr>
        <td>Making charge</td><td>Included in the taxable total</td><td>Single price or separate line, which changes the treatment</td></tr>
        <tr>
        <td>Gold rate on invoice</td><td>Recommended</td><td>Recommended</td></tr>
        </tbody>
        </table>
        <h2>Rate locking for deposits and custom orders</h2>
        <p>Decide in advance which rate a custom order uses, write it on the order, and let the POS enforce it.</p>
        <p>The two common rules are simple. Lock at deposit, where the rate on the day the customer pays a deposit fixes the gold price. Or price at delivery, where the final rate applies when the piece is collected.</p>
        <p>Whichever you choose, the deposit receipt should show the locked rate, the estimated weight and the making charge method. When the piece is ready, the final invoice pulls those values, adjusts only for the actual weight, and nets off the deposit.</p>
        <h2>A POS setup checklist for gold pricing</h2>
        <p>Check that your system can do each of the following. Any gap is the first thing to fix.</p>
        <ul>
        <li>Item records with gross weight, stone weight, net weight and karat.</li>
        <li>A rate table per karat, editable by managers only, with a change log.</li>
        <li>Making charge method stored per item or category, with a discount floor.</li>
        <li>Invoices that show rate, weight, making charge and VAT on each line.</li>
        <li>A trade invoice flow that captures TRN and the reverse charge declaration.</li>
        <li>Custom orders that store a locked rate and net off deposits.</li>
        </ul>
        <p>Some systems can read weight directly from a connected scale, which removes typing errors at the counter. If a feature on this list matters to you, ask for a demo to see how MultiTech handles it. For shops weighing a wider upgrade, our <a href="{{ $base }}/pos-software-uae/">overview of POS software in the UAE</a> is a useful starting point.</p>
        <h3>Frequently asked questions</h3>
        <h4>How is the price of gold jewelry calculated in the UAE?</h4>
        <p>Most shops multiply the item’s net gold weight by the day’s rate for its karat, add the making charge, then add 5% VAT on the total for a retail customer. Stone values, if any, are added as a separate line.</p>
        <h4>Is VAT charged on making charges for gold jewelry?</h4>
        <p>Yes. For a normal retail sale, 5% VAT applies to the full selling price, which includes both the gold value and the making charge. Between VAT registered dealers, the FTA guidance explains when the reverse charge covers the goods and when a separately priced making service is taxed in the normal way.</p>
        <h4>Does the reverse charge apply when I sell jewelry to a walk-in customer?</h4>
        <p>No. The reverse charge only applies when the buyer is VAT registered in the UAE, intends to resell or manufacture, and gives you a written declaration before the sale. A walk-in shopper meets none of those conditions, so you charge VAT on the invoice as usual.</p>
        <h4>Which gold rate should a custom order use?</h4>
        <p>Pick one rule and apply it every time. Common choices are the rate on the day the deposit is paid, which locks the price, or the rate on the day of delivery. Record the locked rate on the order either way.</p>
        <h4>How often should a jewelry shop update gold rates in the POS?</h4>
        <p>At least every time your displayed shop rate changes. Some UAE retailers refresh their displayed rates several times a day, so the POS should make it quick to enter a new rate and should stamp every invoice with the rate that was used.</p>
        <h3>Ready to make gold pricing simple at the counter?</h3>
        <p>Talk to our team about weight based inventory, purity tracking and clear invoices for your jewelry shop.</p>
        <a href="{{ $base }}/contact/">Book a Free Demo</a><h4>Related reading</h4>
        <ul>
        <li><a href="{{ $base }}/retail-inventory-shrinkage-uae/">Inventory Shrinkage: Where UAE Retail Stores Quietly Lose Stock and Money</a></li>
        <li><a href="{{ $base }}/why-your-daily-totals-do-not-match/">Reconciling Online and In-Store Payments: Making the Daily Close Work</a></li>
        <li><a href="{{ $base }}/uae-e-invoicing-guide-for-retailers/">UAE E-Invoicing: What the 2027 Mandate Means for Your POS System</a></li></ul>
    </div>
    @include('partials.post-cta')
</article>

<nav class="post-nav" aria-label="Posts">
    <a href="{{ $base }}/pharmacy-expiry-date-management/" rel="prev"><span>&larr; Previous</span>Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs</a>
    
</nav>
@endsection

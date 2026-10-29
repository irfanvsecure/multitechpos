@extends('layouts.app')

@section('title', 'Pharmacy Expiry Date Management: FEFO Guide for UAE')
@section('description', 'Pharmacy expiry date management with FEFO: what UAE rules say about expired medicines, near-expiry windows and a monthly routine that cuts write-offs.')
@section('og_image', $base . '/images/2026/10/pharmacy-expiry-date-management.jpg')
@section('og_type', 'article')
@section('body_class', 'page-post')

@section('content')
@include('partials.schema-breadcrumb', ['items' => [['name' => 'Home', 'url' => '/'], ['name' => 'Blog', 'url' => '/our-blogs/'], ['name' => 'Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs', 'url' => $current]]])
@include('partials.schema-article', ['headline' => 'Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs', 'image' => '/images/2026/10/pharmacy-expiry-date-management.jpg', 'published' => '2026-10-14', 'author' => 'multitech_admin'])

<article class="post">
    <header class="post__header">
        <h1>Pharmacy Expiry Management: How FEFO Stock Rotation Cuts Write-Offs</h1>
        <div class="post__meta">By <a href="{{ $base }}/author/multitech_admin/">multitech_admin</a> / October 14, 2026</div>
    </header>
    <div class="post__content prose">
        <figure><img src="{{ $base }}/images/2026/10/pharmacy-expiry-date-management.webp" width="1280" height="854" alt="Pharmacy expiry date management: pharmacist reaching for medication on a pharmacy shelf"></figure>
        <p>Pharmacy expiry date management comes down to two habits: knowing which batch of each medicine expires first, and selling or returning that batch before the date arrives. The method that makes this work is FEFO, short for first expired, first out. Done well, it turns expired stock from a monthly surprise into a short, predictable list.</p>
        <p>Most write-offs do not come from bad luck. They come from batches nobody saw coming. A <a href="{{ $base }}/pharmacy-pos-system-in-the-uae/">pharmacy POS that records batch numbers and expiry dates</a> at receiving gives you that visibility, and the routine below shows how to use it.</p>
        <h4>Key takeaways</h4>
        <ul>
        <li>FEFO sells the batch with the nearest expiry date first, whatever order it arrived in.</li>
        <li>Dubai’s medication disposal guideline asks facilities to check for expired medication every month and keep it apart from active stock.</li>
        <li>Near-expiry windows (for example 6, 3 and 1 month) tell your team when to push, return or move stock.</li>
        <li>Capturing batch and expiry at the receiving stage is the single change that makes everything else possible.</li></ul>
        <h4>In this guide</h4>
        <ol>
        <li><a href="#what-is-fefo">What FEFO means at a pharmacy counter</a></li>
        <li><a href="#uae-rules">What UAE rules say about expired stock</a></li>
        <li><a href="#near-expiry-windows">Near-expiry windows: decide before it is too late</a></li>
        <li><a href="#expiry-routine">A pharmacy expiry date management routine</a></li>
        <li><a href="#software-checklist">What your pharmacy software should do</a></li>
        <li><a href="#pharmacy-expiry-date-management-faq">Frequently asked questions</a></li></ol>
        <h2>What FEFO means at a pharmacy counter</h2>
        <p>FEFO means the unit you dispense is always the one that expires soonest. It sounds like FIFO, first in first out, but the two are not the same.</p>
        <p>Suppliers do not always deliver in date order. A delivery that arrives today can carry a shorter expiry than the stock already on your shelf, especially when a distributor clears older batches. Under FIFO you would sell the older delivery first and leave the short-dated new one to expire. Under FEFO you sell by the date printed on the pack.</p>
        <p>For medicines this matters more than for most retail goods. An expired medicine cannot be marked down and sold. It has to come off the shelf.</p>
        <h2>What UAE rules say about expired stock</h2>
        <p>UAE regulators expect regular checks, separate storage for expired items and documented disposal. The detail depends on your emirate and licensing authority, so always confirm with your own regulator.</p>
        <p>In Dubai, the DHA guideline on <a href="https://www.dha.gov.ae/uploads/112021/aef828d1-d1a3-4e5d-8bde-7c22dd68d218.pdf" target="_blank" rel="noopener">medications disposal and waste management</a> asks facilities to check for expired medication on a monthly basis. Expired, damaged, recalled or withdrawn items must sit in designated, clearly labelled areas, separate from active stock. Expired medicines should normally go back to the drug store they were bought from, and anything the drug store cannot take back is discarded as medical waste through an approved contractor. Controlled and narcotic drugs follow stricter steps.</p>
        <p>At federal level, the Ministry of Health and Prevention runs a service to <a href="https://mohap.gov.ae/en/services/authorize-a-pharmaceutical-company-to-dispose-of-any-pharmaceutical-products-as-medical-waste" target="_blank" rel="noopener">authorise disposal of pharmaceutical products as medical waste</a>, with pharmacies among its listed users. The request form lists each item’s name, unit, quantity, batch number, expiry date and reason for destruction, and the original form must be kept at the pharmacy for inspectors.</p>
        <p>Notice what those records need: batch numbers and expiry dates, item by item. If your system never captured them, someone ends up copying them from boxes by hand.</p>
        <strong>Quick check:</strong> Pick five fast-moving medicines and ask your system for each batch on hand and its expiry date. If you cannot get the answer in under a minute, your expiry control depends on memory and shelf checks, and that is where write-offs start.<h2>Near-expiry windows: decide before it is too late</h2>
        <p>A near-expiry window is a set point before the expiry date when a batch triggers a specific action. Without one, short-dated stock is spotted only when it has already expired.</p>
        <p>The right windows depend on how fast each item sells and on your supplier return terms, which vary from one distributor to the next. The table below is an example policy you can start from and adjust.</p>
        <h4>Example near-expiry action plan</h4>
        <table>
        <thead>
        <tr>
        <th>Time to expiry</th><th>Status</th><th>Action</th></tr>
        </thead>
        <tbody>
        <tr>
        <td>More than 6 months</td><td>Normal</td><td>Sell under FEFO; no special action</td></tr>
        <tr>
        <td>3 to 6 months</td><td>Watch</td><td>Move to the front, stop reordering if stock is high, check supplier return terms</td></tr>
        <tr>
        <td>1 to 3 months</td><td>Act</td><td>Request a return or exchange, or transfer to a branch where it sells faster</td></tr>
        <tr>
        <td>Under 1 month</td><td>Last call</td><td>Final return attempt; prepare for quarantine</td></tr>
        <tr>
        <td>Expired</td><td>Quarantine</td><td>Remove from sale, store separately, record for disposal under your regulator’s process</td></tr>
        </tbody>
        </table>
        <p>The table is only half the work. The other half is a weekly report that lists every batch in the Watch and Act bands, so the decision lands on someone’s desk with time to spare.</p>
        <h2>A pharmacy expiry date management routine</h2>
        <p>The routine has four steps: capture, shelve, sell and sweep. Each one closes a gap the others leave open.</p>
        <h3>1. Capture batch and expiry at receiving</h3>
        <p>Enter the batch number and expiry date for every line when goods arrive, not later. Many medicine packs now carry GS1 2D barcodes, which <a href="https://www.gs1.org/standards/barcodes/2d" target="_blank" rel="noopener">can hold data such as an expiry date or serial number</a>, so scanning can save typing and errors.</p>
        <h3>2. Shelve short dates in front</h3>
        <p>Place the earliest expiry at the front and the newest at the back. Check this every time a delivery is put away. It takes seconds.</p>
        <h3>3. Let the POS pick the batch</h3>
        <p>When a sale is made, the system should take stock from the earliest-expiring batch. If staff pick a different batch, the stock record drifts away from the shelf, and your reports stop being trusted.</p>
        <h3>4. Run a monthly sweep</h3>
        <p>Once a month, run the near-expiry report, act on each band and move any expired items to the quarantine area. In Dubai this lines up with the monthly check the DHA guideline asks for.</p>
        <h3>See FEFO and expiry alerts in action</h3>
        <p>Send us a message and we will show you how MultiTech tracks batches, flags expiring medicines and keeps your shelves in date order.</p>
        <a href="https://wa.me/971588979925?text=Hi%20MultiTech%2C%20I%27d%20like%20a%20free%20POS%20demo" target="_blank" rel="noopener">Chat With Us on WhatsApp</a><h2>What your pharmacy software should do</h2>
        <p>Good software makes FEFO automatic rather than a matter of discipline. Use this list when you compare systems or review your current one:</p>
        <ul>
        <li>Batch number and expiry date stored for every received line</li>
        <li>Sales deducted from the earliest-expiring batch by default</li>
        <li>Alerts for medicines entering your near-expiry windows</li>
        <li>Reports by batch, supplier and expiry band, ready to export</li>
        <li>Stock moves between branches with batch details kept intact</li>
        </ul>
        <p>Chains have one extra option: a slow branch can send short-dated stock to a busier one before it expires. That only works when every outlet shares one stock view, which is the job of <a href="{{ $base }}/multi-branch-business-pos-uae/">POS software for multi-branch businesses</a>.</p>
        <p>MultiTech POS for pharmacies already applies FEFO batch control and sends alerts for expiring medicines. For supplier returns and your own window settings, ask for a demo to see how MultiTech handles it with your stock.</p>
        <h3>Frequently asked questions</h3>
        <h4>What is the difference between FEFO and FIFO in a pharmacy?</h4>
        <p>FIFO sells the stock that arrived first. FEFO sells the stock that expires first. Because deliveries do not always arrive in expiry order, FEFO is the safer method for medicines.</p>
        <h4>How often should a pharmacy check for expired medicines?</h4>
        <p>The DHA medication disposal guideline asks Dubai facilities to check for expired medication every month. A weekly review of near-expiry stock gives you time to act before the date passes.</p>
        <h4>What should a pharmacy do with expired medicines in the UAE?</h4>
        <p>Remove them from sale and keep them in a separate, labelled area. Return them to the supplier where possible, or dispose of them as medical waste through the process your regulator sets. Controlled drugs follow stricter rules.</p>
        <h4>How far ahead should near-expiry alerts start?</h4>
        <p>It depends on how fast each item sells and on your supplier return terms. One practical starting point is to watch stock from six months out and act from three months out, then adjust by product.</p>
        <h4>Can a POS system prevent expired medicines from being sold?</h4>
        <p>A POS can help a lot by tracking batches, selling the earliest expiry first and flagging items close to their date. It still depends on staff entering batch and expiry details correctly when stock arrives.</p>
        <h3>Stop finding expired stock too late</h3>
        <p>Talk to our team about batch tracking, FEFO and expiry alerts for your pharmacy or chain.</p>
        <a href="{{ $base }}/contact/">Book a Free Demo</a><h4>Related reading</h4>
        <ul>
        <li><a href="{{ $base }}/retail-inventory-shrinkage-uae/">Inventory Shrinkage: Where UAE Retail Stores Quietly Lose Stock and Money</a></li>
        <li><a href="{{ $base }}/woocommerce-inventory-sync-how-variations-bundles-and-multi-branch-stock-actually-work/">How Multi-Branch Stock Sync Actually Works</a></li>
        <li><a href="{{ $base }}/what-makes-a-tax-invoice-valid/">UAE Tax Invoice Requirements: What Your POS Receipt Must Show</a></li></ul>
    </div>
    @include('partials.post-cta')
</article>

<nav class="post-nav" aria-label="Posts">
    <a href="{{ $base }}/uae-e-invoicing-for-distributors/" rel="prev"><span>&larr; Previous</span>UAE E-Invoicing 2027: What Distributors and Van Sales Teams Must Do Now</a>
    <a href="{{ $base }}/gold-jewelry-pricing-uae/" rel="next"><span>Next &rarr;</span>Gold Jewelry Pricing in Your POS: Daily Gold Rate, Making Charges and VAT</a>
</nav>
@endsection

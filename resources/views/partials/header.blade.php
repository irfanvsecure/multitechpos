{{-- Top bar (scrolls away) --}}
<div class="topbar">
    <div class="wrap topbar__inner">
        <p class="topbar__note"><i class="fas fa-shield-alt"></i> UAE VAT &amp; FTA compliant POS &amp; ERP solutions</p>
        <ul class="topbar__links">
            <li><a href="tel:+971588979925"><i class="fas fa-phone-alt"></i> +971 58 897 9925</a></li>
            <li><a href="mailto:support@multitechpos.com"><i class="far fa-envelope"></i> support@multitechpos.com</a></li>
            <li><a href="http://wa.me/+971588979925" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
        </ul>
    </div>
</div>

{{-- Main header (sticky) --}}
<header class="site-header" data-header>
    <div class="header-main">
        <div class="wrap header-main__inner">
            <a href="{{ $base }}/" class="header-logo" aria-label="MultiTech POS home">
                <img src="{{ $base }}/wp-content/uploads/2025/12/logo4.png" alt="MultiTech POS" width="122" height="50">
            </a>

            <nav class="main-nav" id="main-nav" aria-label="Main menu">
                <div class="main-nav__head">
                    <img src="{{ $base }}/wp-content/uploads/2025/12/logo4.png" alt="MultiTech POS" width="100" height="41">
                    <button class="nav-close" type="button" aria-label="Close menu"><i class="fas fa-times"></i></button>
                </div>

                <ul class="menu">
                    <li class="menu__home"><a href="{{ $base }}/" class="menu__link">Home</a></li>
                    <li><a href="{{ $base }}/about-us/" class="menu__link">About Us</a></li>

                    {{-- POS Software --}}
                    <li class="has-mega">
                        <a href="{{ $base }}/pos-software-uae/" class="menu__link">POS Software</a>
                        <button class="submenu-toggle" type="button" aria-label="Show POS Software menu"><i class="fas fa-chevron-down"></i></button>
                        <div class="mega">
                            <div class="wrap mega__inner">
                                <div class="mega__col">
                                    <p class="mega__title"><a href="{{ $base }}/by-industry/">By Industry</a></p>
                                    <ul class="mega__list">
                                        <li><a href="{{ $base }}/retail-pos-software-uae/"><i class="fas fa-shopping-cart"></i><span>Retail POS<small>Shops, boutiques &amp; chains</small></span></a></li>
                                        <li><a href="{{ $base }}/salon-spa-pos-software-uae/"><i class="fas fa-spa"></i><span>Salon &amp; Spa POS<small>Bookings, staff &amp; packages</small></span></a></li>
                                        <li><a href="{{ $base }}/food-restaurant-pos-software-uae/"><i class="fas fa-utensils"></i><span>Food &amp; Restaurant POS<small>Tables, KOT &amp; delivery</small></span></a></li>
                                        <li><a href="{{ $base }}/service-business-pos-software-uae/"><i class="fas fa-concierge-bell"></i><span>Service Business POS<small>Jobs, invoices &amp; customers</small></span></a></li>
                                        <li><a href="{{ $base }}/custom-pos-software-uae/"><i class="fas fa-sliders-h"></i><span>Custom POS<small>Built around your workflow</small></span></a></li>
                                    </ul>
                                </div>
                                <div class="mega__col">
                                    <p class="mega__title"><a href="{{ $base }}/business-size/">By Business Size</a></p>
                                    <ul class="mega__list">
                                        <li><a href="{{ $base }}/small-business-pos-uae/"><i class="fas fa-store"></i><span>Small Business POS<small>Start simple, grow later</small></span></a></li>
                                        <li><a href="{{ $base }}/multi-branch-business-pos-uae/"><i class="fas fa-network-wired"></i><span>Multi Branch POS<small>All branches, one dashboard</small></span></a></li>
                                        <li><a href="{{ $base }}/enterprise-pos-uae/"><i class="fas fa-building"></i><span>Enterprise POS<small>Scale, control &amp; reporting</small></span></a></li>
                                    </ul>
                                </div>
                                @include('partials.mega-promo')
                            </div>
                        </div>
                    </li>

                    {{-- POS Features --}}
                    <li class="has-mega">
                        <a href="{{ $base }}/features/" class="menu__link">POS Features</a>
                        <button class="submenu-toggle" type="button" aria-label="Show POS Features menu"><i class="fas fa-chevron-down"></i></button>
                        <div class="mega">
                            <div class="wrap mega__inner">
                                <div class="mega__col mega__col--wide">
                                    <p class="mega__title"><a href="{{ $base }}/features/">POS Features</a></p>
                                    <ul class="mega__list mega__list--2">
                                        <li><a href="{{ $base }}/multi-location-integration/"><i class="fas fa-map-marked-alt"></i><span>Multi Location Integration<small>Stock &amp; sales across branches</small></span></a></li>
                                        <li><a href="{{ $base }}/customers/"><i class="fas fa-users"></i><span>Customers Management<small>Profiles, loyalty &amp; history</small></span></a></li>
                                        <li><a href="{{ $base }}/appointment-booking-pos-uae/"><i class="far fa-calendar-check"></i><span>Appointment Booking<small>Fewer no-shows, full calendar</small></span></a></li>
                                        <li><a href="{{ $base }}/table-management-pos-uae/"><i class="fas fa-chair"></i><span>Table Management<small>Floor plans &amp; split bills</small></span></a></li>
                                        <li><a href="{{ $base }}/mobile-multitechpos/"><i class="fas fa-mobile-alt"></i><span>Mobile App POS<small>Sell &amp; report on the go</small></span></a></li>
                                        <li><a href="{{ $base }}/woocommerce-pos/"><i class="fab fa-wordpress"></i><span>WooCommerce POS<small>Online &amp; store in sync</small></span></a></li>
                                        <li><a href="{{ $base }}/paperless-invoicing/"><i class="fas fa-file-invoice"></i><span>Paperless Invoicing<small>Digital, VAT-ready receipts</small></span></a></li>
                                    </ul>
                                </div>
                                @include('partials.mega-promo')
                            </div>
                        </div>
                    </li>

                    {{-- Business ERPs --}}
                    <li class="has-mega">
                        <a href="{{ $base }}/business-erps/" class="menu__link">Business ERPs</a>
                        <button class="submenu-toggle" type="button" aria-label="Show Business ERPs menu"><i class="fas fa-chevron-down"></i></button>
                        <div class="mega">
                            <div class="wrap mega__inner">
                                <div class="mega__col">
                                    <p class="mega__title">Operations</p>
                                    <ul class="mega__list">
                                        <li><a href="{{ $base }}/stock-management/"><i class="fas fa-boxes"></i><span>Stock Management</span></a></li>
                                        <li><a href="{{ $base }}/retail-management/"><i class="fas fa-tags"></i><span>Retail Management</span></a></li>
                                        <li><a href="{{ $base }}/restaurant-management/"><i class="fas fa-hamburger"></i><span>Restaurant Management</span></a></li>
                                        <li><a href="{{ $base }}/distribution-management/"><i class="fas fa-truck"></i><span>Distribution Management</span></a></li>
                                    </ul>
                                </div>
                                <div class="mega__col">
                                    <p class="mega__title">Industry Systems</p>
                                    <ul class="mega__list">
                                        <li><a href="{{ $base }}/salon-management/"><i class="fas fa-cut"></i><span>Salon Management</span></a></li>
                                        <li><a href="{{ $base }}/tailor-management/"><i class="fas fa-tshirt"></i><span>Tailor Management</span></a></li>
                                        <li><a href="{{ $base }}/laundry-management/"><i class="fas fa-soap"></i><span>Laundry Management</span></a></li>
                                        <li><a href="{{ $base }}/hospital-management/"><i class="fas fa-hospital"></i><span>Hospital Management</span></a></li>
                                    </ul>
                                </div>
                                <div class="mega__col">
                                    <p class="mega__title">People &amp; Finance</p>
                                    <ul class="mega__list">
                                        <li><a href="{{ $base }}/employee-management/"><i class="fas fa-user-tie"></i><span>Employee Management</span></a></li>
                                        <li><a href="{{ $base }}/payroll-software/"><i class="fas fa-money-check-alt"></i><span>Payroll Software</span></a></li>
                                        <li><a href="{{ $base }}/accounting-software/"><i class="fas fa-calculator"></i><span>Accounting Software</span></a></li>
                                    </ul>
                                    <a href="{{ $base }}/business-erps/" class="mega__all">View all ERPs <i class="fas fa-arrow-right"></i></a>
                                </div>
                                @include('partials.mega-promo')
                            </div>
                        </div>
                    </li>

                    {{-- Downloads --}}
                    <li class="has-sub">
                        <a href="{{ $base }}/downloads/" class="menu__link">Downloads</a>
                        <button class="submenu-toggle" type="button" aria-label="Show Downloads menu"><i class="fas fa-chevron-down"></i></button>
                        <ul class="dropdown">
                            <li><a href="{{ $base }}/restropro/"><i class="fas fa-download"></i> RestroPro</a></li>
                            <li><a href="{{ $base }}/salonpro/"><i class="fas fa-download"></i> SalonPro</a></li>
                            <li><a href="{{ $base }}/laundrypro/"><i class="fas fa-download"></i> LaundryPro</a></li>
                            <li><a href="{{ $base }}/tailorpro/"><i class="fas fa-download"></i> TailorPro</a></li>
                        </ul>
                    </li>

                    {{-- Industries --}}
                    <li class="has-mega">
                        <a href="{{ $base }}/more-pos-industries/" class="menu__link">Industries</a>
                        <button class="submenu-toggle" type="button" aria-label="Show Industries menu"><i class="fas fa-chevron-down"></i></button>
                        <div class="mega">
                            <div class="wrap mega__inner mega__inner--industries">
                                <div class="mega__col">
                                    <p class="mega__title"><i class="fas fa-shopping-bag"></i> Retail Stores</p>
                                    <ul class="mega__links">
                                        <li><a href="{{ $base }}/fashion-pos-system-in-uae/">Fashion POS</a></li>
                                        <li><a href="{{ $base }}/jewelry-pos-system-in-uae/">Jewelry POS</a></li>
                                        <li><a href="{{ $base }}/gold-shop-pos-software/">Gold Shop POS</a></li>
                                        <li><a href="{{ $base }}/perfume-shop-pos-software-in-uae/">Perfume Shop POS</a></li>
                                        <li><a href="{{ $base }}/gift-store-pos-system-in-the-uae/">Gift Store POS</a></li>
                                        <li><a href="{{ $base }}/flower-shop-pos-system-in-the-uae/">Flower Shop POS</a></li>
                                        <li><a href="{{ $base }}/book-store-pos-software/">Book Store POS</a></li>
                                        <li><a href="{{ $base }}/furniture-store-pos-software/">Furniture Store POS</a></li>
                                        <li><a href="{{ $base }}/toy-shop-pos-in-uae/">Toy Shop POS</a></li>
                                    </ul>
                                </div>
                                <div class="mega__col">
                                    <p class="mega__title"><i class="fas fa-mug-hot"></i> Food &amp; Hospitality</p>
                                    <ul class="mega__links">
                                        <li><a href="{{ $base }}/bakery-pos-software/">Bakery POS</a></li>
                                        <li><a href="{{ $base }}/fast-food-pos-software/">Fast Food POS</a></li>
                                        <li><a href="{{ $base }}/food-truck-pos-software/">Food Truck POS</a></li>
                                        <li><a href="{{ $base }}/coffee-shop-pos-system-uae/">Coffee Shop POS</a></li>
                                        <li><a href="{{ $base }}/chocolate-shop-pos-system-in-the-uae/">Chocolate Shop POS</a></li>
                                        <li><a href="{{ $base }}/dates-shop-pos-system-in-the-uae/">Dates Shop POS</a></li>
                                        <li><a href="{{ $base }}/hotel-pos-software/">Hotel POS</a></li>
                                        <li><a href="{{ $base }}/delivery-pos-software/">Delivery POS</a></li>
                                    </ul>
                                </div>
                                <div class="mega__col">
                                    <p class="mega__title"><i class="fas fa-heartbeat"></i> Beauty &amp; Health</p>
                                    <ul class="mega__links">
                                        <li><a href="{{ $base }}/nail-salon-pos-software/">Nail Salon POS</a></li>
                                        <li><a href="{{ $base }}/barber-shop-pos-software/">Barber Shop POS</a></li>
                                        <li><a href="{{ $base }}/spa-pos-software-uae/">Spa POS</a></li>
                                        <li><a href="{{ $base }}/medical-spa-pos-software/">Medical Spa POS</a></li>
                                        <li><a href="{{ $base }}/beauty-clinic-pos-software-uae/">Beauty Clinic POS</a></li>
                                        <li><a href="{{ $base }}/pharmacy-pos-system-in-the-uae/">Pharmacy POS</a></li>
                                        <li><a href="{{ $base }}/gym-pos-software/">Gym POS</a></li>
                                    </ul>
                                </div>
                                <div class="mega__col">
                                    <p class="mega__title"><i class="fas fa-tools"></i> Services &amp; Specialty</p>
                                    <ul class="mega__links">
                                        <li><a href="{{ $base }}/grocery-pos-system-in-uae/">Grocery POS</a></li>
                                        <li><a href="{{ $base }}/van-sales-management-system-in-uae/">Van Sales</a></li>
                                        <li><a href="{{ $base }}/car-wash-pos-software/">Car Wash POS</a></li>
                                        <li><a href="{{ $base }}/gas-station-pos-software/">Gas Station POS</a></li>
                                        <li><a href="{{ $base }}/golf-pos-software/">Golf POS</a></li>
                                        <li><a href="{{ $base }}/bike-shop-pos-software/">Bike Shop POS</a></li>
                                        <li><a href="{{ $base }}/gaming-store-pos-software/">Gaming Store POS</a></li>
                                        <li><a href="{{ $base }}/music-store-pos-point-of-sale-software/">Music Store POS</a></li>
                                        <li><a href="{{ $base }}/pet-shop-pos-software/">Pet Shop POS</a></li>
                                        <li><a href="{{ $base }}/hemp-pos-software/">Hemp POS</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mega__foot">
                                <div class="wrap mega__foot-inner">
                                    <span>Can't find your business type? We build POS for every industry in the UAE.</span>
                                    <a href="{{ $base }}/more-pos-industries/" class="mega__all">View all industries <i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li><a href="{{ $base }}/contact/" class="menu__link">Contact</a></li>
                </ul>

                <div class="main-nav__cta">
                    <a href="{{ $base }}/contact/" class="btn btn--primary btn--block">Book a Free Demo</a>
                    <a href="http://wa.me/+971588979925" class="btn btn--whatsapp btn--block" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
                </div>
            </nav>

            <div class="header-actions">
                <a href="tel:+971588979925" class="header-call">
                    <span class="header-call__icon"><i class="fas fa-phone-alt"></i></span>
                    <span class="header-call__text"><small>Call our experts</small>+971 58 897 9925</span>
                </a>
                <a href="{{ $base }}/contact/" class="btn btn--primary header-btn">Book Free Demo</a>
                <button class="nav-toggle" type="button" aria-label="Open menu" aria-controls="main-nav" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </div>
    <div class="nav-backdrop" data-nav-close></div>
</header>

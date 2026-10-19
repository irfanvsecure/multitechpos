@php
    $base = $base ?? '';
    $current = $current ?? '/';
    $isHere = function (string $href) use ($current): bool {
        return rtrim($href, '/') === rtrim($current, '/');
    };
    $inGroup = function (array $hrefs) use ($isHere): bool {
        foreach ($hrefs as $href) {
            if ($isHere($href)) {
                return true;
            }
        }

        return false;
    };
    $software = [
        '/pos-software-uae/', '/by-industry/', '/business-size/',
        '/retail-pos-software-uae/', '/salon-spa-pos-software-uae/', '/food-restaurant-pos-software-uae/',
        '/service-business-pos-software-uae/', '/custom-pos-software-uae/',
        '/small-business-pos-uae/', '/multi-branch-business-pos-uae/', '/enterprise-pos-uae/',
    ];
    $features = [
        '/features/', '/multi-location-integration/', '/customers/', '/appointment-booking-pos-uae/',
        '/table-management-pos-uae/', '/mobile-multitechpos/', '/woocommerce-pos/', '/paperless-invoicing/',
    ];
    $erps = [
        '/business-erps/', '/stock-management/', '/restaurant-management/', '/retail-management/',
        '/employee-management/', '/salon-management/', '/tailor-management/', '/laundry-management/',
        '/hospital-management/', '/distribution-management/', '/payroll-software/', '/accounting-software/',
    ];
    $downloads = ['/downloads/', '/restropro/', '/salonpro/', '/laundrypro/', '/tailorpro/'];
    $industries = [
        '/more-pos-industries/',
        '/fashion-pos-system-in-uae/', '/jewelry-pos-system-in-uae/', '/grocery-pos-system-in-uae/',
        '/gift-store-pos-system-in-the-uae/', '/flower-shop-pos-system-in-the-uae/', '/dates-shop-pos-system-in-the-uae/',
        '/book-store-pos-software/', '/furniture-store-pos-software/', '/toy-shop-pos-in-uae/',
        '/gold-shop-pos-software/', '/perfume-shop-pos-software-in-uae/',
        '/bakery-pos-software/', '/fast-food-pos-software/', '/food-truck-pos-software/',
        '/coffee-shop-pos-system-uae/', '/chocolate-shop-pos-system-in-the-uae/', '/hotel-pos-software/',
        '/delivery-pos-software/',
        '/nail-salon-pos-software/', '/barber-shop-pos-software/', '/spa-pos-software-uae/',
        '/medical-spa-pos-software/', '/beauty-clinic-pos-software-uae/', '/pharmacy-pos-system-in-the-uae/',
        '/gym-pos-software/',
        '/van-sales-management-system-in-uae/', '/car-wash-pos-software/', '/bike-shop-pos-software/',
        '/gaming-store-pos-software/', '/gas-station-pos-software/', '/golf-pos-software/',
        '/hemp-pos-software/', '/music-store-pos-point-of-sale-software/', '/pet-shop-pos-software/',
    ];
@endphp
<div class="mtp-topbar">
    <div class="mtp-wrap mtp-topbar-in">
        <ul>
            <li><a href="mailto:support@multitechpos.com"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6.5A2.5 2.5 0 0 1 5.5 4h13A2.5 2.5 0 0 1 21 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 17.5v-11Zm2.2.3 6.3 4.7a.8.8 0 0 0 1 0l6.3-4.7"/></svg>support@multitechpos.com</a></li>
            <li><a href="tel:+971588979925"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8.2 3.8a1.6 1.6 0 0 1 1.8 1l1.1 2.6a1.6 1.6 0 0 1-.4 1.8l-1.2 1a12.4 12.4 0 0 0 5.3 5.3l1-1.2a1.6 1.6 0 0 1 1.8-.4l2.6 1.1a1.6 1.6 0 0 1 1 1.8l-.6 2.4a1.6 1.6 0 0 1-1.6 1.2A16.2 16.2 0 0 1 3.6 6.4a1.6 1.6 0 0 1 1.2-1.6l2.4-.6Z"/></svg>+971 58 897 9925</a></li>
        </ul>
    </div>
</div>
<header class="mtp-header">
    <div class="mtp-wrap mtp-header-in">
        <a class="mtp-logo" href="{{ $base }}/"><img src="{{ $base }}/images/2025/12/logo4.png" alt="MultiTech POS" width="194" height="80"></a>
        <nav class="mtp-nav" id="mtp-nav">
            <a href="{{ $base }}/" @class(['active' => $isHere('/')])>Home</a>
            <a href="{{ $base }}/about-us/" @class(['active' => $isHere('/about-us/')])>About Us</a>

            <div @class(['mtp-has-sub', 'mtp-has-mega', 'is-current' => $inGroup($software)])>
                <a href="{{ $base }}/pos-software-uae/" @class(['active' => $isHere('/pos-software-uae/')])>POS Software</a>
                <div class="mtp-sub mtp-mega">
                    <div class="mtp-mega-cols mtp-cols-2">
                        <div class="mtp-mega-col">
                            <a href="{{ $base }}/by-industry/" @class(['mtp-mega-head', 'active' => $isHere('/by-industry/')])>By Industry</a>
                            <a href="{{ $base }}/retail-pos-software-uae/" @class(['active' => $isHere('/retail-pos-software-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Retail POS</span></a>
                            <a href="{{ $base }}/salon-spa-pos-software-uae/" @class(['active' => $isHere('/salon-spa-pos-software-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Salon &amp; Spa POS</span></a>
                            <a href="{{ $base }}/food-restaurant-pos-software-uae/" @class(['active' => $isHere('/food-restaurant-pos-software-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Food &amp; Restaurant POS</span></a>
                            <a href="{{ $base }}/service-business-pos-software-uae/" @class(['active' => $isHere('/service-business-pos-software-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Service Business POS</span></a>
                            <a href="{{ $base }}/custom-pos-software-uae/" @class(['active' => $isHere('/custom-pos-software-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Custom POS</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <a href="{{ $base }}/business-size/" @class(['mtp-mega-head', 'active' => $isHere('/business-size/')])>Business Size</a>
                            <a href="{{ $base }}/small-business-pos-uae/" @class(['active' => $isHere('/small-business-pos-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Small Business POS</span></a>
                            <a href="{{ $base }}/multi-branch-business-pos-uae/" @class(['active' => $isHere('/multi-branch-business-pos-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Multi Branch Business POS</span></a>
                            <a href="{{ $base }}/enterprise-pos-uae/" @class(['active' => $isHere('/enterprise-pos-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Enterprise POS</span></a>
                        </div>
                    </div>
                    <div class="mtp-mega-foot">
                        <span class="mtp-mega-note">POS software for retail, food, salon and service businesses.</span>
                        <span class="mtp-mega-actions">
                            <a class="mtp-mega-all" href="{{ $base }}/pos-software-uae/">View all POS software</a>
                            <a class="mtp-mega-cta" href="{{ $base }}/contact/">Request Demo</a>
                        </span>
                    </div>
                </div>
            </div>

            <div @class(['mtp-has-sub', 'is-current' => $inGroup($features)])>
                <a href="{{ $base }}/features/" @class(['active' => $isHere('/features/')])>POS Features</a>
                <div class="mtp-sub">
                    <a href="{{ $base }}/multi-location-integration/" @class(['active' => $isHere('/multi-location-integration/')])>Multi Location Integration</a>
                    <a href="{{ $base }}/customers/" @class(['active' => $isHere('/customers/')])>Customers Management</a>
                    <a href="{{ $base }}/appointment-booking-pos-uae/" @class(['active' => $isHere('/appointment-booking-pos-uae/')])>Appointment Booking POS</a>
                    <a href="{{ $base }}/table-management-pos-uae/" @class(['active' => $isHere('/table-management-pos-uae/')])>Table Management POS</a>
                    <a href="{{ $base }}/mobile-multitechpos/" @class(['active' => $isHere('/mobile-multitechpos/')])>Mobile App POS</a>
                    <a href="{{ $base }}/woocommerce-pos/" @class(['active' => $isHere('/woocommerce-pos/')])>Woocommerce POS</a>
                    <a href="{{ $base }}/paperless-invoicing/" @class(['active' => $isHere('/paperless-invoicing/')])>Paperless Invoicing</a>
                </div>
            </div>

            <div @class(['mtp-has-sub', 'mtp-has-mega', 'is-current' => $inGroup($erps)])>
                <a href="{{ $base }}/business-erps/" @class(['active' => $isHere('/business-erps/')])>Business ERPs</a>
                <div class="mtp-sub mtp-mega">
                    <div class="mtp-mega-cols mtp-cols-3">
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Operations</div>
                            <a href="{{ $base }}/stock-management/" @class(['active' => $isHere('/stock-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Stock Management</span></a>
                            <a href="{{ $base }}/retail-management/" @class(['active' => $isHere('/retail-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Retail Management</span></a>
                            <a href="{{ $base }}/distribution-management/" @class(['active' => $isHere('/distribution-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Distribution Management</span></a>
                            <a href="{{ $base }}/restaurant-management/" @class(['active' => $isHere('/restaurant-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Restaurant Management</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Industry Systems</div>
                            <a href="{{ $base }}/salon-management/" @class(['active' => $isHere('/salon-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Salon Management</span></a>
                            <a href="{{ $base }}/tailor-management/" @class(['active' => $isHere('/tailor-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Tailor Management</span></a>
                            <a href="{{ $base }}/laundry-management/" @class(['active' => $isHere('/laundry-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Laundry Management</span></a>
                            <a href="{{ $base }}/hospital-management/" @class(['active' => $isHere('/hospital-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Hospital Management</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">People &amp; Finance</div>
                            <a href="{{ $base }}/employee-management/" @class(['active' => $isHere('/employee-management/')])><span class="mtp-ico" aria-hidden="true"></span><span>Employee Management</span></a>
                            <a href="{{ $base }}/payroll-software/" @class(['active' => $isHere('/payroll-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Payroll Software</span></a>
                            <a href="{{ $base }}/accounting-software/" @class(['active' => $isHere('/accounting-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Accounting Software</span></a>
                        </div>
                    </div>
                    <div class="mtp-mega-foot">
                        <span class="mtp-mega-note">Stock, payroll, accounting and industry management in one place.</span>
                        <span class="mtp-mega-actions">
                            <a class="mtp-mega-all" href="{{ $base }}/business-erps/">View all ERPs</a>
                            <a class="mtp-mega-cta" href="{{ $base }}/contact/">Request Demo</a>
                        </span>
                    </div>
                </div>
            </div>

            <div @class(['mtp-has-sub', 'is-current' => $inGroup($downloads)])>
                <a href="{{ $base }}/downloads/" @class(['active' => $isHere('/downloads/')])>Downloads</a>
                <div class="mtp-sub">
                    <a href="{{ $base }}/restropro/" @class(['active' => $isHere('/restropro/')])>Restropro</a>
                    <a href="{{ $base }}/salonpro/" @class(['active' => $isHere('/salonpro/')])>Salonpro</a>
                    <a href="{{ $base }}/laundrypro/" @class(['active' => $isHere('/laundrypro/')])>Laundrypro</a>
                    <a href="{{ $base }}/tailorpro/" @class(['active' => $isHere('/tailorpro/')])>Tailorpro</a>
                </div>
            </div>

            <div @class(['mtp-has-sub', 'mtp-has-mega', 'is-current' => $inGroup($industries)])>
                <a href="{{ $base }}/more-pos-industries/" @class(['active' => $isHere('/more-pos-industries/')])>More POS Industries</a>
                <div class="mtp-sub mtp-mega">
                    <div class="mtp-mega-cols mtp-cols-6">
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Retail</div>
                            <a href="{{ $base }}/fashion-pos-system-in-uae/" @class(['active' => $isHere('/fashion-pos-system-in-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Fashion POS</span></a>
                            <a href="{{ $base }}/jewelry-pos-system-in-uae/" @class(['active' => $isHere('/jewelry-pos-system-in-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Jewelry POS</span></a>
                            <a href="{{ $base }}/grocery-pos-system-in-uae/" @class(['active' => $isHere('/grocery-pos-system-in-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Grocery POS</span></a>
                            <a href="{{ $base }}/gift-store-pos-system-in-the-uae/" @class(['active' => $isHere('/gift-store-pos-system-in-the-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Gift Store</span></a>
                            <a href="{{ $base }}/flower-shop-pos-system-in-the-uae/" @class(['active' => $isHere('/flower-shop-pos-system-in-the-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Flower Shop</span></a>
                            <a href="{{ $base }}/dates-shop-pos-system-in-the-uae/" @class(['active' => $isHere('/dates-shop-pos-system-in-the-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Dates Shop</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Stores</div>
                            <a href="{{ $base }}/book-store-pos-software/" @class(['active' => $isHere('/book-store-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Book Store</span></a>
                            <a href="{{ $base }}/furniture-store-pos-software/" @class(['active' => $isHere('/furniture-store-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Furniture Store</span></a>
                            <a href="{{ $base }}/toy-shop-pos-in-uae/" @class(['active' => $isHere('/toy-shop-pos-in-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Toy Shop POS</span></a>
                            <a href="{{ $base }}/gold-shop-pos-software/" @class(['active' => $isHere('/gold-shop-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Gold Shop POS</span></a>
                            <a href="{{ $base }}/perfume-shop-pos-software-in-uae/" @class(['active' => $isHere('/perfume-shop-pos-software-in-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Perfume Shop</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Food</div>
                            <a href="{{ $base }}/bakery-pos-software/" @class(['active' => $isHere('/bakery-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Bakery POS</span></a>
                            <a href="{{ $base }}/fast-food-pos-software/" @class(['active' => $isHere('/fast-food-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Fast Food</span></a>
                            <a href="{{ $base }}/food-truck-pos-software/" @class(['active' => $isHere('/food-truck-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Food Truck</span></a>
                            <a href="{{ $base }}/coffee-shop-pos-system-uae/" @class(['active' => $isHere('/coffee-shop-pos-system-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Coffee Shop POS</span></a>
                            <a href="{{ $base }}/chocolate-shop-pos-system-in-the-uae/" @class(['active' => $isHere('/chocolate-shop-pos-system-in-the-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Chocolate Shop</span></a>
                            <a href="{{ $base }}/hotel-pos-software/" @class(['active' => $isHere('/hotel-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Hotel POS</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Care</div>
                            <a href="{{ $base }}/nail-salon-pos-software/" @class(['active' => $isHere('/nail-salon-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Nail Salon</span></a>
                            <a href="{{ $base }}/barber-shop-pos-software/" @class(['active' => $isHere('/barber-shop-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Barber Shop</span></a>
                            <a href="{{ $base }}/spa-pos-software-uae/" @class(['active' => $isHere('/spa-pos-software-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Spa POS</span></a>
                            <a href="{{ $base }}/medical-spa-pos-software/" @class(['active' => $isHere('/medical-spa-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Medical Spa</span></a>
                            <a href="{{ $base }}/beauty-clinic-pos-software-uae/" @class(['active' => $isHere('/beauty-clinic-pos-software-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Beauty Clinic POS</span></a>
                            <a href="{{ $base }}/pharmacy-pos-system-in-the-uae/" @class(['active' => $isHere('/pharmacy-pos-system-in-the-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Pharmacy</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Services</div>
                            <a href="{{ $base }}/gym-pos-software/" @class(['active' => $isHere('/gym-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Gym POS</span></a>
                            <a href="{{ $base }}/delivery-pos-software/" @class(['active' => $isHere('/delivery-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Delivery POS</span></a>
                            <a href="{{ $base }}/van-sales-management-system-in-uae/" @class(['active' => $isHere('/van-sales-management-system-in-uae/')])><span class="mtp-ico" aria-hidden="true"></span><span>Van Sales</span></a>
                            <a href="{{ $base }}/car-wash-pos-software/" @class(['active' => $isHere('/car-wash-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Car Wash</span></a>
                            <a href="{{ $base }}/bike-shop-pos-software/" @class(['active' => $isHere('/bike-shop-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Bike Shop</span></a>
                            <a href="{{ $base }}/gaming-store-pos-software/" @class(['active' => $isHere('/gaming-store-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Gaming Store</span></a>
                        </div>
                        <div class="mtp-mega-col">
                            <div class="mtp-mega-head">Specialty</div>
                            <a href="{{ $base }}/gas-station-pos-software/" @class(['active' => $isHere('/gas-station-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Gas Station</span></a>
                            <a href="{{ $base }}/golf-pos-software/" @class(['active' => $isHere('/golf-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Golf POS</span></a>
                            <a href="{{ $base }}/hemp-pos-software/" @class(['active' => $isHere('/hemp-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Hemp POS</span></a>
                            <a href="{{ $base }}/music-store-pos-point-of-sale-software/" @class(['active' => $isHere('/music-store-pos-point-of-sale-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Music Store</span></a>
                            <a href="{{ $base }}/pet-shop-pos-software/" @class(['active' => $isHere('/pet-shop-pos-software/')])><span class="mtp-ico" aria-hidden="true"></span><span>Pet Shop POS</span></a>
                        </div>
                    </div>
                    <div class="mtp-mega-foot">
                        <span class="mtp-mega-note">Secure POS for every kind of shop across the UAE.</span>
                        <span class="mtp-mega-actions">
                            <a class="mtp-mega-all" href="{{ $base }}/more-pos-industries/">View all industries</a>
                            <a class="mtp-mega-cta" href="{{ $base }}/contact/">Request Demo</a>
                        </span>
                    </div>
                </div>
            </div>

            <a href="{{ $base }}/contact/" @class(['active' => $isHere('/contact/')])>Contact</a>
        </nav>
        <div class="mtp-header-cta"><a class="mtp-book" href="{{ $base }}/contact/">Request Demo</a></div>
        <button type="button" class="mtp-nav-toggle" aria-label="Open menu" aria-expanded="false" aria-controls="mtp-nav"><span></span><span></span><span></span></button>
    </div>
</header>

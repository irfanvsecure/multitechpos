<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// SEO
Route::get('/sitemap.xml', SitemapController::class);

// Demo / quote forms
Route::post('/request-demo', [LeadController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('lead.store');

// Old WordPress URLs -> their new home (301, keeps search rankings).
// RedirectResponse is used directly because redirect() would drop the trailing slash.
$moved = fn (string $to) => fn (Request $request) => new RedirectResponse(
    $request->getSchemeAndHttpHost().$request->getBaseUrl().$to, 301
);

foreach (['wp-sitemap.xml', 'sitemap_index.xml', 'page-sitemap.xml', 'post-sitemap.xml'] as $old) {
    Route::get($old, $moved('/sitemap.xml'));
}
Route::get('/wp-sitemap-{any}.xml', $moved('/sitemap.xml'))->where('any', '.*');
Route::get('/feed', $moved('/our-blogs/'));
Route::get('/comments/feed', $moved('/our-blogs/'));
Route::get('/blog', $moved('/our-blogs/'));
Route::get('/category/{any}', $moved('/our-blogs/'))->where('any', '.*');
Route::get('/author/multitech_admin/page/{n}', $moved('/author/multitech_admin/'));
Route::get('/download/restropro', $moved('/restropro/'));

// Images moved from /wp-content/uploads/... to /images/...
Route::get('/wp-content/uploads/{path}', fn (Request $request, string $path) => new RedirectResponse(
    $request->getSchemeAndHttpHost().$request->getBaseUrl().'/images/'.$path, 301
))->where('path', '.*');

// ---------------------------------------------------------------------------
// Pages (one route per page, grouped like the header menu).
// To add a page: create resources/views/<slug>.blade.php and add a line here.
// ---------------------------------------------------------------------------

// Company (3)
Route::view('/', 'index')->name('home');
Route::view('/about-us/', 'about-us')->name('about-us');
Route::view('/contact/', 'contact')->name('contact');

// POS Software (11)
Route::view('/business-size/', 'business-size')->name('business-size');
Route::view('/by-industry/', 'by-industry')->name('by-industry');
Route::view('/custom-pos-software-uae/', 'custom-pos-software-uae')->name('custom-pos-software-uae');
Route::view('/enterprise-pos-uae/', 'enterprise-pos-uae')->name('enterprise-pos-uae');
Route::view('/food-restaurant-pos-software-uae/', 'food-restaurant-pos-software-uae')->name('food-restaurant-pos-software-uae');
Route::view('/multi-branch-business-pos-uae/', 'multi-branch-business-pos-uae')->name('multi-branch-business-pos-uae');
Route::view('/pos-software-uae/', 'pos-software-uae')->name('pos-software-uae');
Route::view('/retail-pos-software-uae/', 'retail-pos-software-uae')->name('retail-pos-software-uae');
Route::view('/salon-spa-pos-software-uae/', 'salon-spa-pos-software-uae')->name('salon-spa-pos-software-uae');
Route::view('/service-business-pos-software-uae/', 'service-business-pos-software-uae')->name('service-business-pos-software-uae');
Route::view('/small-business-pos-uae/', 'small-business-pos-uae')->name('small-business-pos-uae');

// POS Features (8)
Route::view('/appointment-booking-pos-uae/', 'appointment-booking-pos-uae')->name('appointment-booking-pos-uae');
Route::view('/customers/', 'customers')->name('customers');
Route::view('/features/', 'features')->name('features');
Route::view('/mobile-multitechpos/', 'mobile-multitechpos')->name('mobile-multitechpos');
Route::view('/multi-location-integration/', 'multi-location-integration')->name('multi-location-integration');
Route::view('/paperless-invoicing/', 'paperless-invoicing')->name('paperless-invoicing');
Route::view('/table-management-pos-uae/', 'table-management-pos-uae')->name('table-management-pos-uae');
Route::view('/woocommerce-pos/', 'woocommerce-pos')->name('woocommerce-pos');

// Business ERPs (12)
Route::view('/accounting-software/', 'accounting-software')->name('accounting-software');
Route::view('/business-erps/', 'business-erps')->name('business-erps');
Route::view('/distribution-management/', 'distribution-management')->name('distribution-management');
Route::view('/employee-management/', 'employee-management')->name('employee-management');
Route::view('/hospital-management/', 'hospital-management')->name('hospital-management');
Route::view('/laundry-management/', 'laundry-management')->name('laundry-management');
Route::view('/payroll-software/', 'payroll-software')->name('payroll-software');
Route::view('/restaurant-management/', 'restaurant-management')->name('restaurant-management');
Route::view('/retail-management/', 'retail-management')->name('retail-management');
Route::view('/salon-management/', 'salon-management')->name('salon-management');
Route::view('/stock-management/', 'stock-management')->name('stock-management');
Route::view('/tailor-management/', 'tailor-management')->name('tailor-management');

// Downloads (5)
Route::view('/downloads/', 'downloads')->name('downloads');
Route::view('/laundrypro/', 'laundrypro')->name('laundrypro');
Route::view('/restropro/', 'restropro')->name('restropro');
Route::view('/salonpro/', 'salonpro')->name('salonpro');
Route::view('/tailorpro/', 'tailorpro')->name('tailorpro');

// Industries (39)
Route::view('/bakery-pos-software/', 'bakery-pos-software')->name('bakery-pos-software');
Route::view('/barber-shop-pos-software/', 'barber-shop-pos-software')->name('barber-shop-pos-software');
Route::view('/beauty-clinic-pos-software-uae/', 'beauty-clinic-pos-software-uae')->name('beauty-clinic-pos-software-uae');
Route::view('/bike-shop-pos-software/', 'bike-shop-pos-software')->name('bike-shop-pos-software');
Route::view('/book-store-pos-software/', 'book-store-pos-software')->name('book-store-pos-software');
Route::view('/cafeteria-pos-software-systems/', 'cafeteria-pos-software-systems')->name('cafeteria-pos-software-systems');
Route::view('/car-wash-pos-software/', 'car-wash-pos-software')->name('car-wash-pos-software');
Route::view('/chocolate-shop-pos-system-in-the-uae/', 'chocolate-shop-pos-system-in-the-uae')->name('chocolate-shop-pos-system-in-the-uae');
Route::view('/coffee-shop-pos-system-uae/', 'coffee-shop-pos-system-uae')->name('coffee-shop-pos-system-uae');
Route::view('/dates-shop-pos-system-in-the-uae/', 'dates-shop-pos-system-in-the-uae')->name('dates-shop-pos-system-in-the-uae');
Route::view('/delivery-pos-software/', 'delivery-pos-software')->name('delivery-pos-software');
Route::view('/digital-dining-pos-software/', 'digital-dining-pos-software')->name('digital-dining-pos-software');
Route::view('/dispensary-pos-software/', 'dispensary-pos-software')->name('dispensary-pos-software');
Route::view('/fashion-pos-system-in-uae/', 'fashion-pos-system-in-uae')->name('fashion-pos-system-in-uae');
Route::view('/fast-food-pos-software/', 'fast-food-pos-software')->name('fast-food-pos-software');
Route::view('/flower-shop-pos-system-in-the-uae/', 'flower-shop-pos-system-in-the-uae')->name('flower-shop-pos-system-in-the-uae');
Route::view('/food-truck-pos-software/', 'food-truck-pos-software')->name('food-truck-pos-software');
Route::view('/footwear-pos-system-uae/', 'footwear-pos-system-uae')->name('footwear-pos-system-uae');
Route::view('/furniture-store-pos-software/', 'furniture-store-pos-software')->name('furniture-store-pos-software');
Route::view('/gaming-store-pos-software/', 'gaming-store-pos-software')->name('gaming-store-pos-software');
Route::view('/gas-station-pos-software/', 'gas-station-pos-software')->name('gas-station-pos-software');
Route::view('/gift-store-pos-system-in-the-uae/', 'gift-store-pos-system-in-the-uae')->name('gift-store-pos-system-in-the-uae');
Route::view('/gold-shop-pos-software/', 'gold-shop-pos-software')->name('gold-shop-pos-software');
Route::view('/golf-pos-software/', 'golf-pos-software')->name('golf-pos-software');
Route::view('/grocery-pos-system-in-uae/', 'grocery-pos-system-in-uae')->name('grocery-pos-system-in-uae');
Route::view('/gym-pos-software/', 'gym-pos-software')->name('gym-pos-software');
Route::view('/hemp-pos-software/', 'hemp-pos-software')->name('hemp-pos-software');
Route::view('/hotel-pos-software/', 'hotel-pos-software')->name('hotel-pos-software');
Route::view('/jewelry-pos-system-in-uae/', 'jewelry-pos-system-in-uae')->name('jewelry-pos-system-in-uae');
Route::view('/medical-spa-pos-software/', 'medical-spa-pos-software')->name('medical-spa-pos-software');
Route::view('/more-pos-industries/', 'more-pos-industries')->name('more-pos-industries');
Route::view('/music-store-pos-point-of-sale-software/', 'music-store-pos-point-of-sale-software')->name('music-store-pos-point-of-sale-software');
Route::view('/nail-salon-pos-software/', 'nail-salon-pos-software')->name('nail-salon-pos-software');
Route::view('/perfume-shop-pos-software-in-uae/', 'perfume-shop-pos-software-in-uae')->name('perfume-shop-pos-software-in-uae');
Route::view('/pet-shop-pos-software/', 'pet-shop-pos-software')->name('pet-shop-pos-software');
Route::view('/pharmacy-pos-system-in-the-uae/', 'pharmacy-pos-system-in-the-uae')->name('pharmacy-pos-system-in-the-uae');
Route::view('/spa-pos-software-uae/', 'spa-pos-software-uae')->name('spa-pos-software-uae');
Route::view('/toy-shop-pos-in-uae/', 'toy-shop-pos-in-uae')->name('toy-shop-pos-in-uae');
Route::view('/van-sales-management-system-in-uae/', 'van-sales-management-system-in-uae')->name('van-sales-management-system-in-uae');

// Blog (42)
Route::view('/author/multitech_admin/', 'author.multitech_admin')->name('author.multitech_admin');
Route::view('/best-point-of-sale-system-for-salons-spas-2026/', 'best-point-of-sale-system-for-salons-spas-2026')->name('best-point-of-sale-system-for-salons-spas-2026');
Route::view('/choosing-restaurant-pos-software-uae-guide/', 'choosing-restaurant-pos-software-uae-guide')->name('choosing-restaurant-pos-software-uae-guide');
Route::view('/cloud-vs-on-premise-restaurant-pos-uae-guide/', 'cloud-vs-on-premise-restaurant-pos-uae-guide')->name('cloud-vs-on-premise-restaurant-pos-uae-guide');
Route::view('/cloud-vs-traditional-pos-for-salons/', 'cloud-vs-traditional-pos-for-salons')->name('cloud-vs-traditional-pos-for-salons');
Route::view('/essential-restaurant-pos-features/', 'essential-restaurant-pos-features')->name('essential-restaurant-pos-features');
Route::view('/florists-manage-multiple-locations-pos-software/', 'florists-manage-multiple-locations-pos-software')->name('florists-manage-multiple-locations-pos-software');
Route::view('/gold-jewelry-pricing-uae/', 'gold-jewelry-pricing-uae')->name('gold-jewelry-pricing-uae');
Route::view('/gym-membership-management-uae/', 'gym-membership-management-uae')->name('gym-membership-management-uae');
Route::view('/how-click-and-collect-works/', 'how-click-and-collect-works')->name('how-click-and-collect-works');
Route::view('/how-mobile-pos-simplifies-salon-operations/', 'how-mobile-pos-simplifies-salon-operations')->name('how-mobile-pos-simplifies-salon-operations');
Route::view('/how-pos-software-manages-car-wash-inventory/', 'how-pos-software-manages-car-wash-inventory')->name('how-pos-software-manages-car-wash-inventory');
Route::view('/how-to-choose-a-point-of-sale-system-for-your-beauty-business/', 'how-to-choose-a-point-of-sale-system-for-your-beauty-business')->name('how-to-choose-a-point-of-sale-system-for-your-beauty-business');
Route::view('/how-to-choose-flower-shop-pos-uae-guide/', 'how-to-choose-flower-shop-pos-uae-guide')->name('how-to-choose-flower-shop-pos-uae-guide');
Route::view('/one-customer-two-records/', 'one-customer-two-records')->name('one-customer-two-records');
Route::view('/our-blogs/', 'our-blogs')->name('our-blogs');
Route::view('/pharmacy-expiry-date-management/', 'pharmacy-expiry-date-management')->name('pharmacy-expiry-date-management');
Route::view('/pos-system-transforms-car-wash-operations-uae/', 'pos-system-transforms-car-wash-operations-uae')->name('pos-system-transforms-car-wash-operations-uae');
Route::view('/restaurant-delivery-app-integration-uae/', 'restaurant-delivery-app-integration-uae')->name('restaurant-delivery-app-integration-uae');
Route::view('/restaurant-menu-engineering-uae/', 'restaurant-menu-engineering-uae')->name('restaurant-menu-engineering-uae');
Route::view('/restaurant-pos-buying-guide-uae/', 'restaurant-pos-buying-guide-uae')->name('restaurant-pos-buying-guide-uae');
Route::view('/restaurant-pos-cuts-food-costs-uae/', 'restaurant-pos-cuts-food-costs-uae')->name('restaurant-pos-cuts-food-costs-uae');
Route::view('/retail-inventory-shrinkage-uae/', 'retail-inventory-shrinkage-uae')->name('retail-inventory-shrinkage-uae');
Route::view('/salon-appointment-management-reduce-no-shows-uae/', 'salon-appointment-management-reduce-no-shows-uae')->name('salon-appointment-management-reduce-no-shows-uae');
Route::view('/salon-management-software-features-uae-2026/', 'salon-management-software-features-uae-2026')->name('salon-management-software-features-uae-2026');
Route::view('/salon-pos-reduces-no-shows-client-loyalty-uae/', 'salon-pos-reduces-no-shows-client-loyalty-uae')->name('salon-pos-reduces-no-shows-client-loyalty-uae');
Route::view('/salon-pos-systems-uae-increase-revenue/', 'salon-pos-systems-uae-increase-revenue')->name('salon-pos-systems-uae-increase-revenue');
Route::view('/signs-upgrade-car-wash-pos-uae/', 'signs-upgrade-car-wash-pos-uae')->name('signs-upgrade-car-wash-pos-uae');
Route::view('/uae-e-invoicing-for-distributors/', 'uae-e-invoicing-for-distributors')->name('uae-e-invoicing-for-distributors');
Route::view('/uae-e-invoicing-guide-for-retailers/', 'uae-e-invoicing-guide-for-retailers')->name('uae-e-invoicing-guide-for-retailers');
Route::view('/van-sales-pos-software-buying-guide-uae/', 'van-sales-pos-software-buying-guide-uae')->name('van-sales-pos-software-buying-guide-uae');
Route::view('/vat-compliant-pos-software-uae-salons/', 'vat-compliant-pos-software-uae-salons')->name('vat-compliant-pos-software-uae-salons');
Route::view('/what-is-beauty-clinic-pos-system/', 'what-is-beauty-clinic-pos-system')->name('what-is-beauty-clinic-pos-system');
Route::view('/what-is-restaurant-pos-software-uae/', 'what-is-restaurant-pos-software-uae')->name('what-is-restaurant-pos-software-uae');
Route::view('/what-makes-a-tax-invoice-valid/', 'what-makes-a-tax-invoice-valid')->name('what-makes-a-tax-invoice-valid');
Route::view('/when-online-orders-come-back-to-the-store/', 'when-online-orders-come-back-to-the-store')->name('when-online-orders-come-back-to-the-store');
Route::view('/why-salon-inventory-management-matters/', 'why-salon-inventory-management-matters')->name('why-salon-inventory-management-matters');
Route::view('/why-store-and-online-prices-drift/', 'why-store-and-online-prices-drift')->name('why-store-and-online-prices-drift');
Route::view('/why-your-daily-totals-do-not-match/', 'why-your-daily-totals-do-not-match')->name('why-your-daily-totals-do-not-match');
Route::view('/woocommerce-integration-for-uae-retailers-how-multitech-pos-keeps-every-sale-in-sync/', 'woocommerce-integration-for-uae-retailers-how-multitech-pos-keeps-every-sale-in-sync')->name('woocommerce-integration-for-uae-retailers-how-multitech-pos-keeps-every-sale-in-sync');
Route::view('/woocommerce-inventory-sync-how-variations-bundles-and-multi-branch-stock-actually-work/', 'woocommerce-inventory-sync-how-variations-bundles-and-multi-branch-stock-actually-work')->name('woocommerce-inventory-sync-how-variations-bundles-and-multi-branch-stock-actually-work');
Route::view('/your-pos-data-is-not-web-ready/', 'your-pos-data-is-not-web-ready')->name('your-pos-data-is-not-web-ready');

// Legal & help (6)
Route::view('/cookie-policy/', 'cookie-policy')->name('cookie-policy');
Route::view('/frequently-asked-questions-multitech-pos/', 'frequently-asked-questions-multitech-pos')->name('frequently-asked-questions-multitech-pos');
Route::view('/privacy-policy/', 'privacy-policy')->name('privacy-policy');
Route::view('/security-policy/', 'security-policy')->name('security-policy');
Route::view('/support-policy/', 'support-policy')->name('support-policy');
Route::view('/terms-of-service/', 'terms-of-service')->name('terms-of-service');

// After a demo / quote form is sent (not indexed by search engines)
Route::view('/thank-you/', 'thank-you')->name('thank-you');

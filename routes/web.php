<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// SEO
Route::get('/sitemap.xml', SitemapController::class);
Route::get('/robots.txt', RobotsController::class);

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
Route::get('/', function (\Illuminate\Http\Request $request) {
    // Redirect legacy ?item/... URLs (e.g. /?item/8109777586) to clean home
    if (str_starts_with($request->server('QUERY_STRING', ''), 'item')) {
        return redirect('/', 301);
    }
    return app(PageController::class)->show($request, '');
})->name('home');
Route::view('/about-us/', 'about-us')->name('about-us');
Route::view('/contact/', 'contact')->name('contact');

// POS Software (11)

// POS Features (8)

// Business ERPs (12)

// Downloads (5)

// Industries (39)

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

// Pages and blog posts stored in the database (managed through the MCP server).
// Unknown paths: a saved redirect / 410, else 301 to the home page (the old catch-all).
Route::get('/{path}', [PageController::class, 'show'])->where('path', '.*')->name('page');

// Catch-all for other methods: any URL not matched above → home (301)
Route::fallback(fn () => redirect('/', 301));

<?php

namespace Tests\Support;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

/**
 * Imports a slice of the real site content (database/content/site.json) for feature tests.
 */
trait LoadsSiteContent
{
    use RefreshDatabase;

    /** Pages to import ('' = home). */
    protected array $pages = ['', 'retail-pos-software-uae', 'pos-software-uae', 'by-industry', 'bakery-pos-software',
        'pharmacy-pos-system-in-the-uae', 'salonpro', 'restropro', 'our-blogs', 'contact', 'frequently-asked-questions-multitech-pos', 'thank-you'];

    protected array $posts = ['what-makes-a-tax-invoice-valid', 'gold-jewelry-pricing-uae', 'uae-e-invoicing-guide-for-retailers'];

    protected function loadSiteContent(): void
    {
        $data = json_decode(File::get(base_path('database/content/site.json')), true);
        $data['pages'] = array_values(array_filter($data['pages'], fn ($p) => in_array($p['slug'], $this->pages, true)));
        foreach ($data['pages'] as &$p) {
            if ($p['parent'] && ! in_array($p['parent'], $this->pages, true)) {
                $p['parent'] = null;
            }
        }
        unset($p);
        $data['posts'] = array_values(array_filter($data['posts'], fn ($p) => in_array($p['slug'], $this->posts, true)));
        $data['faqs'] = array_values(array_filter($data['faqs'], fn ($f) => in_array($f['page'], $this->pages, true)));
        $data['downloads'] = array_values(array_map(function ($d) {
            $d['page'] = in_array($d['page'], $this->pages, true) ? $d['page'] : null;

            return $d;
        }, $data['downloads']));
        $file = storage_path('framework/testing/site-slice.json');
        File::ensureDirectoryExists(dirname($file));
        File::put($file, json_encode($data));
        Artisan::call('cms:import', ['--file' => 'storage/framework/testing/site-slice.json']);
    }
}

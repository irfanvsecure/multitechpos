<?php

namespace App\Console\Commands;

use App\Cms\Cms;
use App\Cms\Components;
use App\Mcp\Support\Activity;
use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Console\Command;

/**
 * Gives each page's "service-why" block (before/after + 4 steps) its own copy, from
 * database/content/service-why.json ({slug: attrs}). Only the component's attrs change,
 * so the layout stays the shared Blade design. Keeps a revision of every section it changes.
 * Pages whose block was hidden and rebuilt as an html section are reported, not touched.
 */
class CmsServiceWhy extends Command
{
    protected $signature = 'cms:service-why {--file=database/content/service-why.json} {--dry-run : Show what would change}';

    protected $description = 'Set per-page copy on the service-why component from a JSON file';

    public function handle(): int
    {
        $file = base_path($this->option('file'));
        $data = is_file($file) ? json_decode((string) file_get_contents($file), true) : null;
        if (! is_array($data)) {
            $this->error("Cannot read $file");

            return self::FAILURE;
        }

        $dry = (bool) $this->option('dry-run');
        $changed = $same = 0;
        $problems = [];

        foreach ($data as $slug => $attrs) {
            $page = Page::query()->where('slug', $slug)->first();
            $section = $page ? PageSection::query()->where('page_id', $page->id)->where('component', 'service-why')->first() : null;
            if (! $section) {
                $problems[] = "$slug: ".($page ? 'no service-why section' : 'no such page');

                continue;
            }

            $new = ['service' => $section->attrs['service'] ?? $page->name] + $attrs;
            if ($errors = Components::validate('service-why', $new)) {
                $problems[] = "$slug: ".implode(' ', $errors);

                continue;
            }
            if (! $section->is_visible) {
                $problems[] = "$slug: service-why is hidden (an html copy probably replaced it). Attrs set; show it and remove the copy by hand.";
            }
            if ($new == ($section->attrs ?? [])) {
                $same++;

                continue;
            }

            $changed++;
            $this->line(($dry ? '[dry-run] ' : '')."$slug ← ".($attrs['compare_heading'] ?? '').' '.($attrs['compare_highlight'] ?? ''));
            if (! $dry) {
                Activity::revision($section, 'cms:service-why');
                $section->attrs = $new;
                $section->save();
            }
        }

        if (! $dry && $changed) {
            Cms::flush([]);
        }

        $this->info(($dry ? 'Would change' : 'Changed')." $changed, unchanged $same.");
        foreach ($problems as $p) {
            $this->warn($p);
        }

        return self::SUCCESS;
    }
}

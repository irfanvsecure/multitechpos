<?php

namespace App\Cms;

/**
 * Code components a page section can use (type=component), or an html section can
 * place with {{embed:N}}. Their markup lives in Blade; only their options are content.
 */
class Components
{
    /**
     * name => [view, description, attrs (name => description), h1 count, embed_only].
     */
    public const LIST = [
        'page-hero' => ['partials.page-hero', 'Dark banner with breadcrumb, the page H1, intro text, demo/WhatsApp buttons and the short demo form. Also outputs BreadcrumbList schema.', [
            'title' => 'string, required. The H1 (may contain simple inline HTML such as <span class="text-gradient">)',
            'image' => 'string, optional. Background image path, e.g. /images/2025/12/x.jpeg',
            'parent' => 'object {name, url}, optional. Middle breadcrumb item',
            'text' => 'string, optional. Intro under the H1 (defaults to the meta description)',
            'actions' => 'boolean, optional. false hides the buttons and the demo form',
        ], 1, false],
        'home-hero' => ['cms.components.home-hero', 'Home page hero: H1 (A/B test when AB_TEST=true), check list, demo form (business types from form_options "business_types"), stats bar from settings.', [], 1, false],
        'business-picker' => ['cms.components.business-picker', 'Home "What kind of business do you run?" tabs, from form_options "business_types" (meta: icon, tile, url, text, points).', [
            'tag' => 'string', 'heading' => 'string (HTML allowed)', 'text' => 'string',
        ], 0, false],
        'plans' => ['cms.components.plans', 'Plans (no prices unless set) from pricing_plans.', [
            'tag' => 'string', 'heading' => 'string (HTML allowed)', 'text' => 'string', 'note' => 'string',
        ], 0, false],
        'home-faq' => ['cms.components.home-faq', 'FAQ accordion (this page\'s faqs) with the contact box (phone/WhatsApp/email from settings).', [
            'tag' => 'string', 'heading' => 'string (HTML allowed)', 'text' => 'string',
        ], 0, false],
        'schema-faq' => ['cms.components.schema-faq', 'FAQPage JSON-LD built from this page\'s faqs (outputs nothing visible).', [], 0, false],
        'schema-breadcrumb' => ['partials.schema-breadcrumb', 'BreadcrumbList JSON-LD (nothing visible).', [
            'items' => 'list of {name, url}; url "$current" = this page',
        ], 0, false],
        'clients-band' => ['partials.clients-band', 'Client logo marquee from client_logos.', [], 0, false],
        'cta-band' => ['partials.cta-band', 'Demo call-to-action banner.', ['ctaTitle' => 'string, optional heading'], 0, false],
        'service-why' => ['partials.service-why', 'Before/after card pair + 4-step timeline for service pages, in the site design. Every text is optional and per page (leave one out to keep the default copy); text is plain (no HTML), placeholders allowed. Change THIS component\'s attrs to tailor the section; never hide it and rebuild it as an html section.', [
            'service' => 'string, the product name',
            'compare_tag' => 'string, small pill above the heading (default "Why Businesses Switch")',
            'compare_heading' => 'string, h2 of the before/after part (plain text)',
            'compare_highlight' => 'string, optional words after the heading shown in the blue gradient',
            'compare_text' => 'string, line under the heading',
            'before_label' => 'string, red card title (default "Without the right POS")',
            'before' => 'list of 3-6 strings, the red "without" points',
            'after_label' => 'string, white card title (default "With MultiTech POS")',
            'after' => 'list of 3-6 strings, the "with MultiTech POS" points',
            'after_button' => 'string, button text (default "See it in a free demo")',
            'steps_tag' => 'string, pill above the timeline heading (default "How It Works")',
            'steps_heading' => 'string, h2 of the timeline (plain text)',
            'steps_highlight' => 'string, optional gradient words after steps_heading',
            'steps_text' => 'string, line under the timeline heading',
            'steps' => 'list of exactly 4 {title, text} objects, the timeline steps',
        ], 0, false],
        'service-quote' => ['partials.service-quote', '"Everything you need to get started" tiles + testimonials.', ['service' => 'string, the product name'], 0, false],
        'testimonials' => ['partials.testimonials', 'Customer testimonials from the testimonials table (hidden while empty).', [], 0, false],
        'contact-next' => ['partials.contact-next', 'Contact page: what happens after the form is sent.', [], 0, false],
        'post-cta' => ['partials.post-cta', 'Call-to-action card used at the end of blog posts.', [], 0, false],
        'conversion-tracking' => ['cms.components.conversion-tracking', 'Thank-you page only: sends the "generate_lead" conversion to Google Tag Manager / Analytics (nothing visible).', [], 0, false],
        'post-grid' => ['cms.components.post-grid', 'Blog list cards from published posts (newest first).', ['limit' => 'integer, optional (default 6)'], 0, false],
        'quote-form' => ['cms.components.quote-form', '"Get your quote" lead form (CSRF protected). Embed only: place it in an html section with {{embed:N}}. Service options from form_options "quote_service".', [
            'variant' => 'string: "standard" (name, email, phone, company, service, message) or "names" (name + message, used on /pos-software-uae/)',
        ], 0, true],
    ];

    public static function exists(string $name): bool
    {
        return isset(self::LIST[$name]);
    }

    public static function view(string $name): string
    {
        return self::LIST[$name][0];
    }

    public static function h1(string $name): int
    {
        return self::LIST[$name][3] ?? 0;
    }

    public static function describe(): array
    {
        $out = [];
        foreach (self::LIST as $name => [$view, $description, $attrs, $h1, $embedOnly]) {
            $out[] = ['name' => $name, 'description' => $description, 'attrs' => (object) $attrs, 'renders_h1' => (bool) $h1, 'embed_only' => $embedOnly];
        }

        return $out;
    }

    /** Problems with a component name + attrs (empty = fine). */
    public static function validate(string $name, mixed $attrs, bool $asEmbed = false): array
    {
        if (! self::exists($name)) {
            return ["Unknown component '$name'. Use list_components."];
        }
        if (! $asEmbed && self::LIST[$name][4]) {
            return ["Component '$name' can only be embedded in an html section with {{embed:N}}."];
        }
        if ($attrs !== null && ! is_array($attrs)) {
            return ['attrs must be an object.'];
        }
        $allowed = array_keys(self::LIST[$name][2]);
        $errors = [];
        foreach (array_keys($attrs ?? []) as $key) {
            if (! in_array($key, $allowed, true)) {
                $errors[] = "Component '$name' has no option '$key'. Options: ".(implode(', ', $allowed) ?: 'none');
            }
        }
        if ($name === 'page-hero' && blank($attrs['title'] ?? null)) {
            $errors[] = 'page-hero needs a title.';
        }
        if ($name === 'quote-form' && isset($attrs['variant']) && ! in_array($attrs['variant'], ['standard', 'names'], true)) {
            $errors[] = 'quote-form variant must be "standard" or "names".';
        }
        if ($name === 'service-why') {
            $errors = array_merge($errors, self::validateServiceWhy($attrs ?? []));
        }
        foreach ($attrs ?? [] as $value) {
            foreach (Placeholders::validate(is_string($value) ? $value : json_encode($value)) as $e) {
                $errors[] = $e;
            }
        }

        return $errors;
    }

    /** service-why texts must keep the shape the fixed layout expects. */
    private static function validateServiceWhy(array $attrs): array
    {
        $errors = [];
        foreach ($attrs as $key => $value) {
            if (in_array($key, ['before', 'after', 'steps'], true)) {
                continue;
            }
            if (! is_string($value)) {
                $errors[] = "service-why '$key' must be a string.";
            } elseif ($value !== strip_tags($value)) {
                $errors[] = "service-why '$key' is plain text: no HTML.";
            }
        }
        foreach (['before', 'after'] as $key) {
            if (! array_key_exists($key, $attrs)) {
                continue;
            }
            $list = $attrs[$key];
            if (! is_array($list) || ! array_is_list($list) || count($list) < 3 || count($list) > 6) {
                $errors[] = "service-why '$key' must be a list of 3 to 6 strings.";
                continue;
            }
            foreach ($list as $item) {
                if (! is_string($item) || trim($item) === '' || $item !== strip_tags($item)) {
                    $errors[] = "service-why '$key' items must be non-empty plain text (no HTML).";
                    break;
                }
            }
        }
        if (array_key_exists('steps', $attrs)) {
            $steps = $attrs['steps'];
            if (! is_array($steps) || ! array_is_list($steps) || count($steps) !== 4) {
                $errors[] = "service-why 'steps' must be a list of exactly 4 {title, text} objects.";
            } else {
                foreach ($steps as $step) {
                    if (! is_array($step) || array_diff(array_keys($step), ['title', 'text'])
                        || ! is_string($step['title'] ?? null) || trim($step['title']) === ''
                        || ! is_string($step['text'] ?? null) || trim($step['text']) === ''
                        || $step['title'] !== strip_tags($step['title']) || $step['text'] !== strip_tags($step['text'])) {
                        $errors[] = "service-why 'steps' items need a plain-text title and text (no other keys, no HTML).";
                        break;
                    }
                }
            }
        }

        return $errors;
    }
}

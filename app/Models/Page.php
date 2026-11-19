<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    public const TYPES = ['core_pos', 'business_size', 'industry', 'feature', 'erp', 'download', 'standard', 'legal'];

    protected $guarded = ['id'];

    protected $casts = ['show_cro' => 'boolean'];

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order')->orderBy('id');
    }

    public function parent()
    {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function faqs()
    {
        return $this->hasMany(Faq::class)->orderBy('sort_order')->orderBy('id');
    }

    public function schemaBlocks()
    {
        return $this->morphMany(SchemaBlock::class, 'schemable');
    }

    public static function pathFor(string $slug): string
    {
        return $slug === '' ? '/' : '/'.trim($slug, '/').'/';
    }
}

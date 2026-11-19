<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = ['published_at' => 'datetime', 'options' => 'array'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function seo()
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function schemaBlocks()
    {
        return $this->morphMany(SchemaBlock::class, 'schemable');
    }

    public function revisions()
    {
        return $this->morphMany(SectionRevision::class, 'revisionable')->latest('id');
    }

    /** Published, or scheduled with a publish time that has passed. */
    public function scopeLive(Builder $q): Builder
    {
        return $q->whereIn('status', ['published', 'scheduled'])->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getPathAttribute(): string
    {
        return '/'.$this->slug.'/';
    }
}

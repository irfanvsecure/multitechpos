<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PageSection extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = ['attrs' => 'array', 'embeds' => 'array', 'is_visible' => 'boolean'];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function revisions()
    {
        return $this->morphMany(SectionRevision::class, 'revisionable')->latest('id');
    }
}

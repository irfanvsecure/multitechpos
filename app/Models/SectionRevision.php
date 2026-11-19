<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A saved copy of a section (or blog post) before a change. */
class SectionRevision extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = ['id'];

    protected $casts = ['snapshot' => 'array'];

    public function revisionable()
    {
        return $this->morphTo();
    }
}

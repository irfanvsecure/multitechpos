<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemaBlock extends Model
{
    use SoftDeletes;

    protected $table = 'schema_blocks';

    protected $guarded = ['id'];

    protected $casts = ['data' => 'array', 'is_active' => 'boolean'];

    public function schemable()
    {
        return $this->morphTo();
    }
}

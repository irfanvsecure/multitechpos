<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use SoftDeletes;

    protected $table = 'offices';

    protected $guarded = ['id'];

    protected $casts = ['is_primary' => 'boolean'];
}

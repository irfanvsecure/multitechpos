<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HardwareItem extends Model
{
    use SoftDeletes;

    protected $table = 'hardware_items';

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];
}

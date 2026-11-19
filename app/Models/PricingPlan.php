<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PricingPlan extends Model
{
    use SoftDeletes;

    protected $table = 'pricing_plans';

    protected $guarded = ['id'];

    protected $casts = ['inclusions' => 'array', 'is_featured' => 'boolean', 'is_active' => 'boolean'];
}

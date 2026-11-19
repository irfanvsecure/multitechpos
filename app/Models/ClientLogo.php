<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientLogo extends Model
{
    use SoftDeletes;

    protected $table = 'client_logos';

    protected $guarded = ['id'];

    protected $casts = ['is_active' => 'boolean'];
}

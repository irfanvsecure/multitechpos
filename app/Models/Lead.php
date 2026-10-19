<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A demo / quote request sent from one of the website forms.
 */
class Lead extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'company', 'service', 'message', 'page', 'ip',
    ];
}

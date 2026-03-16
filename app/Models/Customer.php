<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = ['name', 'mobile_no'];

    // Use mobile_no as the "username"
    public function getAuthIdentifierName()
    {
        return 'mobile_no';
    }
}

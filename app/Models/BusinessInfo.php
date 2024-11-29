<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessInfo extends Model
{
    protected $table = 'wp_business_info';
    public $timestamps = false;
    protected $guarded = [];
}

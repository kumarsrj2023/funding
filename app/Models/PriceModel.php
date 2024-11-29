<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceModel extends Model
{
    use HasFactory;
    protected $table = 'wp_price_model';
    protected $guarded = [];
    public $timestamps = false;
}

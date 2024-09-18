<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorLiabilities extends Model
{
    use HasFactory;
    protected $table = 'wp_director_liabilities';
    public $timestamps = false;
    protected $guarded = [];

}

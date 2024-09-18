<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorInfo extends Model
{
    protected $table = 'wp_director_info';
    public $timestamps = false;
    protected $guarded = [];
}

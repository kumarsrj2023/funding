<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorContingentLiabilities extends Model
{
    use HasFactory;
    protected $table = 'wp_director_contingent_liabilities';
    public $timestamps = false;
    protected $guarded = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorUnlistedShares extends Model
{
    use HasFactory;
    protected $table = 'wp_director_unlisted_shares';
    public $timestamps = false;
    protected $guarded = [];
}

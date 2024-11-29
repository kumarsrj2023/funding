<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifClient extends Model
{
    use HasFactory;
    protected $table = 'wp_sif_client';
    protected $guarded = [];
}

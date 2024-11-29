<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SifVerificationAndCompletion extends Model
{
    use HasFactory;
    protected $table = 'wp_sif_verification_and_completion';
    protected $guarded = [];
}

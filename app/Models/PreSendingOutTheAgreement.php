<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreSendingOutTheAgreement extends Model
{
    use HasFactory;

    protected $table = 'wp_pre_sending_out_the_agreements';
    // public $timestamps = false;

    protected $guarded = [];
}

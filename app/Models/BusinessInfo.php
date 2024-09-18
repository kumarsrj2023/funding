<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessInfo extends Model
{
    protected $table = 'wp_business_info';
    public $timestamps = false;

     // Define the relationship with WpDirectorInfo (if needed)
     public function directorInfo()
     {
         return $this->belongsTo(DirectorInfo::class, 'wp_director_info_id');
     }

     // Optional: Add fillable properties if you plan to use mass assignment
    protected $fillable = [
        'wp_director_info_id',
        'account_or_regnumber',
        'cash_in_bank_and_deposit',
        'public_listed_shares',
        'properties',
        'motor_vehicles_boats',
        'other_cash_investments',
        'details_of_personal_pension',
        'other_assets',
    ];
}

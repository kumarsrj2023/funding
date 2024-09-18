<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorHouseholdIncome extends Model
{
    use HasFactory;
    protected $table = 'wp_director_household_income';
    public $timestamps = false;
    protected $guarded = [];
}

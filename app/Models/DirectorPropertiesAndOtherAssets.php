<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorPropertiesAndOtherAssets extends Model
{
    use HasFactory;
    protected $table = 'wp_director_properties_and_other_assets';
    public $timestamps = false;
    protected $guarded = [];
}

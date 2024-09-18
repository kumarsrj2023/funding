<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorAsset extends Model
{
    use HasFactory;
    protected $table = 'wp_director_assets';
    public $timestamps = false;
    protected $guarded = [];


    // Define the inverse relationship with WpDirectorInfo
    public function directorInfo()
    {
        return $this->belongsTo(DirectorInfo::class, 'wp_director_info_id');
    }

}

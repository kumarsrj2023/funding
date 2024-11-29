<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteePaperDirectorInfo extends Model
{
    use HasFactory;
    protected $table = 'wp_committee_paper_director_info';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommitteePaper extends Model
{
    use HasFactory;
    protected $table = 'wp_committee_paper';
    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatementOfPosition extends Model
{
    use HasFactory;
    protected $table = 'wp_statement_of_position';
    public $timestamps = false;
    protected $guarded = [];
}

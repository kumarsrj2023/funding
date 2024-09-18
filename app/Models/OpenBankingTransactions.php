<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenBankingTransactions extends Model
{
    protected $table = 'ga_plaid_request_log';
    public $updated_at = false;
}

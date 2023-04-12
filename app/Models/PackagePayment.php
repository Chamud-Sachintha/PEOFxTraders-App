<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet_address',
        'wallet_type',
        'package_id',
        'status',
        'txn_number',
        'amount_with_interest',
        'interest',
        'daily_int_amount',
        'earning_status'
    ];
}

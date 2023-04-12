<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id',
    //     'wallet_type',
    //     'user_wallet_address',
    //     'txn_number',
    //     'package_name',
    //     'rate',
    //     'peo_value',
    //     'package_amount',
    //     'amount_with_interest',
    //     'status'
    // ];

    protected $fillable = [
        'package_name',
        'package_amount',
        'peo_value',
        'rate',
        'status'
    ];
}

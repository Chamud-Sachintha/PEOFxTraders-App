<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class P2PTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_from_id',
        'transfer_to_id',
        'amount',
        'time'
    ];
}

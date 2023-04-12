<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalUserEarning extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_earnings',
        'total_without_deduct',
        'earning_status'
    ];
}

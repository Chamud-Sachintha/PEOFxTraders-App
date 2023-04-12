<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'coin_name',
        'coin_rate',
        'status'
    ]; 
}

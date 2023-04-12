<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KYCDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kyc_type',
        'f_image',
        'b_image',
        'selfi_image',
        'status',
        'reason'
    ];
}

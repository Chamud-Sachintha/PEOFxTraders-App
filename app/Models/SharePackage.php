<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharePackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_from_id',
        'user_to_id',
        'package_id',
    ];
}

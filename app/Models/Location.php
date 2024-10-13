<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class location extends Model
{
    use HasFactory;

    protected $fillable = ['country', 'province', 'city', 'address', 'postal_code', 'latitude', 'longitude'];
}

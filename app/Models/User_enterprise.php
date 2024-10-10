<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_enterprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'enterprise_id',
        'user_type',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'user_enterprise_id');
    }

}

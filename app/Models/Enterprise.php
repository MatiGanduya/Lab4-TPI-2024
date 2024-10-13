<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enterprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location_id',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function userEnterprises()
    {
        return $this->hasMany(userEnterprises::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}

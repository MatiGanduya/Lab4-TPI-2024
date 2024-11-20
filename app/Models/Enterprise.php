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
        // Relación con los usuarios a través de la tabla intermedia
        public function users()
        {
            return $this->belongsToMany(User::class, 'user_enterprises')
                        ->withPivot('user_type'); // Si usas un campo extra en la tabla pivote
        }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function userEnterprises()
    {
        return $this->hasMany(User_enterprise::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'empresa_id');
    }
}

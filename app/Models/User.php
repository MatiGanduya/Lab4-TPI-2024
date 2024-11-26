<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $table = 'users';

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'number_phone',
        'dni',
        'user_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     public function userEnterprise()
     {
         return $this->hasMany(User_enterprise::class);
     }

     public function services()
     {
         return $this->belongsToMany(Service::class, 'service_collaborators', 'user_enterprise_id', 'service_id');
     }

     public function enterprises()
     {
        return $this->belongsToMany(Enterprise::class, 'user_enterprises')->withPivot('user_type');
     }

     public function availabilities()
    {
        return $this->hasMany(Availability::class, 'userProf_id');
    }
}

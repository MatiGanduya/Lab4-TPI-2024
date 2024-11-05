<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;

    protected $table = 'availabilities';

    protected $fillable = [
        'start_time',
        'end_time',
        'day_of_week',
        'userProf_id'
    ];

    // Relación con el modelo User
    public function user()
    {
        return $this->belongsTo(User::class, 'userProf_id');
    }
}

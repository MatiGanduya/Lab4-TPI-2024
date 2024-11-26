<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_date', 'status', 'user_id', 'userProf_id', 'service_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    protected $dates = ['appointment_date'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration',
        'empresa_id',
    ];

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class, 'empresa_id');
    }


    public function collaborator(){

        return $this->belongsTo(User_enterprise::class, 'user_enterprise_id');

    }


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = ['user_id', 'specialty', 'license_number', 'clinic_hours', 'phone_number'];

    public function user() {
        return $this->belongsTo(User::class);
    }


}

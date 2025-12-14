<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = ['user_id', 'speciality', 'license_number', 'clinic_hours'];

    protected $casts = ['clinic_hours' => 'array'];

    public function user() {
        return $this->belongsTo(User::class);
    }


}

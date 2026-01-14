<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'emergency_contact', 'doctor_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id');
}

public function appointments() {
        return $this->hasMany(Appointment::class, 'patient_id');
}
    public function records() {
        return $this->hasMany(MedicalRecord::class, 'patient_id');
    }
}

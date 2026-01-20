<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'doctor_id', 'starts_at', 'ends_at', 'status', 'notes'];

    protected $dates = ['starts_at', 'ends_at'];

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patientProfile() {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }
}

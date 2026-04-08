<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = ['patient_id', 'doctor_id', 'starts_at', 'ends_at', 'status', 'notes', 'reminder_sent'];

    protected $casts = [
        'starts_at' => 'datetime:Y-m-d H:i:s',
        'ends_at' => 'datetime:Y-m-d H:i:s',
    ];

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

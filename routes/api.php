<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Models\PatientProfile;
use App\Http\Controllers\UserDirectoryController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
   Route::post('/registerPatient', [AuthController::class, 'patientRegister']);
   Route::post('/registerDoctor', [AuthController::class, 'doctorRegister']);
   Route::post('/registerAdmin', [AuthController::class, 'adminRegister']);

   Route::post('/storeAppointment', [AppointmentController::class, 'storeAppointment']);
   Route::put('/updateAppointment/{id}', [AppointmentController::class, 'updateAppointment']);
   Route::get('/getAllMyAppointments', [AppointmentController::class, 'getAllMyAppointments']);
   Route::delete('/deleteAppointment/{id}', [AppointmentController::class, 'deleteAppointment']);
   Route::get('/getMyAppointment/{id}', [AppointmentController::class, 'getAppointment']);
   Route::get('/upcomingAppointments', [AppointmentController::class, 'upcomingAppointments']);
   Route::get('/getUpcomingAppointmentsDoctor', [AppointmentController::class, 'getAllDoctorAppointments']);
   Route::get('patient/doctor', function () {
       $user = auth()->user();
      if(!$user) {
          return response()->json(['doctorId' => null], 401);
      }
       $doctorId = $user->profile?->doctor_id ?? null;
      return response()->json(['doctorId' => $doctorId], 200);
   });

  Route::get('getDoctors', [UserDirectoryController::class, 'getDoctors']);
    Route::get('/doctors/{doctor}/availability', DoctorAvailabilityController::class);

    Route::post('/storeMedicalRecord', [MedicalRecordController::class, 'storeRecord']);
    Route::delete('/deleteMedicalRecord/{id}', [MedicalRecordController::class, 'deleteRecord']);
    Route::get('/showMedicalRecord/{id}', [MedicalRecordController::class, 'showRecord']);
});


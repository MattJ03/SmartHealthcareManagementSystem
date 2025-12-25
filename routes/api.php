<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
   Route::post('/registerPatient', [AuthController::class, 'patientRegister']);
   Route::post('/registerDoctor', [AuthController::class, 'doctorRegister']);
   Route::post('/registerAdmin', [AuthController::class, 'adminRegister']);
   Route::post('/storeAppointment', [AppointmentController::class, 'storeAppointment']);
   Route::put('/updateAppointment/{id}', [AppointmentController::class, 'updateAppointment']);
   Route::get('/getAllMyAppointments', [AppointmentController::class, 'getAllMyAppointments']);
});

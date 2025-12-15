<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->group(function () {
   Route::post('/registerPatient', [AuthController::class, 'patientRegister']);
   Route::post('/registerDoctor', [AuthController::class, 'doctorRegister']);
   Route::post('/registerAdmin', [AuthController::class, 'adminRegister']);
});

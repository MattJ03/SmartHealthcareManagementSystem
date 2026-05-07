<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Models\PatientProfile;
use App\Http\Controllers\UserDirectoryController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\ActivityLogsController;
use Illuminate\Support\Facades\Log;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
   Route::post('/registerPatient', [AuthController::class, 'patientRegister']);
   Route::post('/registerDoctor', [AuthController::class, 'doctorRegister']);
   Route::post('/registerAdmin', [AuthController::class, 'adminRegister']);
   Route::post('/logout', [AuthController::class, 'logout']);

   Route::post('/storeAppointment', [AppointmentController::class, 'storeAppointment']);
   Route::put('/updateAppointment/{id}', [AppointmentController::class, 'updateAppointment']);
   Route::get('/getAllMyAppointments', [AppointmentController::class, 'getAllMyAppointments']);
   Route::delete('/deleteAppointment/{id}', [AppointmentController::class, 'deleteAppointment']);
   Route::get('/getMyAppointment/{id}', [AppointmentController::class, 'getAppointment']);
   Route::get('/upcomingAppointments', [AppointmentController::class, 'upcomingAppointments']);
   Route::get('/getUpcomingAppointmentsDoctor', [AppointmentController::class, 'getAllDoctorAppointments']);
   Route::get('/getAllUpcomingAppointments', [AppointmentController::class, 'getAllUpcomingAppointments']);
   Route::get('patient/doctor', function () {
       $user = auth()->user();
      if(!$user) {
          return response()->json(['doctorId' => null], 401);
      }
       $doctorId = $user->profile?->doctor_id ?? null;
      return response()->json(['doctorId' => $doctorId], 200);
   });
   Route::get('/getLastVisit/{patient}', [AppointmentController::class, 'getPatientLastVisit']);
   Route::get('/lastSevenAppointmentsCount', [AppointmentController::class, 'totalNumberAppointmentsNext7Days']);
   Route::get('/totalNumberAppointments', [AppointmentController::class, 'totalNumberOfAppointments']);
  Route::get('getDoctors', [UserDirectoryController::class, 'getDoctors']);
  Route::get('/doctorPatients', [UserDirectoryController::class, 'getDoctorsPatients']);
  Route::get('/getAllPatients', [UserDirectoryController::class, 'getPatientIndex']);
  Route::get('/admin/doctors/{doctor}/patients', [AppointmentController::class, 'getPatientsOfDoctor']);
  Route::get('/doctors/{doctor}/availability', DoctorAvailabilityController::class);
  Route::get('/doctorAppointmentRate', [DoctorAvailabilityController::class, 'doctorActivityThisWeek']);
    Route::post('/storeMedicalRecord', [MedicalRecordController::class, 'storeRecord']);
    Route::delete('/deleteMedicalRecord/{id}', [MedicalRecordController::class, 'deleteRecord']);
    Route::get('/showMedicalRecord/{id}', [MedicalRecordController::class, 'showRecord']);
    Route::get('/getAllRecords', [MedicalRecordController::class, 'getAllRecords']);
    Route::get('/doctor/records', [MedicalRecordController::class, 'doctorIndex']);
    Route::get('/downloadFile/{record}/download', [MedicalRecordController::class, 'downloadFile']);
    Route::get('/me', fn () => auth()->user()->load('profile.doctor'));
    Route::get('/getCompleteLogList', [ActivityLogsController::class, 'completeLogList']);
    Route::get('/getPatientsLogList', [ActivityLogsController::class, 'patientLogList']);
    Route::get('/getDoctorsLogList', [ActivityLogsController::class, 'doctorLogList']);
    Route::get('/getFilteredLogList', [ActivityLogsController::class, 'filterLogList']);
    Route::get('/allActionsCategories', [ActivityLogsController::class, 'getAllActionsCategories']);
    Route::get('/numberCancelledAppointments', [ActivityLogsController::class, 'totalCancelledAppointments']);
    Route::get('/fiveRecentLogsAdmin', [ActivityLogsController::class, 'fiveRecentActivityLogsAdmin']);
});


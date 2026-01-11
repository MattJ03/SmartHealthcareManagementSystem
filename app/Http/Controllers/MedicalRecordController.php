<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Policies\MedicalRecordPolicy;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Log;


class MedicalRecordController extends Controller
{
    public function storeRecord(Request $request) {
                $this->authorize('create', MedicalRecord::class);

       $validatedData = $request->validate([
            'patient_id' => 'required|exists:patient_profiles,id',
            'title' => 'required|max:255',
            'file' => 'required|file|mimes:pdf,docx,doc,ppt,pptx,odt,png,jpg,jpeg|max:2048',
            'notes' => 'nullable|max:500',
        ]);

        $path = $request->file('file')->store('medical-records', 'private');

        $patientProfile = PatientProfile::findOrFail($validatedData['patient_id']);

        abort_unless(
            auth()->id() === $patientProfile->doctor_id, 403
        );

        $record = MedicalRecord::create([
            'patient_id' => $validatedData['patient_id'],
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientOriginalExtension(),
            'file_size' => $request->file('file')->getSize(),
            'title' => $validatedData['title'],
            'notes' => $validatedData['notes'] ?? null,
            'doctor_id' => auth()->id(),
        ]);

        Log::info('record created by: ' . auth()->id());

        return response()->json([
            'record' => $record,
            'message' => 'record created',
            'uploaded_by' => auth()->id(),
        ], 201);
    }

    public function deleteMedicalRecord(Request $request, $id) {
        abort_unless(
            auth()->id() === $request->get('doctor_id'), 403
        );

        $record = MedicalRecord::findOrFail($id);
        $this->authorize('delete', $record);
        $record->delete();

        Log::info('record deleted by: ' . auth()->id());
        return response()->json([
            'message' => 'record deleted',
            'deleted_by' => auth()->id(),
        ], 200);
    }
}

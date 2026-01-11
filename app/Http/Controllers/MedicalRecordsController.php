<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Policies\MedicalRecordsPolicy;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Log;

class MedicalRecordsController extends Controller
{
    public function storeRecord(Request $request) {
        $this->authorize('create', MedicalRecordsPolicy::class);

       $validatedData = $request->validate([
            'patient_id' => 'required|exists:patient_profiles,id',
            'title' => 'required|max:255',
            'file' => 'required|file|mimes:pdf,docx,doc,ppt,pptx,odt,png,jpg,jpeg|max:2048',
            'notes' => 'nullable|max:500',
        ]);

        $path = $request->file('file')->store('medical-records', 'private');

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
}

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

    public function deleteRecord(Request $request, $id) {
        $record = MedicalRecord::findOrFail($id);
        $this->authorize('delete', $record);
        $record->delete();

        Log::info('record deleted by: ' . auth()->id() . ' from database');

        Storage::disk('private')->delete($record->file_path);

        Log::info('record deleted by: ' . auth()->id() . ' from storage');
        return response()->json([
            'message' => 'record deleted',
            'deleted_by' => auth()->id(),
        ], 200);
    }

    public function showRecord($id) {
        $record = MedicalRecord::findOrFail($id);
        $this->authorize('view', $record);

        Log::info('record view by: ' . auth()->id());

        abort_unless(
            Storage::disk('private')->exists($record->file_path), 404
        );

        return response()->file(
            Storage::disk('private')->path($record->file_path),
          ['Content-Type' => mime_content_type(
              Storage::disk('private')->path($record->file_path)
          ),
              'Content-Disposition' => 'inline; filename="'. $record->title . '"',
              ]
        );
    }

    public function getAllRecords(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('doctor')) {

            $patientId = $request->query('patient_id');
            if (!$patientId) {
                return response()->json([
                    'records' => [],
                    'message' => 'No patient_id provided',
                ], 400);
            }

            $records = MedicalRecord::where('doctor_id', $user->id)
                ->where('patient_id', $patientId)
                ->with('doctor:id,name')
                ->get();
        } else {
            $patientProfile = $user->profile;
            if (!$patientProfile) {
                return response()->json([
                    'records' => [],
                    'message' => 'Patient profile not found',
                ], 404);
            }

            $records = MedicalRecord::where('patient_id', $patientProfile->id)
                ->with('doctor:id,name')
                 ->get();
        }

        Log::info('number of records returned: ' . $records->count());

        return response()->json([
            'records' => $records,
            'message' => 'Records retrieved',
        ]);
    }

    public function downloadFile(Request $request, MedicalRecord $record) {
        $this->authorize('view', $record);

        if(!Storage::disk('private')->exists($record->file_path)) {
            abort(404, 'File not found');
        }
        return Storage::disk('private')->download($record->file_path);
    }

}

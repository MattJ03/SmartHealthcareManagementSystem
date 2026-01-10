<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Policies\MedicalRecordsPolicy;
use App\Models\MedicalRecord;

class MedicalRecordsController extends Controller
{
    public function storeRecord(Request $request) {
        $this->authorize(MedicalRecordsPolicy::class);

        $request->validate([
            'patient_id' => 'required|exists:patient_profiles,id',
            'title' => 'required|max:255',
            'file' => 'required|file|mimes:pdf,docx,doc,ppt,pptx,odt,png,jpg,jpeg|max:2048',
            'notes' => 'nullable|max:500',
        ]);

    }
}

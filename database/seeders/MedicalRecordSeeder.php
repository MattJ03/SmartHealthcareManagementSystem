<?php

namespace Database\Seeders;

use App\Models\PatientProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Storage;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $profiles = PatientProfile::all();
        $num = 1;

        foreach ($profiles as $profile) {
            $filePath = 'medical_records/fake-report-' . $profile->id . '.pdf';
            Storage::disk('private')->put($filePath, 'this is gay');

            MedicalRecord::create([
               'patient_id' => $profile->id,
               'doctor_id' => $profile->doctor_id,
               'file_path' => $filePath,
               'file_type' => 'pdf',
                'title' => 'Blood Work ' . $num,
            ]);
            $num++;
        }

    }
}

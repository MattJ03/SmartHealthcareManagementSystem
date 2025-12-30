<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDirectoryController extends Controller
{
    public function getDoctors() {
          $doctors = User::where('role', 'doctor')->get();
          if(!$doctors) {
              return response()->json([
                  'message' => 'doctors not found',
                  'doctors' => [],
              ]);
          }
          return response()->json([
              'doctors' => $doctors,
          ]);
    }
}

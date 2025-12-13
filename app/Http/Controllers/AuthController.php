<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Database\Seeders\RolePermissionSeeder;
class AuthController extends Controller
{
    public function patientRegister(Request $request) {
        $allowed = $this->authorize('create patient');
        if(!$allowed) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $data = $request->validate([
           'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed|max:50',
            'contact_number' => 'required|max:15|min:8',
        ]);
    }

}

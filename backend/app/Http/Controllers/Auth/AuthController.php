<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validateRegister());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone = $request->phone;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil melakukan pendaftaran.',
            'data' => $user
        ], 201);
    }

    // Validasi Request Register
    protected function validateRegister()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'phone' => 'required|regex:/^08\d{7,10}$/'
        ];
    }
}

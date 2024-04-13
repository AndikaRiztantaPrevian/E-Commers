<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'message' => 'Gagal melakukan pendaftaran.',
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

    // Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validateLogin());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal melakukan masuk ke dalam akun.',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Email dan Password yang dimasukkan tidak sesuai.'
            ], 401);
        }

        $dataUser = User::where('email', $request->email)->first();
        return response()->json([
            'status' => true,
            'message' => 'Berhasil melakukan proses masuk ke dalam akun.',
            'token' => $dataUser->createToken('auth-token')->plainTextToken
        ]);
    }

    // Logout
    public function logout()
    {
        Auth::logout();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil logout',
        ]);
    }

    // Validate Request Login
    protected function validateLogin()
    {
        return [
            'email' => 'required|email',
            'password' => 'required'
        ];
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

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller {
    // Register
    public function register(Request $req){
        $data = $req->validate([
            'full_name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|confirmed',
            'role_id'=>'nullable|exists:roles,role_id',
            'mobile_number'=>'nullable',
            'id_number'=>'nullable'
        ]);
        $user = User::create($data);
        return response()->json(['user'=>$user],201);
    }

    // Login
    public function login(Request $req){
        $req->validate(['email'=>'required|email','password'=>'required']);
        $user = User::where('email',$req->email)->first();
        if(! $user || ! Hash::check($req->password, $user->password)){
            throw ValidationException::withMessages(['email'=>['Invalid credentials']]);
        }
        // create token
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json(['user'=>$user,'token'=>$token]);
    }

    // Logout
    public function logout(Request $req){
        $req->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logged out']);
    }

    // Me
    public function me(Request $req){
        return response()->json($req->user());
    }
}


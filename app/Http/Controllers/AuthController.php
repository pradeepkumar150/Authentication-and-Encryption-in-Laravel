<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json([
            "status" => true,
            "message" => 'Registration successfully',
            "data" => [],
        ]);

    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where("email", $request->email)->first(); 
        if(!empty($user)){
            if(Hash::check($request->password, $user->password)){
                $token = $user->createToken('auth_token')->plainTextToken;
                
                return response()->json([
                    "status" => false,
                    "token" => $token,
                    "message" => 'User Loged In',
                    "data" => [],
                ]);
            }else{
                return response()->json([
                    "status" => false,
                    "message" => 'Inavalid Password',
                    "data" => [],
                ]);
            }
        }else{
            return response()->json([
                "status" => false,
                "message" => 'Invalid login details',
                "data" => [],
            ]);

        }

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(["message" => 'Successfully logged out']);
    } 
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('AuthToken')->plainTextToken;
    
            return response()->json([
                'message' => 'Login successful',
                'access_token' => $token,
              //  'user' => $user,
            ]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }



    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if ($request->has('name')) {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $user->name = $request->input('name');
        }

        if ($request->has('password')) {
            $request->validate([
                'password' => 'required|string',
            ]);

            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
        ]);
    
        }



        public function logout(Request $request)
        {
            $token = $request->user()->currentAccessToken();
        
            $token->delete();
        
            return response()->json(['message' => 'Logout successful']);
        }
        


}

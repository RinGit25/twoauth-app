<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerificationMail;

class LoginUserController extends Controller{

    public function login(Request $request){

        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($validatedData)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $request->user()->createToken('authToken')->plainTextToken;

        return response()->json(['access_token' => $token]);
    }
}

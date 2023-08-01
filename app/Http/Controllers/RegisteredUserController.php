<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\VerificationMail;

class RegisteredUserController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $verificationCode = Str::random(6);

        //↓ユーザーのレコードをデータベース上に作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verification_code' => $verificationCode,
        ]);

        //メールのカプセルを作成するVerificationMailに認証コードを送る
        Mail::to($request->email)->send(new VerificationMail($verificationCode));

        return response()->json(['verification_code' => $verificationCode]);
    }

    public function verify(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && hash_equals((string)$user->verification_code, $request->verification_code)) {
            // Update the user's status to "verified"
            $user->update(['verification_code' => null]);  // remove the verification code

            return response()->json(['status' => 'Registration completed']);
        }

        return response()->json(['status' => 'Invalid verification code'], 400);
    }

}

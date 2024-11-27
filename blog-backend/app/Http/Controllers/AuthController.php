<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    // Register user
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'Registration successful. Please check your email to verify your account.'
        ], 201);
    }

    // Login user
    public function login(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Check if credentials match
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // If successful, get the user and generate token (if using Sanctum)
            $user = Auth::user();
            $token = $user->createToken('YourAppName')->plainTextToken;

            // Return response with token
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function sendVerificationEmail()
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Your email is already verified.'], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['status' => 'verification-link-sent'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (! hash_equals($request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new \Exception('Invalid hash');
        }

        $user->markEmailAsVerified();

        // Redirect the user to your Vue frontend
        return redirect()->away(env('FRONT_URL') . '/login');
    }
}

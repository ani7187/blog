<?php

use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify');

/*Route::get('email/verify/{id}/{hash}', function ($id, $hash) {
    $user = App\Models\User::findOrFail($id);

    if (Hash::check($user->getEmailForVerification(), $hash)) {
        $user->markEmailAsVerified();
    }

//    return redirect('/')->with('verified', true);
})->middleware(['auth'])->name('verification.verify');*/

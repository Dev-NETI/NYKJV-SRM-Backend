<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $google_account = Socialite::driver('google')->user();
        // dd($google_account->user);
        $user = User::updateOrCreate([
            'provider_id' => $google_account->id,
        ], [
            'f_name' => $google_account->user['given_name'],
            'l_name' => $google_account->user['family_name'],
            'email' => $google_account->user['email'],
            'email_verified_at' => $google_account->user['email_verified'],
            'profile_picture_path' => $google_account->user['picture'],
            'provider_token' => $google_account->token,
            'password' => bcrypt(Str::random(16)),
        ]);

        Auth::login($user);

        $redirectUrl = 'http://localhost:3000/login-otp'; // Update with your actual Next.js URL

        return redirect($redirectUrl);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpRequest;
use App\Mail\MailerLoginOtp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public $temp_otp;
    public function authenticating(OtpRequest $request)
    {
        $this->temp_otp = $request->input('temp_otp'); // Retrieve 'tempt_otp' from request
        Mail::to(Auth::user()->email)->send(new MailerLoginOtp(Auth::user()->email, $this->temp_otp));
        Session::put('temp_otp', $this->temp_otp);
        // return response()->json(true, 200);
        return response()->json(['status' =>  'already send otp: ' .  Session::get('temp_otp') . ' to ' . Auth::user()->email], 200);
    }

    public function verifyOTP(OtpRequest $request)
    {
        $user_otp =  (int)  $request->input('otp');
        $temp_otp = 123123;

        // Validate OTP
        if ($temp_otp === $user_otp) {
            // OTP verification successful
            Session::put('isVerified', true);
            $isVerified = Session::get('isVerified');
            return response()->json(['status' => $isVerified], 200);
        }

        return response()->json(['status' => 'Check your OTP again, make sure you entered it correctly.'], 401);
    }

    public function checkingStatusOTP()
    {
        $isVerified = Session::get('isVerified');
        return response()->json(['status' => $isVerified], 200);
    }
}

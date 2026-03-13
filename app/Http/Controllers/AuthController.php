<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use App\Models\User;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $email = $request->email;
            $code = (string) rand(1000, 9999);

            EmailOtp::updateOrCreate(
                ['email' => $email],
                [
                    'code' => $code,
                    'code_email' => $email,
                    'expires_at' => Carbon::now()->addMinutes(5)
                ]
            );

            Mail::to($email)->send(new SendOtpMail($code));

            // Log để debug
            Log::info('OTP sent', ['email' => $email, 'code' => $code]);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent successfully',
                'email' => $email,
                // ONLY FOR DEVELOPMENT - REMOVE IN PRODUCTION
                'debug_code' => config('app.debug') ? $code : null
            ], 200);
        } catch (\Exception $e) {
            Log::error('Send OTP failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send verification code',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code'  => 'required'
        ]);

        $email = strtolower(trim($request->email));
        $inputCode = trim($request->code); 

        $otp = EmailOtp::where('email', $email)  
            ->where('code', $inputCode)
            ->first();

        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code'
            ], 422);
        }

        if ($otp->expires_at < now()) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code expired'
            ], 422);
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => explode('@', $email)[0], 'password' => bcrypt(str()->random(16))]
        );

        Auth::login($user);
        $request->session()->regenerate();

        $otp->delete();

        return response()->json([
            'success' => true,
            'message' => 'Login successful!'
        ]);
    }
}

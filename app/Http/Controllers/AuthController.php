<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\EmailVerification;
use App\Models\PhoneVerification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\WhatsApp;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function me()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json([
                'status' => false,
                'message' => 'Email / Password salah!'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'user' => auth()->user(),
            'token' => $token
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|unique:users',
            'otp_email' => 'required',
        ]);

        $emailVerif = EmailVerification::where('email', $request->email)->where('otp', $request->otp_email)->first();

        if (!$emailVerif) {
            return response()->json([
                'status' => false,
                'message' => 'Email tidak terverifikasi.'
            ], 403);
        }

        $emailVerif->delete();

        $data = $request->only(['nama', 'email', 'password', 'phone']);
        $data['password'] = bcrypt($request->password);
        $data['phone'] = AppHelper::parsePhone($request->phone);
        $data['email_verified_at'] = Carbon::now();
        $data['phone_verified_at'] = Carbon::now();
        $data['confirmed'] = 1;

        $user = User::create($data);
        $user->assignRole('user');

        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Gagal'
        ]);
    }

    public function registerGetEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'nama' => 'required',
        ]);

        $email = $request->email;
        $check = User::where('email', $email)->first();
        if ($check) {
            return response()->json([
                'status' => false,
                'message' => 'Email sudah terdaftar.'
            ], 403);
        }

        $otp = AppHelper::generateOTP(6);

        Mail::to($email)->send(new \App\Mail\SendOTPMail($request->nama, $otp, $email));

        $verif = EmailVerification::where('email', $email)->first();

        if (!$verif) {
            $verif = EmailVerification::create([
                'email' => $email,
                'otp' => $otp,
                'otp_expired_at' => Carbon::now()->addMinutes(2),
            ]);
        } else {
            $verif->otp = $otp;
            $verif->otp_expired_at = Carbon::now()->addMinutes(2);
            $verif->update();
        }

        return response()->json([
            'status' => true,
            'resend_time' => 30,
            'email' => $email,
            'message' => 'Berhasil mengirim Kode OTP.',
        ], 200);
    }
    


    public function registerCheckEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'otp' => 'required',
        ]);

        $email = $request->email;
        $verif = EmailVerification::where('email', $email)->where('otp', $request->otp)->first();

        if ($verif) {
            if (Carbon::parse($verif->otp_expired_at)->isPast()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kode OTP telah kadaluarsa.'
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Kode OTP tidak sesuai'
        ], 400);
    }

    public function registerGetPhoneOtp(Request $request){

    }

    public function registerCheckPhoneOtp(Request $request){
        
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['success' => true]);
    }
}

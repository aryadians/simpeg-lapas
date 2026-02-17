<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\TwoFactorCode;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $otp = '';
    public $showOtpForm = false;
    public $userIdToVerify = null;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $this->email)->first();

        if ($user && Hash::check($this->password, $user->password)) {
            
            // Jika Admin, wajib MFA
            if ($user->role === 'admin') {
                $this->generateAndSendOtp($user);
                $this->userIdToVerify = $user->id;
                $this->showOtpForm = true;
                return;
            }

            // Jika Staff, langsung login
            Auth::login($user);
            session()->regenerate();
            return redirect()->intended('/');
        }

        $this->addError('email', 'Kredensial tidak valid.');
    }

    public function verifyOtp()
    {
        $this->validate([
            'otp' => 'required|numeric|digits:6',
        ]);

        $user = User::find($this->userIdToVerify);

        if (!$user) {
            $this->reset();
            return;
        }

        if ($user->two_factor_code == $this->otp && $user->two_factor_expires_at > now()) {
            
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null
            ]);

            Auth::login($user);
            session()->regenerate();
            return redirect()->intended('/');
        }

        $this->addError('otp', 'Kode OTP salah atau kadaluarsa.');
    }

    public function generateAndSendOtp($user)
    {
        $code = rand(100000, 999999);
        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10)
        ]);

        // Kirim Email (Gunakan Try Catch agar tidak crash jika mail server belum setup)
        try {
            Mail::to($user->email)->send(new TwoFactorCode($code));
        } catch (\Exception $e) {
            // Log code untuk development/fallback
            Log::info("OTP Login for {$user->email}: $code");
        }
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('components.layouts.guest');
    }
}

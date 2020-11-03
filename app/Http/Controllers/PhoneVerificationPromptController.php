<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\VerifyEmailViewResponse;
use Illuminate\Auth\Events\Verified;
use App\Rules\VerificationCode;

class PhoneVerificationPromptController extends Controller
{
    /**
     * Display the phone verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Fortify\Contracts\VerifyEmailViewResponse
     */
    public function index(Request $request)
    {
        return $request->user()->hasVerifiedPhone()
                    ? redirect()->intended(config('fortify.home'))
                    : app(VerifyEmailViewResponse::class);
    }

    public function verify(Request $request)
    {
        if ($request->user()->hasVerifiedPhone()) {
            return redirect()->intended(config('fortify.home').'?verified=1');
        }

        // Validate code before marking as verified
        $request->validate([
            'verification_code' => ['bail', 'required', 'integer', 'digits:6', new VerificationCode($request->user()->verification_code)],
        ]);

        if ($request->verification_code == $request->user()->verification_code) {
            if ($request->user()->markPhoneAsVerified()) {
                event(new Verified($request->user()));
            }
        }

        return redirect()->intended(config('fortify.home').'?verified=1');
    }
}

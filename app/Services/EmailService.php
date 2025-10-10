<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeUserMail;
use App\Mail\ResetPasswordMail;

class EmailService
{
    public function sendWelcomeEmail($user)
    {
        //Mail::to($user->email)->send(new WelcomeUserMail($user));
    }

    public function sendResetPasswordEmail($user, $token)
    {
        //Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
    }
}

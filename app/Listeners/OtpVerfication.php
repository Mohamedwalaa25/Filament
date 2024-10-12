<?php

namespace App\Listeners;

use App\Events\SendOtpCode;
use App\Mail\OTPMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class OtpVerfication
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(SendOtpCode $event): void
    {
        $user = $event->user;
        Mail::to($user->email)->send(new OTPMail($user->otp));



    }
}

<?php

namespace App\Events\EventListeners;

use App\Events\ChangedEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class SendNewEmailVerification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\ChangedEmail  $event
     * @return void
     */
    public function handle(ChangedEmail $event): void
    {
        /** @var MustVerifyEmail $user */
        $user = $event->user;
        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }
    }
}

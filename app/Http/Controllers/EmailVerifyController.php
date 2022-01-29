<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponse;
use App\Http\Requests\EmailVerify\EmailVerifyBaseRequest;
use App\Http\Requests\EmailVerify\EmailVerifyResendRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerifyController extends Controller
{
    public function isVerified(Request $request): JsonResponse
    {
        $isVerified = $request->user()->hasVerifiedEmail();

        return response()->json(
            APIResponse::formatJSONPayload(
                'success',
                ['isVerified' => $isVerified]
            ),
            200
        );
    }

    public function resendEmail(EmailVerifyResendRequest $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->json(
            APIResponse::formatJSONPayload(
                'success',
                ['resend' => 'Email was successfully resend to user.']
            ),
            200
        );
    }

    public function verifyEmail(EmailVerifyBaseRequest $request): RedirectResponse
    {
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect('/credentials');
    }
}

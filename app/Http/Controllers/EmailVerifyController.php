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
    private string $postConfirmationRedirectRoute = "/account/verified";

    /**
     * Returns current state of email confirmation for the requesting user.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with: 200 status for success.
     */
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

    /**
     * Resend email to address attach within requiring user.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with: 200 status for success.
     */
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

    /**
     * Endpoint linked in the verification email. It's responsibility is signalizing email as verified.
     *
     * @return RedirectResponse Redirect user back to a front-end route.
     */
    public function verifyEmail(EmailVerifyBaseRequest $request): RedirectResponse
    {
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($this->postConfirmationRedirectRoute);
    }
}

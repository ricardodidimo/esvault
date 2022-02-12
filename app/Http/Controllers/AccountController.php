<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponse;
use App\Http\Requests\Account\AccountBaseRequest;
use App\Http\Requests\Account\AccountConfirmRequest;
use App\Services\Account\IAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    private IAccountService $accountService;

    public function __construct(IAccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * Returns information about the current authenticated user.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with 200 status for success.
     */
    public function index(): JsonResponse
    {
        $currentUser = $this->accountService->currentUser();

        $jsonSuccessReturn = APIResponse::formatJSONPayload('success', $currentUser);
        return response()->json($jsonSuccessReturn, 200);
    }

    /**
     * Confirms the given password input matches the password for current authenticated user.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with: 204 status for success.
     */
    public function confirm(AccountConfirmRequest $request): JsonResponse
    {
        $jsonSuccessReturn = APIResponse::formatJSONPayload('success', null);
        return response()->json($jsonSuccessReturn, 204);
    }

    /**
     * Creates an authentication session if valid credentials are given.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with: 201 status for success.
     */
    public function store(AccountBaseRequest $request): JsonResponse
    {
        $loginAttemptData = $request->validated();
        $this->accountService->login($loginAttemptData);

        $jsonSuccessReturn = APIResponse::formatJSONPayload('success', null);
        return response()->json($jsonSuccessReturn, 201);
    }

    /**
     * Destroy's previously establish session.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with 204 status for success.
     */
    public function destroy(): JsonResponse
    {
        Auth::guard('web')->logout();

        $jsonSuccessReturn = APIResponse::formatJSONPayload('success', null);
        return response()->json($jsonSuccessReturn, 204);
    }
}

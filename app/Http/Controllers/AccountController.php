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

    public function index(): JsonResponse
    {
        $currentUser = $this->accountService->currentUser();

        $jsonSuccessReturn = APIResponse::formatJSONPayload('success', $currentUser);
        return response()->json($jsonSuccessReturn, 200);
    }

    public function confirm(AccountConfirmRequest $request): JsonResponse
    {
        $jsonSuccessReturn = APIResponse::formatJSONPayload('success', null);
        return response()->json($jsonSuccessReturn, 204);
    }

    public function store(AccountBaseRequest $request): JsonResponse
    {
        $loginAttemptData = $request->validated();
        $this->accountService->login($loginAttemptData);

        $jsonSuccessReturn = APIResponse::formatJSONPayload('success', null);
        return response()->json($jsonSuccessReturn, 201);
    }

    public function destroy(): JsonResponse
    {
        Auth::guard('web')->logout();
        return response()->json([], 204);
    }
}

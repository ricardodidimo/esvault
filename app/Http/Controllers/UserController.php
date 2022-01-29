<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponse;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\User\IUserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(UserCreateRequest $request): JsonResponse
    {
        $entityData = $request->validated();
        $this->userService->store($entityData);

        $jsonReturn = APIResponse::formatJSONPayload('success', null);
        return response()->json($jsonReturn, 201);
    }

    public function update(UserUpdateRequest $request): JsonResponse
    {
        $safeUpdateData = $request->validated();
        $this->userService->update($safeUpdateData);

        return response()->json([], 204);
    }

    public function destroy(): JsonResponse
    {
        $this->userService->destroy();
        return response()->json([], 204);
    }
}

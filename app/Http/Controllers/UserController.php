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

    /**
     * Persist a User record in database and creates an authentication session
     * for the newly created user.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with: 201 status for success.
     */
    public function store(UserCreateRequest $request): JsonResponse
    {
        $safeUserCreationData = $request->validated();
        $this->userService->store($safeUserCreationData);

        $jsonReturn = APIResponse::formatJSONPayload('success', null);
        return response()->json($jsonReturn, 201);
    }

    /**
     * Updates the current authenticated user record in database.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with: 204 status for success.
     */
    public function update(UserUpdateRequest $request): JsonResponse
    {
        $safeUserUpdateData = $request->validated();
        $this->userService->update($safeUserUpdateData);

        return response()->json([], 204);
    }

    /**
     * Destroy the current authenticated user record from database.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with: 204 status for success.
     */
    public function destroy(): JsonResponse
    {
        $this->userService->destroy();
        return response()->json([], 204);
    }
}

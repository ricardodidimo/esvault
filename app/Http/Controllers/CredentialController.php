<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponse;
use App\Http\Requests\Credentials\CredentialBaseRequest;
use App\Http\Requests\Credentials\CredentialCreateRequest;
use App\Services\Credential\ICredentialService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CredentialController extends Controller
{
    public function __construct(ICredentialService $credentialService)
    {
        $this->credentialService = $credentialService;
    }

    private ICredentialService $credentialService;

    public function index(?string $titleSearchParam = null): JsonResponse
    {
        $data = $titleSearchParam === null ?
            $this->credentialService->index() :
                $this->credentialService->search($titleSearchParam);

        $successResponse = APIResponse::formatJSONPayload('success', $data);
        return response()->json($successResponse, 200);
    }

    public function show(int $id, CredentialBaseRequest $request): JsonResponse
    {
        $data = $request->getRetrievedCredentialInstance();
        $data = $this->credentialService->show($data);

        $successResponse = APIResponse::formatJSONPayload('success', $data);
        return response()->json($successResponse, 200);
    }

    public function store(CredentialCreateRequest $request): JsonResponse
    {
        $safeEntityData = $request->validated();
        $insertion = $this->credentialService->store($safeEntityData);

        $successResponse = APIResponse::formatJSONPayload('success', null);
        return response()->json($successResponse, 201);
    }

    public function update(int $id, CredentialBaseRequest $request): JsonResponse
    {
        $safeUpdatePayload = $request->validated();
        $update = $this->credentialService->update($id, $safeUpdatePayload);
        return response()->json([], 204);
    }

    public function destroy(int $id, CredentialBaseRequest $request): JsonResponse
    {
        $destruction =  $this->credentialService->destroy($id);
        return response()->json([], 204);
    }
}

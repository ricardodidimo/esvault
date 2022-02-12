<?php

namespace App\Http\Controllers;

use App\Helpers\APIResponse;
use App\Http\Requests\Credentials\CredentialBaseRequest;
use App\Http\Requests\Credentials\CredentialCreateRequest;
use App\Services\Credential\ICredentialService;
use Illuminate\Http\JsonResponse;

class CredentialController extends Controller
{
    public function __construct(ICredentialService $credentialService)
    {
        $this->credentialService = $credentialService;
    }

    private ICredentialService $credentialService;

    /**
     * Returns a paginated listing of Credential records from database.
     *
     * @param ?string $titleAsSearchParam When present the listing will follow this as a criteria.
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with 200 status for success.
     */
    public function index(?string $titleAsSearchParam = null): JsonResponse
    {
        $credentialsList = $titleAsSearchParam === null ?
            $this->credentialService->index() :
                $this->credentialService->search($titleAsSearchParam);

        $successResponse = APIResponse::formatJSONPayload('success', $credentialsList);
        return response()->json($successResponse, 200);
    }

    /**
     * Returns a specific Credential record from database.
     *
     * @param int $id Database identifier for the desired record.
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with 200 status for success.
     */
    public function show(int $id, CredentialBaseRequest $request): JsonResponse
    {
        $wantedCredential = $request->getRetrievedCredentialInstance();
        $wantedCredential = $this->credentialService->show($wantedCredential);

        $successResponse = APIResponse::formatJSONPayload('success', $wantedCredential);
        return response()->json($successResponse, 200);
    }

    /**
     * Persist a Credential record in database.
     *
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with 201 status for success.
     */
    public function store(CredentialCreateRequest $request): JsonResponse
    {
        $safeCreationData = $request->validated();
        $this->credentialService->store($safeCreationData);

        $successResponse = APIResponse::formatJSONPayload('success', null);
        return response()->json($successResponse, 201);
    }

    /**
     * Update a specific Credential record from database.
     *
     * @param int $id Database identifier for the desired record.
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with 204 status for success.
     */
    public function update(int $id, CredentialBaseRequest $request): JsonResponse
    {
        $safeUpdateData = $request->validated();
        $this->credentialService->update($id, $safeUpdateData);

        return response()->json([], 204);
    }

    /**
     * Destroy a specific Credential record from database.
     *
     * @param int $id Database identifier for the desired record.
     * @return JsonResponse A JSON response as defined in App\Helpers\APIResponse
     * with 204 status for success.
     */
    public function destroy(int $id, CredentialBaseRequest $request): JsonResponse
    {
        $this->credentialService->destroy($id);

        return response()->json([], 204);
    }
}

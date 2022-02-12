<?php

namespace App\Services\Credential;

use App\Models\Credential;
use Illuminate\Contracts\Pagination\Paginator;

interface ICredentialService
{
    /**
     * Return paginated list of credentials from database.
     */
    public function index(): Paginator;

    /**
     * Return paginated list of credentials from database that match the search parameter.
     */
    public function search(string $titleSearch): Paginator;

    /**
     * Return a credential following presentation necessities
     */
    public function show(Credential $credential): Credential;

    /**
     * @throws Exception If insert unexpectly returns false
     */
    public function store(array $entityData): void;

    /**
     * @throws Exception If update unexpectly returns false
     */
    public function update(int $entityId, array $updatePayload): void;

    /**
     * @throws Exception If delete unexpectly returns false
     */
    public function destroy(int $entityId): void;
}

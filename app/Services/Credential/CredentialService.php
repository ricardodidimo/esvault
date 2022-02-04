<?php

namespace App\Services\Credential;

use App\Models\Credential;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator as PaginationPaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\This;

class CredentialService implements ICredentialService
{
    private const QUERY_FIELDS = [
        'id',
        'title',
        'description',
        'first_claim',
        'second_claim',
        'user_id'
    ];
    private const RECORDS_PER_PAGE = 8;

    private function setOwnership(array &$credential): void
    {
        $credential['user_id'] = Auth::user()->id;
    }

    private function encryptClaims(array &$credential): void
    {
        $credential['first_claim'] = Crypt::encryptString($credential['first_claim']);
        $credential['second_claim'] = Crypt::encryptString($credential['second_claim']);
    }

    private function decryptClaims(Paginator &$credentials): void
    {
        foreach ($credentials as $credential) {
            $credential->first_claim = Crypt::decryptString($credential->first_claim);
            $credential->second_claim = Crypt::decryptString($credential->second_claim);
        }
    }

    public function index(): Paginator
    {
        $targetUser = Auth::user()->id;
        $credentialRecords = DB::table('credentials')
            ->where('user_id', $targetUser)
            ->simplePaginate($this::RECORDS_PER_PAGE, $this::QUERY_FIELDS);

        $this->decryptClaims($credentialRecords);
        return $credentialRecords;
    }

    public function search(string $titleForSearch): Paginator
    {
        $targetUser = Auth::user()->id;
        $credentialRecordsMatches = DB::table('credentials')
            ->where('user_id', $targetUser)
            ->where('title', 'LIKE', "{$titleForSearch}%")
            ->simplePaginate($this::RECORDS_PER_PAGE, $this::QUERY_FIELDS);

        $this->decryptClaims($credentialRecordsMatches);
        return $credentialRecordsMatches;
    }

    public function show(object $credential): object
    {
        $paginatedCredential = new PaginationPaginator([$credential], 1);
        $this->decryptClaims($paginatedCredential);
        return $credential;
    }

    public function store(array $recordInsertData): void
    {
        $this->encryptClaims($recordInsertData);
        $this->setOwnership($recordInsertData);
        $insertSuccess = DB::table('credentials')->insert($recordInsertData);

        if ($insertSuccess === false) {
            throw new Exception('Fail on insert over credentials table.');
        };
    }

    public function update(int $recordId, array $recordUpdateData): void
    {
        $this->encryptClaims($recordUpdateData);
        $updateEffect = DB::table('credentials')
            ->where('id', $recordId)
            ->update($recordUpdateData);

        if ($updateEffect === 0) {
            throw new Exception('Fail on update over credential record.');
        }
    }

    public function destroy(int $recordId): void
    {
        $deleteEffect = DB::table('credentials')->delete($recordId);

        if ($deleteEffect === 0) {
            throw new Exception('Fail on delete over credential record.');
        }
    }
}

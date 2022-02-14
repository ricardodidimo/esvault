<?php

namespace App\Events\EventListeners;

use App\Models\Credential;
use App\Models\User;
use App\Services\Credential\CredentialService;
use App\Services\Credential\ICredentialService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\App;

class CreateSampleCredential
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered $newlyUser
     * @return void
     */
    public function handle(Registered $event): void
    {
        $sampleCredentialData = [
            'title' => 'example website',
            'description' => 'This is a sample credential record',
            'first_claim' => 'exampleEmail@test.net',
            'second_claim' => 'exampleP@ssw0rd',
        ];

        $credential = App::make(ICredentialService::class);
        $credential->store($sampleCredentialData);
    }
}

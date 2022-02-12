<?php

namespace Tests\Feature\Credentials;

use App\Models\Credential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ShowCredentialsTest extends TestCase
{
    use RefreshDatabase;

    private User $sessionUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionUser = User::factory()->create();
        Auth::setUser($this->sessionUser);
    }

    /**
    * @test
    */
    public function CredentialShow_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $targetCredential = Credential::factory()->create();

        $res = $this->get(
            route(
                'credentials.show',
                ['id' => $targetCredential->id]
            )
        );

        $res->assertStatus(200);
        $res->assertJsonFragment([
            'title' => $targetCredential->title,
            'description' => $targetCredential->description,
            'first_claim' => Crypt::decryptString($targetCredential->first_claim),
            'second_claim' => Crypt::decryptString($targetCredential->second_claim)
        ]);
    }

    /**
    * @test
    */
    public function CredentialShow_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->get(
            route(
                'credentials.show',
                ['id' => 0]
            )
        );

        $res->assertStatus(401);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['authentication' => 'Unauthenticated.']
        ]);
    }

    /**
    * @test
    */
    public function CredentialShow_OverInexistingCredential_ReturnsFailureResponse()
    {
        $res = $this->get(
            route(
                'credentials.show',
                ['id' => 0]
            )
        );

        $res->assertStatus(400);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['id' => ['Invalid ID given.']]
        ]);
    }

    /**
    * @test
    */
    public function CredentialShow_OverAnotherUserCredential_ReturnsFailureResponse()
    {
        // Created as one user
        $targetCredential = Credential::factory()->create();

        // "logged" as another
        Auth::setUser(User::factory()->create());

        $res = $this->get(
            route(
                'credentials.show',
                ['id' => $targetCredential->id]
            )
        );

        $res->assertStatus(403);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['forbidden' => 'You do not own this data. Invalid action taken.']
        ]);
    }
}

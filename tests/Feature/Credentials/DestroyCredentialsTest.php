<?php

namespace Tests\Feature\Credentials;

use App\Models\Credential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DestroyCredentialsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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
    public function CredentialDestroy_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $targetCredential = Credential::factory()->create();

        $res = $this->
            delete(
                route(
                    'credentials.destroy',
                    ['id' => $targetCredential->id]
                )
            );

        $res->assertStatus(204);
    }

    /**
    * @test
    */
    public function CredentialDestroy_OnSuccessfulCall_ClearRecordInDatabase()
    {
        $targetCredential = Credential::factory()->create();
        $this->assertDatabaseHas('credentials', $targetCredential->toArray());

        $res = $this->
            delete(
                route(
                    'credentials.destroy',
                    ['id' => $targetCredential->id]
                )
            );

        $this->assertDatabaseMissing('credentials', $targetCredential->toArray());
    }

    /**
    * @test
    */
    public function CredentialDestroy_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->
            delete(
                route(
                    'credentials.destroy',
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
    public function CredentialDestroy_OverInexistingCredential_ReturnsFailureResponse()
    {
        $res = $this->
            delete(
                route(
                    'credentials.destroy',
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
    public function CredentialDestroy_OverAnotherUserCredential_ReturnsFailureResponse()
    {
        $targetCredential = Credential::factory()->create();

        // logging as a different user
        Auth::setUser(User::factory()->create());

        $res = $this->
            delete(
                route(
                    'credentials.destroy',
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

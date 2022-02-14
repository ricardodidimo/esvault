<?php

namespace Tests\Feature\Credentials;

use App\Models\Credential;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class ListCredentialsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionUser = User::factory()->create();
        Auth::setUser($this->sessionUser);
    }

    private Model $sessionUser;

    /**
    * @test
    */
    public function CredentialIndex_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $credentials = Credential::factory()->count(3)->create();

        $res = $this->get(route('credentials.index'));

        $res->assertStatus(200);
        foreach ($credentials as $credential) {
            $credential->first_claim = Crypt::decryptString($credential->first_claim);
            $credential->second_claim = Crypt::decryptString($credential->second_claim);
            $res->assertJsonFragment([
                [
                    "id" => $credential->id,
                    "title" => $credential->title,
                    "description" => $credential->description,
                    "first_claim" => $credential->first_claim,
                    "second_claim" => $credential->second_claim,
                    "user_id" => $this->sessionUser->id
                ]
            ]);
        }
    }

    /**
     * @test
     */
    public function CredentialIndex_OnSuccessfulCall_ReturnsPaginatedResults()
    {
        $credentials = Credential::factory()->count(31)->create();

        $res = $this->get(route('credentials.index'));

        $this->assertDatabaseCount('credentials', 31);
        /**
         * data key references pagination details
         * data.data references actual APIResponse data
         */
        $res->assertJsonCount(30, 'data.data');
        $res->assertJsonFragment(['per_page' => 30]);
    }

    /**
     * @test
     */
    public function CredentialIndex_OnSuccessfulCallContainingSearchParameter_ReturnsExpectedResults()
    {
        $records = Credential::factory()->count(3)->create();
        $credentialToBeSearched = $records->first();
        // turn into readable form
        $credentialToBeSearched->first_claim = Crypt::decryptString($credentialToBeSearched->first_claim);
        $credentialToBeSearched->second_claim = Crypt::decryptString($credentialToBeSearched->second_claim);
        // exclude non present props
        unset($credentialToBeSearched->created_at);
        unset($credentialToBeSearched->updated_at);

        $res = $this->actingAs(Auth::user())
            ->get(route(
                'credentials.index',
                ['title' => $credentialToBeSearched->title]
            ));

        $res->assertStatus(200);
        $res->assertJsonCount(1, 'data.data');
        $res->assertJsonFragment(
            json_decode(json_encode($credentialToBeSearched), true)
        );
    }

    /**
     * @test
    */
    public function CredentialIndex_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->get(route('credentials.index'));

        $res->assertStatus(401);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['authentication' => 'Unauthenticated.']
        ]);
    }
}

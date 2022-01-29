<?php

namespace Tests\Feature\Credentials;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Str;

class CreateCredentialsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionUser = User::factory()->create();
        Auth::setUser($this->sessionUser);

        $this->payload = [
            'title' => $this->faker->userName(),
            'description' => $this->faker->text(140),
            'first_claim' => $this->faker->email(),
            'second_claim' => $this->faker->password(),
        ];
    }

    private array $payload = [];
    private Model $sessionUser;

    /**
     * @test
     */
    public function CredentialCreate_onSuccessfulCall_ReturnsExpectedResponse()
    {
        $res = $this->post(route('credentials.create'), $this->payload);

        $res->assertStatus(201);
        $res->assertSimilarJson([
            'status' => 'success',
            'data' => null
        ]);
    }

    /**
     * @test
     */
    public function CredentialCreate_OnSuccessfulCall_PersistsDataInDatabase()
    {
        $this->post(route('credentials.create'), $this->payload);

        $this->assertDatabaseHas('credentials', [
            'title' => $this->payload['title'],
            'description' => $this->payload['description'],
            'user_id' => $this->sessionUser->id,
        ]);
        $this->assertDatabaseCount('credentials', 1);
    }

    /**
     * @test
     */
    public function CredentialCreate_OnSuccessfulCall_PersistsClaimsEncrypted()
    {
        $this->post(route('credentials.create'), $this->payload);

        $insertedCredential = DB::table('credentials')
            ->where('id', DB::getPdo()->lastInsertId())
            ->first([
                'first_claim',
                'second_claim'
            ]);

        $this->assertEquals(
            $this->payload['first_claim'],
            Crypt::decryptString($insertedCredential->first_claim)
        );

        $this->assertEquals(
            $this->payload['second_claim'],
            Crypt::decryptString($insertedCredential->second_claim)
        );
    }

    /**
     * @test
     */
    public function CredentialCreate_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->post(route('credentials.create'), $this->payload);

        $res->assertStatus(401);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['authentication' => 'Unauthenticated.']
        ]);
    }

    /**
    * @test
    * @dataProvider invalidPayloads
    */
    public function CredentialCreate_OnCallWithInvalidInput_ReturnsFailureResponse(
        array $invalidPayload,
        array $expectedJson
    ) {
        $res = $this->post(route('credentials.create'), $invalidPayload);
        $res->assertStatus(400);
        $res->assertJsonFragment($expectedJson);
    }

    public function invalidPayloads(): array
    {
        return [
            'missingFields' => [
                'invalidPayload' => [
                    'title' => '',
                    'first_claim' => '',
                    'second_claim' => '',
                ],
                'expectedJson' => [
                    'title' => ['The title field is required.'],
                    'first_claim' => ['The first claim field is required.'],
                    'second_claim' => ['The second claim field is required.']
                ]
            ],
            'invalidInputLength' => [
                'invalidPayload' => [
                    'title' => Str::random(41),
                    'description' => Str::random(141),
                    'first_claim' => Str::random(5),
                    'second_claim' => Str::random(5),
                ],
                'expectedJson' => [
                    'title' => ['title must not exceed limit of 40 characters.'],
                    'description' => ['description must not exceed limit of 140 characters.'],
                ]
            ],
        ];
    }
}

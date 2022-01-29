<?php

namespace Tests\Feature\Credentials;

use App\Models\Credential;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Str;

class UpdateCredentialsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $sessionUser;
    private array $updatePayload;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionUser = User::factory()->create();
        Auth::setUser($this->sessionUser);

        $this->updatePayload = [
            'title' => $this->faker->userName(),
            'description' => $this->faker->text(140),
            'first_claim' => $this->faker->email(),
            'second_claim' => $this->faker->password()
        ];
    }

    /**
    * @test
    */
    public function CredentialUpdate_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $targetCredential = Credential::factory()->create();

        $res = $this->put(
            route(
                'credentials.update',
                ['id' => $targetCredential->id]
            ),
            $this->updatePayload
        );

        $res->assertStatus(204);
    }

    /**
    * @test
    */
    public function CredentialUpdate_OnSuccessfulCall_ChangesRecordInDatabase()
    {
        $targetCredential = Credential::factory()->create();
        $this->assertDatabaseHas('credentials', $targetCredential->toArray());

        $res = $this->put(
            route(
                'credentials.update',
                ['id' => $targetCredential->id]
            ),
            $this->updatePayload
        );

        $columnsForReturn = ['title', 'description', 'first_claim', 'second_claim'];
        $record = DB::table('credentials')->find($targetCredential->id, $columnsForReturn);
        /** Assert encryption on update */
        $record->first_claim = Crypt::decryptString($record->first_claim);
        $record->second_claim = Crypt::decryptString($record->second_claim);

        $this->assertNotNull($record);
        $this->assertEquals($record->title, $this->updatePayload['title']);
        $this->assertEquals($record->description, $this->updatePayload['description']);
        $this->assertEquals($record->first_claim, $this->updatePayload['first_claim']);
        $this->assertEquals($record->second_claim, $this->updatePayload['second_claim']);
    }

    /**
    * @test
    */
    public function CredentialUpdate_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->put(
            route(
                'credentials.update',
                ['id' => 0]
            ),
            $this->updatePayload
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
    public function CredentialUpdate_OverInexistingCredential_ReturnsFailureResponse()
    {
        $res = $this->put(
            route(
                'credentials.update',
                ['id' => 0]
            ),
            $this->updatePayload
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
    public function CredentialUpdate_OverAnotherUserCredential_ReturnsFailureResponse()
    {
        // Created as one user
        $targetCredential = Credential::factory()->create();

        // "logged" as another
        Auth::setUser(User::factory()->create());

        $res = $this->put(
            route(
                'credentials.update',
                ['id' => $targetCredential]
            ),
            $this->updatePayload
        );

        $res->assertStatus(403);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['forbidden' => 'You do not own this data. Invalid action taken.']
        ]);
    }

    /**
    * @test
    * @dataProvider invalidPayloads
    */
    public function CredentialUpdate_OnCallWithInvalidInput_ReturnsFailureResponse(
        array $invalidPayload,
        array $expectedJson
    ) {
        $targetCredential = Credential::factory()->create();

        $res = $this->put(
            route(
                'credentials.update',
                ['id' => $targetCredential->id]
            ),
            $invalidPayload
        );

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

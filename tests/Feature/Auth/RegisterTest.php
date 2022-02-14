<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Support\Str;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private array $payload;

    public function setUp(): void
    {
        parent::setUp();

        $password = $this->faker->password(8);
        $this->payload = [
            'name' => Str::random(10),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password,
            'password_confirmation' => $password
        ];
    }

    /**
     * @test
     */
    public function Register_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $response = $this->post(route('user.register'), $this->payload);

        $response->assertStatus(201);
        $response->assertJson([]);
    }

    /**
     * @test
     */
    public function Register_OnSuccessfulCall_PersistsUserInDatabase()
    {
        $this->post(route('user.register'), $this->payload);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => $this->payload['name'],
            'email' => $this->payload['email'],
        ]);
    }

    /**
     * @test
     */
    public function Register_OnSuccessfulCall_PersistsUserInDatabaseWithHashedPassword()
    {
        $this->post(route('user.register'), $this->payload);
        $persistedPassword = DB::table('users')->first(['password'])->password;

        /** Missing plain text */
        $this->assertDatabaseMissing('users', ['password' => $this->payload['password']]);
        $this->assertTrue(password_verify($this->payload['password'], $persistedPassword));
    }

    /**
     * @test
     */
    public function Register_OnSuccessfulCall_CreatesUserSession()
    {
        $this->post(route('user.register'), $this->payload);

        $this->assertAuthenticated();
    }

    /**
     * @test
     */
    public function Register_OnSuccessfulCall_CreateSampleCredentialRecord()
    {
        $this->post(route('user.register'), $this->payload);

        $this->assertDatabaseCount('credentials', 1);
        $this->assertDatabaseHas('credentials', [
            'title' => 'example website',
            'description' => 'This is a sample credential record'
        ]);
    }

    /**
     * @test
     */
    public function Register_OnCallFromAuthenticatedUser_ReturnsFailureResponse()
    {
        /** @var Authenticatable $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('user.register'), $this->payload);

        $response->assertStatus(403);
        $response->assertSimilarJson([
            'status' => 'fail',
            'data' => [
                'authentication' => 'You are already authenticated.'
            ]
        ]);
    }

    /**
     * @test
     */
    public function Register_OnCallWithAlreadyInUseEmail_ReturnsFailureResponse()
    {
        $firstUser = User::factory()->create();

        $invalidPayload = [
            'name' => Str::random(5),
            'email' => $firstUser->email,
            'password' => $firstUser->password,
            'password_confirmation' => $firstUser->password
        ];

        $response = $this->post(route('user.register'), $invalidPayload);

        $response->assertSimilarJson([
            'status' => 'fail',
            'data' => [
                'email' => ['This email is invalid. Try other.']
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider invalidPayloads
     */
    public function Register_OnCallWithInvalidInput_ReturnsFailureResponse(
        array $invalidPayload,
        array $expectedJson
    ) {
        $response = $this->post(route('user.register'), $invalidPayload);

        $response->assertStatus(400);
        $response->assertJsonFragment($expectedJson);
    }

    public function invalidPayloads(): array
    {
        return [
            'missingFields' => [
                'invalidPayload' => [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                    'password_confirmation' => ''
                ],
                'expectedJson' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ],
            'invalidName' => [
                'invalidPayload' => [
                    'name' => 'hardcodedInvalidName#%$',
                    'email' => 'hardcodedemail@debian.net',
                    'password' => 'hardcodedPassword123',
                    'password_confirmation' => 'hardcodedPassword123'
                ],
                'expectedJson' => [
                    'name' => ['The name must only contain letters, numbers, dashes and underscores.']
                ]
            ],
            'invalidEmail' => [
                'invalidPayload' => [
                    'name' => 'hardcoded_Name',
                    'email' => 'hardcodedInvalidemaildebian.net',
                    'password' => 'hardcodedPassword123',
                    'password_confirmation' => 'hardcodedPassword123'
                ],
                'expectedJson' => [
                    'email' => ['This email format is not accepted.']
                ]
            ],
            'invalidPassword' => [
                'invalidPayload' => [
                    'name' => 'hardcoded_Name',
                    'email' => 'hardcodedemail@debian.net',
                    'password' => 'inva',
                    'password_confirmation' => 'inv@'
                ],
                'expectedJson' => [
                    'password' => [
                        'Passwords doesn\'t match.',
                        'password must be at least 8 characters long.'
                    ]
                ]
            ],
        ];
    }
}

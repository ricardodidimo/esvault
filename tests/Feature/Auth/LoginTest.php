<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Str;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private array $payload;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $password = $this->faker->password(8);
        $this->user = User::factory()->create(
            [
                'name' => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'remember_token' => Str::random(10),
            ]
        );

        $this->payload = [
            'email' => $this->user->email,
            'password' => $password
        ];
    }

    /**
     * @test
     */
    public function Login_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $res = $this->post(route('account.login'), $this->payload);

        $res->assertStatus(201);
        $res->assertSimilarJson([
            'status' => 'success',
            'data' => null
        ]);
    }

    /**
     * @test
     */
    public function Login_OnSuccessfulCall_SetsServerSession()
    {
        $this->post(route('account.login'), $this->payload);
        $this->assertAuthenticated();
    }

    /**
     * @test
     */
    public function Login_OnCallFromAuthenticatedUser_ReturnsFailureResponse()
    {
        $fakeSession = Auth::setUser(new User());

        $res = $this->post(route('account.login'), $this->payload);

        $res->assertStatus(403);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => [
                'authentication' => 'You are already authenticated.'
            ]
        ]);
    }

    /**
     * @test
     */
    public function Login_OnCallWithInexistingLoginCredentials_ReturnsFailureResponse()
    {
        // Change data
        $this->payload['email'] = $this->payload['email'] . 'axx12';
        $this->payload['password'] = $this->payload['password'] . 'axx12';

        $res = $this->post(route('account.login'), $this->payload);

        $res->assertStatus(400);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => [
                'login' => ['Invalid email or password.']
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider invalidPayloads
     */
    public function Login_OnCallWithInvalidInput_ReturnsFailureResponse(array $invalidPayload)
    {
        $res = $this->post(route('account.login'), $invalidPayload);

        $res->assertStatus(400);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => [
                'login' => ['Invalid email or password.']
            ]
        ]);
    }

    public function invalidPayloads(): array
    {
        return [
            'malformedEmail' => [
                'invalidPayload' => [
                    'email' => Str::random(),
                    'password' => Str::random(8)
                ]
            ],
            'malformedPassword' => [
                'invalidPayload' => [
                    'email' => 'hardcodedemail@debian.net',
                    'password' => Str::random(5)
                ]
            ]
        ];
    }
}

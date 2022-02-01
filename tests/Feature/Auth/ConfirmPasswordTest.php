<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Str;

class ConfirmPasswordTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;
    private string $passwordInUse;

    public function setUp(): void
    {
        parent::setUp();

        $password = $this->faker->password(8);
        $this->passwordInUse = $password;
        $this->user = User::factory()->create(
            [
                'name' => Str::random(10),
                'email' => $this->faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'remember_token' => Str::random(10),
            ]
        );
        Auth::setUser($this->user);
    }

    /**
     * @test
     */
    public function ConfirmPassword_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $res = $this->post(route('account.confirm'), ['current_password' => $this->passwordInUse]);
        $res->assertStatus(204);
    }

    /**
    * @test
    */
    public function ConfirmPassword_OnCallWithInvalidInput_RetunsFailureResponse()
    {
        $res = $this->post(route('account.confirm'), ['current_password' => Str::random(8)]);

        $res->assertStatus(400);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => [
                'current_password' => ['The password is incorrect.']
            ]
        ]);
    }

    /**
    * @test
    */
    public function ConfirmPassword_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->post(route('account.confirm'), ['current_password' => $this->passwordInUse]);

        $res->assertStatus(401);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['authentication' => 'Unauthenticated.']
        ]);
    }
}

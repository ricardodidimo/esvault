<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Str;

class LogOutTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $password = $this->faker->password(8);
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
    public function LogOut_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $res = $this->delete(route('account.destroy'));

        $res->assertStatus(204);
    }

    /**
    * @test
    */
    public function LogOut_OnSuccessfulCall_RemovesServerSession()
    {
        $this->assertAuthenticated();

        $this->delete(route('account.destroy'));
        $this->assertGuest('web');
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Str;

class DestroyUserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private array $updatePayload;
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
    public function UserDestroy_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $res = $this->delete(route('user.destroy'));

        $res->assertStatus(204);
    }

    /**
    * @test
    */
    public function UserDestroy_OnSuccessfulCall_RemovesFromDatabase()
    {
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', $this->user->toArray());

        $res = $this->delete(route('user.destroy'));

        $this->assertDatabaseCount('users', 0);
    }

    /**
    * @test
    */
    public function UserDestroy_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->delete(route('user.destroy'));

        $res->assertStatus(401);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['authentication' => 'Unauthenticated.']
        ]);
    }
}

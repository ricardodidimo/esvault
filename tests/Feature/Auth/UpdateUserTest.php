<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Support\Str;

class UpdateUserTest extends TestCase
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

        $this->updatePayload = [
            'name' => $this->user->name . Str::random(5),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => $password . Str::random(5),
            'current_password' => $password
        ];
    }

    /**
     * @test
     */
    public function UserUpdate_OnSuccessfulCall_ReturnsExpectedResponse()
    {
        $res = $this->put(route('user.update'), $this->updatePayload);

        $res->assertStatus(204);
    }

    /**
    * @test
    */
    public function UserUpdate_OnSuccessfulCall_UpdatesDatabase()
    {
        $searchPayload = [
            'name' => $this->updatePayload['name'],
            'email' => $this->updatePayload['email']
        ];
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', $searchPayload);

        $res = $this->put(route('user.update'), $this->updatePayload);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', $searchPayload);
    }

    /**
    * @test
    */
    public function UserUpdate_OnCallForPartialUpdateOfName_UpdatesDatabaseAsExpected()
    {
        $res = $this->put(route('user.update'), [
            'name' => $this->updatePayload['name'],
            'current_password' => $this->updatePayload['current_password']
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => $this->updatePayload['name'],
            'email' => $this->user->email //keeps old data
        ]);
    }

    /**
    * @test
    */
    public function UserUpdate_OnCallForPartialUpdateOfEmail_UpdatesDatabaseAsExpected()
    {
        $res = $this->put(route('user.update'), [
            'email' => $this->updatePayload['email'],
            'current_password' => $this->updatePayload['current_password']
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => $this->user->name, //keeps old data
            'email' => $this->updatePayload['email'],
            'email_verified_at' => null
        ]);
    }

    /**
    * @test
    */
    public function UserUpdate_OnCallForPartialUpdateOfPassword_UpdatesDatabaseAsExpected()
    {
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'password' => $this->user->password
        ]);

        $res = $this->put(route('user.update'), [
            'password' => $this->updatePayload['password'],
            'current_password' => $this->updatePayload['current_password']
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
            'password' => $this->user->password // old password
        ]);
    }

    /**
    * @test
    */
    public function UserUpdate_OnCallWithAlreadyInUseEmail_ReturnsFailureResponse()
    {
        $this->updatePayload['email'] = $this->user->email;

        $res = $this->put(route('user.update'), $this->updatePayload);

        $res->assertStatus(400);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => [
                'email' => ['This email is invalid. Try other.']
            ]
        ]);
    }

    /**
    * @test
    */
    public function UserUpdate_OnCallWithIncorretCurrentPassword_ReturnsFailureResponse()
    {
        $this->updatePayload['current_password'] = Str::random(9);

        $res = $this->put(route('user.update'), $this->updatePayload);

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
    public function UserUpdate_OnUnauthenticatedCall_ReturnsFailureResponse()
    {
        Auth::logout();
        $res = $this->put(route('user.update'), $this->updatePayload);

        $res->assertStatus(401);
        $res->assertSimilarJson([
            'status' => 'fail',
            'data' => ['authentication' => 'Unauthenticated.']
        ]);
    }
}

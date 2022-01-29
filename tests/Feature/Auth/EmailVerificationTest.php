<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyEmailQueued;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;
use Illuminate\Support\Str;

class EmailVerificationTest extends TestCase
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
    public function Register_OnSuccessfulCall_DispatchEmailVerificationEvent()
    {
        Event::fake();

        $response = $this->post(route('user.register'), $this->payload);

        Event::assertDispatched(Registered::class);
    }

    /**
     * @test
     */
    public function EmailVerificationRoute_OnCallFromVerifiedUser_ReturnsFailureResponse()
    {
        $user = User::factory()->create();
        Auth::setUser($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $response = $this->get($verificationUrl);

        $response->assertStatus(400);
        $response->assertJson([
            'status' => 'fail',
            'data' => [
                'verification' => ['Email already verified.']
            ]
        ]);
    }

    /**
     * @test
     */
    public function EmailVerificationRoute_OnSuccessfulCall_MarksUserAsVerified()
    {
        $this->post(route('user.register'), $this->payload);
        $user = Auth::user();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        $response = $this->get($verificationUrl);

        /**@var MustVerifyEmail $user */
        $user = $user->fresh();
        $this->assertTrue($user->hasVerifiedEmail());
    }

    /**
     * @test
     */
    public function ResendEmailRoute_OnSuccessfulCall_TriggersVerifyEmailNotification()
    {
        $this->post(route('user.register'), $this->payload);
        Notification::fake();

        $response = $this->post(route('verification.send'));

        $response->assertStatus(200);
        Notification::assertSentTo(Auth::user(), VerifyEmailQueued::class);
    }

    /**
     * @test
     */
    public function VerifiedEmail_AfterEmailUpdateCall_IsMarkedAsUnverified()
    {
        $password = $this->faker->password(8);
        $user = User::factory()->create(
            [
                'name' => Str::random(10),
                'email' => $this->faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'remember_token' => Str::random(10),
            ]
        );
        Auth::setUser($user);

        /**@var MustVerifyEmail $user */
        $user = Auth::user();
        $this->assertTrue($user->hasVerifiedEmail());

        $updatePayload = [
            'email' => $this->faker->unique()->safeEmail(),
            'current_password' => $password
        ];
        $updateRes = $this->put(route('user.update'), $updatePayload);
        $updateRes->assertStatus(204);

        /**@var MustVerifyEmail $user */
        $user = $user->fresh();
        $this->assertFalse($user->hasVerifiedEmail());
    }
}

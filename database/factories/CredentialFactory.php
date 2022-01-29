<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class CredentialFactory extends Factory
{

    public function definition()
    {
        return [
            'title' => $this->faker->name(),
            'description' => $this->faker->text(10),
            'first_claim' => Crypt::encryptString($this->faker->userName()),
            'second_claim' => Crypt::encryptString($this->faker->password()),
            'user_id' => Auth::user()->id,
        ];
    }
}

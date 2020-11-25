<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Faker\Provider\pt_BR\Person as FakerBr;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fakerBr = new FakerBr($this->faker);

        return [
            'user_id' => $this->faker->uuid,
            'user_type' => 'user',
            'user_name' => $this->faker->name,
            'user_document' => $fakerBr->cpf(false),
            'user_email' => $this->faker->unique()->safeEmail,
            'user_password' => Hash::make('password'),
            'user_wallet' => $this->faker->numberBetween(1500, 12579)
        ];
    }
}

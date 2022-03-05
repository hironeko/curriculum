<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'name_kana' => $this->faker->company(),
            'post_code' => $this->faker->postcode(),
            'prefecture' => $this->faker->prefecture,
            'address' => $this->faker->address(),
            'tel' => $this->faker->phoneNumber(),
            'representative_first_name' => $this->faker->firstName(),
            'representative_last_name' => $this->faker->lastName(),
            'representative_first_name_kana' => $this->faker->firstKanaName,
            'representative_last_name_kana' => $this->faker->lastKanaName,
        ];
    }
}

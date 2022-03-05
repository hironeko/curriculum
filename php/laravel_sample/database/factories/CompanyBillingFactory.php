<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyBillingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory()->create()->id,
            'name' => $this->faker->company(),
            'name_kana' => $this->faker->company(),
            'post_code' => $this->faker->postcode(),
            'prefecture' => $this->faker->prefecture,
            'address' => $this->faker->address(),
            'tel' => $this->faker->phoneNumber(),
            'department' => $this->faker->name(),
            'billing_first_name' => $this->faker->firstName(),
            'billing_last_name' => $this->faker->lastName(),
            'billing_first_name_kana' => $this->faker->firstKanaName,
            'billing_last_name_kana' => $this->faker->lastKanaName,
        ];
    }
}

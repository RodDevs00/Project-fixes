<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserInfo>
 */
class UserInfoFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $user_id = 1;
        $gender = fake()->randomElement(['Male', 'Female']);
        $civil_status = fake()->randomElement(['Single', 'Married']);
        $baranggay = fake()->randomElement(['Bagacay', 'Bahay', 'Bairan']);

        return [
            'user_id' => $user_id++,
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->lastName(),
            'surname' => fake()->lastName(),
            'phone_number' => fake("en_PH")->mobileNumber(),
            'sex' => $gender,
            'civil_status' => $civil_status,
            'citizenship' => "Filipino",
            'date_of_birth' => fake()->date('m-d-Y'),
            'place_of_birth' => fake("en_PH")->barangay(),
            'profession_occupation' => fake()->jobTitle(),
            'tax_identification_number' => fake()->randomNumber(9, true),
            'address' => fake("en_PH")->province().", ".fake("en_PH")->municipality().", ".fake("en_PH")->barangay(),
            'baranggay' => $baranggay,
            'height' => fake()->randomNumber(3, true),
            'weight' => fake()->randomNumber(3, true),
        ];
    }
}

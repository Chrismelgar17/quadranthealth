<?php
namespace Database\Factories;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicFactory extends Factory
{
    protected $model = Clinic::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'medical_practice_phone_number' => $this->faker->phoneNumber,
            'clinic_phone_number' => $this->faker->phoneNumber,
            'clinic_name' => $this->faker->company,
            'clinic_address' => $this->faker->address,
            'clinic_hours' => '9 AM - 5 PM',
            'additional_clinic_goals' => $this->faker->sentence,
            'first_message' => $this->faker->sentence,
        ];
    }
}
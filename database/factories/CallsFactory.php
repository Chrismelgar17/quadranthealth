<?php

namespace Database\Factories;

use App\Models\Calls;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CallsFactory extends Factory
{
    protected $model = Calls::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid()->toString(), // Generate a unique string for the 'id' field
            'call_sid' => Str::uuid()->toString(), // Generate a unique string for the 'call_sid' field
            'request_type' => $this->faker->word,
            'transcript' => $this->faker->sentence,
            'summary' => $this->faker->paragraph,
        ];
    }
}
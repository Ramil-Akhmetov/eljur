<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\LessonType;

class LessonTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LessonType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'short_name' => $this->faker->unique()->word(),
            'name' => $this->faker->unique()->name(),
        ];
    }
}

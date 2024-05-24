<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Group;
use App\Models\GroupStatus;
use App\Models\Specialty;
use App\Models\Teacher;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'semester' => $this->faker->numberBetween(-10000, 10000),
            'specialty_id' => Specialty::random()->id,
            'teacher_id' => Teacher::random()->id,
            'start_date' => $this->faker->date(),
            'group_status_id' => GroupStatus::factory(),
        ];
    }
}

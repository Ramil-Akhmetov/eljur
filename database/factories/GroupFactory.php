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
            'semester' => $this->faker->numberBetween(1, 8),
            'specialty_id' => Specialty::all()->random()->id,
            'teacher_id' => Teacher::all()->random()->id,
            'start_date' => $this->faker->date(),
            'group_status_id' => GroupStatus::all()->random()->id,
        ];
    }
}

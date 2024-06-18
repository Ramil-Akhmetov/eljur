<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

class TopicFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Topic::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
// Get a random subject id
        $subject_id = Subject::all()->random()->id;

// Use DB transaction to avoid race conditions
        $number = DB::transaction(function () use ($subject_id) {
// Lock the rows for this subject to prevent race conditions
            DB::table('topics')->where('subject_id', $subject_id)->lockForUpdate()->get();

// Get the max number and increment by 1
            $maxNumber = Topic::where('subject_id', $subject_id)->max('number');
            return $maxNumber ? $maxNumber + 1 : 1;
        });

        return [
            'subject_id' => $subject_id,
            'number' => $number,
            'name' => $this->faker->sentence(),
            'hours' => $this->faker->numberBetween(1, 4),
        ];
    }
}

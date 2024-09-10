<?php

namespace Database\Factories;

use App\Models\Semester;
use DateTime;
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
        $group_status_id = 1;
        $startDate = new DateTime($this->faker->dateTimeBetween('-6 years', 'now')->format('Y-9-1'));
        $secondSemesterStart = new DateTime(date('Y') . '-01-01');
        $currentDate = new DateTime();

        // Determine if the current date is in the second half of the year
        $isSecondHalf = $currentDate > $secondSemesterStart;

        // Calculate the age difference in years
        $groupAge = $startDate->diff($currentDate)->y;

        // Calculate the semester number
        $semesterNumber = 1 + $groupAge * 2 + ($isSecondHalf ? 1 : 0);

        // Fetch the semester or default to semester number 8
        $semester = Semester::where('number', $semesterNumber)->first();
        if ($semester === null) {
            $semester = Semester::where('number', 8)->first();
            $group_status_id = 3;
        }


        return [
            'code' => $this->faker->bothify('?##'),
            'semester_id' => $semester->id,
            'specialty_id' => Specialty::all()->random()->id,
            'teacher_id' => null,
            'start_date' => $startDate->format('Y-m-d'),
            'group_status_id' => $group_status_id,
        ];
    }
}

<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Termwind\Components\Dd;

class Group extends Model
{
    use CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'specialty_id',
        'teacher_id',
        'start_date',
        'semester_id',
        'group_status_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'specialty_id' => 'integer',
        'teacher_id' => 'integer',
        'start_date' => 'date',
        'group_status_id' => 'integer',
    ];

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function subjects()
    {
        return $this->hasManyThrough(Subject::class, Specialty::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function groupStatus(): BelongsTo
    {
        return $this->belongsTo(GroupStatus::class);
    }

    protected $appends = ['current_semester', 'semester_by_month'];

//    public function getCurrentSemesterAttribute()
//    {
//        // Semester have only number field, 1 - first semester, 2 - second semester, 3 - summer semester...
//
//        // static
//        $firstSemesterStart = \date('Y') . '-09-01';
//        $firstSemesterEnd = \date('Y') . '-01-31';
//        $secondSemesterStart = \date('Y') . '-01-01';
//        $secondSemesterEnd = \date('Y') . '-06-30';
//
//        $currentDate = \date('Y-m-d');
//        $isSecondHalf = \strtotime($currentDate) > \strtotime($secondSemesterStart);
//
//        $groupStartDate = $this->start_date;
//        $groupAge = floor($groupStartDate->diffInYears($currentDate));
//
//        $semesterNumber = 1 + $groupAge * 2 + ($isSecondHalf ? 1 : 0);
//
//
//        return Semester::where('number', $semesterNumber)->first();
//    }

    public function getCurrentSemesterAttribute()
    {
        $startDate = new DateTime($this->date);
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

        return $semester;
    }

    public function getSemesterByMonth($month)
    {
        $startDate = new DateTime($month);
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

        return $semester;
    }
}

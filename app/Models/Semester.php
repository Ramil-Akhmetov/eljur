<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Semester extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_semester');
    }


//    static public function getSemesterByMonth($month) // month date('Y-m')
//    {
//        $firstHalfStart = \date('Y') . '-09-01';
//        $firstHalfEnd = \date('Y') . '-01-31';
//        $secondHalfStart = \date('Y') . '-01-01';
//        $secondHalfEnd = \date('Y') . '-06-30';
//
//        $isFirstHalf = $month >= $firstHalfEnd && $month <= $firstHalfStart;
//        $isSecondHalf = $month >= $secondHalfEnd && $month <= $secondHalfStart;
//
//
//
//
//
//    }
//    static public function getSemesterByMonth($month) // month date('Y-m')
//    {
//        $firstHalfStart = \date('Y') . '-09-01';
//        $firstHalfEnd = \date('Y') . '-01-31';
//        $secondHalfStart = \date('Y') . '-01-01';
//        $secondHalfEnd = \date('Y') . '-06-30';
//
//        $semesterNumber = ;
//        $semester = Semester::where('number', $semesterNumber)->first();
//
//        return $semester;
//    }
}

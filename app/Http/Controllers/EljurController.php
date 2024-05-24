<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Specialty;

class EljurController extends Controller
{
    public function showCreateEljur()
    {
        $teacher = backpack_user()->teacher;

        if (!$teacher) {
            return view('error',['error_number' => 403, 'title' => 'У вас нет доступа к этой странице']);
        }

        $groups = $teacher->groups;


        return view('eljur_create', compact('groups'));
    }

    public function getSpecialties($groupId)
    {
        $group = Group::find($groupId);
        $specialty = $group->specialty;

        return response()->json($specialty);
    }

    public function getJournalData($groupId, $specialtyId)
    {
        $group = Group::with('students.user')->find($groupId);
        $subjects = $group->specialty->subjects()->whereHas('teachers', function ($query) use ($specialtyId) {
            $query->where('specialty_id', $specialtyId);
        })->get();

        $students = $group->students;

        return response()->json([
            'students' => $students,
            'subjects' => $subjects,
        ]);
    }
}

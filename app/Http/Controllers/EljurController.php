<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Subject;
use App\Models\Lesson;

class EljurController extends Controller
{
    public function showCreateEljur(Request $request)
    {
        $user = backpack_user();
        if ($user->role->name !== 'Администратор' && $user->role->name !== 'Преподаватель') {
            return view('error', ['error_number' => 403, 'title' => 'У вас нет доступа к этой странице']);
        }

        $groupId = $request->query('group_id');
        if (!$groupId) {
            return view('error', ['error_number' => 400, 'title' => 'Группа не выбрана']);
        }

        $group = Group::find($groupId);
        if (!$group) {
            return view('error', ['error_number' => 404, 'title' => 'Группа не найдена']);
        }

        $lessonDates = Lesson::where('group_id', $groupId)->distinct()->pluck('timestamp')->toArray();

        return view('eljur_create', [
            'group' => $group,
            'lessonDates' => $lessonDates
        ]);
    }

    public function getSubjects($groupId)
    {
        $group = Group::find($groupId);
        $subjects = $group->specialty->subjects;

        return response()->json($subjects);
    }

    public function getJournalData($groupId, $subjectId)
    {
        $group = Group::with('students.user')->find($groupId);
        $students = $group->students;
        $lessonDates = Lesson::where('group_id', $groupId)->where('subject_id', $subjectId)->distinct()->pluck('timestamp')->toArray();

        return response()->json([
            'students' => $students,
            'lessonDates' => $lessonDates
        ]);
    }
}

<?php

use App\Http\Controllers\EljurController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.


Route::get('admin/register', [\App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('backpack.auth.register')->middleware('web');
Route::post('admin/register', [\App\Http\Controllers\Admin\Auth\RegisterController::class, 'register'])->name('backpack.auth.register')->middleware('web');

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('classroom', 'ClassroomCrudController');
    Route::crud('specialty', 'SpecialtyCrudController');
    Route::crud('group', 'GroupCrudController');
    Route::crud('student', 'StudentCrudController');
    Route::crud('teacher', 'TeacherCrudController');
    Route::crud('lesson-type', 'LessonTypeCrudController');
    Route::crud('attendance-option', 'AttendanceOptionCrudController');
    Route::crud('subject', 'SubjectCrudController');
    Route::crud('invite-code', 'InviteCodeCrudController');
    Route::crud('teacher-group-subject', \App\Http\Controllers\Admin\TeacherGroupSubjectCrudController::class);


    Route::get('/eljur/student', [EljurController::class, 'showEljurStudent'])->name('eljur.student');
    Route::get('/eljur', [EljurController::class, 'showCreateEljur'])->name('eljur');

    Route::post('/eljur', [EljurController::class, 'saveEljur'])->name('eljur');
    Route::post('/eljur/add', [EljurController::class, 'eljurAdd'])->name('eljur.add');

    Route::get('/report-group-month', [EljurController::class, 'showReportGroupMonth'])->name('report.group.month');
    Route::get('/report-group-semester', [EljurController::class, 'showReportGroupSemester'])->name('report.group.semester');
    Route::post('/report-group-semester', [EljurController::class, 'saveReportGroupSemester'])->name('report.group.semester');

});

Route::group([], function() {

});

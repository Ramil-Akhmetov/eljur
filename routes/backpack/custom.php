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


    Route::get('/eljur', [EljurController::class, 'showCreateEljur'])->name('eljur.create');
    Route::get('/eljur/subjects/{groupId}', [EljurController::class, 'getSubjects']);
    Route::get('/eljur/journal/{groupId}/{subjectId}', [EljurController::class, 'getJournalData']);
});

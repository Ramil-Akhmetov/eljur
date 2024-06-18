<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Role;
use App\Models\Teacher;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\Operations\Concerns\HasForm;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait CreateTeacherOperation
{
    use HasForm;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupCreateTeacherRoutes(string $segment, string $routeName, string $controller): void
    {
        $this->formRoutes(
            operationName: 'createTeacher',
            routesHaveIdSegment: true,
            segment: $segment,
            routeName: $routeName,
            controller: $controller,
        );
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateTeacherDefaults(): void
    {
        $this->formDefaults(
            operationName: 'createTeacher',
            buttonStack: 'line', // alternatives: top, bottom
            buttonMeta: [
                'icon' => 'la la-chalkboard-teacher',
                'label' => 'Сделать преподавателем',
            ],
        );

        $this->crud->operation('createTeacher', function () {
            $this->crud->setSubheading('Сделать преподавателем');

            $this->crud->addField([
                'name' => 'teacherSubjects',
                'label' => 'Дисциплины',
                'type' => 'select_multiple',
                'entity' => 'teacherSubjects', // the method that defines the relationship in your Model
                'model' => "App\Models\Subject", // foreign key model
                'attribute' => 'full_name',
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?

                'options' => (function ($query) {
                    return $query->join('specialties', 'subjects.specialty_id', '=', 'specialties.id')
                        ->select('subjects.*') // make sure to select subjects.* to avoid overriding subject attributes
                        ->with('specialty')
                        ->orderBy('specialties.code', 'ASC')
                        ->orderBy('subjects.name', 'ASC')
                        ->get();
                }), // force the related options to be a custom query, instead of all(); y
            ]);
        });
    }

    public function getCreateTeacherForm(int $id)
    {
        $this->crud->hasAccessOrFail('createTeacher');

        $user = User::find($id);

        if ($user->teacher) {
            return redirect(config('backpack.base.route_prefix') . '/teacher/' . $user->teacher->id . '/edit');
        }

        return $this->formView($id);
    }

    /**
     * Method to handle the POST request and perform the operation
     *
     * @param int $id
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function postCreateTeacherForm(int $id)
    {
        $this->crud->hasAccessOrFail('createTeacher');

        return $this->formAction(id: $id, formLogic: function ($inputs, $entry) {
            // TODO debug
            $valid = Validator::make($inputs, [
                'teacherSubjects.*' => 'required|exists:subjects,id',
            ])->validate();

            if ($entry->role && $entry->role->name == 'Студент') {
                \Alert::error('Пользователь уже является студентом')->flash();
                return;
            }

            if ($entry->teacher) {
                \Alert::error('Пользователь уже является преподавателем')->flash();
                return;
            }

            DB::transaction(function () use ($entry, $valid) {
                $user = User::find($entry->id);
//                dd($user->id);

                $user->role_id = Role::where('name', 'Преподаватель')->first()->id;
                $teacher = Teacher::create([
                    'user_id' => $user->id,
                ]);
                if (isset($valid['teacherSubjects'])) {
                    $teacher->subjects()->sync($valid['teacherSubjects']);
                }
                $user->save();
            });


            // show a success message
            \Alert::success('Запись была успешно добавлена.')->flash();
        });
    }
}

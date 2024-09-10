<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Role;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\Operations\Concerns\HasForm;
use Illuminate\Support\Facades\Validator;

trait CreateStudentOperation
{
    use HasForm;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupCreateStudentRoutes(string $segment, string $routeName, string $controller): void
    {
        $this->formRoutes(
            operationName: 'createStudent',
            routesHaveIdSegment: true,
            segment: $segment,
            routeName: $routeName,
            controller: $controller,
        );
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateStudentDefaults(): void
    {
        $this->formDefaults(
            operationName: 'createStudent',
            buttonStack: 'line', // alternatives: top, bottom
            buttonMeta: [
                'icon' => 'la la-graduation-cap',
                'label' => 'Сделать студентом',
            ],
        );

        $this->crud->operation('createStudent', function () {
            $this->crud->setSubheading('Сделать студентом');

            $this->crud->addField([
                'name' => 'code',
                'label' => 'Код',
                'entity' => 'student.code',
            ]);
            $this->crud->addField([
                'name' => 'group',
                'label' => 'Группа',
                'entity' => 'student.group',
            ]);
        });
    }

    public function getCreateStudentForm(int $id)
    {
        $this->crud->hasAccessOrFail('createStudent');

        $user = User::find($id);

        if ($user->student) {
            return redirect(config('backpack.base.route_prefix') . '/student/' . $user->student->id . '/edit');
        }

        return $this->formView($id);
    }

    /**
     * Method to handle the POST request and perform the operation
     *
     * @param int $id
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function postCreateStudentForm(int $id)
    {
        $this->crud->hasAccessOrFail('createStudent');

        return $this->formAction(id: $id, formLogic: function ($inputs, $entry) {
            $valid = Validator::make($inputs, [
                'code' => 'required|unique:students',
                'group' => 'required|exists:groups,id',
            ])->validate();

            if ($entry->role && $entry->role->name == 'Преподаватель') {
                \Alert::error('Пользователь уже является преподавателем')->flash();
                return;
            }
            if ($entry->student) {
                \Alert::error('Пользователь уже является студентом')->flash();
                return;
            }

            $entry->role_id = Role::where('name', 'Студент')->first()->id;
            $entry->student()->create([
                'code' => $valid['code'],
                'group_id' => $valid['group'],
                'student_status_id' => 1,
            ]);
            $entry->save();

            \Alert::success('Запись была успешно добавлена.')->flash();
        });
    }
}

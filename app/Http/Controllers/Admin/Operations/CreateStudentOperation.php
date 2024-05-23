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
            $this->crud->addField([
                'name' => 'student_status',
                'label' => 'Статус',
                'type' => 'select',
                'default' => 1,
                'entity' => 'student.studentStatus',
                'model' => 'App\Models\StudentStatus',
                'attribute' => 'name',

                'options' => (function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                }), //  you can use this to filter the results show in the select            ]);
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
                'student_status' => 'required|exists:student_statuses,id',
            ])->validate();

            if (!$entry->student) {
                $entry->role_id = Role::where('name', 'Студент')->first()->id;
                $entry->student()->create([
                    'code' => $valid['code'],
                    'group_id' => $valid['group'],
                    'student_status_id' => $valid['student_status'],
                ]);
                $entry->save();
            } else {
                \Alert::error('Пользователь уже является студентом')->flash();
            }

            \Alert::success('Запись была успешно добавлена.')->flash();
        });
    }
}

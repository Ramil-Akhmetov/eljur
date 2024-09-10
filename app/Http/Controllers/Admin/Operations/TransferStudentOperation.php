<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Role;
use App\Models\Student;
use App\Models\StudentStatus;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\Operations\Concerns\HasForm;
use Illuminate\Support\Facades\Validator;

trait TransferStudentOperation
{
    use HasForm;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupTransferStudentRoutes(string $segment, string $routeName, string $controller): void
    {
        $this->formRoutes(
            operationName: 'transferStudent',
            routesHaveIdSegment: true,
            segment: $segment,
            routeName: $routeName,
            controller: $controller,
        );
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupTransferStudentDefaults(): void
    {
        $this->formDefaults(
            operationName: 'transferStudent',
            buttonStack: 'line', // alternatives: top, bottom
            buttonMeta: [
                'icon' => 'la la-redo',
                'label' => 'Перевести студента',
            ],
        );

        $this->crud->operation('transferStudent', function () {
            $this->crud->setSubheading('Перевести студента');
            $this->crud->addField([
                'name' => 'group',
                'label' => 'Группа',
                'type' => 'select',
            ]);
        });
    }

    public function getTransferStudentForm(int $id)
    {
        $this->crud->hasAccessOrFail('transferStudent');

        return $this->formView($id);
    }

    /**
     * Method to handle the POST request and perform the operation
     *
     * @param int $id
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function postTransferStudentForm(int $id)
    {
        $this->crud->hasAccessOrFail('transferStudent');

        return $this->formAction(id: $id, formLogic: function ($inputs, $entry) {
            $valid = Validator::make($inputs, [
                'group' => 'required|exists:groups,id',
            ])->validate();

            if ($entry->studentStatus->name === 'Переведен') {
                \Alert::error('Студент уже переведен')->flash();
                return;
            } else if ($entry->studentStatus->name === 'Отчислен') {
                \Alert::error('Студент уже отчислен')->flash();
                return;
            } else if ($entry->studentStatus->name === 'Выпустился') {
                \Alert::error('Студент уже выпустился')->flash();
                return;
            }

            $entry->student_status_id = StudentStatus::where('name', 'Переведен')->first()->id;
            $entry->save();

            $new_student = Student::create([
                'user_id' => $entry->user_id,
                'code' => $entry->code,
                'group_id' => $valid['group'],
                'student_status_id' => StudentStatus::where('name', 'Активен')->first()->id,
            ]);

            // show a success message
            \Alert::success('Студент был успешно переведен.')->flash();
        });
    }
}

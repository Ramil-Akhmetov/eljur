<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Operations\OldTransferStudentOperation;
use App\Http\Controllers\Admin\Operations\TransferStudentOperation;
use App\Http\Requests\StudentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StudentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StudentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
//    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

//    use OldTransferStudentOperation;
    use TransferStudentOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Student::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/student');
        CRUD::setEntityNameStrings('студента', 'студенты');
        CRUD::setListView('student_list_with_filters');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'code',
            'label' => 'Код',
        ]);
        CRUD::addColumn([
            'name' => 'group',
            'label' => 'Группа',
        ]);
        CRUD::addColumn([
            'name' => 'user.surname',
            'label' => 'Фамилия',
        ]);
        CRUD::addColumn([
            'name' => 'user.name',
            'label' => 'Имя',
        ]);
        CRUD::addColumn([
            'name' => 'user.patronymic',
            'label' => 'Отчество',
        ]);

        CRUD::addColumn([
            'name' => 'user.phone',
            'label' => 'Телефон',
            'type' => 'phone',
        ]);

        CRUD::addColumn([
            'name' => 'user.email',
            'label' => 'email',
            'type' => 'Email',
        ]);

        CRUD::addColumn([
            'name' => 'user.sex',
            'label' => 'Пол',
            'type' => 'enum',
        ]);
        CRUD::addColumn([
            'name' => 'user.birthdate',
            'label' => 'День рождения',
            'type' => 'date',
        ]);
        CRUD::addColumn([
            'name' => 'studentStatus',
            'label' => 'Статус',
        ]);

        // Add this line to add custom query based on filter
        if (request()->has('group')) {
            $this->crud->addClause('where', 'group_id', request()->input('group'));
        }
    }
    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StudentRequest::class);
//        CRUD::setFromDb(); // set fields from db columns.

        $this->crud->addField([
            'name' => 'code',
            'label' => 'Код',
        ]);
        $this->crud->addField([
            'name' => 'group',
            'label' => 'Группа',
        ]);
        $this->crud->addField([
            'name' => 'student_status',
            'label' => 'Статус',
            'type' => 'select',
            'default' => 1,
            'model' => 'App\Models\StudentStatus',
            'attribute' => 'name',

            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), //  you can use this to filter the results show in the select            ]);
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}

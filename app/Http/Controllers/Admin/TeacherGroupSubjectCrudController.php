<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherGroupSubjectRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TeacherGroupSubjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TeacherGroupSubjectCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\TeacherGroupSubject::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/teacher-group-subject');
        CRUD::setEntityNameStrings('связь', 'Связи преподаватель-группа-предмет');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
//        CRUD::setFromDb(); // set columns from db columns.

        CRUD::addColumn([
            'name' => 'teacher',
            'entity' => 'teacherSubject',
            'label' => 'Преподаватель',
            'attribute' => 'teacher_full_name',
            'orderable' => true,
        ]);

        CRUD::addColumn([
            'name' => 'subject',
            'entity' => 'teacherSubject',
            'label' => 'Дисциплина',
            'attribute' => 'item_name',
        ]);

        CRUD::addColumn([
            'name' => 'group',
            'label' => 'Группа',
            'type' => 'select',
            'entity' => 'group',
            'attribute' => 'code',
            'model' => \App\Models\Group::class,
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(TeacherGroupSubjectRequest::class);
//        CRUD::setFromDb(); // set fields from db columns.


        CRUD::addField([
            'name' => 'teacherSubject',
            'label' => 'Преподаватель - предмет',
            'attribute' => 'full_name',
            'type' => 'select',
        ]);
        CRUD::addField([
            'name' => 'group',
            'label' => 'Группа',
            'type' => 'select',
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

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }
}

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
//    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

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

    protected function setupShowOperation()
    {
        $this->setupListOperation();
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
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('surname', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'students.user_id')
                    ->orderBy('users.surname', $columnDirection)->select('students.*');
            }
        ]);
        CRUD::addColumn([
            'name' => 'user.name',
            'label' => 'Имя',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'students.user_id')
                    ->orderBy('users.name', $columnDirection)->select('students.*');
            }
        ]);
        CRUD::addColumn([
            'name' => 'user.patronymic',
            'label' => 'Отчество',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('patronymic', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'students.user_id')
                    ->orderBy('users.patronymic', $columnDirection)->select('students.*');
            }
        ]);

        CRUD::addColumn([
            'name' => 'user.phone',
            'label' => 'Телефон',
            'type' => 'phone',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('phone', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'students.user_id')
                    ->orderBy('users.phone', $columnDirection)->select('students.*');
            }

        ]);

        CRUD::addColumn([
            'name' => 'user.email',
            'label' => 'email',
            'type' => 'Email',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('email', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'students.user_id')
                    ->orderBy('users.email', $columnDirection)->select('students.*');
            }
        ]);

        CRUD::addColumn([
            'name' => 'user.sex',
            'label' => 'Пол',
            'type' => 'enum',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('sex', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'students.user_id')
                    ->orderBy('users.sex', $columnDirection)->select('students.*');
            }

        ]);
        CRUD::addColumn([
            'name' => 'user.birthdate',
            'label' => 'Дата рождения',
            'type' => 'date',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('birthdate', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'students.user_id')
                    ->orderBy('users.birthdate', $columnDirection)->select('students.*');
            }
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

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $student = \App\Models\Student::find($id);
        $student->user->role_id = null;
        $student->user->save();

        return $this->crud->delete($id);
    }
}

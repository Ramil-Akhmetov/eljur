<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TeacherCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TeacherCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Teacher::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/teacher');
        CRUD::setEntityNameStrings('teacher', 'teachers');
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
            'name' => 'user.surname',
            'label' => 'Фамилия',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('surname', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.surname', $columnDirection)->select('teachers.*');
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
                return $query->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.name', $columnDirection)->select('teachers.*');
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
                return $query->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.patronymic', $columnDirection)->select('teachers.*');
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
                return $query->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.phone', $columnDirection)->select('teachers.*');
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
                return $query->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.email', $columnDirection)->select('teachers.*');
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
                return $query->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.sex', $columnDirection)->select('teachers.*');
            }

        ]);
        CRUD::addColumn([
            'name' => 'user.birthdate',
            'label' => 'День рождения',
            'type' => 'date',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('user', function ($q) use ($column, $searchTerm) {
                    $q->where('birthdate', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.birthdate', $columnDirection)->select('teachers.*');
            }
        ]);

        CRUD::addColumn([
            'name' => 'subjects',
            'label' => 'Предметы',
            'type' => 'select_multiple',
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
        CRUD::setValidation(TeacherRequest::class);
//        CRUD::setFromDb(); // set fields from db columns.


        CRUD::addField([
            'name' => 'subjects',
            'type' => 'select_multiple',
            // optional
            'entity' => 'subjects', // the method that defines the relationship in your Model
            'model' => "App\Models\Subject", // foreign key model
            'attribute' => 'name',
            'pivot' => true, // on create&update, do you need to add/delete pivot table entries?

            // also optional
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }), // force the related options to be a custom query, instead of all(); y
        ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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

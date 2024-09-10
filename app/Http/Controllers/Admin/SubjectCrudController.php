<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubjectRequest;
use App\Http\Requests\SubjectUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubjectCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Subject::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subject');
        CRUD::setEntityNameStrings('дисциплину', 'дисциплины');
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
            'name' => 'name',
            'label' => 'Название',
        ]);

        CRUD::addColumn([
            'name' => 'specialty',
            'label' => 'Специальность',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('specialty', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('specialties', 'specialties.id', '=', 'subjects.specialty_id')
                    ->orderBy('specialties.name', $columnDirection)->select('subjects.*');
            }
        ]);

        CRUD::addColumn([
            'name' => 'hours',
            'label' => 'Количество часов',
        ]);

        CRUD::addColumn([
            'name' => 'semesters',
            'label' => 'Семестры',
            'type' => 'select_multiple',
            'attribute' => 'number',
        ]);

        CRUD::addColumn([
            'name' => 'teachers',
            'label' => 'Преподаватели',
            'type' => 'select_multiple',
            'attribute' => 'full_name',

            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('teachers', function ($q) use ($column, $searchTerm) {
                    $q->whereHas('user', function ($q) use ($column, $searchTerm) {
                        $q->where('surname', 'like', '%' . $searchTerm . '%')
                            ->orWhere('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('patronymic', 'like', '%' . $searchTerm . '%');
                    });
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('teachers', 'teachers.id', '=', 'groups.teacher_id')
                    ->leftJoin('users', 'users.id', '=', 'teachers.user_id')
                    ->orderBy('users.surname', $columnDirection)->select('groups.*');
            }
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation($is_update = false)
    {
        if ($is_update) {
            CRUD::setValidation(SubjectUpdateRequest::class);
        } else {
            CRUD::setValidation(SubjectRequest::class);
        }

        CRUD::addField([
            'name' => 'name',
            'label' => 'Название',
        ]);

        CRUD::addField([
            'name' => 'specialty',
            'label' => 'Специальность',
        ]);

        CRUD::addField([
            'name' => 'hours',
            'label' => 'Количество часов',
        ]);

        CRUD::addField([
            'name' => 'semesters',
            'label' => 'Семестры',
            'type' => 'select_multiple',
            'attribute' => 'number',
        ]);

        CRUD::addField([
            'name' => 'teachers',
            'label' => 'Преподаватели',
            'type' => 'select_multiple',
            'entity' => 'teachers', // the method that defines the relationship in your Model
            'model' => "App\Models\Teacher", // foreign key model
            'attribute' => 'full_name', // Use the full_name accessor
            'pivot' => true,

            'options' => (function ($query) {
                return $query->join('users', 'teachers.user_id', '=', 'users.id')
                    ->select('teachers.*')
                    ->with('user')
                    ->orderBy('users.surname', 'ASC')
                    ->get();
            }),
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
        $this->setupCreateOperation(true);
    }
}

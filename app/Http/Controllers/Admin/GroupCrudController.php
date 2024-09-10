<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GroupRequest;
use App\Http\Requests\GroupUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GroupCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Group::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/group');
        CRUD::setEntityNameStrings('группу', 'группы');
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
            'name' => 'specialty',
            'label' => 'Специальность',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('specialty', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('specialties', 'specialties.id', '=', 'groups.specialty_id')
                    ->orderBy('specialties.name', $columnDirection)->select('groups.*');
            }
        ]);

        CRUD::addColumn([
            'name' => 'teacher',
            'label' => 'Классный руководитель',
            'attribute' => 'full_name',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('teacher', function ($q) use ($column, $searchTerm) {
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

        CRUD::addColumn([
            'name' => 'start_date',
            'label' => 'Дата формирования',
            'type' => 'date',
        ]);

        CRUD::addColumn([
            'name' => 'semester',
            'label' => 'Семестр',
            'attribute' => 'number',
        ]);

        CRUD::addColumn([
            'name' => 'groupStatus',
            'label' => 'Статус',
            'default' => 1,
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
            CRUD::setValidation(GroupUpdateRequest::class);
        } else {
            CRUD::setValidation(GroupRequest::class);
        }

        CRUD::addField([
            'name' => 'code',
            'label' => 'Код',
        ]);

        CRUD::addField([
            'name' => 'specialty',
            'label' => 'Специальность',
        ]);

        CRUD::addField([
            'name' => 'start_date',
            'label' => 'Дата формирования',
            'type' => 'date',
        ]);

        CRUD::addField([
            'name' => 'semester',
            'label' => 'Семестр',
            'attribute' => 'number',
            'default' => 1,
        ]);

        CRUD::addField([
            'name' => 'groupStatus',
            'label' => 'Статус',
            'default' => 1,
        ]);

        CRUD::addField([
            'name' => 'teacher',
            'label' => 'Классный руководитель',
            'attribute' => 'full_name',
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

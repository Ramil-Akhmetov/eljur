<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AttendanceOptionRequest;
use App\Http\Requests\AttendanceOptionUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AttendanceOptionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AttendanceOptionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\AttendanceOption::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/attendance-option');
        CRUD::setEntityNameStrings('тип посещаемости', 'типы посещаемости');
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
            'name' => 'short_name',
            'label' => 'Сокращенное название',
        ]);

        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Название',
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
            CRUD::setValidation(AttendanceOptionUpdateRequest::class);
        } else {
            CRUD::setValidation(AttendanceOptionRequest::class);
        }

        CRUD::addField([
            'name' => 'short_name',
            'label' => 'Сокращенное название',
        ]);

        CRUD::addField([
            'name' => 'name',
            'label' => 'Название',
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

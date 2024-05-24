<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LessonTypeRequest;
use App\Http\Requests\LessonTypeUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class LessonTypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LessonTypeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\LessonType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/lesson-type');
        CRUD::setEntityNameStrings('тип лекций', 'типы лекций');
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
            CRUD::setValidation(LessonTypeUpdateRequest::class);
        } else {
            CRUD::setValidation(LessonTypeRequest::class);
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

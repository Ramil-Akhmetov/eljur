<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SpecialtyRequest;
use App\Http\Requests\SpecialtyUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SpecialtyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SpecialtyCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Specialty::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/specialty');
        CRUD::setEntityNameStrings('специальность', 'специальности');
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
            CRUD::setValidation(SpecialtyUpdateRequest::class);
        } else {
            CRUD::setValidation(SpecialtyRequest::class);
        }

        CRUD::addField([
            'name' => 'code',
            'label' => 'Код',
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

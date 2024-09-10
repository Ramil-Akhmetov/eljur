<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TopicRequest;
use App\Models\Topic;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TopicCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TopicCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Topic::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/topic');
        CRUD::setEntityNameStrings('тему', 'темы');
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
            'name' => 'number',
            'label' => 'Порядковый номер',
            'type' => 'number',
        ]);
        CRUD::addColumn([
            'name' => 'subject.name',
            'label' => 'Дисциплина',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('subject', function ($q) use ($column, $searchTerm) {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            },
            'orderable' => true,
            'orderLogic' => function ($query, $column, $columnDirection) {
                return $query->leftJoin('subjects', 'subjects.id', '=', 'topics.subject_id')
                    ->orderBy('subjects.name', $columnDirection)->select('topics.*');
            }
        ]);
        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Название',
        ]);
        CRUD::addColumn([
            'name' => 'hours',
            'label' => 'Кол-во часов',
            'type' => 'number',
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
        CRUD::setValidation(TopicRequest::class);

        CRUD::addField([
            'name' => 'number',
            'label' => 'Порядковый номер',
            'type' => 'number',
        ]);
        $this->crud->addField([
            'name' => 'subject',
            'label' => 'Дисциплина',
            'type' => 'select',
            'entity' => 'subject', // the method that defines the relationship in your Model
            'model' => "App\Models\Subject", // foreign key model
            'attribute' => 'full_name_with_specialty',

            'options' => (function ($query) {
                return $query->join('specialties', 'subjects.specialty_id', '=', 'specialties.id')
                    ->select('subjects.*') // make sure to select subjects.* to avoid overriding subject attributes
                    ->with('specialty')
                    ->orderBy('specialties.code', 'ASC')
                    ->orderBy('subjects.name', 'ASC')
                    ->get();
            }), // force the related options to be a custom query, instead of all(); y
        ]);
        CRUD::addField([
            'name' => 'name',
            'label' => 'Название',
        ]);
        CRUD::addField([
            'name' => 'hours',
            'label' => 'Кол-во часов',
            'type' => 'number',
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

<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait CreateTeacherOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupCreateTeacherRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/create-teacher', [
            'as'        => $routeName.'.createTeacher',
            'uses'      => $controller.'@createTeacher',
            'operation' => 'createTeacher',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateTeacherDefaults()
    {
        CRUD::allowAccess('createTeacher');

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'create-teacher', 'create-teacher', 'end');
        });

    }

    public function createTeacher()
    {
        CRUD::hasAccessOrFail('createTeacher');
        $this->crud->setOperation('createTeacher');

        return response()->json([
            'message' => 'hello',
        ]);

//        $clonedEntry = $this->crud->model->findOrFail($id)->replicate();

//        return (string) $clonedEntry->push();
    }
}

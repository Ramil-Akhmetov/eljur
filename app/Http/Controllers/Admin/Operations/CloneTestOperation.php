<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Role;
use App\Models\Teacher;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait CloneTestOperation
{
    protected function setupCloneRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/{id}/clone', [
            'as'        => $routeName.'.clone',
            'uses'      => $controller.'@clone',
            'operation' => 'clone',
        ]);
    }
    public function clone($id)
    {
        $this->crud->hasAccessOrFail('create');
        $this->crud->setOperation('Clone');

        $user = $this->crud->model->findOrFail($id);
        if(Teacher::where('user_id', $user->id)->first()) {
//            abort(400, 'Пользователь уже является преподавателем');
            return response()->json([
                'message' => 'Пользователь уже является преподавателем',
            ], 400);
        }
        Teacher::create([
            'user_id' => $user->id,
        ]);
        $teacherRole = Role::where('name', 'Преподаватель')->first();
        $user->role = $teacherRole;

        return;
    }
    protected function setupCloneDefaults()
    {
        $this->crud->allowAccess('clone');

        $this->crud->operation(['list', 'show'], function () {
            $this->crud->addButtonFromView('line', 'clone', 'clone', 'end');
        });
    }
}

<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Http\Controllers\Operations\Concerns\HasForm;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait CreateStudentOperation
{
    use HasForm;

    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
//    protected function setupCreateTeacherRoutes(string $segment, string $routeName, string $controller): void
//    {
//        $this->formRoutes(
//            operationName: 'comment',
//            routesHaveIdSegment: true,
//            segment: $segment,
//            routeName: $routeName,
//            controller: $controller
//        );
//    }

    protected function setupCreateStudentRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/create-student', [
            'as'        => $routeName.'.getCreateStudent',
            'uses'      => $controller.'@getCreateStudentForm',
            'operation' => 'createStudent',
        ]);
        Route::post($segment.'/{id}/create-student', [
            'as'        => $routeName.'.postModerate',
            'uses'      => $controller.'@postCreateStudentForm',
            'operation' => 'createStudent',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCreateStudentDefaults(): void
    {
        $this->formDefaults(
            operationName: 'createStudent',
            buttonStack: 'line', // alternatives: top, bottom
         buttonMeta: [
             'icon' => 'la la-graduation-cap',
             'label' => 'Создать студента',
             'wrapper' => [
                  'target' => '_blank',
             ],
         ],
        );
    }

    /**
     * Method to handle the GET request and display the View with a Backpack form
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function getCreateStudentForm(int $id)
    {
        $this->crud->hasAccessOrFail('update');
        $this->crud->setOperation('createStudent');

        // get the info for that entry
        $this->data['entry'] = $this->crud->getEntry($id);
        $this->data['crud'] = $this->crud;
        $this->data['title'] = 'Создание студента '.$this->crud->entity_name;

        return view('vendor.backpack.crud.create-student', $this->data);    }

    /**
     * Method to handle the POST request and perform the operation
     *
     * @param  int  $id
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function postCreateStudentForm(int $id)
    {
        $this->crud->hasAccessOrFail('update');

        // TODO: do whatever logic you need here
        // ...
        // You can use
        // - $this->crud
        // - $this->crud->getEntry($id)
        // - $request
        // ...

        // show a success message
        \Alert::success('Студент успешно создан.')->flash();

        return \Redirect::to($this->crud->route);    }
}

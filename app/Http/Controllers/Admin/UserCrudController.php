<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Operations\CloneTestOperation;
use App\Http\Controllers\Admin\Operations\CreateTeacherOperation;
use App\Http\Controllers\Admin\Operations\CreateStudentOperation;
use App\Http\Controllers\Admin\Operations\OCreateTeacherOperation;
use App\Http\Controllers\Admin\Operations\OldCreateTeacherOperation;
use App\Http\Controllers\InviteCodeController;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\InviteCode;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Termwind\Components\Dd;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use CreateTeacherOperation;
    use CreateStudentOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('пользователя', 'пользователи');
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
            'name' => 'surname',
            'label' => 'Фамилия',
        ]);
        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Имя',
        ]);
        CRUD::addColumn([
            'name' => 'patronymic',
            'label' => 'Отчество',
        ]);

        CRUD::addColumn([
            'name' => 'phone',
            'label' => 'Телефон',
            'type' => 'phone',
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'label' => 'email',
            'type' => 'Email',
        ]);

        CRUD::addColumn([
            'name'  => 'sex',
            'label' => 'Пол',
            'type'  => 'enum',
        ]);
        CRUD::addColumn([
            'name'  => 'birthdate',
            'label' => 'Дата рождения',
            'type'  => 'date',
        ]);
        CRUD::addColumn([
            'name'  => 'role',
            'label' => 'Роль',
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
        CRUD::setValidation(UserRequest::class);
//        CRUD::setFromDb(); // set fields from db columns.

        CRUD::addField([
            'name' => 'surname',
            'label' => 'Фамилия',
        ]);
        CRUD::addField([
            'name' => 'name',
            'label' => 'Имя',
        ]);
        CRUD::addField([
            'name' => 'patronymic',
            'label' => 'Отчество',
        ]);
        CRUD::addField([
            'name' => 'phone',
            'label' => 'Телефон',
        ]);
        CRUD::addField([
            'name'  => 'sex',
            'label' => 'Пол',
            'type'  => 'enum',
        ]);
        CRUD::addField([
            'name'  => 'birthdate',
            'label' => 'Дата рождения',
            'type'  => 'date',
        ]);
        CRUD::addField([
            'name'  => 'password',
            'label' => 'Пароль',
            'type' => 'password',
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
        CRUD::setValidation(UserUpdateRequest::class);

        CRUD::addField([
            'name' => 'surname',
            'label' => 'Фамилия',
        ]);
        CRUD::addField([
            'name' => 'name',
            'label' => 'Имя',
        ]);
        CRUD::addField([
            'name' => 'patronymic',
            'label' => 'Отчество',
        ]);

        CRUD::addField([
            'name' => 'phone',
            'label' => 'Телефон',
        ]);

        CRUD::addField([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        CRUD::addField([
            'name'  => 'sex',
            'label' => 'Пол',
            'type'  => 'enum',
        ]);
        CRUD::addField([
            'name'  => 'birthdate',
            'label' => 'Дата рождения',
            'type'  => 'date',
        ]);
        CRUD::addField([
            'name'  => 'password',
            'label' => 'Пароль',
            'type' => 'password',
        ]);
    }

    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    public function store()
    {
        $password = $this->crud->getRequest()->request->get('password');
        if (! $password) {
            $this->crud->getRequest()->request->remove('password');
        }

        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $item = $this->crud->create($this->crud->getStrippedSaveRequest($request));
        $this->data['entry'] = $this->crud->entry = $item;

        $invite_code = InviteCodeController::generateCode();
        $item->inviteCode()->create(['code' => $invite_code]);
        if($password) {
            $item->password = bcrypt($password);
            $item->save();
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $password = $this->crud->getRequest()->request->get('password');
        if (! $password) {
            $this->crud->getRequest()->request->remove('password');
        }

        $item = $this->crud->getEntry($this->crud->getCurrentEntryId());
        if($password) {
            $item->password = bcrypt($password);
            $item->save();
        }

        $response = $this->traitUpdate();
        // do something after save
        return $response;
    }

    public function destroy($id)
    {
        $this->crud->hasAccessOrFail('delete');

        // get entry ID from Request (makes sure its the last ID for nested resources)
        $id = $this->crud->getCurrentEntryId() ?? $id;

        $invite_code = InviteCode::where('user_id', $id)->first();
        if ($invite_code) {
            $invite_code->delete();
        }

        return $this->crud->delete($id);
    }
}

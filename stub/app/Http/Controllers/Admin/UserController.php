<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\UserRequest as WorkRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User as WorkModel;
use Netto\Http\Controllers\Abstract;
use Netto\Models\Permission;
use Netto\Models\Role;
use Netto\Traits\CrudControllerActions;

class UserController extends Abstract\AdminCrudController
{
    use CrudControllerActions;

    protected string $class = WorkModel::class;
    protected string $id = 'user';

    protected array $list = [
        'relations' => [],
        'title' => 'cms::main.list_user',
        'url' => [
            'create',
            'delete',
        ],
    ];

    protected array $messages = [
        'create' => 'cms::main.create_user',
    ];

    protected array $route = [
        'index' => 'admin.user.index',
        'create' => 'admin.user.create',
        'delete' => 'admin.user.delete',
        'destroy' => 'admin.user.destroy',
        'edit' => 'admin.user.edit',
        'store' => 'admin.user.store',
        'update' => 'admin.user.update',
    ];

    protected string $title = 'cms::main.list_user';

    protected array $sync = [
        'roles',
        'permissions',
    ];

    protected array $view = [
        'index' => 'cms::user.index',
        'edit' => 'admin.user.user'
    ];

    /**
     * @param string $id
     * @return View
     */
    public function edit(string $id): View
    {
        $object = $this->getObject($this->class, [
            'id' => [
                'operator' => '=',
                'value' => $id,
            ],
        ]);

        $this->setRouteParams();
        $this->addCrumbIndex();

        return $this->_edit($object, [
            'balance' => format_number($object->getBalance(), 2),
        ]);
    }

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        return $this->_store($request);
    }

    /**
     * @param WorkRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $id): RedirectResponse
    {
        return $this->_update($request, $id);
    }

    /**
     * @param $object
     * @return array
     */
    protected function getReference($object): array
    {
        return [
            'role' => get_labels(Role::class),
            'permission' => get_labels(Permission::class),
        ];
    }

    /**
     * @param WorkRequest $request
     * @param WorkModel $model
     * @return RedirectResponse
     */
    protected function _save(FormRequest $request, Model $model): RedirectResponse
    {
        foreach ($request->safe()->except(['password']) as $key => $value) {
            $model->setAttribute($key, $value);
        }

        $password = $request->safe()->only('password');
        if (!empty($password['password'])) {
            $model->setAttribute('password', Hash::make($password['password']));
        }

        $model->save();

        if (Gate::allows('manage-access')) {
            foreach ($this->sync as $item) {
                $model->{$item}()->sync($request->get($item, []));
            }
        }

        $model->refresh();
        return $this->redirect(empty($request->get('button_apply')), $model->id);
    }
}

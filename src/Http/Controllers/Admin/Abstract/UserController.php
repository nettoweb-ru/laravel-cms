<?php

namespace Netto\Http\Controllers\Admin\Abstract;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\Hash;
use Netto\Http\Controllers\Admin\Abstract\CrudController as BaseController;
use Netto\Traits\Crud\AdminActions;

use App\Models\User as WorkModel;
use App\Http\Requests\Admin\UserRequest as WorkRequest;

use Netto\Models\{Permission, Role};

abstract class UserController extends BaseController
{
    use AdminActions;

    protected string $baseRoute = 'user';
    protected string $className = WorkModel::class;

    protected array $crudTitle = [
        'list' => 'main.list_user',
        'create' => 'main.create_user',
    ];

    protected string $itemRouteId = 'user';

    protected array $syncRelations = ['roles', 'permissions'];

    protected array $viewId = [
        'list' => 'cms::user.index',
        'edit' => 'cms::user.user',
    ];

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        $model = $this->createModel();
        return $this->redirect($model, $request);
    }

    /**
     * @param WorkRequest $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(WorkRequest $request, string $id): RedirectResponse
    {
        $model = $this->getModel($id);
        return $this->redirect($model, $request);
    }

    /**
     * @param $model
     * @return array
     */
    protected function getReference($model): array
    {
        return [
            'role' => get_labels_translated(Role::class),
            'permission' => get_labels_translated(Permission::class),
        ];
    }

    /**
     * @param BaseModel $model
     * @param FormRequest $request
     * @return bool
     */
    protected function save(BaseModel $model, FormRequest $request): bool
    {
        foreach ($request->safe()->except(['password']) as $key => $value) {
            $model->setAttribute($key, $value);
        }

        $password = $request->safe()->only('password');
        if (!empty($password['password'])) {
            $model->setAttribute('password', Hash::make($password['password']));
        }

        if (!$model->save()) {
            return false;
        }

        foreach ($this->syncRelations as $relation) {
            $model->{$relation}()->sync(array_filter($request->get($relation, [])));
        }

        return true;
    }
}

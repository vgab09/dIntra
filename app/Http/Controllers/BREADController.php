<?php

namespace App\Http\Controllers;


use App\Http\Components\FormHelper\FormHelper;
use App\Traits\AlertMessage;
use App\Traits\DataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Exception;

abstract class BREADController extends Controller
{
    use DataTable, AlertMessage;

    /**
     * @var string unique resource name
     */
    protected $slug;

    /**
     * @var string
     */
    protected $modelClass;


    /**
     * (B)READ Browse data
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $list = $this->buildListHelper();

        if ($request->ajax()) {
            return $list->createDataTables($this->collectListData())->make(true);
        }

        return $list->render();
    }

    /**
     * BRE(A)D Add data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new()
    {
        return $this->getFormHelperToInsert()->render();
    }

    /**
     * Save resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws Exception
     */
    public function insert()
    {
        $form = $this->getFormHelperToInsert();
        if (!$form->validateAndSave()) {
            return $form->render();
        }
        return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres létrehozás');
    }

    /**
     * BR(E)AD Edit data
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $form = $this->getFormHelperToUpdate($model);
        return $form->render();
    }

    /**
     * Update resource
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|RedirectResponse
     * @throws Exception
     */
    public function update($id)
    {
        $form = $this->getFormHelperToUpdate($this->modelClass::findOrFail($id));
        if (!$form->validateAndSave()) {
            return $form->render();
        }
        return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres modósítás');
    }

    /**
     * BREA(D) Edit data
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|RedirectResponse
     */
    public function delete($id)
    {
        $confirmDelete = $this->confirmDelete($id);
        if ($confirmDelete !== true) {
            return $confirmDelete;
        }

        $this->modelClass::findOrFail($id)->delete();
        return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres törlés');
    }

    /**
     * @param Model $model
     * @param Collection $alternativeModel
     * @param array $actions
     * @return \Illuminate\Http\RedirectResponse|bool
     */
    public function resolveRelationContract($model, $alternativeModel, $actions)
    {

        $relations = $model->getRelations();
        $relationsNames = array_keys($relations);

        foreach ($relationsNames as $relationName) {
            if (!array_key_exists($relationName, $actions)) {
                return $this->redirectError($this->getFailedRedirectUrl(), 'Kapcsolódó bejegyzések müvelete hibásan lett megadva.');
            }

            if ($actions[$relationName] !== 'DELETE' && !$alternativeModel->has($actions[$relationName])) {
                return $this->redirectError($this->getFailedRedirectUrl(), $relationName . ' kapcsolat nem állítható be a kijelölt értékre.');
            }
        }

        /**
         * @var Collection $relatedModel
         */
        foreach ($relations as $relation => $relatedModel) {
            $relatedModel = collect($relatedModel);
            $action = $actions[$relation];
            $callBack = null;

            if ($action === 'DELETE') {
                $relatedModel->map(function ($model, $key) {
                    $model->delete();
                });
                continue;
            }

            $foreignKey = $model->$relation()->getForeignKeyName();
            $relatedModel->map(function ($model, $key) use ($foreignKey, $action) {
                $model->$foreignKey = $action;
                $model->save();
            });
        }
        return true;
    }

    /**
     * @return string
     */
    protected function getSuccessRedirectUrl()
    {
        return action('\\' . static::class . '@index');
    }

    /**
     * @return string
     */
    protected function getFailedRedirectUrl()
    {
        return $this->getSuccessRedirectUrl();
    }

    /**
     * return bool|Illuminate\View\View
     */
    protected function confirmDelete($id)
    {
        return true;
    }

    /**
     * Get DataTable rows
     *
     * @return \Eloquent|Collection|QueryBuilder
     */
    protected function collectListData()
    {
        return App::make($this->modelClass)->newQuery();
    }

    protected function getFormHelperToUpdate($model)
    {
        return $this->buildFormHelper($model);
    }

    protected function getFormHelperToInsert($model)
    {
        return $this->buildFormHelper($model);
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected abstract function buildFormHelper($model);

}
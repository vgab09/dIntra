<?php

namespace App\Http\Controllers;


use App\Http\Components\FormHelper\FormHelper;
use App\Traits\AlertMessage;
use App\Traits\DataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\App;

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
        return $this->buildFormHelper()->render();
    }

    /**
     * Save resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function insert()
    {
        $form = $this->buildFormHelper();
        if (!$form->validateAndSave()) {
            return $form->render();
        }
        return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres létrehozás');
    }

    /**
     * BR(E)AD Edit data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->modelClass::findOrFail($id);
        $form = $this->buildFormHelper($model);
        return $form->render();
    }

    /**
     * Update resource
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|RedirectResponse
     */
    public function update($id)
    {
        $form = $this->buildFormHelper($this->modelClass::findOrFail($id));
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
     * @return string
     */
    protected function getSuccessRedirectUrl()
    {
        return action('\\' . static::class . '@index');
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
    protected function getQueryBuilder()
    {
        return App::make($this->modelClass)->newQuery();
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected abstract function buildFormHelper($model = null);

}
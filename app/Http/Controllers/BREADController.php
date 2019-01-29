<?php
namespace App\Http\Controllers;


use App\Http\Components\FormHelper\FormHelper;
use App\Traits\AlertMessage;
use App\Traits\DataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\App;

abstract class BREADController extends Controller
{
    use DataTable,AlertMessage;

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
    public function index(Request $request){

        $list = $this->buildListHelper();

        if($request->ajax()){
            return $list->createDataTables($this->collectListData())->make(true);
        }

        return $list->render();
    }

    public function edit($id){
        $model = $this->modelClass::findOrFail($id);
        $form = $this->buildFormHelper($model);
        return $form->render();
    }

    /**
     * Update resource
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id)
    {
        $form = $this->buildFormHelper($this->modelClass::findOrFail($id));
        if (!$form->validateAndSave()) {
            return $form->render();
        }
        return $this->redirectSuccess($this->getSuccessRedirectUrl(),'Sikeres modósítás');
    }

    /**
     * @return string
     */
    protected function getSuccessRedirectUrl()
    {
        return action('\\' . static::class . '@index');
    }

    /**
     * Get DataTable rows
     *
     * @return \Eloquent|Collection|QueryBuilder
     */
    protected function getQueryBuilder(){
        return App::make($this->modelClass)->newQuery();
    }

    /**
     * @param Model $model
     * @return FormHelper
     */
    protected abstract function buildFormHelper(Model $model);

}
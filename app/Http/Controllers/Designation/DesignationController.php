<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.15.
 * Time: 8:46
 */

namespace App\Http\Controllers\Designation;


use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Designation;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;

class DesignationController
{

    protected $slug = 'designations';

    public function __construct()
    {
        $this->modelClass = Designation::class;
    }

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

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {

        return ListHelper::to($this->slug, $this->modelClass,
            [
                ListFieldHelper::to('name', 'Megnevezés'),
                ListFieldHelper::to('description', 'Leírás')
                    ->setMaxLength(20),
                ListFieldHelper::to('active', 'Aktív')
                    ->setType('bool')
                    ->setSearchTypeBool()
            ]
        )
            ->addRowActions(function ($model){
                return '<div class="btn-group">  
  <a class="btn btn-primary" href="#">Action</a>
  <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="#">Action</a>
    <a class="dropdown-item" href="#">Another action</a>
    <a class="dropdown-item" href="#">Something else here</a>
    <div class="dropdown-divider"></div>
    <a class="dropdown-item" href="#">Separated link</a>
  </div>
</div>';
            })
            ->setTitle('Beosztások')
            ->addTimeStamps();
    }

    /**
     * Get DataTable rows
     *
     * @return \Eloquent|Collection|QueryBuilder
     */
    protected function collectListData(){
        return App::make($this->modelClass)->newQuery();
    }
}
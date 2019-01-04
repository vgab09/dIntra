<?php
namespace App\Http\Controllers;


use App\Traits\AlertMessage;
use App\Traits\DataTable;
use Illuminate\Http\Request;
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){

        if($request->ajax()){
            return $this->getData();
        }

        return $this->buildListHelper()->render();
    }


    /**
     * Get data to dataTable
     *
     * @return \Eloquent|Collection|QueryBuilder
     */
    protected function collectListData(){
        return App::make($this->modelClass)->newQuery();
    }

}
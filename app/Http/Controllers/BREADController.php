<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.08.
 * Time: 15:12
 */

namespace App\Http\Controllers;


use App\Traits\DataTable;

abstract class BREADController extends Controller
{
    use DataTable;

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
    public function index(){
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
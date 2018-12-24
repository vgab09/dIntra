<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.14.
 * Time: 19:13
 */

namespace App\Traits;

use App\Http\Components\ListHelper\ListHelper;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

trait DataTable
{

    public function getData(){
        $list = $this->buildListHelper();
        return $list->createDataTables($this->collectListData())->make(true);
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected abstract function buildListHelper();


    /**
     * Get DataTable rows
     *
     * @return \Eloquent|Collection|QueryBuilder
     */
    protected function collectListData(){
        return App::make($this->modelClass)->newQuery();
    }
}
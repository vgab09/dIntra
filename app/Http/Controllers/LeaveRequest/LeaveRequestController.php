<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.03.08.
 * Time: 7:03
 */

namespace App\Http\Controllers\LeaveRequest;


use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use Illuminate\Database\Eloquent\Model;

class LeaveController extends BREADController
{


    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        // TODO: Implement buildFormHelper() method.
    }

    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        // TODO: Implement buildListHelper() method.
    }
}
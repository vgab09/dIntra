<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 2019.03.17.
 * Time: 17:47
 */

namespace App\Http\Controllers\LeavePolicy;


use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\LeavePolicy;
use Illuminate\Database\Eloquent\Model;

class LeavePolicyController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = LeavePolicy::class;
    }

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
       return ListHelper::to('leavePolicy',[
           ListFieldHelper::to('name','Név'),
           ListFieldHelper::to('days','Napok száma')->setSuffix(' nap'),
           ListFieldHelper::to('active','Engedélyezve')->setType(ListFieldHelper::BOOL_TYPE),
       ]);
    }
}
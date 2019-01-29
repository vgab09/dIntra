<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.15.
 * Time: 8:46
 */

namespace App\Http\Controllers\Designation;


use App\Http\Components\FormHelper\FormCheckboxFieldHelper;
use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Designation;
use Illuminate\Database\Eloquent\Model;


class DesignationController extends BREADController
{

    protected $slug = 'designations';

    public function __construct()
    {
        $this->modelClass = Designation::class;
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
        )->addRowActions(function ($model){
            return FormDropDownFieldHelper::to('action')
                ->addActionLinkIfCan('update App\Persistence\Models\Designation',route('editDesignation',$model->getKey()),'<i class="fas fa-pencil-alt"></i> Szerkesztés')
                ->addActionLinkIfCan('delete App\Persistence\Models\Designation','#','<i class="fas fa-trash-alt"></i> Törlés')
                ->renderTag();
        })
            ->setTitle('Beosztások')
            ->addTimeStamps();
    }

    protected function buildFormHelper(Model $model){
        return FormHelper::to($this->slug,$this->modelClass,$model,[
            FormInputFieldHelper::toText('name','Megnevezés'),
            FormCheckboxFieldHelper::toSwitch('active','Aktív'),
            FormInputFieldHelper::toTextarea('description','Leírás'),
        ])->setTitle(sprintf('#%d Beosztás modósítása',$model->getKey()));
    }


}
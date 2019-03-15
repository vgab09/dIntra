<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2019.02.03.
 * Time: 16:14
 */

namespace App\Http\Controllers\Holiday;



use App\Http\Components\FormHelper\FormDropDownFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Components\ToolbarLink\Link;
use App\Http\Components\ToolbarLink\ToolbarLinks;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Holiday;
use Illuminate\Database\Eloquent\Model;

class HolidayController extends BREADController
{

    public function __construct()
    {
        $this->modelClass = Holiday::class;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('holiday',$model,[
            FormInputFieldHelper::toText('name','Név')->setRequired(),
            FormInputFieldHelper::toDate('start','Kezdés')->setRequired(),
            FormInputFieldHelper::toDate('end','Vége')->setRequired(),
            FormInputFieldHelper::toTextarea('description','Megjegyzés'),
        ]);
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Plusz munkanapok szerkesztése');
    }

    protected function getFormHelperToInsert()
    {
        return parent::getFormHelperToInsert()->setTitle('Új munkanap hozzáadása');
    }


    /**
     * Create a new ListHelper instance, and fill up.
     * @return ListHelper
     */
    protected function buildListHelper()
    {
        return ListHelper::to('holiday',[
            ListFieldHelper::to('id_holiday','#'),
            ListFieldHelper::to('name','Név'),
            ListFieldHelper::to('start','Kezdés'),
            ListFieldHelper::to('end','Vége'),
            ListFieldHelper::to('description','Megjegyzés')->setMaxLength(30),

        ])
            ->addTimeStamps()
            ->setTitle('Munkaszüneti napok')
            ->addRowActions(function ($model) {
                return FormDropDownFieldHelper::to('action')
                    ->addActionLinkIfCan('update_holiday', route('editHoliday', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                    ->addActionLinkIfCan('delete_holiday', route('deleteHoliday', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                    ->renderTag();
            })
            ->setToolbarLinkInstance(
                ToolbarLinks::make()->addLinkIfCan('create_holiday',route('newHoliday'),'<i class="fas fa-plus-circle"></i> Új hozzáadása')
            );
    }

    protected function collectListData()
    {
        return Holiday::query();
    }
}
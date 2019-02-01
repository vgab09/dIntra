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
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper;
use App\Http\Components\FormHelper\FormSelectFieldHelper;
use App\Http\Components\ListHelper\ListFieldHelper;
use App\Http\Components\ListHelper\ListHelper;
use App\Http\Controllers\BREADController;
use App\Persistence\Models\Designation;
use App\Persistence\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
                    ->setSearchTypeBool(),
            ]
        )->addRowActions(function ($model) {
            return FormDropDownFieldHelper::to('action')
                ->addActionLinkIfCan('update App\Persistence\Models\Designation', route('editDesignation', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                ->addActionLinkIfCan('delete App\Persistence\Models\Designation', route('deleteDesignation', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                ->renderTag();
        })
            ->setTitle('Beosztások')
            ->addTimeStamps();
    }

    protected function buildFormHelper($model = null)
    {
        return FormHelper::to($this->slug, $this->modelClass, $model, [
            FormInputFieldHelper::toText('name', 'Megnevezés'),
            FormCheckboxFieldHelper::toSwitch('active', 'Aktív',1,1),
            FormInputFieldHelper::toTextarea('description', 'Leírás'),
        ]);
    }

    public function new()
    {
        return $this->buildFormHelper()->setTitle('Új beosztás hozzáadása')->render();
    }

    public function edit($id)
    {
     return $this->buildFormHelper($this->modelClass::findOrFail($id))->setTitle('Beosztás szerkesztése')->render();
    }


    /**
     * @param $id
     * @return bool|View
     */
    protected function confirmDelete($id)
    {
        $designation = new Designation();
        $designation = $designation->newQuery()->withCount('employees')->findOrFail($id);
        if (!empty($designation->employees_count)) {

            $alternativeDesignations = DB::table($designation->getTable())
                ->select('id_designation','name')
                ->where('id_designation', '<>',$id)
                ->pluck('name','id_designation');
            return FormHelper::to('confirmDesignation', '', null, [
                FormSelectFieldHelper::to('confirmDeleteSelect', 'Kapcsolódó bejegyzések:',
                    [
                        'DELETE' =>'Törlése',
                        'Áthelyezése ide:' => $alternativeDesignations,
                    ]
                ),
            ])
                ->setActionFromMethod('designationController@resolveContractAndDelete')
                ->setTitle('Törlési müvelet')
                ->render();
        }

        return true;
    }

    protected function resolveContractAndDelete(Request $request)
    {
        dd($action = $request->get('confirmDeleteSelect'));
    }


}
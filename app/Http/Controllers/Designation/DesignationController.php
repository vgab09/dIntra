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

        return ListHelper::to($this->slug,
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
                ->addActionLinkIfCan('update_designation', route('editDesignation', $model->getKey()), '<i class="fas fa-pencil-alt"></i> Szerkesztés')
                ->addActionLinkIfCan('delete_designation', route('deleteDesignation', $model->getKey()), '<i class="fas fa-trash-alt"></i> Törlés')
                ->renderTag();
        })
            ->setTitle('Beosztások')
            ->addTimeStamps();
    }

    protected function buildFormHelper($model)
    {
        return FormHelper::to($this->slug, $model, [
            FormInputFieldHelper::toText('name', 'Megnevezés'),
            FormCheckboxFieldHelper::toSwitch('active', 'Aktív', 1, 1),
            FormInputFieldHelper::toTextarea('description', 'Leírás'),
        ]);
    }

    protected function getFormHelperToUpdate($model)
    {
        return parent::getFormHelperToUpdate($model)->setTitle('Beosztás szerkesztése');
    }

    protected function getFormHelperToInsert()
    {
        return parent::getFormHelperToInsert()->setTitle('Új beosztás hozzáadása');
    }


    /**
     * @param $id
     * @return bool|View
     */
    protected function confirmDelete($id)
    {
        /**
         * @var Designation $designation
         */
        $designation = new Designation();
        $designation = $designation->newQuery()->withCount('employees')->findOrFail($id);
        if (!empty($designation->employees_count)) {

            return FormHelper::to('confirmDesignation', null, [
                FormSelectFieldHelper::to('contractAction[employees]', 'Kapcsolódó bejegyzések ('.$designation->employees_count.'):',
                    [
                        'DELETE' => 'Törlése',
                        'Áthelyezése ide:' => $designation->getAlternativeDesignationOptions(),
                    ]
                ),
            ])
                ->setTitle('Törlési müvelet')
                ->render();
        }

        return true;
    }

    public function resolveContractAndDelete($id, Request $request)
    {
        /**
         * @var Designation $designation
         */
        $designation = Designation::with('employees')->findOrFail($id);

        if ($this->resolveRelationContract($designation, $designation->getAlternativeDesignationOptions(), $request->get('contractAction', []))) {
            $designation->delete();
            return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres törlés');
        } else {
            return $this->redirectError($this->getFailedRedirectUrl(), 'Sikertelen törlés');
        }


        /**
         * @var Employee $employee
         */
        if ($action === 'DELETE') {
            $callback = function ($employee) {
                $employee->delete();
            };
        } else {
            $newDesignation = Designation::find($action);
            if (empty($newDesignation)) {
                return $this->redirectError(route('deleteDesignation'), 'A kiválasztott elem nem található');
            }

            $callback = function ($employee, $key) use ($newDesignation) {
                $employee->id_designation = $newDesignation->id_designation;
                $employee->save();
            };

        }

        $designation->employees->map($callback);
        if ($designation->delete()) {
            return $this->redirectSuccess($this->getSuccessRedirectUrl(), 'Sikeres törlés');
        } else {
            return $this->redirectError($this->getFailedRedirectUrl(), 'Sikertelen törlés');
        }

    }

}
<?php

namespace App\Http\Controllers\Profile;


use App\Http\Components\FormHelper\FormFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\editProfile;
use App\Persistence\Models\Employee;
use App\Traits\AlertMessage;
use App\Traits\FormHelperTrait;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use AlertMessage;

    /**
     * Create a new ListHelper instance, and fill up.
     * @param Employee $model
     * @return FormHelper
     */
    protected function buildFormHelper($model)
    {
        return FormHelper::to('myProfile',Employee::class,$model,[
            FormFieldHelper::to('name',FormFieldHelper::TEXT_TYPE,'Név')->setRequired(),
            FormFieldHelper::to('email',FormFieldHelper::TEXT_TYPE,'E-mail')->setRequired(),
            FormFieldHelper::to('current_password',FormFieldHelper::PASSWORD_TYPE,'Jelenlegi jelszó')->setRequired(),
            FormFieldHelper::to('password',FormFieldHelper::PASSWORD_TYPE,'Új jelszó')->setRequired(),
            FormFieldHelper::to('password_confirmation',FormFieldHelper::PASSWORD_TYPE,'Új jelszó ismételten')->setRequired(),
        ])->setTitle('Profil szerkesztése');
    }

    public function edit(){
        $employee = Auth::user();

        return $this->buildFormHelper($employee)->setActionFromNamedRoute('updateProfile')->render();
    }

    public function update(editProfile $request){

        $employee = Auth::user();
        $helper = $this->buildFormHelper($employee)->setRequest($request);
        if(!$helper->validateAndSave()){
            $this->alertError($helper->getErrors()->first());
        }
        else
        {
            $this->alertSuccess('Sikeres adatmódosítás.');
        }
        return $this->edit();
    }
}
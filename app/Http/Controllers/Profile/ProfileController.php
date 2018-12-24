<?php

namespace App\Http\Controllers\Profile;


use App\Http\Components\FormHelper\FormFieldHelper;
use App\Http\Components\FormHelper\FormHelper;
use App\Http\Controllers\Controller;
use App\Persistence\Models\Employee;
use App\Traits\FormHelperTrait;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    /**
     * Create a new ListHelper instance, and fill up.
     * @param Employee $model
     * @return FormHelper
     */
    protected function getFormHelper($model)
    {
        return FormHelper::to('myProfile',Employee::class,$model,[
            FormFieldHelper::to('name',FormFieldHelper::TEXT_TYPE,'Név'),
            FormFieldHelper::to('E-mail',FormFieldHelper::TEXT_TYPE,'E-mail'),
            FormFieldHelper::to('passoword',FormFieldHelper::PASSWORD_TYPE,'Jelszó'),
            FormFieldHelper::to('passoword_confirm',FormFieldHelper::PASSWORD_TYPE,'Jelszó ismétlét'),
        ]);
    }

    public function editProfile(){
        $employee = Auth::user();

        return $this->getFormHelper($employee)->render();
    }
}
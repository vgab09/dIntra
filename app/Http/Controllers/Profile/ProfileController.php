<?php

namespace App\Http\Controllers\Profile;



use App\Http\Components\FormHelper\FormHelper;
use App\Http\Components\FormHelper\FormInputFieldHelper as Input;
use App\Http\Controllers\Controller;
use App\Http\Requests\editProfile;
use App\Persistence\Models\Employee;
use App\Traits\AlertMessage;
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
        return FormHelper::to('myProfile',$model,[
            Input::toText('name','Név')->setRequired()->setIconClass('fas fa-user'),
            Input::toEmail('email','E-mail')->setRequired()->setIconClass('fas fa-at'),
            Input::toPassword('current_password','Jelenlegi jelszó')->setPlaceholder('Amivel bejelentkezel')->setRequired()->setIconClass('fas fa-key'),
            Input::toPassword('password','Új jelszó')->setRequired()->setDescription('Minimum 6 karakter, kis - nagy betű, szám és speciális karakter')->setIconClass('fas fa-asterisk'),
            Input::toPassword('password_confirmation','Új jelszó ismételten')->setRequired()->setDescription('A gépelési hibák elkerülése véget')->setIconClass('fas fa-asterisk'),
        ])->setTitle('Profil szerkesztése');
    }

    public function edit(){
        $employee = Auth::user();

        return $this->buildFormHelper($employee)->setActionFromNamedRoute('updateProfile')->render();
    }

    public function update(editProfile $request){

        $employee = Auth::user();
        $helper = $this->buildFormHelper($employee)->setRequest($request);
        if($helper->validateAndSave()){
            return $this->redirectSuccess(route('editProfile'),'Sikeres adatmódósítás');
        }

        return $this->redirectError(route('editProfile'),$helper->getErrors());

    }
}
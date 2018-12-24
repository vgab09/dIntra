<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.24.
 * Time: 9:27
 */

namespace App\Traits;


use App\Http\Components\FormHelper\FormHelper;
use Illuminate\Database\Eloquent\Model;

trait FormHelperTrait
{

    /**
     * Create a new ListHelper instance, and fill up.
     * @param Model $model
     * @return FormHelper
     */
    protected abstract function getFormHelper($model);

    /**
     * Show new Form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new()
    {
        $form = $this->getFormHelper();
        $form->setTitle('Hozzáadás');
        return $form->render();
    }

    /**
     * Show edit form
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->modelName::findOrFail($id);
        $form = $this->getFormHelper($model);
        $form->setTitle('Szerkesztés');
        return $form->render();
    }

    /**
     * Save resource
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function insert()
    {
        $form = $this->getFormHelper();
        if (!$form->validateAndSave()) {
            return $form->render();
        }
        return redirect()->action($this->getBaseUrl()['index'])->with('msg', ['text' => 'Successful inserting', 'type' => 'success']);
    }

    /**
     * Update resource
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function update($id)
    {
        $form = $this->getFormHelper($this->modelName::findOrFail($id));
        if (!$form->validateAndSave()) {
            return $form->render();
        }
        return redirect()->action($this->getBaseUrl()['index'])->with('msg', ['text' => 'Successful editing', 'type' => 'success']);
    }

    public function delete($id)
    {
        $this->modelName::findOrFail($id)->delete();
        return redirect()->action($this->getBaseUrl()['index'])->with('msg', ['text' => 'Successful deleting', 'type' => 'success']);
    }

    /**
     * Get the default URLs of the current controllers
     * @return array
     */
    protected function getBaseUrl()
    {

        $className = 'Admin\\' . class_basename(static::class);

        return [
            'index' => $className . '@index',
            'new' => $className . '@new',
            'edit' => $className . '@edit',
            'delete' => $className . '@delete',
        ];

    }
}

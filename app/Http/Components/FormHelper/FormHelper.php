<?php
/**
 * Created by PhpStorm.
 * User: Gab09
 * Date: 2018.08.09.
 * Time: 14:44
 */

namespace App\Http\Components\FormHelper;


use App\Http\Components\Presenter;
use App\Persistence\Models\ValidatableModelInterface;
use Collective\Html\FormBuilder;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\MessageBag;

class FormHelper
{

    /**
     * @var string Form name, resource name.
     */
    protected $name;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string This is the name of the fieldset
     */
    protected $title = '';

    /**
     * @var string|null The fieldset icon
     */
    protected $iconClass = '';

    /**
     * @var Model|null Used model
     */
    protected $model;

    /**
     * @var FormFieldHelper[] FormFieldHelper Form elements
     */
    protected $fieldList = [];

    /**
     * @var string Base Helper tpl folder
     */
    protected $baseFolder;

    /**
     * @var string tpl name
     */
    protected $baseTemplate;

    /**
     * @var FormFieldHelper This is the button that saves the whole fieldset
     */
    protected $submit;

    /**
     * @var array action URL
     */
    protected $action = [];

    /**
     * @var MessageBag Messages
     */
    protected $errors;


    private $request;

    /**
     * @var FormBuilder
     */
    private $formBuilderInstance;


        /**
     * FormHelper constructor.
     * @param string $name Form name
     * @param Model $model filled or empty model
     */
    public function __construct($name, $model = null)
    {

        $this->baseFolder = 'form';
        $this->baseTemplate = 'form';
        $this->errors = new MessageBag();
        $this->name = $name;
        $this->formBuilderInstance = App::make('form');
        $this->setModel($model);
    }

    /**
     * Create new FormHelper instance
     *
     * @param $name
     * @param Model|null $model
     * @param iterable $fields default []
     * @return FormHelper
     */
    public static function to($name, $model = null, iterable $fields = [])
    {
        $instance = new static($name, $model);
        $instance->addFields($fields);
        return $instance;
    }

    /**
     * Add new Entry to form.
     * @param FormFieldHelperInterface $field
     * @return FormHelper
     */
    public function addField(FormFieldHelperInterface $field): FormHelper
    {

        $this->fieldList[$field->getName()] = $field;
        return $this;
    }

    /**
     * Create a new Empty model
     */
    protected function createEmptyModel()
    {
        $this->setModel(App::make(get_class($this->model)));
    }

    /**
     * Get current request. Initialize un posted checkbox to false
     *
     * @return array request
     */
    protected function getRequest()
    {

        if (!empty($this->request)) {
            return $this->request;
        }

        $request = request()->only(array_keys($this->fieldList));

        /*
        foreach($this->fieldList as $key => $column){
            if($column->getType() == 'checkbox' && !isset($request[$key]) )          //  not posted unchecked checkboxes
                $request[ $key ] = 0;
        }
        */
        $this->request = $request;
        return $this->request;
    }

    /**
     * @param Request|FormRequest $request
     * @return $this
     */
    public function setRequest($request)
    {
        $this->request = $request->all();
        return $this;
    }

    /**
     * Validate the current request
     * return true if valid.
     * @return bool
     * @throws Exception
     */
    public function validate()
    {

        if (empty($this->model)) {
            throw new Exception('Formhelper\'s model is not set');
        }

        $this->model->fill($this->getRequest());

        if (!$this->model->validate()) {
            $this->errors->merge($this->model->getValidationErrorMessageBag()->toArray());

            foreach ($this->errors->toArray() as $key => $error) {
                if (array_key_exists($key, $this->fieldList)) {
                    $this->fieldList[$key]->setErrors(array_merge($this->fieldList[$key]->getErrors(), $error));
                }
            }

            return false;
        }

        return true;
    }

    /**
     * Validate and save the resource
     * return true if valid, and save is success.
     * @return bool
     * @throws Exception
     */
    public function validateAndSave()
    {

        if (!$this->validate()) {
            return false;
        }

        if (!$this->model->save()) {
            $this->errors->add('Unable save resource', 'Unable save resource.');
            return false;
        }

        return true;
    }

    /**
     * Get All Error
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * return form opening html tag
     * @return \Illuminate\Support\HtmlString|string
     */
    public function open()
    {
        return $this->formBuilderInstance->open($this->collectAttributes());
    }

    /** return form closing html tag
     * @return string
     */
    public function close()
    {
        return $this->formBuilderInstance->close();
    }

    public function getFormItems(): array
    {
        return $this->fieldList;
    }

    public function render()
    {
        return view($this->baseFolder . '.' . $this->baseTemplate, ['formHelper' => $this]);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Add a class to input
     * @param string $class
     * @return FormHelper
     */
    public function addClass(string $class): FormHelper
    {
        $this->class .= ' ' . $class;
        return $this;
    }

    /**
     * Set input class
     * @param string $class
     * @return FormHelper
     */
    public function setClass(string $class): FormHelper
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $elementId
     * @return FormHelper
     */
    public function setId(string $elementId): FormHelper
    {
        $this->id = $elementId;
        return $this;
    }

    /**
     * @return array
     */
    protected function collectAttributes(): array
    {

        $id = $this->getid();
        $class = $this->getClass();

        $attributes['class'] = empty($class) ? 'frm' . $this->getName() : $class;
        $attributes['id'] = empty($id) ? 'frm' . $this->getName() : $id;

        return array_merge($attributes, $this->getAction());
    }

    /**
     * @param string $title
     * @return FormHelper
     */
    public function setTitle(string $title): FormHelper
    {
        $this->title = $title;
        return $this;
    }


    /**
     * @param ValidatableModelInterface|null $model
     * @return FormHelper
     */
    public function setModel(?ValidatableModelInterface $model): FormHelper
    {
        $this->model = $model;
        $this->formBuilderInstance->setModel($model);
        return $this;
    }

    /**
     * @param string $baseFolder
     * @return FormHelper
     */
    public function setBaseFolder(string $baseFolder): FormHelper
    {
        $this->baseFolder = $baseFolder;
        return $this;
    }

    /**
     * @param string $baseTemplate
     * @return FormHelper
     */
    public function setBaseTemplate(string $baseTemplate): FormHelper
    {
        $this->baseTemplate = $baseTemplate;
        return $this;
    }

    /**
     * @param iterable $fields
     * @return $this
     */
    public function addFields(iterable $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set action URL from named route
     * @param string|array $action ['route.name',param1,param2,...]
     * @return FormHelper
     */
    public function setActionFromNamedRoute($action)
    {
        $this->action = ['route' => $action];
        return $this;
    }

    /**
     * Set action URL from named route
     * @param string|array $action ['foo/bar',param1,param2,...]
     * @return FormHelper
     */
    public function setActionFromUrl($action)
    {
        $this->action = ['url' => $action];
        return $this;
    }

    /**
     * Set action URL from named route
     * @param string|array $action ['Controller@method',param1,param2,...]
     * @return FormHelper
     */
    public function setActionFromMethod($action)
    {
        $this->action = ['action' => $action];
        return $this;
    }

    /**
     * @return FormFieldHelper
     */
    public function getSubmit(): FormFieldHelper
    {
        return $this->submit;
    }

    public function renderSubmit(){
        if($this->hasSubmit()){
            return $this->submit->render();
        }

        return FormButtonFieldHelper::toSubmit('Mentés')->setIconClass('far fa-save')->setClass('btn btn-primary')->render();
    }

    public function hasSubmit(): bool{
        return is_null($this->submit) ? false : true;
    }

    /**
     * @param FormFieldHelper $submit
     * @return FormHelper
     */
    public function setSubmit(FormFieldHelper $submit): FormHelper
    {
        $this->submit = $submit;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    /**
     * @param string $iconClass
     * @return FormHelper
     */
    public function setIconClass(string $iconClass): FormHelper
    {
        $this->iconClass = $iconClass;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasIcon(): bool{
        return is_null($this->iconClass) ? false : true;
    }






}
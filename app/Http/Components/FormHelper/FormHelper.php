<?php
/**
 * Created by PhpStorm.
 * User: Gab09
 * Date: 2018.08.09.
 * Time: 14:44
 */

namespace App\Http\Components\FormHelper;


use Collective\Html\FormBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\MessageBag;

class FormHelper
{

    /**
     * @var string Form name, resource name.
     */
    protected $name;

    /**
     * @var string This is the name of the fieldset
     */
    protected $title = '';

    /**
     * @var string The fieldset icon
     */
    protected $icon = '';

    /**
     * @var string Used model class name
     */
    protected $modelName;

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

    protected $baseUrls = [];

    /**
     * @var MessageBag Messages
     */
    protected $errors;

    protected $icons = [
        'date'      => 'calendar',
        'text'      => 'italic',
        'mail'      => 'envelope',
        'money'     => 'dollar',
        'number'    => 'tachometer',
    ];

    private $request;


    /**
     * FormHelper constructor.
     * @param $name string Form name
     * @param $modelName string the model name
     * @param BaseModel $model filled or empty model
     */
    public function __construct($name, $modelName,$model = null)
    {

        $this->baseFolder = 'form';
        $this->baseTemplate ='form';
        $this->errors = new MessageBag();
        $this->name = $name;
        $this->modelName = $modelName;
        $this->model = $model;
    }

    /**
     * Create new FormHelper instance
     *
     * @param $name
     * @param $modelClass
     * @param Model|null $model
     * @param iterable $fields default []
     * @return FormHelper
     */
    public static function to($name,$modelClass,$model = null,iterable $fields = []){
        $instance = new static($name,$modelClass,$model);
        $instance->addFields($fields);
        return $instance;
    }

    /**
     * Add new Entry to form.
     * @param FormFieldHelper $field
     * @return FormHelper
     */
    public function addField(FormFieldHelper $field) : FormHelper{

        $this->fieldList[$field->getName()] = $field;
        return $this;
    }

    /**
     * Create a new Empty model
     */
    protected function createEmptyModel(){
        $this->model = App::make($this->modelName);
    }

    /**
     * Get current request. Initialize un posted checkbox to false
     *
     * @return array request
     */
    protected function getRequest(){

        if(!empty($this->request)){
            return $this->request;
        }

        $request = request()->all();
        foreach($this->fieldList as $key => $column){
            if($column->getType() == 'checkbox' && !isset($request[$key]) )          //  not posted unchecked checkboxes
                $request[ $key ] = 0;
        }
        $this->request = $request;
        return $this->request;
    }

    /**
     * Validate the current request
     * return true if valid.     *
     * @return bool
     */
    public function validate(){

        if(empty($this->model)){
            $this->createEmptyModel();
        }
        $request = $this->getRequest();

        if(!$this->model->validate($request)){
            $this->errors->merge($this->model->getValidationErrorMessageBag()->toArray());

            foreach ($this->errors->toArray() as $key => $error){
                if(array_key_exists($key,$this->fieldList)){
                    $this->fieldList[$key]->setErrors(array_merge($this->fieldList[$key]->getErrors(),$error));
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
     */
    public function validateAndSave(){

        if(!$this->validate()){
            return false;
        }

        $request = $this->getRequest();

        if(empty($this->model)){
            $this->createEmptyModel();
        }

        if(!$this->model->fill($request)->save()){
            $this->errors->add('Unable save resource','Unable save resource.');
            return false;
        }

        return true;
    }

    /**
     * Get All Error
     * @return MessageBag
     */
    public function getErrors(){
        return $this->errors;
    }

    public function getFormItems() :array{
        return $this->fieldList;
    }

    public function render(){
        return view($this->baseFolder . '.' . $this->baseTemplate,['formHelper' => $this]);
    }

    /**
     * Get the base url
     * @return array
     */
    public function getBaseUrls(): array
    {
        return $this->baseUrls;
    }

    /**
     * Set the base url
     * @param array $baseUrls
     */
    public function setBaseUrls(array $baseUrls)
    {
        $this->baseUrls = $baseUrls;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getModelName(): string
    {
        return $this->modelName;
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
     * @param string $title
     * @return FormHelper
     */
    public function setTitle(string $title): FormHelper
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $modelName
     * @return FormHelper
     */
    public function setModelName(string $modelName): FormHelper
    {
        $this->modelName = $modelName;
        return $this;
    }

    /**
     * @param Model|null $model
     * @return FormHelper
     */
    public function setModel(?Model $model): FormHelper
    {
        $this->model = $model;
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
        foreach ($fields as $field){
            $this->addField($field);
        }
        return $this;
    }


}
<?php

namespace App\Http\Components\FormHelper;


class FormCustomViewFieldHelper implements FormFieldHelperInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string Blade template
     */
    protected $view;

    /**
     * @var array data passed to blade template
     */
    protected $data = [];

    /**
     * @var array Validation errors they can be touchable in the view at 'FormCustomViewFieldHelper' variable.
     */
    protected $error = [];

    public function __construct($name,$view,$data=[])
    {
        $this->setName($name);
        $this->setView($view);
        $this->setData($data);
    }

    public static function to($name,$view,$data=[]){
        return new static($name,$view,$data);
    }

    public function render()
    {
        return $this->renderTag();
    }

    public function renderTag()
    {
        $this->addData('FormCustomViewFieldHelper',$this);
        return view($this->getView(),$this->getData())->render();
    }

    /**
     * @param array $errors
     * @return FormCustomViewFieldHelper
     */
    public function setErrors($errors)
    {
        $this->error = $errors;
        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->getView();
    }

    public function setValue($value)
    {
        return $this->setView();
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     * @return FormCustomViewFieldHelper
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return FormCustomViewFieldHelper
     */
    public function setData(array $data): FormCustomViewFieldHelper
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $key
     * @param $data
     * @return FormCustomViewFieldHelper
     */
    public function addData(string $key,$data): FormCustomViewFieldHelper{
        $this->data[$key] = $data;
        return $this;
    }






}
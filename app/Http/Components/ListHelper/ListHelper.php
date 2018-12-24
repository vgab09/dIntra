<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.08.
 * Time: 17:50
 */

namespace App\Http\Components\ListHelper;


use phpDocumentor\Reflection\Types\Iterable_;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;

class ListHelper
{
    /**
     * @var string List unique name
     */
    public $listName;

    /**
     * @var string Used model class name
     */
    public $modelClass;

    /**
     * @var string display title
     */
    public $title;

    /**
     * @var array ListFieldHelper
     */
    public $fieldList;

    /**
     * @var string Base Helper tpl folder
     */
    public $baseFolder;

    /**
     * @var string tpl name
     */
    public $baseTemplate;

    /**
     * @var array Bulk actions
     */
    public $bulkActions = [];

    /**
     * @var array list of required actions for each list row
     */
    public $rowActions = [];

    /** @var array Number of results in list per page (used in select field) */
    public $availablePagination;

    /**
     * @var EloquentDataTable Datatables instance
     */
    protected $dataTablesInstance;

    /**
     * @var string base URL
     */
    public $baseUrl;

    /**
     * @var string Get data URL
     */
    public $dataUrl;


    /**
     * ListHelper constructor.
     * @param $name
     * @param $modelClass
     */
    public function __construct($name, $modelClass)
    {
        $this->baseFolder = 'bread';
        $this->baseTemplate = 'browse';
        $this->listName = $name;
        $this->modelClass = $modelClass;
        $this->baseUrl = url()->current();
        $this->dataUrl = $this->baseUrl . '/data/';
    }

    /**
     * Create new ListHelper instance
     *
     * @param $name
     * @param $modelClass
     * @param iterable $fields default []
     * @return ListHelper
     */
    public static function to($name,$modelClass,iterable $fields = []){
        $instance = new static($name,$modelClass);
        $instance->addFields($fields);
        return $instance;
    }

    public function render()
    {
        $view = $this->baseFolder . '.' . $this->baseTemplate;
        return view($view, $this);

    }

    /**
     * @param $source
     * @return EloquentDataTable
     * @throws \Exception
     */
    public function createDataTables($source)
    {

        $this->dataTablesInstance = Datatables::of($source);
        $this->addRowActionButtons();
        $this->addRowHelpers();

        return $this->dataTablesInstance;

    }

    protected function addRowActionButtons()
    {
        $this->dataTablesInstance->addColumn('action', function ($model) {
            return view($this->baseFolder . '.partials.button')->with(['actions' => $this->getRowActions(), 'rowId' => $model->getKey()]);
        });
    }

    protected function addRowHelpers()
    {

        /**
         * @var ListFieldHelper $field
         */
        foreach ($this->fieldList as $field) {
            $fieldName = $field->getName();
            switch ($field->getType()) {
                case 'bool':
                    $this->dataTablesInstance->editColumn($fieldName, function ($model) use ($fieldName) {
                        return empty($model->$fieldName) ? 'nem' : 'igen';
                    });
                    break;
                case 'date':
                    break;
                case 'datetime':
                    break;
                case 'decimal':
                    $this->dataTablesInstance->editColumn($fieldName, function ($model) use ($fieldName) {
                        return sprintf('%.2f', $model->$fieldName);
                    });
                    break;
                case 'float':
                    break;
                case 'percent':
                    break;
                case 'price':
                    $this->dataTablesInstance->editColumn($fieldName, function ($model) use ($fieldName) {
                        return displayPrice($model->$fieldName);
                    });
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Get table list item, check fields
     */
    public function getListItems()
    {
        return $this->fieldList;
    }

    /**
     * Add new Entry to list.
     * @param ListFieldHelper $field
     * @return ListHelper
     */
    public function addField(ListFieldHelper $field): ListHelper
    {
        $this->fieldList[$field->getName()] = $field;
        return $this;
    }


    public function addFields(iterable $fields)
    {
        foreach ($fields as $field){
            $this->addField($field);
        }
        return $this;
    }

    public function addTimeStamps(){
        $this->addField(ListFieldHelper::to('created_at','Létrehozva')->setType('datetime'));
        $this->addField(ListFieldHelper::to('updated_at','Módosítva')->setType('datetime'));

        return $this;
    }

    public function addRowAction($url, $text): listHelper
    {
        //Jogosultság ellenőrzés.
        $this->rowActions[] = [
            'url' => $url,
            'text' => $text,
        ];

        return $this;
    }

    public function getRowActions()
    {
        return $this->rowActions;
    }

}
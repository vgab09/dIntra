<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.08.
 * Time: 17:50
 */

namespace App\Http\Components\ListHelper;


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
    public $modelName;

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
    public $bulkActions;

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
     * @var array Table columns visibility settings 0 - hidden ; 1 - shown
     */
    private $TableColumnsVisibility;


    public function __construct($name, $modelName)
    {
        $this->baseFolder = 'vendor/adminlte';
        $this->baseTemplate = 'list';
        $this->listName = $name;
        $this->modelName = $modelName;
        $this->baseUrl = url()->current();
        $this->dataUrl = $this->baseUrl . '/data/';
        $this->setAvailablePagination();

        //  key example: name=TableColumnsVisibility.category.created_at value='0'/'1'
        $this->TableColumnsVisibility = [];
    }

    public function render()
    {
        $P['P'] = [
            'Columns' => $this->getListItems(),
            'listName' => $this->listName,
        ];
        $this->setInitialSearch();

        $view = $this->baseFolder . '.' . $this->baseTemplate;
        return view($view, $P);

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

    public function processFilters()
    {
        $columns = request('columns', []);
        $prefix = 'ActiveSearch-' . $this->listName . '-';

        foreach ($columns as $column) {

            if (!empty($column['name']) && array_key_exists($column['name'], $this->fieldList)) {

                Cookie::forget($prefix . $column['name']);

                if ($column['search']['value'] !== null) {
                    Cookie::queue($prefix . $column['name'], $column['search']['value'], 1440);
                }
            }
        }
    }

    protected function setInitialSearch()
    {

        $prefix = 'ActiveSearch-' . $this->listName . '-';

        /**
         * @var ListFieldHelper $field
         */
        foreach ($this->fieldList as &$field) {

            if (empty($field->searchable)) {
                continue;
            }
            $field->searchValue = strip_tags(Cookie::get($prefix . $field->getName(), ''));

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

        $fieldName = $field->getName();

        if (in_array($fieldName, array_keys($this->TableColumnsVisibility))) {
            if ($this->TableColumnsVisibility[$fieldName] == '1') {
                $field->show = true;
            } else {
                $field->show = false;
            }

        }
        $this->fieldList[$fieldName] = $field;
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

    /**
     * Set availablePagination, if not pass array, get from configuration
     * @param array $availablePagination
     * @return ListHelper
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function setAvailablePagination(array $availablePagination = []): ListHelper
    {
        if (empty($availablePagination)) {
            $availablePagination = ['10,25,50'];
        }
        $this->availablePagination = $availablePagination;

        return $this;
    }
}
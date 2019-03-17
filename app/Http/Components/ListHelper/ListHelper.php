<?php
/**
 * Created by PhpStorm.
 * User: g09
 * Date: 2018.12.08.
 * Time: 17:50
 */

namespace App\Http\Components\ListHelper;

use App\Http\Components\FormHelper\FormCheckboxFieldHelper;
use App\Http\Components\ToolbarLink\ToolbarLinks;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Facades\DataTables;

class ListHelper
{
    protected const ROW_ACTION_COLUMN = 'row_actions';

    /**
     * @var string List unique name
     */
    protected $listName;

    /**
     * @var string display title
     */
    protected $title;

    /**
     * @var Collection ListFieldHelper
     */
    protected $fieldList;

    /**
     * @var string tpl name
     */
    protected $baseTemplate;

    /**
     * @var callable|null row action
     */
    protected $rowActions = null;

    /**
     * @var EloquentDataTable Datatables instance
     */
    protected $dataTablesInstance;

    /**
     * @var string Get data URL
     */
    protected $ajaxUrl;

    /**
     * @var array dataTables javascript properties
     */
    protected $properties = [];

    /**
     * @var bool table has checkbox column
     */
    protected $hasCheckboxColumn = false;

    /**
     * @var array dataTables sanitize every row except there
     */
    protected $rawColumns = [];

    /**
     * @var string|null
     */
    protected $iconClass;

    protected $toolbarLinkInstance;


    /**
     * ListHelper constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->baseTemplate = 'table.table';
        $this->setListName($name);
        $this->setTitle($name);
        $this->fieldList = new Collection;
        $this->toolbarLinkInstance = new ToolbarLinks();
    }

    /**
     * Create new ListHelper instance
     *
     * @param $name
     * @param iterable $fields default []
     * @return ListHelper
     */
    public static function to($name,iterable $fields = [])
    {
        $instance = new static($name);
        $instance->addFields($fields);
        return $instance;
    }

    public function addFields(iterable $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
        return $this;
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

    public function render()
    {
        $this->addActionColumn();
        return view($this->baseTemplate, ['listHelper' => $this]);
    }

    /**
     * @param string $name
     * @param string $title
     */
    public function addCheckboxes($name = 'rowSelector', $title = '')
    {

        if (!$this->hasCheckboxColumn) {
            $this->fieldList->prepend(ListFieldHelper::to($name, $title), $name);
            $this->hasCheckboxColumn = true;
            $this->addRawColumn($name);
        }
    }

    public function addTimeStamps()
    {
        $this->addField(ListFieldHelper::to('created_at', 'Létrehozva')->setType('datetime'));
        $this->addField(ListFieldHelper::to('updated_at', 'Módosítva')->setType('datetime'));

        return $this;
    }

    protected function addActionColumn($title = '')
    {
        $title = empty($title) ? 'Műveletek' : $title;

        $hasSearchableColumn = !$this->getListItems()->every(function ($value, $key) {
            return !$value->isSearchable();
        });

        if ($hasSearchableColumn === true || $this->rowActions !== null) {

            $actionField = ListFieldHelper::to(static::ROW_ACTION_COLUMN, $title)
                ->setSearchable(false)
                ->setOrderable(false)
                ->setDefaultContent('');

            if ($hasSearchableColumn === true) {
                $searchButton = '<button name ="search" class="btn btn-primary btn-sm d-inline ml-1 btn-filter"><i class="fas fa-search">Keresés</i></button>';
                $resetButton = '<button name="resetSearch" class="btn btn-warning btn-sm d-none ml-1 btn-reset-filter"><i class="fas fa-eraser">Alaphelyzetbe állít</i></button>';

                $actionField->setSearchElement($searchButton . $resetButton);
            }

            $this->fieldList->put(static::ROW_ACTION_COLUMN, $actionField);
            $this->addRawColumn(static::ROW_ACTION_COLUMN);
        }
    }

    /**
     * return array DataTables parameters
     */
    public function getDataTableParameters()
    {

        $params['serverSide'] = true;
        $params['processing'] = true;
        $params['ajax'] = $this->getAjaxUrl();
        $params['language'] = $this->getDataTableLocalization();
        $params['dom'] = 'rt<"container mt-3"<"row"<"col p-0"l><"col p-0"p><"col p-0"i>>>';
        $params['orderCellsTop'] = true;
        $params['autoWidth'] = true;
        $params['stateSave'] = true;

        foreach ($this->getListItems() as $item) {
            $params['columns'][] = $item->toArray();
        }
        return array_merge($params, $this->getProperties());
    }

    /**
     * return array DataTables localization
     */
    public function getDataTableLocalization()
    {
        return [
            "decimal" => "",
            "emptyTable" => "Nincs rendelkezésre álló adat",
            "info" => "Találatok: _START_ - _END_ Összesen: _TOTAL_",
            "infoEmpty" => "Nincs találat",
            "infoFiltered" => "(_MAX_ rekord közül szűrve)",
            "infoPostFix" => "",
            "thousands" => " ",
            "lengthMenu" => "_MENU_ találat oldalanként",
            "loadingRecords" => "Betöltés...",
            "processing" => "Feldolgozás...",
            "search" => "Keresés:",
            "zeroRecords" => "Nincs a keresésnek megfelelő találat",
            "paginate" => [
                "first" => "Első",
                "last" => "Utolsó",
                "next" => "Következő",
                "previous" => "Előző"
            ],
            "aria" => [
                "sortAscending" => ": Növelvő rendezés",
                "sortDescending" => ": Csökkenő rendezés"
            ]
        ];
    }

    /**
     * Get table list item, check fields
     * @return ListFieldHelper[]|Collection
     */
    public function getListItems()
    {
        return $this->fieldList;
    }

    /**
     * @param $source
     * @return EloquentDataTable
     * @throws \Exception
     */
    public function createDataTables($source)
    {
        $this->dataTablesInstance = Datatables::of($source);

        /**
         * @var ListFieldHelper $field
         */
        foreach ($this->fieldList as $field) {
            $this->applyDisplayModifier($field);
            $this->applyLengthModifier($field);
        }

        if ($this->hasCheckboxColumn) {
            $listName = $this->getListName();
            $this->dataTablesInstance->addColumn($this->fieldList->get('rowSelector')->getName(), function ($model) use ($listName) {
                return FormCheckboxFieldHelper::toCheckbox($listName . 'rowSelector[]', '', $model->getKey())->render();
            });
        }

        if(is_callable($this->rowActions)){
            $this->dataTablesInstance->editColumn(static::ROW_ACTION_COLUMN, $this->rowActions);
            $this->addRawColumn(static::ROW_ACTION_COLUMN);
        }

        $this->dataTablesInstance->rawColumns($this->rawColumns);
        return $this->dataTablesInstance;
    }

    /**
     * Format values
     * @param ListFieldHelper $field
     */
    protected function applyDisplayModifier(ListFieldHelper $field)
    {

        $fieldName = $field->getName();
        switch ($field->getType()) {
            case 'bool':
                $this->dataTablesInstance->editColumn($fieldName, function ($model) use ($fieldName) {
                    return empty($model->$fieldName) ? '<i class="fas fa-times"></i>' : '<i class="fas fa-check"></i>';
                });
                $this->addRawColumn($fieldName);
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
                break;
            default:
                    if($field->hasSuffix()){
                        $this->dataTablesInstance->editColumn($fieldName, function ($model) use ($fieldName,$field) {
                            return $model->$fieldName . $field->getSuffix();
                        });
                    }
                break;
        }


    }

    /**
     * Trim values
     * @param ListFieldHelper $field
     */
    protected function applyLengthModifier(ListFieldHelper $field)
    {
        $maxLength = $field->getMaxLength();
        $fieldName = $field->getName();
        if ($maxLength > 0) {
            $this->dataTablesInstance->editColumn($fieldName, function ($model) use ($fieldName, $maxLength) {
                return Str::limit($model->$fieldName, $maxLength);
            });
        }
    }

    /**
     * The DataTables default remove html from cells. Use this method to add a column to exceptions.
     * @param string $fieldName
     */
    protected function addRawColumn($fieldName)
    {
        $this->rawColumns[] = $fieldName;
    }

    /**
     * @param callable $callback the current item passed in callback parameter
     * @return ListHelper
     */
    public function addRowActions(callable $callback): listHelper
    {
        $this->rowActions = $callback;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return ListHelper
     */
    public function setTitle(string $title): ListHelper
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getListName(): string
    {
        return $this->listName;
    }

    /**
     * @param string $listName
     * @return ListHelper
     */
    public function setListName(string $listName): ListHelper
    {
        $this->listName = $listName;
        return $this;
    }

    public function getRowActions()
    {
        return $this->rowActions;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     * @return ListHelper
     */
    public function setProperties(array $properties): ListHelper
    {
        $this->properties = array_merge($this->properties, $properties);
        return $this;
    }

    /**
     * @return string
     */
    public function getAjaxUrl(): string
    {
        return empty($this->ajaxUrl) ? url()->current() : $this->ajaxUrl;
    }

    /**
     * @param string $ajaxUrl
     * @return ListHelper
     */
    public function setAjaxUrl(string $ajaxUrl): ListHelper
    {
        $this->ajaxUrl = $ajaxUrl;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    public function hasIcon(): bool{
        return is_null($this->iconClass) ? false : true;
    }

    /**
     * @param null|string $iconClass
     * @return ListHelper
     */
    public function setIconClass(?string $iconClass): ListHelper
    {
        $this->iconClass = $iconClass;
        return $this;
    }


    /**
     * @return ToolbarLinks
     */
    public function getToolbarLinkInstance(): ToolbarLinks
    {
        return $this->toolbarLinkInstance;
    }

    /**
     * @param ToolbarLinks $toolbarLinkInstance
     */
    public function setToolbarLinkInstance(ToolbarLinks $toolbarLinkInstance): ListHelper
    {
        $this->toolbarLinkInstance = $toolbarLinkInstance;
        return $this;
    }

    public function renderToolbarLinks(){
        return $this->toolbarLinkInstance->render();
    }




}
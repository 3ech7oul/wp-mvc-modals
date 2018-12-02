<?php
namespace FormsPlugin\Admin\Widgets;
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

use FormsPlugin\Services\DataBaseService;

class AdminTableWidget extends \WP_List_Table
{
    /**
     * Значение для таба по умолчанию
     *
     * @var string
     */
    public static $tabsVariableAll = 'all';

    private $columns;

    private $editAction;

    public $deleteAction;

    private $actionVariable;

    protected $wpnonceDeleteName;

    protected $wpnonceDeleteAction;

    private $tabsColumns = [];

    private $tabsVariable;

    private $hideTabShowAll = false;

    /**
     * Дополнительные параметры для кнопки All в табах
     *
     * @var array
     */
    private $additionalsParamTabShowAll = [];

    private $nameTabShowAll;

    private $itemActions = [];

    /**
     * Объект с переопределёнными действиями в таблице (например: удалить, редактировать).
     *
     * @var ItemActionsBuilder
     */
    private $widgetItemActions;

    /**
     * Количесов элементов на странице.
     *
     * @var int
     */
    private $perPageItems = 15;

    private $dataBaseService;

    public function __construct($data, $columns, $actionVariable, $editAction, $deleteAction, $wpnonceDeleteName, $wpnonceDeleteAction)
    {
        $this->dataBaseService = new DataBaseService();
        global $status, $page;

        parent::__construct([
            'singular'  => 'biller',
            'plural'    => 'billers',
            'ajax'      => false
        ]);

        $this->items = $data;
        $this->columns = $columns;
        $this->actionVariable = $actionVariable;
        $this->editAction = $editAction;
        $this->wpnonceDeleteName = $wpnonceDeleteName;
        $this->wpnonceDeleteAction = $wpnonceDeleteAction;
        $this->deleteAction = $deleteAction;
        $this->convertItems();
    }

    /**
     * Устанавливает, перезаписывая существующие, объект эккшенов для таблицы виджета.
     *
     * @param ItemActionsBuilder $widgetItemActions
     */
    public function setWidgetItemActions($widgetItemActions)
    {
        $this->widgetItemActions = $widgetItemActions;
    }

    /**
     * @return mixed
     */
    public function getWidgetItemActions()
    {
        return $this->widgetItemActions;
    }

    /**
     * Возвращает массив с действиями для виджета таблицы.
     *
     * @param $itemId
     * @return mixed
     */
    public function getItemActions($itemId)
    {
        if (empty($this->widgetItemActions)) {
            $itemActions = $this->defaultItemActions();
        } else {
            $itemActions = $this->widgetItemActions;
        }

        return $itemActions->getActions($itemId);
    }

    /**
     * Возвращает объект по-умолчанию, с действиями для виджета таблицы.
     *
     * @return ItemActionsBuilder
     */
    private function defaultItemActions()
    {
        $itemActionEdit = new ItemAction($this->actionVariable, $this->editAction, 'Edit', $_REQUEST['page']);

        $itemActionDelete = new ItemAction($this->actionVariable, $this->deleteAction, 'Delete', $_REQUEST['page']);
        $itemActionDelete->setWpnonce([$this->wpnonceDeleteName => $this->wpnonceDeleteAction]);
        $itemActionDelete->setOnclick('Are you sure you want to delete this entry?');

        $widgetItemActions = new ItemActionsBuilder();
        $widgetItemActions->addActions($itemActionEdit);
        $widgetItemActions->addActions($itemActionDelete);
        return $widgetItemActions;
    }

    public function column_default($item, $column_name)
    {
        return (isset($item[$column_name]))?$item[$column_name]:null;
    }

    public function column_id($item)
    {
        $actions = $this->getItemActions($item['id']);

        return sprintf('%1$s <span style="color:silver"></span>%2$s',
            /*$1%s*/ $item['id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            $this->_args['plural'], // ("members")
            $item['id']//The value of the checkbox should be the record's id
        );
    }

    public function get_columns()
    {
        //$cdColumn = ['cb' => '<input type="checkbox" />'];
        //$columns = array_merge($cdColumn, $this->columns);
        return $this->columns;
    }

    public function get_sortable_columns()
    {
        $sortable_columns = [
            //	'sort' => array('sort', false),
            //	'name' => array('name', false),
        ];
        return $sortable_columns;
    }

    public function prepareItems()
    {
        $this->process_bulk_action();

        $per_page = $this->perPageItems;

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $this->process_bulk_action();
        @usort($this->items, [$this, 'usortReorder']);
        $current_page = $this->get_pagenum();

        $total_items = count($this->items);

        if (is_array($this->items)) {
            $this->items = array_slice($this->items,(($current_page-1)*$per_page),$per_page);
        }

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }

    function usortReorder($a,$b){
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'sort'; //If no sort, default to date_registration
        $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc

        $result = strnatcmp($a[$orderby], $b[$orderby]); //Determine sort order

        return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
    }

    public function convertItems()
    {
        $items  = $this->items;
        $result = [];

        if (is_array($items)){
            foreach ($items as $value) {
                $result[] =  $this->dataBaseService->convertModelToArray($value);
            }

            $this->items = $result;
        }
    }

    /**
     * Рендер табов
     */
    public function tabs()
    {
        $activeSet = false;
        if (empty($this->tabsColumns)) {
            return;
        }

        if (empty($this->tabsVariable)){
            return;
        }

        $tab = $_GET[$this->tabsVariable];
        $html = '';
        $activeAll = '';

        if ((empty($tab) || ($tab == self::$tabsVariableAll)) && ( $this->hideTabShowAll == false)){
            $activeAll = 'nav-tab-active';
            $activeSet = true;
        }

        $urlParameters =  [
            'page' => $_REQUEST['page'],
            $this->actionVariable => $_REQUEST[$this->actionVariable]
        ];

        if ($this->hideTabShowAll == false) {
            $urlParameters[$this->tabsVariable] = self::$tabsVariableAll;

            if (!empty($this->additionalsParamTabShowAll)) {
                $urlParameters = array_merge($urlParameters, $this->additionalsParamTabShowAll);
            }

            $urlPart = http_build_query($urlParameters);

            $allLabel = (isset($this->nameTabShowAll)) ? $this->nameTabShowAll : 'All';
            $html = '<a class="nav-tab '.$activeAll.'" href="?'.$urlPart.'">'.$allLabel.'</a>';
        }

        $tabCount = 0;


        foreach ($this->tabsColumns as $id => $item) {
            $tabCount++;
            $active = '';
            if ($tab == $item['id']) {
                $activeSet = true;
                $active = 'nav-tab-active';
            }

            if (($tab == null) && ($tabCount == 1) && ($activeSet == false)) {
                $active = 'nav-tab-active';
            }

            $html .= '<a class="nav-tab '.$active.'" href="?'.$item['url'].'">'.$item['name'].'</a>';
        }

        $result = '<h2 class="nav-tab-wrapper">'.$html.'</h2>';

        echo $result;
    }

    /**
     * @param array $tabsColumns
     */
    public function setTabsColumns(array $tabsColumns)
    {
        $this->tabsColumns = $tabsColumns;
    }

    /**
     * @param mixed $tabsVariable
     */
    public function setTabsVariable($tabsVariable)
    {
        $this->tabsVariable = $tabsVariable;
    }

    /**
     * @param bool $hideTabShowAll
     */
    public function setHideTabShowAll($hideTabShowAll)
    {
        $this->hideTabShowAll = $hideTabShowAll;
    }

    /**
     * @param array $itemActions
     */
    public function setItemActions($itemActions)
    {
        $this->itemActions = $itemActions;
    }

    /**
     * Устанавливает дополнительные параметры для кнопки All в табах
     *
     * @param array $additionalsParamTabShowAll
     */
    public function setAdditionalsParamTabShowAll(array $additionalsParamTabShowAll)
    {
        $this->additionalsParamTabShowAll = $additionalsParamTabShowAll;
    }

    /**
     * @param mixed $nameTabShowAll
     */
    public function setNameTabShowAll($nameTabShowAll)
    {
        $this->nameTabShowAll = $nameTabShowAll;
    }

    /**
     * @param int $perPageItems
     */
    public function setPerPageItems($perPageItems)
    {
        $this->perPageItems = $perPageItems;
    }

    /**
     * @param string $tabsVariableAll
     */
    public static function setTabsVariableAll($tabsVariableAll)
    {
        self::$tabsVariableAll = $tabsVariableAll;
    }

}
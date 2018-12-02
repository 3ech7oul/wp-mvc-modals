<?php


namespace FormsPlugin\Admin\Widgets;


class ItemActionsBuilder
{
    /**
     * Массив объектов VrbmAdminTableWidgetItemAction
     *
     * @var array
     */
    private $actions = [];

    /**
     * Добавляет ItemAction в свойство класса.
     *
     * @param ItemAction $actions
     */
    public function addActions(ItemAction $actions)
    {
        array_push($this->actions, $actions);
    }

    /**
     * Возращает массив готовых ссылок с экшенами для виджета таблицы.
     *
     * @param $itemId
     * @return array
     */
    public function getActions($itemId)
    {
        foreach ($this->actions as $action) {
            $actionKey = $action->getActionName();
            $result[mb_strtolower($actionKey)] =  $action->getItemActionUrl($itemId);
        }

        return $result;
    }
}
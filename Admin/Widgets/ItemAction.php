<?php


namespace FormsPlugin\Admin\Widgets;


class ItemAction
{
    private $actionVariable;

    private $actionValue;

    private $actionName;

    private $actionPage;

    private $actionPageVariable = 'page';

    private $actionItemIdVariable = 'id';

    private $onclick;

    private $wpnonce = [];

    private $additionalParameters = [];

    public function __construct( $actionVariable, $actionValue, $actionName, $actionPage)
    {
        $this->actionValue = $actionValue;
        $this->actionVariable = $actionVariable;
        $this->actionName = $actionName;
        $this->actionPage = $actionPage;
    }

    /**
     * @param array $wpnonce
     */
    public function setWpnonce(array $wpnonce)
    {
        $this->wpnonce = $wpnonce;
    }

    /**
     * @param string $actionPageVariable
     */
    public function setActionPageVariable($actionPageVariable)
    {
        $this->actionPageVariable = $actionPageVariable;
    }

    /**
     * @param mixed $onclick
     */
    public function setOnclick($onclick)
    {
        $this->onclick = $onclick;
    }

    /**
     * @param array $additionalParameters
     */
    public function setAdditionalParameters($additionalParameters)
    {
        $this->additionalParameters = $additionalParameters;
    }

    /**
     * @param string $actionItemIdVariable
     */
    public function setActionItemIdVariable($actionItemIdVariable)
    {
        $this->actionItemIdVariable = $actionItemIdVariable;
    }

    /**
     * Готовая ссылка с экшеном для виджета таблицы.
     *
     * @param $itemId
     * @return string
     */
    public function getItemActionUrl($itemId)
    {
        $onclick = '';
        $actiontUrl = $this->buildUrl($itemId);

        if (strlen($this->onclick)>0) {
            $onclick = 'onclick="return confirm(\''.$this->onclick.'\')"';
        }

        return  '<a href="'.$actiontUrl.'" '.$onclick.'>'.$this->actionName.'</a>';
    }

    /**
     * Конструктор url действия.
     *
     * @param $itemId
     * @return string
     */
    private function buildUrl($itemId)
    {
        $urlParam = [
            $this->actionPageVariable => $this->actionPage,
            $this->actionVariable => $this->actionValue,
            $this->actionItemIdVariable => $itemId
        ];

        if (!empty($this->wpnonce)) {
            $urlParam = array_merge($urlParam, $this->wpnonce);
        }

        if (!empty($this->additionalParameters)) {
            $urlParam = array_merge($urlParam, $this->additionalParameters);
        }

        return add_query_arg($urlParam);
    }

    /**
     * @return mixed
     */
    public function getActionName()
    {
        return $this->actionName;
    }
}
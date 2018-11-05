<?php

namespace FormsPlugin\Admin\Controller;

class BaseController
{

    /**
     * Параметр url на которой весят все экшены
     *
     * @var
     */
    protected $actionVariable;

    /**
     * @param $actionVariable
     */
    public function setActionVariable($actionVariable)
    {
        $this->actionVariable = $actionVariable;
    }

    /**
     * Инициализирует контролер
     */
    public function initController()
    {
        global $_parent_pages;

        $action = filter_input(INPUT_GET, $this->actionVariable);
        $_parent_pages[$action] = $action;
        $action = empty($action) ? filter_input(INPUT_POST, 'action') : $action;

        if ($action == null) {
          return $this->actionIndex();
        }

        $actionMethod = 'action'.$this->dashesToCamelCase($action, true);

        $this->$actionMethod();
    }

    /**
     * Конвертирует строку вида add-region-price в камелкейс
     *
     * @param $string
     * @param bool $capitalizeFirstCharacter
     *
     * @return mixed
     */
    function dashesToCamelCase($string, $capitalizeFirstCharacter = false)
    {
        $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }
        return $str;
    }

    /**
     * @param $actionName
     *
     * @return string
     */
    protected function getWpNonceAction($actionName)
    {
        $actionName = (string)$actionName;
        return 'create_'.$actionName;
    }

    /**
     * @param $actionName
     *
     * @return string
     */
    protected function getWpNonceName($actionName)
    {
        $actionName = (string)$actionName;
        return '_wpnonce_'.$actionName;
    }

    /**
     * Показывает уведомление в админке.
     *
     * @param $message
     *
     * @return bool
     */
    public function notices($message) {

        $succeeded = false;
        if (empty($message)) {
            return false;
        }
        if ($message['succeeded']) {
            echo "<div id='message' class='updated'>";
            $succeeded = true;
        } else {
            echo "<div id='message' class='error'>";
        }
        echo $message['message'];
        $extra = isset($message['extra']) ? $message['extra'] : array();
        if (is_string($extra)) {
            echo $extra;
        } else if (is_array($extra)) {
            echo '<ul>';
            foreach ($extra as $key => $value) {
                echo '<li>' . $value . '</li>';
            }
            echo '</ul>';
        }
        echo "</div>";

        return $succeeded;
    }

    /**
     * @param $attributes
     * @param $value
     * @param bool $required
     *
     * @return string
     */
    public function showInputTextField($attributes, $value, $required = false)
    {
        $requiredClass = '';
        $requiredAttribute = '';
        $stepAttribute = '';
        $description = '';

        if (empty($attributes['class'])){
            $attributes['class'] = 'description';
        }

        if (empty($attributes['autocomplete'])){
            $attributes['autocomplete'] = 'off';
        }

        if (empty($attributes['type'])){
            $attributes['type'] = 'text';
        }

        if (isset($attributes['step'])){
            $stepAttribute = 'step="'. $attributes['step'].'"';
        }

        if ($required == true) {
            $requiredClass = 'validate[required]';
            $requiredAttribute = 'required';
        }

        if (isset($attributes['description'])) {
            $description = '<br/><i></i><br/><i>'.$attributes['description'].'</i>';
        }

        if (is_object($value)) {
            return null;
        }

        if (empty($attributes['attributes'])){
            $attributes['attributes'] = $attributes['attributes'];
        }

        $template = <<<HTML
		    <tr class="form-required">
                <th scope="row">
                    <label for="{$attributes['name']}">{$attributes['label']}<span class="{$attributes['class']}"></span></label>
                </th>
                <td>
                    <input name="{$attributes['name']}" autocomplete="{$attributes['autocomplete']}" $requiredAttribute class="regular-text $requiredClass"
                           type="{$attributes['type']}" {$attributes['attributes']} {$stepAttribute} id="{$attributes['name']}"  value="{$value}" />
                          {$description}                           
                </td>
            </tr>
HTML;


        return $template;
    }

    public function showDatepickerField($attributes, $value, $required = false)
    {
        if ($required == true) {
            $requiredClass = 'validate[required]';
            $requiredAttribute = 'required';
        }

        $template = <<<HTML
		    <tr class="form-required">
				<th scope="row">
					<label for="{$attributes['name']}">{$attributes['lable']} <span class="{$attributes['class']}"></span></label>
				</th>
				<td>
					<input type="date" id="{$attributes['name']}" name="{$attributes['name']}" value="{$value}" $requiredAttribute class="example-datepicker $requiredClass" />
				</td>
			</tr>
HTML;

        return $template;
    }

    public function render($templateName, $items = null, $object = null)
    {
        if (!$items) {
            $items = array();
        }

        ob_start();

        include(FORMS_PLUGIN_PATH . $templateName . '.php');

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

}
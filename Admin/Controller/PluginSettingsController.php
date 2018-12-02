<?php


namespace FormsPlugin\Admin\Controller;


class PluginSettingsController extends BaseController
{

    const SETTINGS_NAME = 'plugin_form';

    private $items;

    private $settings;

    public function __construct()
    {
        $this->items['pageSection'] = self::SETTINGS_NAME.'_main';
        $this->items['page'] = 'plugin_form-main-settings';
        $this->items['settings_name'] =  self::SETTINGS_NAME.'-tab-1';
        $this->settings = get_option( self::SETTINGS_NAME );
    }

    public function actionIndex()
    {
        do_action(  self::SETTINGS_NAME.'-nav-tabs');
		do_action( self::SETTINGS_NAME.'_menu_after_nav_tabs' );
        echo $this->render('/admin/views/settings', $this->items);
    }

    public function pluginSettings()
    {
        $this->items['pageSection'] = self::SETTINGS_NAME.'_main';
        $this->items['page'] = 'plugin_form-main-settings';
        $this->items['settings_name'] =  self::SETTINGS_NAME.'-tab-1';
        register_setting( self::SETTINGS_NAME.'-tab-1', self::SETTINGS_NAME, array( &$this, 'sanitizeSetting' ) );

        add_settings_section( $this->items['pageSection'], 'Настройки плагина', array(
            $this,
            'generalSettingsCallback'
        ), $this->items['page'] );

        add_settings_field(self::SETTINGS_NAME.'_email_to', 'Email администратора',
            [&$this, 'textfieldCallback'],	$this->items['page'] ,  $this->items['pageSection'],
            ['item' => self::SETTINGS_NAME.'_email_to', 'default' => '']);

        add_settings_field(self::SETTINGS_NAME.'_username', 'Telegram usernames',
            [&$this, 'textfieldCallback'],	$this->items['page'] ,  $this->items['pageSection'],
            ['item' => self::SETTINGS_NAME.'_username', 'default' => '']);

        add_settings_field(self::SETTINGS_NAME.'_bot_token', 'Bot token',
            [&$this, 'textfieldCallback'],	$this->items['page'] ,  $this->items['pageSection'],
            ['item' => self::SETTINGS_NAME.'_bot_token', 'default' => '']);


    }

    public function sanitizeSetting($input)
    {
        $output[self::SETTINGS_NAME.'_email_to'] = force_balance_tags($input[self::SETTINGS_NAME.'_email_to']);
        $output[self::SETTINGS_NAME.'_bot_token'] = force_balance_tags($input[self::SETTINGS_NAME.'_bot_token']);
        $output[self::SETTINGS_NAME.'_username'] = force_balance_tags($input[self::SETTINGS_NAME.'_username']);
        return $output;

    }

    public function textfieldCallback($args) {

        $item = $args['item'];
        $msg = isset($args['message']) ? $args['message'] : '';
        $text = $this->getValue($item) ? esc_attr($this->getValue($item)) : $args['default'];
        echo "<input type='text' name='".self::SETTINGS_NAME."[" . $item . "]' value='" . $text . "' />";
        echo '<br/><i>' . $msg . '</i>';
    }

    public function getValue( $key, $default = "" )
    {
        if ( isset( $this->settings[ $key ] ) )
        {
            return $this->settings[ $key ];
        }

        return $default;
    }

    //settings callback section
    public function generalSettingsCallback() {
        //_e('Настройки плагина.');
    }

}
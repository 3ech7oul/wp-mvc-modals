<?php
namespace FormsPlugin\Admin;

use FormsPlugin\Admin\Controller\FormsTypesController;
use FormsPlugin\Admin\Controller\FormsRequestController;
use FormsPlugin\Admin\Controller\PluginSettingsController;

class AdminBuilder
{
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

		add_action('admin_init', array($this, 'adminInitHook'));
		add_action('admin_menu', array($this,'addAdminMenu') );
		add_action('admin_notices', array(&$this, 'doAdminNotices'));
	}

	public function adminInitHook()
	{
	    $settings = new PluginSettingsController();
        $settings->pluginSettings();
      // $this->adminController->initController();
	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueueStyles() {
		//wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/vrbk-admin.css', array(), $this->version, 'all' );

	}
	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueueScripts()
	{
		//wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/vrbm-admin.js', array( 'jquery' ), $this->version, TRUE );
	}

	public function doAdminNotices()
	{
		$this->notices();
	}

	public function notices()
	{
	}

	/**
	 * Добавляет пункт меню в админку
	 */
	public function addAdminMenu()
	{
		$vrbmParent = 'forms_dashboard';
		global $admin_page_hooks;
        $pluginSettings = new PluginSettingsController();
		if ( isset($admin_page_hooks[$vrbmParent]) ) {
			$this->createMenuField($vrbmParent);

		} else {
			add_menu_page( 'forms_dashboard',
				'Формы обратной связи',
				'manage_options',
				$vrbmParent,
                [$pluginSettings, 'initController'],
				'dashicons-admin-users', 88 );
			$this->createMenuField($vrbmParent);
		}
	}

	/**
	 * Список подпунктов меню
	 *
	 * @param $parent
	 */
	public function createMenuField($parent)
	{
	    $formsController = new FormsTypesController();
	    add_submenu_page(
	        $parent,
            "Темы",
			'Темы',
			'manage_options',
            'forms-index',
			[$formsController, 'initController']
        );

	    $formsRequestController = new FormsRequestController();
        $formsRequestController->setActionVariable('requests-index');
        add_submenu_page(
            $parent,
            'Заявки',
            'Заявки',
            'manage_options',
            'requests-index',
            [$formsRequestController, 'initController']
        );
	}
}
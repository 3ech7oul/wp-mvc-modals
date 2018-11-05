<?php
namespace FormsPlugin;

use FormsPlugin\Admin\AdminBuilder;
use FormsPlugin\FrontEnd\PublicBuilder;

class Plugin
{
	const PLUGIN_ALIAS = 'forms';

	protected $loader;

	protected $version;

	protected $pluginName;

	public function __construct()
	{
		$this->pluginName = self::PLUGIN_ALIAS;
		$this->version = '1.0';
		$this->loader = new Loader();
		$this->defineAdminHooks();
		$this->definePublicHooks();
	}

	private function defineAdminHooks()
	{
		$pluginAdmin = new AdminBuilder( $this->getPluginName(), $this->getVersion() );
	}

	private function definePublicHooks()
    {
        $pluginPublic = new PublicBuilder( $this->getPluginName(), $this->getVersion() );
        //$this->loader->addAction( 'wp_enqueue_scripts', $pluginPublic, 'enqueueStyles' );
        $this->loader->addAction( 'wp_enqueue_scripts', $pluginPublic, 'enqueueScripts' );

    }

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function getPluginName() {
		return $this->pluginName;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function getVersion() {
		return $this->version;
	}

	public function run() {
		$this->loader->run();
	}

}
<?php

/*
Plugin Name: FormsPlugin
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: A brief description of the Plugin.
Version: 1.0
Author: denni
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/
namespace FormsPlugin;
require_once plugin_dir_path( __FILE__ ) . 'autoload.php';
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('FORMS_PLUGIN_PATH', dirname(__FILE__));

\register_activation_hook( __FILE__, [ __NAMESPACE__ .'\\Activator', 'activate' ] );
$plugin = new Plugin();
$plugin->run();







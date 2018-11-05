<?php
namespace FormsPlugin;
use FormsPlugin\Plugin;


class Activator
{

	private static $moduleTablePrifex = Plugin::PLUGIN_ALIAS.'_';

	public static function activate()
	{

		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$charset_collate = '';
		if (!empty($wpdb->charset)) {
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		} else {
			$charset_collate = "DEFAULT CHARSET=utf8";
		}
		if (!empty($wpdb->collate)) {
			$charset_collate .= " COLLATE $wpdb->collate";
		}

		self::createFormsTable($charset_collate);

	}

	public static function createFormsTable($charset_collate)
	{
		$tableName = 'forms';

		global $wpdb;
		$sql = "CREATE TABLE " . $wpdb->prefix . self::$moduleTablePrifex.$tableName." (
			id int(12) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			sort int(12),
			model varchar(255) DEFAULT '',
			settings_obj JSON default ''
          )" . $charset_collate . ";";
		\dbDelta($sql);
	}

	public static function deactivate()
	{


	}

}
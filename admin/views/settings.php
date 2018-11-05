<?php
/**
 * @var $items
 */
?>
<form action="options.php" method="POST">
    <?php settings_fields(  $items['settings_name'] ); ?>
    <?php do_settings_sections( $items['page'] ); ?>
    <?php submit_button(); ?>
</form>
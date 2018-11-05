<?php
/**
 * @var $items
 * @var FormsPlugin\Admin\Controller\BaseController $object
 */
?>
<div class="wrap" id="vrbm-settings-biller" type="add">
    <form action="" method="post" name="vrbk-settings-usre" id="vrbm-settings-biller" class="validate vrbm-validate-form"
          enctype="multipart/form-data"
        <?php do_action($items['wpnonce_action']); ?>
    >
        <input name="action" type="hidden" value="createuser" />
        <?if(isset($items['data']['id'])):?><input name="id" type="hidden" value="<?php echo esc_attr($items['data']['id']); ?>" /><?endif;?>
        <?php wp_nonce_field($items['wpnonce_action'], $items['wpnonce_name']); ?>
        <h3><?php echo $items['title'] ?></h3>

        <table class="form-table">
            <tbody>
            <?php echo $object->showInputTextField(['label'=>'Name', 'name'=>'name'], (isset($items['data']['name']))?$items['data']['name']:'', true );?>
            </tbody>
        </table>
        <?php /*include('admin_member_form_common_js.php'); */?>
        <?php submit_button('Save ', 'primary', 'create_biller_settings', true, array('id' => 'createbillersettings')); ?>

    </form>
</div>

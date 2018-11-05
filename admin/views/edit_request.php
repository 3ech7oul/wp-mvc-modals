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
            <?php foreach ($items['data'] as $atrName =>  $atrValue) {
               if (isset($atrValue)) {
                   echo $object->showInputTextField(['label'=>$atrName, 'name'=>$atrName, 'attributes' => 'readonly'], $atrValue, true );
               }
            }?>

         </tbody>
        </table>
        <?php /*include('admin_member_form_common_js.php'); */?>

    </form>
</div>

<?php
/**
 * @var $items
 * @var FormsPlugin\Admin\Widgets\AdminTableWidget $object
 */
?>
<div class="wrap">
    <h1><?php echo $items['title']; ?></h1>
    <div>
        <h2><a
                href="?page=<?=$items['action_parent'];?>&<?=$items['action_variable']?>=<?=$items['action_add']?>"
                class="add-new-h2"><?php echo (isset($items['add_new_text'])) ? $items['add_new_text'] : 'Add New';?></a></h2>
        <form id="members-filter" method="get">
            <?php $object->tabs();?>
            <?php $object->display();?>
        </form>
    </div>
</div>

<?php
/*
 * template for a side column containing site-wide updates
 */
?>
<div class="row">
    <div class="col-sm-12">
        <h2 class="column-h2"><?php echo lang('sidebar_recent_updates'); ?></h2>
        <p><em><?php echo lang('sidebar_recent_blurb'); ?></em></p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php echo build_brief_item_list($sidebar_list, 'whats_new'); ?>
    </div>
</div>

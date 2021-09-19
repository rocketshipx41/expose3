<?php
/*
* template for collapsible issue list
*/
?>
<div class="row">
    <div class="col-sm-12">
        <h2 class="column-h2"><?php echo lang('issue_index_header'); ?></h2>
        <p><em><?php echo lang('issue_index_blurb'); ?></em></p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <ul id="issue_list">
    <?php foreach ($issue_list as $issue_no => $issue) : ?>
        <?php if ( $issue_no < 40 ) : ?>
            <li><?php echo anchor('articles/issue/' . $issue_no, $issue_no . ' (' . $issue->description . ')'); ?></li>
        <?php endif; ?>
    <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($issue); ?>
</div>

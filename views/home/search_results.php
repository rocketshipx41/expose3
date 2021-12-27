<div class="row">
    <div class="col-sm-2">
        <p class="text-start"><?php if ( $prev_link != '' ) echo anchor($prev_link, lang($prev_link_label)); ?></p>
    </div>
    <?php foreach ($filter_links as $item) :  ?>
    <div class="col-sm-2">
        <?php if ( $item['link'] == '' ) : ?>
            &nbsp;
        <?php else : ?>
        <p class="text-center"><?php echo anchor($item['link'], $item['label']); ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
    <div class="col-sm-2">
        <p class="text-end"><?php if ( $next_link != '' ) echo anchor($next_link, lang($next_link_label)); ?></p>
    </div>
</div>
<?php if ( count($main_list) > 0 ) : ?>
<div class="row">
    <div class="col-sm-12 text-center">
        <p><em>Showing <?php echo ($offset + 1) . ' to ' . ($offset + count($main_list)) . ' of ' . $search_total; ?></em></p>
    </div>
</div>
<?php foreach ($main_list as $item) : ?>
    <div class="row">
        <?php if ( $item->image_path ) : ?>
        <div class="col-sm-4">
            <img src="<?php echo base_url('assets/img/' . $item->image_path);?>" class="index-art"
                        height="190" width="190" alt="<?php echo lang('article_cover_art_alt'); ?>"> 
        </div>
        <div class="col-sm-8">
        <h3> <?php echo $item->result_type; ?></h3>
            <p>
            <?php echo anchor($item->url, $item->display); ?><br>
            <?php if ($item->result_type == 'Release') : ?>
                <?php echo $item->extra; ?><br>
            <?php elseif ($item->result_type == 'Review') : ?>
                <?php echo $item->extra; ?><br>
            <?php elseif ($item->result_type == 'Artist') : ?>
                <?php echo $item->extra; ?><br>
            <?php else : ?>
                <?php echo $item->extra; ?><br>
            <?php endif; ?>
            </p>
        <?php else : // no image ?>
        <div class="col-sm-12">
            <h3> <?php echo $item->result_type . ': ' . anchor($item->url, $item->display); ?></h3>
            <?php if ( $item->extra ) : ?>
                <?php echo $item->extra; ?>
            <?php endif;?>
        <?php endif; ?>
        </div> <!-- col -->
    </div> <!-- row -->
<?php endforeach; ?>
<div class="row">
    <div class="col-sm-6">
        <p class="text-start"><?php if ( $prev_link != '' ) echo anchor($prev_link, lang($prev_link_label)); ?></p>
    </div>
    <div class="col-sm-6">
        <p class="text-end"><?php if ( $next_link != '' ) echo anchor($next_link, lang($next_link_label)); ?></p>
    </div>
</div>
<?php else : ?>
<div class="row">
    <div class="col-sm-12">
        <p>No results match your search.</p>
    </div>
</div>
<?php endif; ?>

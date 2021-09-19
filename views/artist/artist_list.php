<div class="row">
    <div class="col-sm-2">
        <p class="text-left"><?php if ( $prev_link != '' ) echo anchor($prev_link, lang('artist_index_prev')); ?></p>
    </div>
    <div class="col-sm-8">
        <?php echo anchor('artists/' . $list_source . '/0', '0'); ?>
        <?php echo anchor('artists/' . $list_source . '/a', 'A'); ?>
        <?php echo anchor('artists/' . $list_source . '/b', 'B'); ?>
        <?php echo anchor('artists/' . $list_source . '/c', 'C'); ?>
        <?php echo anchor('artists/' . $list_source . '/d', 'D'); ?>
        <?php echo anchor('artists/' . $list_source . '/e', 'E'); ?>
        <?php echo anchor('artists/' . $list_source . '/f', 'F'); ?>
        <?php echo anchor('artists/' . $list_source . '/g', 'G'); ?>
        <?php echo anchor('artists/' . $list_source . '/h', 'H'); ?>
        <?php echo anchor('artists/' . $list_source . '/i', 'I'); ?>
        <?php echo anchor('artists/' . $list_source . '/j', 'J'); ?>
        <?php echo anchor('artists/' . $list_source . '/k', 'K'); ?>
        <?php echo anchor('artists/' . $list_source . '/l', 'L'); ?>
        <?php echo anchor('artists/' . $list_source . '/m', 'M'); ?>
        <?php echo anchor('artists/' . $list_source . '/n', 'N'); ?>
        <?php echo anchor('artists/' . $list_source . '/o', 'O'); ?>
        <?php echo anchor('artists/' . $list_source . '/p', 'P'); ?>
        <?php echo anchor('artists/' . $list_source . '/q', 'Q'); ?>
        <?php echo anchor('artists/' . $list_source . '/r', 'R'); ?>
        <?php echo anchor('artists/' . $list_source . '/s', 'S'); ?>
        <?php echo anchor('artists/' . $list_source . '/t', 'T'); ?>
        <?php echo anchor('artists/' . $list_source . '/u', 'U'); ?>
        <?php echo anchor('artists/' . $list_source . '/v', 'V'); ?>
        <?php echo anchor('artists/' . $list_source . '/w', 'W'); ?>
        <?php echo anchor('artists/' . $list_source . '/x', 'X'); ?>
        <?php echo anchor('artists/' . $list_source . '/y', 'Y'); ?>
        <?php echo anchor('artists/' . $list_source . '/z', 'Z'); ?>
    </div>
    <div class="col-sm-2">
        <p class="text-right"><?php if ( $next_link != '' ) echo anchor($next_link, lang('artist_index_next')); ?></p>
    </div>
</div>
<?php foreach ($main_list as $item) : ?>
    <div class="row">
        <div class="col-sm-12">
            <h3><?php echo anchor('artists/display/' . $item->slug, $item->display); ?></h3>
            <?php if ( $item->image_file ) : ?>
            <img src="<?php echo base_url('assets/img/artists/' . $item->image_file);?>" class="index-art"
                        height="200" width="300" alt="<?php echo $item->display; ?>"> 
            <?php endif; ?>
            <?php echo '<strong>' . lang('artist_field_country') . ':</strong> '  . $item->country; ?><br>
            <?php echo '<strong>' . lang('artist_display_article_list') . ':</strong> '  . $item->article_count; ?><br>
            <?php echo '<strong>' . lang('artist_display_release_list') . ':</strong> '  . $item->release_count; ?>
        </div> <!-- col -->
    </div> <!-- row -->
<?php endforeach; ?>
<div class="row">
    <div class="col-sm-6">
    <p class="text-left"><?php if ( $prev_link != '' ) echo anchor($prev_link, lang('artist_index_prev')); ?></p>
    </div>
    <div class="col-sm-6">
        <p class="text-right"><?php if ( $next_link != '' ) echo anchor($next_link, lang('artist_index_next')); ?></p>
    </div>
</div>

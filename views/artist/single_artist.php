<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * template for artist display page
 */

?>
<div class="row">
    <div class="col-sm-12">
        <h2><?php echo $artist->display; ?></h2>
        <?php if ($artist->image_file): ?>
            <img src="<?php echo image_url('artists/'. $artist->image_file);?>" class="artist-art"
                    alt="<?php echo $artist->display; ?>"
                    title="<?php echo $artist->display; ?>">
        <?php endif; ?>
        <dl>
            <dt><?php echo lang('artist_field_country'); ?></dt>
            <dd><?php echo anchor('artists/country/' . $artist->country_id, $artist->country); ?></dd>
            <dt><?php echo lang('artist_field_info'); ?></dt>
        <?php if ($artist->info): ?>
            <dd><?php echo $artist->info; ?></dd>
        <?php else : ?>
            <dd><em><?php echo lang('artist_field_none'); ?></em></dd>
        <?php endif; ?>
            <dt><?php echo lang('artist_field_url'); ?></dt>
        <?php if ($artist->url): ?>
            <dd><?php echo auto_link('http://' . $artist->url, 'url', TRUE); ?></dd>
        <?php else : ?>
            <dd><em><?php echo lang('artist_field_none'); ?></em></dd>
        <?php endif; ?>
            <dt><?php echo lang('artist_display_article_list') . ' (' 
                    . count($artist->article_list) . ')'; ?></dt>
        <?php if (count($artist->article_list)) : ?>
            <dd>
            <?php echo build_article_list($artist->article_list); ?>
            </dd>
        <?php else : ?>
            <dd><em><?php echo lang('artist_field_none'); ?></em></dd>
        <?php endif; ?>
            <dt><?php echo lang('artist_display_release_list') . ' (' 
                    . count($artist->release_list) . ')'; ?></dt>
            <dd>
            <?php if (count($artist->release_list)) : ?>
                <?php echo build_release_list($artist->release_list, $can_edit); ?>
            <?php else : ?>
            <dd><em><?php echo lang('label_search_none'); ?></em></dd>
        <?php endif; ?>
            </dd>
        </dl>
    </div> <!-- col -->
</div> <!-- row -->
<?php if ( $can_edit ) : ?>
<div class='row'>
    <div class="col-sm-10">
        <?php echo anchor('artists/edit/' . $artist->slug, lang('artist_edit_button'),
            array('class' => 'btn btn-primary')); ?>
        <?php echo anchor('releases/edit/0/' . $artist->slug, lang('artist_add_release_button'),
            array('class' => 'btn btn-success')); ?>
    </div>
    <div class="col-sm-2">
        <?php echo 'ID: ' . $artist->id; ?>
    </div>
</div>
<?php endif; ?>
<div class="row bottom-buffer"> <!-- row -->
    &nbsp;
</div>

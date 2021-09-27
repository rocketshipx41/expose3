<?php

/*
 * center section for release info
 */
?>
<div class="row">
    <div class="col-sm-12">
        <h2><?php echo $release->full_display(); ?></h2>
    </div> <!-- col -->
</div> <!-- row -->
<div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-10">
        <img src="<?php echo image_url('releases/'. $release->image_file);?>"
                        height="400" width="400" class="artist-release-art"
                        alt="<?php echo $release->display_artist . ' &mdash; ' 
                                . $release->display_title; ?>"
                        title="<?php echo $release->display_artist . ' &mdash; ' 
                                . $release->display_title; ?>">
    </div> <!-- col -->
    <div class="col-sm-2"></div>
</div> <!-- row -->
<dl>
    <div class="row">
        <div class="col-sm-6">
            <?php if ($release->label_id)  : ?>
                <dt><?php echo lang('release_edit_label'); ?></dt>
                <dd>
                    <?php echo anchor('labels/display/'
                        . $release->label_id, $release->label_name); ?>
                </dd>
            <?php endif; ?>
            <dt><?php echo lang('release_edit_type'); ?></dt>
            <dd><?php echo $release->release_type; ?></dd>
            <dt><?php echo lang('release_edit_year_released'); ?></dt>
            <dd><?php echo $release->year_released; ?></dd>
        </div>
        <div class="col-sm-6">
            <dt><?php echo lang('release_edit_catalog_no'); ?></dt>
            <dd><?php echo $release->catalog_no; ?></dd>
            <dt><?php echo lang('release_edit_media'); ?></dt>
            <dd><?php echo $release->media; ?></dd>
            <?php if ( ( $release->year_recorded > 0 )
                && ( $release->year_recorded != $release->year_released ) ) : ?>
                <dt><?php echo lang('release_edit_year_recorded'); ?></dt>
                <dd><?php echo $release->year_recorded; ?></dd>
            <?php endif; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <dt><?php echo lang('artist_display_article_list'); ?></dt>
            <?php if (count($release->article_list)) : ?>
                <dd>
                    <ul>
                        <?php foreach ($release->article_list as $article) :?>
                            <li>
                                <?php echo $article->category_name . ': ' . anchor('articles/display/'
                                        . $article->slug, $article->title); ?>
                                <?php if ($article->category_id != 5) : ?>
                                    <em>(<?php echo credit_display($article->credit_list, 1) . ' '
                                            . substr($article->published_on, 0, 10); ?>)</em>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </dd>
            <?php else : ?>
                <dd><em><?php echo lang('artist_field_none'); ?></em></dd>
            <?php endif; ?>
            <dt><?php echo lang('article_artist'); ?></dt>
            <dd><?php echo artist_display($release->artist_list); ?></dd>
        </div> <!-- col -->
    </div> <!-- row -->
</dl>
<?php if ( $can_edit ) : ?>
<div class='row'>
    <div class="col-sm-10">
        <?php echo anchor('releases/edit/' . $release->id, lang('release_edit_button'),
            array('class' => 'btn btn-primary')); ?>
        <?php echo anchor('articles/edit/0/' . $release->id, lang('release_review'),
                    array('class' => 'btn btn-primary')); ?>
    </div>
    <div class="col-sm-2">
        <?php echo 'ID: ' . $release->id; ?>
    </div>
</div>
<?php endif; ?>
<div class="row bottom-buffer"> <!-- row -->
    &nbsp;
</div>

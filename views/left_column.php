<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php if ( count($news_list) ) : ?>
<div class="row">
    <div class="col-sm-12">
        <h3><?php echo lang('latest_news'); ?></h3>
    </div>
</div>
<?php foreach ($news_list as $item) : ?>
<div class="row">
    <div class="col-sm-12">
        <p>
            <em><?php echo substr($item['published_on'], 0, 10); ?></em><br/>
            <?php echo anchor('articles/display/' . $item['slug'], '<strong>' 
                    . $item['title'] . '</strong>'); ?> &ndash; 
            <?php echo strip_tags($item['intro']); ?>&nbsp;&raquo; 
                <?php echo anchor('articles/display/' . $item['slug'], lang('read_more')); ?>
        </p>
    </div>
</div>
<?php endforeach; ?>
<div class="row">
    <div class="col-sm-12">
    <?php echo anchor('articles/index/news', lang('more_news')); ?>
    <?php if ($can_edit) : ?>
    &mdash; <?php echo anchor('articles/add/2', lang('add_news')); ?>
    <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ( count($event_list) ) : ?>
<hr>
<div class="row">
    <div class="col-sm-12">
        <h3><?php echo lang('upcoming_events'); ?></h3>
    </div>
</div>
<?php foreach ($event_list as $item) : ?>
<div class="row">
    <div class="col-sm-12">
        <p>
            <strong><?php echo $item['title']; ?></strong> &ndash; 
            <?php echo $item['body']; ?>&nbsp;&raquo; 
                <?php echo anchor('articles/display/' . $item['slug'], lang('read_more')); ?>
        </p>
    </div>
</div>
<?php endforeach; ?>
<div class="row">
    <div class="col-sm-12">
    <?php echo anchor('articles/index/events', lang('more_events')); ?>
    <?php if ($can_edit) : ?>
    &mdash; <?php echo anchor('articles/add/7', lang('add_event')); ?>
    <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ( count($random_list) ) : ?>
<hr>
<div class="row">
    <div class="col-sm-12">
        <h3><?php echo lang('article_random_review'); ?></h3>
    </div>
</div>
<?php foreach ($random_list as $item) : ?>
<div class="row">
    <div class="col-sm-12">
        <p>
            <?php echo anchor('articles/display/' . $item['slug'], '<strong>' 
                    . $item['title'] . '</strong>'); ?> &ndash; 
            <?php echo smart_trim(strip_tags($item['body']), 200); ?>&nbsp;
            <em>(<?php echo substr($item['published_on'], 0, 4); ?>)</em> &raquo; 
                <?php echo anchor('articles/display/' . $item['slug'], lang('read_more')); ?>
        </p>
    </div>
</div>
<?php endforeach; ?>
<div class="row">
    <div class="col-sm-12">
    <?php echo anchor('articles/random/reviews', lang('random_review')); ?>
    </div>
</div>
<?php endif; ?>
<?php if ( $show_ads && $left_column_ad ) : ?>
<hr>
<div class="row">
    <div class="col-sm-12">
        <a href="http://<?php echo $side_ad['url']; ?>" target="_blank">
            <img src="<?php echo image_url('ads/' . $side_ad['image_file']);?>" 
                 alt="<?php echo $side_ad['alt']; ?>"
                 title="<?php echo $side_ad['title']; ?>"
                style="display: block;margin-left: auto;margin-right: auto" />
        </a>
    </div>
</div>
<?php endif; ?>

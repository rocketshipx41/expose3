<?php
/*
 * template for displaying site statistics
 */
?>
<div class="row">
    <div class="col-sm-12">
        <h3>Articles</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4><?php echo lang('statistics_by_category'); ?></h4>
    </div>
</div>
    <?php foreach ($article_count as $row) : ?>
        <?php if ( $row->category_title != 'Total' ) : ?>
<div class="row">
    <div class="col-sm-2">
        &nbsp;
    </div>
    <div class="col-sm-5">
        <?php echo anchor('articles/index/' . $row->slug, $row->category_title); ?>
    </div>
    <div class="col-sm-5">
        <?php echo $row->acount; ?>
    </div>
</div>
        <?php endif; ?>
    <?php endforeach; ?>
<div class="row">
    <div class="col-sm-1">
        &nbsp;
    </div>
    <div class="col-sm-6">
        <strong><?php echo $article_count['all']->category_title; ?></strong>
    </div>
    <div class="col-sm-5">
        <strong><?php echo $article_count['all']->acount; ?></strong>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4><?php echo lang('statistics_by_topic'); ?></h4>
    </div>
</div>
    <?php foreach ($topic_count as $row) : ?>
        <?php if ( $row->topic_title != 'Total' ) : ?>
<div class="row">
    <div class="col-sm-2">
        &nbsp;
    </div>
    <div class="col-sm-5">
        <?php echo anchor('articles/topic/' . $row->slug, $row->topic_title); ?>
    </div>
    <div class="col-sm-5">
        <?php echo $row->acount; ?>
    </div>
</div>
        <?php endif; ?>
    <?php endforeach; ?>
<div class="row">
    <div class="col-sm-1">
        &nbsp;
    </div>
    <div class="col-sm-6">
        <strong><?php echo $topic_count['all']->topic_title; ?></strong>
    </div>
    <div class="col-sm-5">
        <strong><?php echo $topic_count['all']->acount; ?></strong>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4><?php echo lang('statistics_by_recording_year'); ?></h4>
    </div>
</div>
    <?php foreach ($recording_year_count as $row) : ?>
        <?php if ( $row->year_recorded != 'Total' ) : ?>
<div class="row">
    <div class="col-sm-2">
        &nbsp;
    </div>
    <div class="col-sm-5">
        <?php echo anchor('articles/recordings/' . $row->year_recorded, $row->year_recorded); ?>
    </div>
    <div class="col-sm-5">
        <?php echo $row->acount; ?>
    </div>
</div>
        <?php endif; ?>
    <?php endforeach; ?>
<div class="row">
    <div class="col-sm-1">
        &nbsp;
    </div>
    <div class="col-sm-6">
        <strong><?php echo $recording_year_count['Total']->year_recorded; ?></strong>
    </div>
    <div class="col-sm-5">
        <strong><?php echo $recording_year_count['Total']->acount; ?></strong>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h3>Artists</h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <h4><?php echo lang('statistics_country_artist_count'); ?></h4>
    </div>
</div>
    <?php foreach ($country_artist_count as $row) : ?>
<div class="row">
        <?php if ( $row->country_id != 'Total' ) : ?>
    <div class="col-sm-2">
        &nbsp;
    </div>
    <div class="col-sm-5">
        <?php echo anchor('artists/country/' . $row->country_id, $row->country_name); ?>
    </div>
    <div class="col-sm-5">
        <?php echo $row->acount; ?>
    </div>
        <?php endif; ?>
</div>
    <?php endforeach; ?>
<div class="row">
    <div class="col-sm-1">
        &nbsp;
    </div>
    <div class="col-sm-6">
        <strong><?php echo $country_artist_count['Total']->country_name; ?></strong>
    </div>
    <div class="col-sm-5">
        <strong><?php echo $country_artist_count['Total']->acount; ?></strong>
    </div>
</div>

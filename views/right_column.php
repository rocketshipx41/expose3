<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$this->load->helper('form');
$search_choices = array(
    'artists' => 'Artist', 
    'articles' => 'Title', 
    'releases' => 'Release',
    'labels' => 'Label'
);
?>
<div class="row">
    <div class="col-sm-12">
        <div class="fb-like" data-href="http://expose.org/index.php" 
             data-width="208" data-layout="standard" data-action="like" 
             data-show-faces="true" data-share="true"></div>
    </div>
</div>
<hr>
<!--
<div class="row">
    <div class="col-sm-12">
    <div id="searchbox">
    <p>Search the site</p>
	<select name="search-value" id="search-value" >
</select>
</div> -->
<!--                <?php echo anchor('welcome/search', lang('advanced_search')); ?>-->
    </div>
</div>
<?php if ( count($recommendation_list) ) : ?>
<hr>
<div class="row">
    <div class="col-sm-12">
        <h3><?php echo lang('latest_recommendations'); ?></h3>
    </div>
</div>
<?php foreach ($recommendation_list as $item) : ?>
<div class="row">
    <div class="col-sm-12">
        <p><strong><?php echo anchor('articles/display/' . $item['slug'], $item['title']); ?></strong></p>
<!--        <div class="embed-responsive embed-responsive-16by9"> -->
        <?php echo $item['body']; ?>
<!--        </div> -->
    </div>
</div>
<?php endforeach; ?>
<div class="row">
    <div class="col-sm-12">
    <?php echo anchor('articles/index/recommendations', lang('more_recommendations')); ?>
    <?php if ($can_edit) : ?>
    &mdash; <?php echo anchor('articles/add/8', lang('add_recommendation')); ?>
    <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ( $show_ads ) : ?>
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
<hr>
<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading" data-toggle="collapse" href="#issue-list">
                <h3>
                    <?php echo lang('issue_index'); ?>
                    <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                </h3>
            </div>
            <div class="panel-body collapse" id="issue-list">
                <ul  style="font-size: small;">
    <?php foreach ($issue_list as $issue_no => $item) : ?>
                    <li><?php echo anchor('articles/issue/' . $issue_no, 'Issue #' . $issue_no); ?> 
                        (<?php echo $item; ?>)</li>
    <?php endforeach; ?>            
                </ul>
            </div> <!-- panel body -->
        </div> <!-- panel -->
    </div> <!-- column -->
</div> <!-- row -->
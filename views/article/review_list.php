<?php if ( isset($issue) ) : ?>
<div class="row">
    <div class="col-sm-6">
        <p><strong><?php echo $issue->description; ?></strong></p>
    </div>
    <div class="col-sm-6 text-right">
        <p><strong><?php echo $issue->pages . lang('issue_pages');?></strong></p>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <p><?php echo $issue->blurb;?></p>
    </div>
</div>
<?php endif; ?>
<div class="row">
    <div class="col-sm-6">
        <p class="text-left"><?php if ( $prev_link != '' ) echo anchor($prev_link, lang('article_index_newer')); ?></p>
    </div>
    <div class="col-sm-6">
        <p class="text-right"><?php if ( $next_link != '' ) echo anchor($next_link, lang('article_index_older')); ?></p>
    </div>
</div>
<?php foreach ($main_list as $article) : ?>
    <div class="row">
        <div class="col-sm-12">
            <h3><?php echo anchor('articles/display/' . $article->slug, $article->title); ?></h3>
            <?php if ( $article->image_file ) : ?>
            <img src="<?php echo base_url('assets/img/' . $article->image_file);?>" class="index-art"
                        height="190" width="190" alt="<?php echo lang('article_cover_art_alt'); ?>"> 
            <?php endif; ?>
            <?php if ( $article->is_media() ) : ?>
                <?php echo $article->body; ?>
            <?php else : ?>
            <?php echo $article->intro; ?>
            <?php endif; ?>
        &nbsp;&raquo; <?php echo anchor('articles/display/' . $article->slug, lang('read_more')); ?>
        <br/><em>(<?php echo lang('article_posted') . ' ' 
                . credit_display($article->credit_list, 1) . ' '
                . substr($article->published_on, 0, 10); ?>)</em>
        </div>
    </div> <!-- row -->
<?php endforeach; ?>
<div class="row">
<div class="col-sm-6">
<p class="text-left"><?php if ( $prev_link != '' ) echo anchor($prev_link, lang('article_index_newer')); ?></p>
    </div>
    <div class="col-sm-6">
        <p class="text-right"><?php if ( $next_link != '' ) echo anchor($next_link, lang('article_index_older')); ?></p>
    </div>
</div>
<div class="row">
    <!-- <pre><?php echo $trace; ?></pre> -->
</div>
<div class="row bottom-buffer"> <!-- row -->
    &nbsp;
</div>

<?php
$image_file = $article->image_file;
?>
<h2><?php echo $article->title; ?></h2>
<img src="<?php echo image_url('features/' . $image_file); ?>"
    alt="" title=""/>
<p><?php echo $article->intro; ?></p>
<p><em><?php echo lang('article_written_by') . ' ' . credit_display($article->credit_list, 1); ?>,
    <?php echo lang('article_published') . ' ' . substr($article->published_on, 0, 10); ?></em></p>
<?php if ( ( $article->has_photographer() ) && ( $article->photographer_count() ) ) : ?>
<p><em><?php echo lang('article_photo_by') . ' ' . credit_display($article->credit_list, 2); ?></em></p>
<?php endif; ?>
<?php echo $article->body; ?>
<hr>
<p>
    <?php echo lang('article_topic') . ': ' . topic_display($article->topic_list, TRUE); 
    if ( $article->issue_no ) : 
        echo ', ' . anchor('articles/issue/' . $article->issue_no,
                lang('issue_no') . ' ' . $article->issue_no);
    endif; 
    ?>
</p>
<p><?php echo lang('article_artist') . ': ' . artist_display($article->artist_list); ?></p>
<?php if (count($article->link_list)) : ?>
<p><?php echo lang('article_links'); ?><br>
<?php foreach ($article->link_list as $row): ?>
    <?php echo auto_link('http://' . $row, 'url', TRUE); ?><br>
<?php endforeach; ?>
</p>
<?php endif; ?>
<?php if ( $can_edit ) : ?>
    <div class='row'>
        <div class="col-sm-10">
            <?php echo anchor('articles/edit/' . $article->slug, lang('article_edit_button'),
                array('class' => 'btn btn-primary')); ?>
        </div>
        <div class="col-sm-2">
            <p>ID: <?php echo $article->id; ?>
        </div>
    </div>
<?php endif; ?>

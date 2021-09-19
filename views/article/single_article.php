<?php
/*
 * template for article display page
 */
$image_file = '';
$year_list =  '';
?>
<div class="row">
    <div class="col-sm-12">
        <?php if (count($article->release_list)) : ?>
            <?php foreach ($article->release_list as $release) : ?>
                <p><strong class="release-title"><?php echo $release->display_artist . ' &mdash; ' . $release->display_title; ?></strong><br/>
                <em><?php echo release_line($release); ?></em><p>
                <?php if ($release->image_file) : ?>
                    <?php $image_file .= '<img src="' . image_url('releases/'. $release->image_file)
                            . '" class="review-art col-sm-6 pull-right" height="250" width="250" alt="' 
                            . $release->display_title . ' ' . lang('article_cover_art_alt') . '" title="'
                            .  $release->display_title . ' ' . lang('article_cover_art_alt') . '"/>'; ?>
                <?php endif; ?>
                <?php if (stripos($year_list, $release->year_released) === FALSE) $year_list .= ', ' . anchor('articles/releases/' . $release->year_released,
                    $release->year_released . ' ' . lang('article_release_year_topic')); ?>
                <?php if ( $release->year_released != $release->year_recorded) : ?>
                    <?php if (stripos($year_list, $release->year_recorded) === FALSE) $year_list .= ', ' . anchor('articles/recordings/' . $release->year_recorded,
                        $release->year_recorded . ' ' . lang('article_record_year_topic')); ?>
                <?php endif; ?>
            <?php endforeach; // release list ?>
        <?php endif; // has releases ?>
        <?php if ( ! $roundtable ) : ?>
            <p><em><?php echo lang('article_written_by') . ' ' . credit_display($article->credit_list, 1); ?>,
                <?php echo lang('article_published') . ' ' . substr($article->published_on, 0, 10); ?></em></p>
        <?php endif; ?>
        <?php if ($image_file) : ?>
            <?php echo $image_file; ?>
        <?php endif; ?>
        <?php echo $article->body; ?>
        <?php if ( $roundtable ) : ?>
            <p><em><?php echo lang('article_written_by') . ' ' . credit_display($article->credit_list, 1); ?>,
                <?php echo lang('article_published') . ' ' . substr($article->published_on, 0, 10); ?></em></p>
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
            <?php foreach ($related_list as $related) : ?>
                <hr>
                <?php echo $related->body; ?>
                <p><em><?php echo lang('article_written_by') . ' ' . credit_display($related->credit_list, 1); ?>,
                <?php echo lang('article_published') . ' ' . substr($related->published_on, 0, 10); ?></em></p>
                <?php if ( $can_edit ) : ?>
                    <div class='row'>
                        <div class="col-sm-10">
                            <?php echo anchor('articles/edit/' . $related->slug, lang('article_edit_button'),
                                array('class' => 'btn btn-primary')); ?>
                        </div>
                        <div class="col-sm-2">
                            <p>ID: <?php echo $article->id; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
        <hr>
        <p>
            <?php echo lang('article_topic') . ': ' . topic_display($article->topic_list, TRUE); 
            if ( $article->issue_no ) : 
                echo ', ' . anchor('articles/issue/' . $article->issue_no,
                        lang('issue_no') . ' ' . $article->issue_no);
            endif; 
            echo $year_list; ?>
        </p>
        <?php if ( count($article->artist_list) ) : ?>
        <p><?php echo lang('article_artist') . ': ' . artist_display($article->artist_list); ?></p>
        <?php endif; ?>
        <?php if (count($article->link_list)) : ?>
        <p><?php echo lang('article_links'); ?><br>
        <?php foreach ($article->link_list as $row): ?>
            <?php echo auto_link('http://' . $row, 'url', TRUE); ?><br>
        <?php endforeach; ?>
        </p>
        <?php endif; ?>
    </div> <!-- col -->
</div> <!-- row -->
<?php if ( $can_edit && ! $roundtable ) : ?>
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
<div class="row bottom-buffer"> <!-- row -->
    <?php // echo $trace; ?>
    &nbsp;
</div>

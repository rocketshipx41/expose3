<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* template for editing articles */
?>
<div class="row">
    <div class="col-sm-12">
        <h3><?php echo $article->title; ?></h3>
    </div>
</div>
<?php echo form_open('articles/edit', array('id' => 'article-form', 'name' => 'article-form'),
                    array('article_id' => $article->id, 'article-slug' => $article->slug,
                    'release_id' => $release_id, 'article-category' => $article->category_id)); ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-3">
            <label class="control-label" for="article-title"><?php echo lang('article_field_title'); ?></label>
        </div>
        <div class="col-sm-9">
            <?php echo form_input(array('name' => 'article-title','id' => 'article-title','maxlength' => '128',
                'size' => '50','value' => $article->title, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-authors"><?php echo lang('article_field_author'); ?></label><br>
            <?php echo form_multiselect('article-authors[]', $person_list, array_keys($article->credit_list[1]), 
                array('id' => 'article-authors', 'class' => 'select2', 'style' => 'width:100%')); ?>
        </div>
    </div>
</div>
<div class="form-group" id="photog-group"<?php if ( ! $article->has_photographer()) echo ' style="display:none;"'; ?>>
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-photog"><?php echo lang('article_field_photographer'); ?></label><br>
            <?php echo form_multiselect('article-photog[]', $person_list, array_keys($article->credit_list[2]), 
                array('id' => 'article-photog', 'class' => 'select2', 'style' => 'width:100%')); ?>
        </div>
    </div>
</div>
<div class="form-group" id="intro-group"<?php if ( ! $article->has_intro()) echo ' style="display:none;"'; ?>>
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-intro"><?php echo lang('article_edit_intro'); ?></label><br>
            <?php echo form_textarea(array('name' => 'article-intro','id' => 'article-intro',
                    'value' => $article->intro, 'class' => 'rteditor')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-body"><?php echo lang('article_edit_body'); ?></label><br>
            <?php echo form_textarea(array('name' => 'article-body','id' => 'article-body',
                    'value' => $article->body, 'class' => 'rteditor')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-artists"><?php echo lang('article_artist'); ?></label><br>
            <?php echo form_multiselect('article-artists[]', $artist_list, array_keys($article->artist_list), 
                array('id' => 'article-artists', 'class' => 'select2', 'style' => 'width:100%')); ?>
        </div>
    </div>
</div>
<div class="form-group" <?php if ( ! $article->has_releases()) echo ' style="display:none;"'; ?>>
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-releases"><?php echo lang('article_add_remove_releases'); ?></label><br>
            <?php echo form_input(array('name' => 'article-releases','id' => 'article-releases',
                'size' => '70','value' => $article->release_list_display())); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-topics"><?php echo lang('article_topic'); ?></label><br>
            <?php echo form_multiselect('article-topics[]', $topic_list, array_keys($article->topic_list), 
                array('id' => 'article-topics', 'class' => 'select2', 'style' => 'width:100%')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="article-links"><?php echo lang('article_related_links'); ?></label><br>
            <?php echo form_input(array('name' => 'article-links','id' => 'article-links',
                'size' => '70','value' => $article->link_display())); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-3">
            <label class="control-label" for="article-issue"><?php echo lang('issue_no'); ?></label>
        </div>
        <div class="col-sm-9">
            <?php echo form_dropdown('article-issue', $issue_list, $article->issue_no, 
                array('id' => 'article-issue')); ?>
        </div>
    </div>
</div>
<?php if ( $user_group == 'admin' ) : ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-3">
            <label class="control-label" for="article-status"><?php echo lang('article_status'); ?></label>
        </div>
        <div class="col-sm-9">
            <?php echo form_dropdown('article-status', $status_list, $article->status, 
                array('id' => 'article-status')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-3">
            <label class="control-label" for="article-published"><?php echo lang('article_publish_date'); ?></label>
        </div>
        <div class="col-sm-9">
            <?php echo form_input(array('name' => 'article-published','id' => 'article-published',
                'type' => 'date','value' => $article->published_on)); ?>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?php if ( $user_group == 'admin' ) : ?>
                <?php echo form_submit('article-save', lang('article_button_save'), array('class' => 'btn btn-primary')); ?>
            <?php else : ?>
                <?php echo form_submit('article-submit', lang('article_button_submit'), array('class' => 'btn btn-primary')); ?>
                <?php echo form_submit('article-draft', lang('article_button_save_draft'), array('class' => 'btn btn-primary')); ?>
            <?php endif; ?>
            <?php if ( $article->slug ) : ?>
            <?php echo anchor('articles/display/' . $article->slug, lang('cancel_button_text'),
                    array('class' => 'btn btn-secondary', 'id' => 'article-cancel')); ?>
            <?php else : ?>
                <?php echo anchor('releases/display/' . $release_id, lang('cancel_button_text'),
                    array('class' => 'btn btn-secondary', 'id' => 'article-cancel')); ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
<!-- <pre><?php echo print_r($article); ?></pre> -->

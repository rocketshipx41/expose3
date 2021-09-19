<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* template for editing artist info */
?>
<div class="row">
    <div class="col-sm-12">
        <h2><?php echo $release->full_display(); ?></h2>
    </div>
</div>
<?php echo form_open('releases/edit', array('id' => 'release-form', 'name' => 'release-form'),
                    array('release-id' => $release->id, 'artist-slug' => $release->home_artist_slug)); ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="artist-display"><?php echo lang('artist_field_display'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'artist-display','id' => 'artist-display','maxlength' => '128',
                'size' => '50','value' => $release->display_artist, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="artist-name"><?php echo lang('artist_field_name'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'artist-name','id' => 'artist-name','maxlength' => '128',
                'size' => '50','value' => $release->artist, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-display"><?php echo lang('release_edit_display_title'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'release-display','id' => 'release-display','maxlength' => '128',
                'size' => '50','value' => $release->display_title, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-title"><?php echo lang('release_edit_title'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'release-title','id' => 'release-title','maxlength' => '128',
                'size' => '50','value' => $release->title, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-label"><?php echo lang('release_edit_label'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_dropdown('release-label', $label_list, $release->label_id, 
                array('id' => 'release-label')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-catalog"><?php echo lang('release_edit_catalog_no'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'release-catalog','id' => 'release-catalog','maxlength' => '128',
                'size' => '50','value' => $release->catalog_no)); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-image"><?php echo lang('release_edit_image_file'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'release-image','id' => 'release-image','maxlength' => '128',
                'size' => '50','value' => $release->image_file)); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-type"><?php echo lang('release_edit_type'); ?></label>
        </div>
        <div class="col-sm-3">
            <?php echo form_dropdown('release-type', $release_type_list, $release->release_type_id, 
                array('id' => 'release-type')); ?>
        </div>
        <div class="col-sm-2">
            <label class="control-label" for="release-media"><?php echo lang('release_edit_media'); ?></label>
        </div>
        <div class="col-sm-3">
            <?php echo form_input(array('name' => 'release-media','id' => 'release-media','maxlength' => '20',
                'size' => '10','value' => $release->media, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-recorded"><?php echo lang('release_edit_year_recorded'); ?></label>
        </div>
        <div class="col-sm-3">
            <?php echo form_input(array('name' => 'release-recorded','id' => 'release-recorded','maxlength' => '4',
                'size' => '4','value' => $release->year_recorded, 'required' => 'required')); ?>
        </div>
        <div class="col-sm-2">
            <label class="control-label" for="release-released"><?php echo lang('release_edit_year_released'); ?></label>
        </div>
        <div class="col-sm-3">
            <?php echo form_input(array('name' => 'release-released','id' => 'release-released','maxlength' => '4',
                'size' => '4','value' => $release->year_released, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="release-various"><?php echo lang('release_edit_various_artists'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_checkbox(array('name' => 'release-various','id' => 'release-various',
                'checked' => $release->various_artists, 'value' => 'various_artists')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="release-artists"><?php echo lang('release_related_artists'); ?></label><br>
            <?php echo form_multiselect('release-artists[]', $artist_list, array_keys($release->artist_list), 
                array('id' => 'release-artists', 'class' => 'select2', 'style' => 'width:100%')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?php echo form_submit('release-submit', lang('submit_button_text'), array('class' => 'btn btn-primary')); ?>
            <?php echo anchor('releases/display/' . $release->id, lang('cancel_button_text'),
                    array('class' => 'btn btn-secondary')); ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

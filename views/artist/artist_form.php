<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* template for editing artist info */
?>
<div class="row">
    <div class="col-sm-12">
        <h2><?php echo $artist->display; ?></h2>
    </div>
</div>
<?php echo form_open('artists/edit', array('id' => 'artist-form', 'name' => 'artist-form'),
                    array('artist_id' => $artist->id, 'artist-slug' => $artist->slug)); ?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="artist-display"><?php echo lang('artist_field_display'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'artist-display','id' => 'artist-display','maxlength' => '128',
                'size' => '50','value' => $artist->display, 'required' => 'required')); ?>
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
                'size' => '50','value' => $artist->name, 'required' => 'required')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="artist-url"><?php echo lang('artist_field_url'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'artist-url','id' => 'artist-url','maxlength' => '128',
                'size' => '50','value' => $artist->url)); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="artist-image"><?php echo lang('artist_field_image'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input(array('name' => 'artist-image','id' => 'artist-image','maxlength' => '128',
                'size' => '50','value' => $artist->image_file, 'required' => 'required', 
                'placeholder' => 'noimage.png')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="artist-country"><?php echo lang('artist_field_country'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_dropdown('artist-county', $country_list, $artist->country_id, 
                array('id' => 'artist-country')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <label class="control-label" for="artist-info"><?php echo lang('artist_field_info'); ?></label><br>
            <?php echo form_textarea(array('name' => 'artist-info','id' => 'artist-info',
                    'value' => $artist->info, 'class' => 'ckeditor')); ?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?php echo form_submit('artist-submit', lang('submit_button_text'), array('class' => 'btn btn-primary')); ?>
            <?php echo anchor('artists/display/' . $artist->slug, lang('cancel_button_text'),
                    array('class' => 'btn btn-secondary')); ?>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

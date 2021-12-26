<p><?php echo lang('login_subheading');?></p>

<div id="infoMessage"><?php echo $message;?></div>

<?php echo form_open("auth/login");?>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="identity"><?php echo lang('login_identity_label', 'identity'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input($identity);?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="password"><?php echo lang('login_password_label', 'password'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_input($password);?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-4">
            <label class="control-label" for="password"><?php echo lang('login_remember_label', 'remember'); ?></label>
        </div>
        <div class="col-sm-8">
            <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-sm-12">
            <?php echo form_submit('login-submit', lang('login_submit_btn'), array('class' => 'btn btn-primary')); ?>
        </div>
    </div>
</div>
<?php echo form_close();?>

<?php echo anchor('auth/forgot-password', lang('login_forgot_password'));?></p>

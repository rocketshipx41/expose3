<?php
/*
 * main frame for expose web site
 */
$this->load->helper('form');
?>
<!DOCTYPE html>
<html>
    <?php echo build_head($site_name, $page_name, GTAG); ?>
    <body id="expobody">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <img src="<?php echo image_url('site/header3.jpg'); ?>" 
                        class="img-responsive" title="<?php echo $site_name; ?> banner"
                        alt="<?php echo $site_name; ?> banner">
                </div>
            </div>
            <?php echo build_menu($menu_active, $user_name, $user_group); ?>
            <?php if ( $status_message != '' ) : ?>
                <div class="alert alert-<?php echo $incoming_status; ?>" id="alert-box-<?php echo $incoming_status; ?>">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button><?php echo $status_message; ?></div>
            <?php endif; ?>
            <div class="row top-margin-10">
                <div class="col-sm-12 center">
                    <h1 class="text-center"><?php echo $site_name; ?></h1>
                    <p class="text-center top-message"><em><?php echo lang('site_top_message'); ?></em></p>
                </div>
            </div>
            <div class="row top-margin-10">
                <?php if ( $left_side ) : ?>
                <div id="left-column" class="col-sm-<?php echo $left_column_width; ?>">
                        <?php include(APPPATH . 'views/' . $left_side . '.php'); ?>
                </div>
                <?php endif; ?>
                <div class="col-sm-<?php echo $center_column_width; ?>">
                    <div class="row">
                        <div class="col-sm-12 center">
                            <h2><?php echo $page_title; ?></h2>
                        </div>
                    </div>
                    <?php include(APPPATH . 'views/' . $center_view . '.php'); ?>
                </div> <!-- col -->
                <?php if ( $right_side ) : ?>
                <div class="col-sm-<?php echo $right_column_width; ?>" id="right-column">
                    <?php include(APPPATH . 'views/' . $right_side . '.php'); ?>
                </div>
                <?php endif; ?>
            </div> <!-- row -->
            <?php echo build_footer(); ?>
        </div>
        <?php echo build_page_end_scripts($can_edit); ?>
    </body>
</html>
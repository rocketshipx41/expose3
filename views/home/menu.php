<?php
/*
 * menu template
 */
?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#expose-navbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
          </button>
<!--          <a class="navbar-brand" href="#">WebSiteName</a>-->
        </div>
        <div class="collapse navbar-collapse" id="expose-navbar">
            <ul class="nav navbar-nav" id="menu">
                <li <?php if ($menu_active == 'home') echo 'class="active"';?>>
                    <a href="<?php echo site_url(); ?>"><?php echo lang('menu_home'); ?></a>
                </li>
                <li <?php if ($menu_active == 'features') echo 'class="active"';?>>
                    <a href="<?php echo site_url('articles/index/features'); ?>"><?php echo lang('menu_features'); ?></a>
                </li>
                <li <?php if ($menu_active == 'reviews') echo 'class="active"';?>>
                    <a href="<?php echo site_url('articles/index/reviews'); ?>"><?php echo lang('menu_reviews'); ?></a>
                </li>
                <li <?php if ($menu_active == 'news') echo 'class="active"';?>>
                    <a href="<?php echo site_url('articles/index/news'); ?>"><?php echo lang('menu_news'); ?></a>
                </li>
                <li <?php if ($menu_active == 'recommendations') echo 'class="active"';?>>
                    <a href="<?php echo site_url('articles/index/recommendations'); ?>"><?php echo lang('menu_recommendations'); ?></a>
                </li>
                <li <?php if ($menu_active == 'artists') echo 'class="active"';?>>
                    <a href="<?php echo site_url('artists/index'); ?>"><?php echo lang('menu_artists'); ?></a>
                </li>
        <!--            <li><a href="<?php echo site_url('labels/index'); ?>"><?php echo lang('menu_labels'); ?></a></li>-->
                <li <?php if ($menu_active == 'about') echo 'class="active"';?>>
                    <a href="<?php echo site_url('welcome/about'); ?>"><?php echo lang('menu_about'); ?></a>
                </li>
                <li <?php if ($menu_active == 'faqs') echo 'class="active"'; ?>>
                    <a href="<?php echo site_url('articles/index/faqs'); ?>"><?php echo lang('menu_faq'); ?></a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php if ($is_logged_in) : ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <?php echo $user_name; ?> <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo site_url('people/display/' . $user_id); ?>"><?php echo lang('menu_user_page'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('welcome/changepwd'); ?>"><?php echo lang('menu_change_password'); ?></a>
                        </li>
                        <?php if ($can_contribute) : ?>
                        <li>
                            <a href="<?php echo site_url('articles/add'); ?>"><?php echo lang('menu_contribute'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('labels/edit/0'); ?>"><?php echo lang('menu_new_label'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('articles/future'); ?>"><?php echo lang('menu_future'); ?></a>
                        </li>
                            <?php if ( ! $can_edit) : ?>
                        <li>
                            <a href="<?php echo site_url('articles/drafts'); ?>"><?php echo lang('menu_my_drafts'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('articles/submissions'); ?>"><?php echo lang('menu_my_submissions'); ?></a>
                        </li>
                            <?php endif;?>
                        <?php endif;?>
                        <?php if ($can_edit) : ?>
                        <li>
                            <a href="<?php echo site_url('articles/drafts'); ?>"><?php echo lang('menu_drafts'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('articles/submissions'); ?>"><?php echo lang('menu_edit'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('ads/index'); ?>"><?php echo lang('menu_ads'); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('releases/assign'); ?>"><?php echo lang('menu_assign'); ?></a>
                        </li>
                        <?php endif;?>
                        <li>
                            <a href="<?php echo site_url('welcome/logout'); ?>"><?php echo lang('menu_logout'); ?></a>
                        </li>
                    </ul>
                </li>
                <?php else :// not logged in ?>
                <li>
                    <a href="#" data-toggle="modal" data-target="#loginPopup"><?php echo lang('menu_login'); ?></a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

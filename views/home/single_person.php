<div class="row">
    <div class="col-sm-12">
        <h3><?php echo $person_info->display_name; ?></h3>
        <dl>
        <?php if ( $user_group == 'admin' ) : ?>
            <dt><?php echo lang('people_email'); ?></dt> <dd><?php echo $person_info->email; ?></dd>
            <dt><?php echo lang('people_last_ip'); ?></dt> <dd><?php echo $person_info->ip_address; ?></dd>
            <dt><?php echo lang('people_last_login'); ?></dt> <dd><?php echo $person_info->last_login; ?></dd>
            <dt><?php echo lang('people_activated'); ?></dt> <dd><?php echo $person_info->active; ?></dd>
        <?php endif; ?>
        <dt><?php echo lang('people_article_count'); ?></dt> <dd><?php echo $person_info->article_count; ?></dd>
        <?php if (count($article_list)) : ?>
            <dd>
            <?php echo build_article_list($article_list); ?>
            </dd>
        <?php else : ?>
            <dd><em><?php echo lang('artist_field_none'); ?></em></dd>
        <?php endif; ?>
        </dl>
    </div>
</div>

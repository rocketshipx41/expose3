<div class="row">
    <div class="col-sm-12">
        <h2><?php echo $label_data->display; ?></h2>
        <dl>
            <dt><?php echo lang('label_country'); ?></dt>
            <dd><?php echo $label_data->country; ?></dd>
            <dt><?php echo lang('label_url'); ?></dt>
            <dd><a href="http://<?php echo $label_data->url; ?>"><?php echo $label_data->url; ?></a></dd>
            <dt><?php echo lang('label_info'); ?></dt>
            <dd><?php echo $label_data->info; ?></dd>
            <dt><?php echo lang('label_related_articles') . ' (' 
                    . count($article_list) . ')'; ?></dt>
            <dd>
            <?php if (count($article_list)) : ?>
                <?php echo build_article_list($article_list); ?>
            <?php else : ?>
                <p>None found.</p>
            <?php endif; ?>
            </dd>
            <dt><?php echo lang('label_releases') . ' (' . $label_data->release_count . ')'; ?></dt>
            <dd>
            <?php if (count($release_list)) : ?>
                <?php echo build_release_list($release_list, $can_edit, FALSE); ?>
            <?php else : ?>
            <dd><em><?php echo lang('label_search_none'); ?></em></dd>
        <?php endif; ?>
            </dd>
        </dl>
    </div>
</div>
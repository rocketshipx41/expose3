<?php
/*
 * home page center section template
 */
?>
        <div class="row">
            <div class="col-sm-12">
                <!--begin bg-carousel-->
                <div id="bg-fade-carousel" class="carousel slide carousel-fade" data-ride="carousel">
                <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                        <?php foreach ($carousel_list as $key => $item) : ?>
                        <div class="item<?php if ($key == 0) echo ' active'; ?>">
                            <div class="slide<?php echo $key + 1; ?>">
                                <div class="container carousel-overlay">
                                    <?php echo anchor('articles/display/' . $item['slug'], '<h1>' . $item['title'] . '</h1>'); ?>
<!--                                    <h1><?php echo $item['title']; ?></h1>-->
                                    <p>
                                        <?php echo smart_trim($item['intro'], 220); ?>
                                        <?php echo anchor('articles/display/' . $item['slug'], lang('read_more')); ?>
                                    </p>
                                </div> <!-- carousel overlay -->
                            </div> <!-- slide -->
                        </div> <!-- item -->
                        <?php endforeach; ?>
                    </div><!-- .carousel-inner --> 
                </div><!-- .carousel --> 
                <!--end bg-carousel-->
            </div> <!-- span -->
        </div>
        <div class="row top-margin-10">
            <div class="col-sm-8">
                <div class="row">
                    <h2><?php echo lang('home_latest_reviews'); ?></h2>
                </div>
            <?php foreach ($review_list as $item) : ?>
                <div class="row">
                    <div class="col-sm-12">
                        <h3><?php echo anchor('articles/display/' . $item['slug'], $item['title']); ?></h3>
                        <img src="<?php echo image_url($item['image_file']);?>" class="index-art"
                                    height="190" width="190" alt="<?php echo lang('article_cover_art_alt'); ?>">
                        <?php echo $item['intro']; ?>
                    &nbsp;&raquo; <?php echo anchor('articles/display/' . $item['slug'], lang('read_more')); ?>
                    <br/><em>(<?php echo lang('article_posted') . ' ' 
                            . credit_display($item['credits'], 1) . ' '
                            . substr($item['published_on'], 0, 10); ?>)</em>
                    </div>
                </div> <!-- row -->
            <?php endforeach; ?>
                <div class="row nav-list">
                    <span class="pull-right">
                    <?php echo anchor('articles/index/reviews', lang('more_reviews')); ?> &raquo;
                    </span>
                </div>
                <div class="row">
                    <h2><?php echo lang('home_latest_features'); ?></h2>
                </div>
            <?php foreach ($feature_list as $item) : ?>
                <div class="row">
                    <div class="col-sm-12">
                        <h3><?php echo anchor('articles/display/' . $item['slug'], $item['title']); ?></h3>
                        <img src="<?php echo image_url('features/'. $item['image_file']);?>" class="index-art"
                                    height="190" width="190" alt="<?php echo lang('article_cover_art_alt'); ?>">
                        <?php echo $item['intro']; ?>
                    &nbsp;&raquo; <?php echo anchor('articles/display/' . $item['slug'], lang('read_more')); ?>
                    <br/><em>(<?php echo lang('article_posted') . ' ' 
                            . credit_display($item['credits'], 1) . ' '
                            . substr($item['published_on'], 0, 10); ?>)</em>
                    </div>
                </div> <!-- row -->
            <?php endforeach; ?>
                <div class="row nav-list">
                    <span class="pull-right">
                    <?php echo anchor('articles/index/features', lang('more_features')); ?> &raquo;
                    </span>
                </div>
            </div>
            <div class="col-sm-4">
                <?php echo $template['partials']['right_column']; ?>
            </div>
        </div> <!-- row -->

<div class="row">
        <div class="col-sm-12">
            <ul>
<?php foreach ($main_list as $item) : ?>
                <li><?php echo anchor('labels/display/' . $item->id, $item->display) 
                    . ' &mdash; ' . $item->country
                    . ' (' . $item->release_count . ' releases)'; ?></li>
<?php endforeach; ?>
            </ul>
        </div> <!-- col -->
    </div> <!-- row -->

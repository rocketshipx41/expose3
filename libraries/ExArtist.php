<?php
class ExArtist {

    // main data points
    public $id = 0;
    public $slug = '';
    public $name = '';
    public $display = '';
    public $url = '';
    public $country_id = '';
    public $country = '';
    public $image_file = '';
    public $info = '';

    // extra info
    public $release_list = array();
    public $article_list = array();

    public function clear_article_list()
    {
        $this->article_list = array();
    }

    public function clear_release_list()
    {
        $this->release_list = array();
    }

    public function process_post_values($post_values)
    {
        $update_values = array();
        if ( $post_values['slug'] ) {
            if ( $post_values['slug'] != $this->slug ) {
                $update_values['slug'] = $post_values['slug'];
            }
        }
        if ( $post_values['artist-display'] ) {
            if ( $post_values['artist-display'] != $this->display ) {
                $update_values['display'] = $post_values['artist-display'];
            }
        }
        if ( $post_values['artist-name'] ) {
            if ( $post_values['artist-name'] != $this->name ) {
                $update_values['name'] = $post_values['artist-name'];
            }
        }
        if ( $post_values['artist-url'] ) {
            if ( $post_values['artist-url'] != $this->url ) {
                $update_values['url'] = $post_values['artist-url'];
            }
        }
        if ( $post_values['artist-image'] ) {
            if ( $post_values['artist-image'] != $this->image_file ) {
                $update_values['image_file'] = $post_values['artist-image'];
            }
        }
        if ( ( $this->image_file == '' ) && ( ! $update_values['image_file'] ) ) {
            $update_values['image_file'] = 'noimage.png';
        }
        if ( $post_values['artist-county'] ) {
            if ( $post_values['artist-county'] != $this->country_id ) {
                $update_values['country_id'] = $post_values['artist-county'];
            }
        }
        if ( $post_values['artist-info'] ) {
            if ( $post_values['artist-info'] != $this->info ) {
                $update_values['info'] = $post_values['artist-info'];
            }
        }
        return $update_values;
    }

    public function create_unique_slug()
    {
        $base = $this->name . '-' . $this->country_id;
        $this->slug = create_unique_slug($base, 'artists');
    }

}
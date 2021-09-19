<?php

class ExRelease {

    // base info
    public $id = 0;
    public $label_id = 0;
    public $label_name = '';
    public $label_display = '';
    public $release_type_id = 0;
    public $release_type = '';
    public $catalog_no = '';
    public $title = '';
    public $artist = '';
    public $display_title = '';
    public $display_artist = '';
    public $various_artists = FALSE;
    public $media = '';
    public $year_recorded = '';
    public $year_released = '';
    public $image_file = '';

    // other stuff
    public $artist_list = array();
    public $article_list = array();

    // for doing updates
    public $artist_adds = array();
    public $artist_deletes = array();
    public $home_artist_slug = '';

    public function full_display()
    {
        if ( $this->id ) {
            return $this->display_artist . ' &mdash; ' . $this->display_title;
        }
        else {
            return '(Artist) &mdash; (Title)'; 
        }
    }

    public function clear_artist_list()
    {
        $this->artist_list = array();
    }

    public function process_post_values($post_values)
    {
        $update_values = array();
        if ( $post_values['artist-slug'] ) {
            $this->home_artist_slug = $post_values['artist-slug'];
        }
        if ( $post_values['artist-display'] ) {
            if ( $post_values['artist-display'] != $this->display_artist ) {
                $update_values['display_artist'] = $post_values['artist-display'];
            }
        }
        if ( $post_values['artist-name'] ) {
            if ( $post_values['artist-name'] != $this->artist ) {
                $update_values['artist'] = $post_values['artist-name'];
            }
        }
        if ( $post_values['release-display'] ) {
            if ( $post_values['release-display'] != $this->display_title ) {
                $update_values['display_title'] = $post_values['release-display'];
            }
        }
        if ( $post_values['release-title'] ) {
            if ( $post_values['release-title'] != $this->title ) {
                $update_values['title'] = $post_values['release-title'];
            }
        }
        if ( $post_values['release-label'] ) {
            if ( $post_values['release-label'] != $this->label_id ) {
                $update_values['label_id'] = $post_values['release-label'];
            }
        }
        if ( $post_values['release-catalog'] ) {
            if ( $post_values['release-catalog'] != $this->catalog_no ) {
                $update_values['catalog_no'] = $post_values['release-catalog'];
            }
        }
        if ( $post_values['release-image'] ) {
            if ( $post_values['release-image'] != $this->image_file ) {
                $update_values['image_file'] = $post_values['release-image'];
            }
        }
        if ( ( $this->image_file == '' ) && ( ! $update_values['image_file'] ) ) {
            $update_values['image_file'] = 'noimage.png';
        }
        if ( $post_values['release-type'] ) {
            if ( $post_values['release-type'] != $this->release_type_id ) {
                $update_values['release_type_id'] = $post_values['release-type'];
            }
        }
        if ( $post_values['release-media'] ) {
            if ( $post_values['release-media'] != $this->media ) {
                $update_values['media'] = $post_values['release-media'];
            }
        }
        if ( $post_values['release-recorded'] ) {
            if ( $post_values['release-recorded'] != $this->year_recorded ) {
                $update_values['year_recorded'] = $post_values['release-recorded'];
            }
        }
        if ( $post_values['release-released'] ) {
            if ( $post_values['release-released'] != $this->year_released ) {
                $update_values['year_released'] = $post_values['release-released'];
            }
        }
        if ( array_key_exists('release-various', $post_values) ) {
            if ( $post_values['release-various'] == 'various_artists' ) {
                if ( ! $this->various_artists ) {
                    $update_values['various_artists'] = '0';
                }
            }
            else {
                if ( $this->various_artists ) {
                    $update_values['various_artists'] = '1';
                }
            }
        }
        if ( isset($post_values['release-artists']) ) {
            $update_values['related_artists'] = $this->get_artist_list_diff($post_values['release-artists']);
        }
        else {
            $update_values['related_artists'] = 'clear';
        }
        return $update_values;
    }

    public function get_artist_list_diff($new_list)
    {
        $result = array();
        $this->artist_adds = array();
        $this->artist_deletes = array_keys($this->artist_list);
        foreach ($new_list as $new_artist) {
            if ( array_key_exists($new_artist, $this->artist_list) ) {
                unset($this->artist_deletes[$new_artist]);
            }
            else {
                $this->artist_adds[] = $new_artist;
            }
        }
        foreach ($this->artist_adds as $item) {
            $result[$item] = 'insert';
        }
        foreach ($this->artist_deletes as $item) {
            $result[$item] = ' delete';
        }
        return $result;
    }

    public function guess_primary_artist()
    {
        $first_id = 0;
        foreach ($this->artist_list as $id => $artist) {
            if ( $first_id == 0 ) {
                $first_id = $id;
            }
            if ( $artist->display == $this->display_artist ) {
                return $artist;
            }
        }
        if ( $first_id ) {
            return $this->artist_list[$first_id];
        }
        return NULL;
    }

}
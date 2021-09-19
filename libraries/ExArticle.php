<?php

class ExArticle {

    // tracking & debugging
    public $trace = '';

    // main fields
    public $id = 0;
    public $slug = '';
    public $title = '';
    public $intro = '';
    public $front_page = FALSE;
    public $issue_no = 0;
    public $category_id = 0;
    public $category_name = '';
    public $category_slug = '';
    public $category_title = '';
    public $image_file = '';
    public $body = '';
    public $published_on = '';
    public $status = 'draft';

    // other into
    public $artist_list = array();
    public $topic_list = array();
    public $credit_list = array('1' => array(), '2' => array());
    public $link_list = array();
    public $release_list = array();

    // for processing update
    public $update_values = array();
    public $artist_adds = array();
    public $artist_deletes = array();

    function __construct($category_id = 0)
    {
//        $this->category_id = $category_id;
    }

    public function clear_credit_list()
    {
        $this->credit_list = array('1' => array(), '2' => array());
    }

    public function clear_topic_list()
    {
        $this->topic_list = array();
    }

    public function clear_artist_list()
    {
        $this->artist_list = array();
    }

    public function clear_release_list()
    {
        $this->release_list = array();
    }

    public function clear_link_list()
    {
        $this->link_list = array();
    }

    public function is_review()
    {
        return ( $this->category_id == 1 );
    }

    public function is_media()
    {
        return ( $this->category_id == 8 );
    }

    public function has_intro()
    {
        switch ($this->category_id) {
            case '1' : // review
                return FALSE;
                break;
            case '2' : // news
                return TRUE;
                break;
            case '4' : // feature
                return TRUE;
                break;
            case '5' : // faq
                return TRUE;
                break;
            case '8' : // media
                return TRUE;
                break;
        }
    }

    public function has_cover_image()
    {
        switch ($this->category_id) {
            case '1' : // review
                return FALSE;
                break;
            case '2' : // news
                return TRUE;
                break;
            case '4' : // feature
                return TRUE;
                break;
            case '5' : // faq
                return FALSE;
                break;
            case '8' : // media
                return FALSE;
                break;
        }
    }

    public function has_photographer()
    {
        switch ($this->category_id) {
            case '1' : // review
                return FALSE;
                break;
            case '2' : // news
                return TRUE;
                break;
            case '4' : // feature
                return TRUE;
                break;
            case '5' : // faq
                return FALSE;
                break;
            case '8' : // media
                return FALSE;
                break;
        }
    }

    public function has_releases()
    {
        if ( $this->category_id == 1 ) {
            return TRUE;
        }
        return FALSE;
    }

    public function set_image_path()
    {
        if ( $this->category_id == 4 ) { // feature
            $this->image_file = 'features/' . $this->image_file;
        }
    }

    public function list_display()
    {
        if ( ( $this->is_review() ) && ( ! $this->image_file) ) {
            $this->trace .= 'no image assigned to review<br/>';
            if ( ! $this->intro ) {
                $this->intro = smart_trim($this->body, 200);
            }
            unset($this->body);
        }
        elseif ( $this->category_id == 4 ) { // feature
            $this->image_file = 'features/' . $this->image_file;
        }
        elseif ( $this->category_id == 2 ) { // news
            $this->intro = $this->body;
        }
        elseif ( $this->category_id == 8 ) { // media
            $this->intro = $this->body;
            $this->image_file = '';
        }
    }

    public function link_display()
    {
        return implode(';', $this->link_list);
    }

    public function release_list_display()
    {
        return implode(';', array_keys($this->release_list));
    }

    public function parse_links($link_display = '')
    {
        $this->clear_link_list();
        $temp = explode(';', $link_display);
        foreach ($temp as &$link) {
            $link = trim($link);
            $link = preg_replace('#^https?://#', '', rtrim($link, '/'));
            $this->link_list[] = $link;
        }
    }

    public function user_can_edit($user_id)
    {
        if ( $this->status == 'live' ) {
            return FALSE;
        }
        else {
            foreach ($this->credit_list['1'] as $id => $user) {
                if ( $id == $user_id ) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function author_count()
    {
        return count($this->credit_list['1']);
    }

    public function photographer_count()
    {
        return count($this->credit_list['2']);
    }

    public function process_post_values($post_values, $user_group)
    {
        $result = 0;
        $this->update_values = array();
        if ( $post_values['article-slug'] ) {
            if ( $post_values['article-slug'] != $this->slug ) {
                $this->update_values['slug'] = $post_values['article-slug'];
                $result++;
            }
        }
        if ( $post_values['article-category'] ) {
            if ( $post_values['article-category'] != $this->category_id ) {
                $this->update_values['category_id'] = $post_values['article-category'];
                $result++;
            }
        }
        if ( $post_values['article-title'] ) {
            if ( $post_values['article-title'] != $this->title ) {
                $this->update_values['title'] = $post_values['article-title'];
                $result++;
            }
        }
        if ( $post_values['article-intro'] ) {
            if ( $post_values['article-intro'] != $this->intro ) {
                $this->update_values['intro'] = $post_values['article-intro'];
                $result++;
            }
        }
        else {
            $this->update_values['intro'] = '';
        }
        if ( $post_values['article-body'] ) {
            if ( $post_values['article-body'] != $this->body ) {
                $this->update_values['body'] = $post_values['article-body'];
                $result++;
            }
        }
        if ( $post_values['article-issue'] ) {
            if ( $post_values['article-issue'] != $this->issue_no ) {
                $this->update_values['issue_no'] = $post_values['article-issue'];
                $result++;
            }
        }
        $this->update_values['release_list'] = array();
        if ( $post_values['release_id'] ) {
            $result += $this->add_single_release($post_values['release_id']);
        }
        if ( $user_group == 'admin' ) {
            if ( $post_values['article-status'] ) {
                if ( $post_values['article-status'] != $this->status ) {
                    $this->update_values['status'] = $post_values['article-status'];
                    $result++;
                }
            }
            if ( $post_values['article-published'] ) {
                if ( $post_values['article-published'] != $this->published_on ) {
                    $this->update_values['published_on'] = $post_values['article-published'];
                    $result++;
                }
            }
        }
        else { // not admin
            if ( $post_values['article-submit'] ) {
                if ( $this->status != 'submitted' ) {
                    $this->update_values['status'] = 'submitted';
                    $result++;
                }
            }
            else {
                if ( $this->status != 'draft' ) {
                    $this->update_values['status'] = 'draft';
                    $result++;
                }
            }
        }
        $result += $this->process_artist_list_update($post_values['article-artists']);
        $result += $this->process_credit_list_update($post_values);
        $result += $this->process_topic_list_update($post_values['article-topics']);
        $result += $this->process_link_list_update($post_values);
        $result += $this->process_release_list_update($post_values);
        return $result;
    }

    private function add_single_release($release_id)
    {
        $result = 0;
        $this->update_values['release_list'] = array(
            $release_id => 'insert'
        );
        return $result;
    }

    private function process_artist_list_update($post_values)
    {
        $this->trace .= 'process artist list' . PHP_EOL;
        $this->update_values['related_artists'] = array();
        $this->artist_adds = array();
        $this->artist_deletes = array_keys($this->artist_list);
        $this->trace .= 'initial delete list = [' . print_r($this->artist_deletes, TRUE) . '] ' . PHP_EOL;
        foreach ($post_values as $new_artist) {
            $this->trace .= ' - checking (' . $new_artist . ') ' . PHP_EOL;
            if ( ($key = array_search($new_artist, $this->artist_deletes)) !== FALSE ) {
                unset($this->artist_deletes[$key]);
                $this->trace .= ' -- already on article, delete list = [' . print_r($this->artist_deletes, TRUE) . '] ' . PHP_EOL;
            }
            else {
                $this->artist_adds[] = $new_artist;
                $this->trace .= ' -- not on article, add list = [' . print_r($this->artist_adds, TRUE) . '] ' . PHP_EOL;
            }
        }
        $this->trace .= ' add list: [' . print_r($this->artist_adds, TRUE) . '] ' . PHP_EOL;
        foreach ($this->artist_adds as $item) {
            $this->update_values['related_artists'][$item] = 'insert';
        }
        $this->trace .= 'delete list: [' . print_r($this->artist_deletes, TRUE) . '] ' . PHP_EOL;
        foreach ($this->artist_deletes as $item) {
            $this->update_values['related_artists'][$item] = 'delete';
        }
        return count($this->update_values['related_artists']);
    }

    private function process_topic_list_update($post_values)
    {
        $this->update_values['topics'] = array();
        $topic_adds = array();
        $topic_deletes = array_keys($this->topic_list);
        foreach ($post_values as $new_topic) {
            if ( ($key = array_search($new_topic, $topic_deletes)) !== FALSE ) {
                unset($topic_deletes[$key]);
            }
            else {
                $topic_adds[] = $new_topic;
            }
            foreach ($topic_adds as $item) {
                $this->update_values['topics'][$item] = 'insert'; 
            }
            foreach ($topic_deletes as $item) {
                $this->update_values['topics'][$item] = 'delete';
            }
        }
        return count($this->update_values['topics']);
    }

    private function process_credit_list_update($post_values)
    {
        $this->update_values['credits'] = array();
        $credit_adds = array();
        $credit_deletes = array_keys($this->credit_list['1']);
        foreach ($post_values['article-authors'] as $new_item) {
            if ( ($key = array_search($new_item, $credit_deletes)) !== FALSE ) {
                unset($credit_deletes[$key]);
            }
            else {
                $credit_adds[] = $new_item;
            }
            foreach ($credit_adds as $item) {
                $this->update_values['credits']['1'][$item] = 'insert'; 
            }
            foreach ($credit_deletes as $item) {
                $this->update_values['credits']['1'][$item] = 'delete';
            }
        }
        if ( isset($post_values['article-photog']) ) {
            $credit_adds = array();
            $credit_deletes = array_keys($this->credit_list['2']);
            foreach ($post_values['article-photog'] as $new_item) {
                if ( ($key = array_search($new_item, $credit_deletes)) !== FALSE ) {
                    unset($credit_deletes[$key]);
                }
                else {
                    $credit_adds[] = $new_topic;
                }
                foreach ($credit_adds as $item) {
                    $this->update_values['credits']['2'][$item] = 'insert'; 
                }
                foreach ($credit_deletes as $item) {
                    $this->update_values['credits']['2'][$item] = 'delete';
                }
            }
        }
        return count($this->update_values['credits']);
    }

    private function process_link_list_update($post_values)
    {
        $this->update_values['links'] = array();
        if ( $post_values['article-links'] ) {
            $this->update_values['links'] = explode(';', $post_values['article-links']);
        }
        return count($this->update_values['links']);
    }

    private function process_release_list_update($post_values)
    {
        $this->update_values['release_list'] = array();
        if ( $post_values['article-releases'] ) {
            $release_list = explode(';', $post_values['article-releases']);
            foreach ($release_list as $id) {
                if ( ! array_key_exists($id, $this->release_list) ) {
                    $this->update_values['release_list'][$id] = 'insert';
                }
            }
        }
        return count($this->update_values['release_list']);
    }

}
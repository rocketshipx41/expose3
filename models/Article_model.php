<?php  
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Article Model
 * 
 * Author: Jon Davis
 * 
*/

class Article_model extends CI_Model
{
    public $trace = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    	$this->trace .= '>> construct article model<br/>';
        $this->trace .= $this->db->version();
//        $this->trace .= print_r($this->db, TRUE);
    }
   
    function most_recent($category = '', $max = 5, $offset = 0, $include_carousel = TRUE)
    {
    	$this->trace .= 'most_recent<br/>';
        $result = array();
        $this->db->select('id, slug, title, intro, category_id, '
                    . 'image_file, body, updated_on, published_on')
                ->from('expose_exposeorg4325340.article_list')
                ->order_by('published_on', 'desc')
                ->where('status', 'live')
                ->where('published_on <= CURDATE()');
        if ($category != '') {
            $this->db->where('category_slug', $category);
        }
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        if ( ! $include_carousel ) {
            $this->db->where('front_page', '0');
        }
        $query = $this->db->get();
    	$this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
//        echo $this->trace; exit;
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $article =  $query->custom_row_object($i++, 'ExArticle');
            if ( $article->is_review() ) {
                $this->get_main_image($article);
                $article->intro = smart_trim($article->body, 200);
            }
            else {
                $article->set_image_path();
            }
            $this->get_credits($article);
            $result[] = $article;
        }
//        echo '<pre>' . print_r($result) . '</pre>'; exit;
        return $result;
    }
    
    function articles_by_status($status = 'live', $category = 'any', $max = 0, $offset = 0)
    {
    	$this->trace .= 'most_recent<br/>';
        $result = array();
        $this->db->select('id, slug, title, intro, category_id, '
                    . 'image_file, body, updated_on, published_on')
                ->from('expose_exposeorg4325340.article_list')
            ->order_by('published_on', 'desc')
            ->where('status', $status);
        if ( $status == 'live' ) {
            $this->db->where('published_on <= CURDATE()');
        }
        if ( $category != 'any' ) {
            $this->db->where('category_slug', $category);
        }
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        $query = $this->db->get();
    	$this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
//        echo $this->trace; exit;
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $article =  $query->custom_row_object($i++, 'ExArticle');
            if ( $article->is_review() ) {
                $this->get_main_image($article);
                $article->intro = smart_trim($article->body, 200);
            }
            else {
                $article->set_image_path();
            }
            $this->get_credits($article);
            $result[] = $article;
        }
//        echo '<pre>' . print_r($result) . '</pre>'; exit;
        return $result;
    }

    public function get_most_recent_updates()
    {
        $this->trace .= 'get_search_results<br/>';
        $result = array();
        $this->db->select('skey, result_type, slug, display, name, extra, image_path, url, updated')
            ->from('expose_exposeorg4325340.all_search')
            ->order_by('updated', 'desc')
            ->limit(20, 0);
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
//        echo $this->trace; exit;
        foreach ($query->result() as $row) {
            $result[$row->skey] = $row;
        }
        return $result;
    }
    
    function get_artist_article_list($artist)
    {
	    $this->trace .= 'get_artist_article_list<br/>';
        if ( $artist->id ) {
            $artist->clear_article_list();
            $this->db->select('title, slug, category_id, category_name, '
                        . 'published_on, article_id id')
                    ->from('artist_articles')
                    ->where('artist_id', $artist->id)
                    ->where('status', 'live')
                    ->where('published_on <= CURDATE()')
                    ->order_by('published_on', 'desc');
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $qresult = $query->result();
            $i = 0;
            foreach ($qresult as $row) {
                $article =  $query->custom_row_object($i++, 'ExArticle');
                $this->get_credits($article);
                $artist->article_list[$article->id] = $article;
            }
        }
    }
    
    function get_label_article_list($id = 0)
    {
	    $this->trace .= 'get_label_article_list<br/>';
        $result = array();
        if ($id > 0) {
            $this->db->select('title, slug, category_id, category_name, '
                        . 'published_on, article_id id')
                    ->from('release_articles')
                    ->where('label_id', $id)
                    ->where('status', 'live')
                    ->where('published_on <= CURDATE()')
                    ->order_by('published_on', 'desc');
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $qresult = $query->result();
            $i = 0;
            foreach ($qresult as $row) {
                $article =  $query->custom_row_object($i++, 'ExArticle');
                if ( $article->is_review() ) {
                    $this->get_main_image($article);
                    $article->intro = smart_trim($article->body, 200);
                }
                else {
                    $article->set_image_path();
                }
                $this->get_credits($article);
                $result[] = $article;
            }
        }
        return $result;
    }
    
    function get_author_article_list($user_id = 0, $status = 'live', $include_full = FALSE)
    {
	    $this->trace .= 'get_artist_article_list<br/>';
        $result = array();
        if ( $include_full ) {
            $field_list = 'article_id id, slug, title, category_id, '
                . 'published_on, category_name, image_file, intro, body';
        }
        else {
            $field_list = 'article_id id, slug, title, category_id, '
                . 'published_on, category_name';
        }
        if ($user_id > 0) {
            $this->db->select($field_list)
                    ->from('person_articles')
                    ->where('user_id', $user_id)
                    ->where('status', $status)
                    ->order_by('published_on', 'desc');
            if ( $status == 'live' ) {
                $this->db->where('published_on <= CURDATE()');
            }
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $qresult = $query->result();
            $i = 0;
            foreach ($qresult as $row) {
                $article =  $query->custom_row_object($i++, 'ExArticle');
                if ( $article->is_review() ) {
                    $this->get_main_image($article);
                    $article->intro = smart_trim($article->body, 200);
                }
                else {
                    $article->set_image_path();
                }
                $this->get_credits($article);
                $result[] = $article;
            }
        }
        return $result;
    }
    
    function get_count($category = '')
    {
        $this->trace .= 'get_count()<br/>';
        $result = array();
        $this->db->select('category_id, category_title, slug,  acount')
                ->from('expose_exposeorg4325340.article_count');
        if ($category != '') {
            $this->db->where('slug', $category);
        }
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->row();
        $qresult = $query->result();
        foreach ($qresult as $row) {
            $result[$row->slug] = $row;
        }
        return $result;
    }

    function get_topic_count($topic = '')
    {
        $this->trace .= 'get_topic_count()<br/>';
        $result = array();
        $this->db->select('topic_id, topic_title, slug,  acount')
            ->from('expose_exposeorg4325340.topic_count');
        if ($topic != '') {
            $this->db->where('slug', $topic);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->row();
        $qresult = $query->result();
        foreach ($qresult as $row) {
            $result[$row->slug] = $row;
        }
        return $result;
    }

    function get_release_year_count($year = '')
    {
        $this->trace .= 'get_release_year_count()<br/>';
        $result = array();
        $this->db->select('year_released, acount')
            ->from('expose_exposeorg4325340.release_year_count')
            ->order_by('year_released');
        if ( $year != '' ) {
            $this->db->where('year_released', $year);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->row();
        $qresult = $query->result();
        foreach ($qresult as $row) {
            $result[$row->year_released] = $row;
        }
        return $result;
    }

    function get_recorded_year_count($year = '')
    {
        $this->trace .= 'get_recorded_year_count()<br/>';
        $result = array();
        $this->db->select('year_recorded, acount')
            ->from('expose_exposeorg4325340.recorded_year_count')
            ->order_by('year_recorded');
        if ( $year != '' ) {
            $this->db->where('year_recorded', $year);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->row();
        $qresult = $query->result();
        foreach ($qresult as $row) {
            $result[$row->year_recorded] = $row;
        }
        return $result;
    }

    function get_future_dated()
    {
     	$this->trace .= 'get_future_dated<br/>';
        $result = array();
        $this->db->select('id, slug, title, intro, category_id, '
                    . 'image_file, body, updated_on, published_on')
                ->from('expose_exposeorg4325340.article_list')
                ->order_by('published_on', 'asc')
                ->where('status', 'live')
                ->where('published_on > CURDATE()');
        $query = $this->db->get();
    	$this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
//        echo $this->trace; exit;
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $article =  $query->custom_row_object($i++, 'ExArticle');
            if ( $article->is_review() ) {
                $this->get_main_image($article);
                $article->intro = smart_trim($article->body, 200);
            }
            else {
                $article->set_image_path();
            }
            $this->get_credits($article);
            $result[] = $article;
        }
        return $result;
    }
    
    function get_array_items($id_list = array())
    {
	$this->trace .= 'get_array_items<br/>';
        $result = array();
        $this->db->select('a.id, a.slug, a.title, intro, a.category_id, '
                    . 'a.image_file, a.body, a.updated_on, a.published_on')
                ->from('articles a')
		->join('categories c', 'c.id = a.category_id', 'left')
                ->where('status', 'live')
                ->where('a.published_on <= CURDATE()')
                ->where_in('a.id', $id_list);
        $query = $this->db->get();
	$this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->result_array();
        return $query_result;
    }
    
    function get_random_index($category, $last_issue = 0)
    {
	$this->trace .= 'get_random_index(' . $category . ')<br/>';
        $result = array();
        $this->db->select('a.id')
                ->from('articles a')
                ->where('status', 'live')
                ->where('a.published_on <= CURDATE()');
        if ( $category ) {
            $this->db->where('c.slug', $category)
                    ->join('categories c', 'c.id = a.category_id', 'left');
        }
        if ( $last_issue ) {
            $this->db->where('a.issue_no <=', $last_issue);
            $this->db->where('a.issue_no >', 0);
        }
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->result_array();
        
        return $query_result;
    }
    
    function get_random($category, $max = 1, $date = 0)
    {
	    $this->trace .= 'get_random<br/>';
        $result = array();
        $this->db->select('a.id, a.slug, a.title, intro, a.category_id, '
                    . 'a.image_file, a.body, a.updated_on, a.published_on')
                ->from('articles a')
		->join('categories c', 'c.id = a.category_id', 'left')
                ->limit($max * 5)
                ->where('status', 'live')
                ->where('a.published_on <= CURDATE()')
                ->where('a.id >= (SELECT FLOOR( MAX(id) * RAND()) FROM `articles` )');
        if ( $category ) {
            $this->db->where('c.slug', $category);
        }
        if ( $date ) {
            $this->db->where('a.issue_no <=', $date);
        }
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->result_array();
        $random_items = array_rand($query_result, $max);
        if ( ENVIRONMENT == 'development' ) {
            $this->trace .= 'random items: ' . print_r($random_items, TRUE) . '<br/>';
        }
        if ( $max == 1 ) {
            $result[] = $query_result[$random_items];
        }
        else {
            foreach ( $random_items as $item ) {
                $result[] = $query_result[$item];
            }
        }
        //echo print_r($result); exit;
        return $result;
    }

    public function get_issue_details($issue_no = '0', $get_articles = FALSE, $max = 0, $offset = 0)
    {
        $this->trace .= 'get_issue_articles<br/>';
        $this->db->select('id, description, pub_date, pages, blurb, acount')
            ->from('expose_exposeorg4325340.issue_details');
        if ( $issue_no ) {
            $this->db->where('id', $issue_no);
        }
        else {
            $result = array();
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        $qresult = $query->result();
        if ( $issue_no ) { // only one result
            $result = $query->custom_row_object(0, 'ExIssue');
            if ( $get_articles ) {
                $this->get_issue_articles($result, TRUE, $max, $offset);
            }
        }
        else { // return list of issues
            $i = 0;
            foreach ($qresult as $row) {
                $issue = $query->custom_row_object($i++, 'ExIssue');
                $this->get_issue_articles($issue, FALSE);
                $result[$issue->id] = $issue;
            }
        }
        return $result;
    }
    
    function get_issue_articles($issue, $get_extra = TRUE, $max = 0, $offset = 0)
    {
	    $this->trace .= 'get_issue_articles<br/>';
        $issue->clear_contents();
        $this->db->select('id, slug, title, intro, category_id, issue_no, '
                    . 'image_file, body, updated_on, published_on')
                ->from('expose_exposeorg4325340.article_list')
                ->order_by('category_id', 'desc')
                ->order_by('title', 'asc')
                ->where('status', 'live')
                ->where('published_on <= CURDATE()');
        if ( $issue->id ) {
            $this->db->where('issue_no', $issue->id);
        }
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $article =  $query->custom_row_object($i++, 'ExArticle');
            if ( $get_extra ) {
                if ($article->is_review()) {
                    $this->get_main_image($article);
                    $article->intro = smart_trim($article->body, 200);
                } else {
                    $article->set_image_path();
                }
                $this->get_credits($article);
            }
            $issue->contents[$article->id] = $article;
        }
    }
    
    function get_front_page()
    {
	$this->trace .= 'get_front_page<br/>';
        $this->db->select('a.id, a.title, a.intro, a.slug, a.image_file')
                ->from('articles a')
                ->where('a.front_page', '1')
                ->where('status', 'live')
                ->where('a.published_on <= CURDATE()');
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $result = $query->result_array();
        // process?
        return $result;
    }
    
    function get_full($slug = '', $id = 0)
    {
	    $this->trace .= 'get_full<br/>';
        if ( ( $slug == '' ) || ( $slug == '0' ) ) {
            $result = new ExArticle();
            $this->trace .= 'initialize new article<br>';
        }
        else {
            $this->db->select('id, title, intro, body, status, '
                        . 'category_id, slug, category_name item_name, category_title category_name, '
                        . 'issue_no, category_slug, image_file, published_on, '
                        . 'front_page')
                ->from('expose_exposeorg4325340.article_list');
            if ( $slug != '' ) {
                $this->db->where('slug', $slug);
            }
            else {
                $this->db->where('a.id', $id);
            }
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
//            echo $this->trace; exit;
            $result = $query->custom_row_object(0, 'ExArticle');
            if ( ! $result ) {
                $result = new ExArticle();
                $this->trace .= 'article not found<br>';
            }
        }
//	    echo $this->trace; exit;
        return $result;
    }
    
    function get_credits($article)
    {
        $this->trace .= 'get_credits<br/>';
        if ( $article->id ) {
            $article->clear_credit_list();
            $this->db->select('user_id, role_id, username, display_name')
                ->from('expose_exposeorg4325340.article_credits')
                ->where('article_id', $article->id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            foreach ($query->result() as $row) {
                $article->credit_list[$row->role_id][$row->user_id] = $row->display_name;
            }
        }
    }
    
    function get_meta($article)
    {
        $this->trace .= 'get_meta<br/>';
        $result = array(
            'has_author' => FALSE,
            'has_photographer' => FALSE,
            'has_illustrator' => FALSE
        );
        if ( $article->id ) {
            $this->db->select('aur.role_id')
                ->from('article_user_role aur')
                ->where('aur.article_id', $article->id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            foreach ($query->result() as $row) {
                if ($row->role_id == 1) {
                    $result['has_author'] = TRUE;
                }
                elseif ($row->role_id == 2) {
                    $result['has_photographer'] = TRUE;
                }
                elseif ($row->role_id == 3) {
                    $result['has_illustrator'] = TRUE;
                }
            }
        }
        return $result;
    }
    
    function get_artists($article)
    {
    	$this->trace .= 'get_artists<br/>';
        if ( $article->id ) {
            $article->clear_artist_list();
            $this->db->select('aa.artist_id, a.display, a.slug')
                ->from('article_artist aa')
                ->join('artists a', 'a.id = aa.artist_id', 'left')
                ->where('aa.article_id', $article->id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            foreach ($query->result() as $row) {
                if ( $row->display ) {
                    $artist = new ExArtist();
                    $artist->id = $row->artist_id;
                    $artist->slug = $row->slug;
                    $artist->display = $row->display;
                    $article->artist_list[$row->artist_id] = $artist;
                }
            }
        }
    }
    
    function get_topics($article)
    {
        $this->trace .= 'get_topics<br/>';
        if ( $article->id ) {
            $article->clear_topic_list();
            $this->db->select('topic_id, topic_title, topic_slug')
                ->from('article_topic_list')
                ->where('article_id', $article->id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            foreach ($query->result() as $row) {
                $article->topic_list[$row->topic_id] = $row;
            }
        }
    }
    
    function get_topic_list()
    {
        $this->trace .= 'get_topic_list<br/>';
        $result = array();
        $this->db->select('id, slug, title')
            ->from('topics')
            ->order_by('title');
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        foreach ($query->result() as $row) {
            if ($row->title) {
                $result[$row->id] = array(
                        'title' => $row->title,
                        'slug' => $row->slug
                    );
            }
        }
        return $result;
    }
    
    function get_topic_select_list()
    {
        $this->trace .= 'get_topic_list<br/>';
        $result = array();
        $this->db->select('id, title')
            ->from('topics')
            ->order_by('title');
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        foreach ($query->result() as $row) {
            if ($row->title) {
                $result[$row->id] = $row->title;
            }
        }
        return $result;
    }
    
    function get_category_list()
    {
	    $this->trace .= 'get_category_list<br/>';
	    $result = array();
	    $this->db->select('id, title')
	    	->from('categories')
	    	->order_by('title');
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
	    foreach ($query->result() as $row) {
	        if ($row->title) {
	        	$result[$row->id] = $row->title;
	        }
	    }
	    return $result;
    }

    function get_category_id($category_slug)
    {
	    $this->trace .= 'get_category_list<br/>';
	    $result = 0;
	    $this->db->select('id')
	    	->from('categories')
	    	->where('slug', $category_slug);
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
	    foreach ($query->result() as $row) {
	        if ($row->id) {
	        	return $row->id;
	        }
	    }
	    return $result;
    }

    function get_issue_select_list()
    {
	    $this->trace .= 'get_issue_select_list<br/>';
	    $result = array(
            '0' => 'Select...'
        );
	    $this->db->select('id, description')
	    	->from('issues')
	    	->order_by('id');
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $issue =  $query->custom_row_object($i++, 'ExIssue');
            $result[$issue->id] = 'Issue ' . $issue->id . ' (' . $row->description . ')';
	    }
	    return $result;
    }
    
    function get_link_list($article)
    {
        $this->trace .= 'get_link_list<br/>';
        if ( $article->id ) {
            $article->clear_link_list();
            $this->db->select('article_link_id, link')
                ->from('article_links')
                ->where('article_id', $article->id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            foreach ($query->result() as $row) {
                if ( $row->link ) {
                    $article->link_list[$row->article_link_id] = $row->link;
                }
            }
        }
    }
    
    function get_main_image($article)
    {
	    $this->trace .= 'get_main_image<br/>';
        if ( $article->id ) {
            $result = '';
            $this->db->select('r.image_file')
                    ->from('expose_exposeorg4325340.article_release ar')
                    ->join('releases r', 'r.id = ar.release_id', 'left')
                    ->where('ar.article_id', $article->id)
                    ->limit(1);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            $query_result = $query->result_array();
            if ( count($query_result) ) {
                $article->image_file = 'releases/' . $query_result[0]['image_file'];
            }
        }
    }
    
    function get_releases($article)
    {
	    $this->trace .= 'get_releases<br/>';
        if ( $article->id ) {
            $article->clear_release_list();
            $this->db->select('ar.release_id id, r.display_title, r.display_artist, '
                        . 'r.media, r.media_count, r.year_released, r.image_file, '
                        . 'r.catalog_no, r.year_recorded, r.label_id, l.display label_name')
                ->from('expose_exposeorg4325340.article_release ar')
                ->join('expose_exposeorg4325340.releases r', 'r.id = ar.release_id', 'left')
                ->join('labels l', 'l.id = r.label_id', 'left')
                ->where('ar.article_id', $article->id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            $query_result = $query->result();
            $i = 0;
            foreach ($query_result as $row) {
                $release =  $query->custom_row_object($i++, 'ExRelease');
                $article->release_list[$release->id] = $release;
            }
        }
    }
    
    function update_info($article)
    {
    	$this->trace = 'update_info<br/>';
        $params = $article->update_values;
        $result = array('status' => 'ok');
        $related_artists = $params['related_artists'];
        unset($params['related_artists']);
        $credit_list = $params['credits'];
        unset($params['credits']);
        $topic_list = $params['topics'];
        unset($params['topics']);
        $link_list = $params['links'];
        unset($params['links']);
        $release_list = $params['release_list'];
        unset($params['release_list']);
	    $this->db->trans_start();
        $article_id = 0;
	    if ( $article->id == 0 ) {
            $this->trace .= 'new article, insert' . "<br/>\n";
            $slug_src = $params['title'];
    	    $this->db->insert('articles', $params);
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            $article->id = $this->db->insert_id();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            $this->trace .= 'new id is: ' . $article_id . "<br/>\n";
        }
        else {
    	    $this->db->where('id', $article->id);
            $this->db->update('articles', $params);
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
//            echo $this->trace; exit;
	    }
        // credits
        $this->update_credits($article, $credit_list);
        // artists
        $this->update_artists($article, $related_artists);
        // topics
        $this->update_topics($article, $topic_list);
        // links
        $this->update_links($article, $link_list);
        // releases
        $this->update_releases($article, $release_list);
        // and done
	    $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            $result['status'] = 'error';
        } 
        return $result;
    }

    public function update_credits($article, $update_list)
    {
    	$this->trace .= 'update_credits<br/>';
        $this->trace .= 'credit_list: ' . print_r($update_list, TRUE) . '<br>';
        if ( count($update_list) ) {
            foreach ($update_list as $role_id => $user) {
                foreach ($user as $user_id => $action) {
                    $this->trace .= 'working on ' . $user_id . '/' . $role_id . ' (' . $action . ')<br>';
//                    echo $this->trace; exit;
                    if ( $action == 'insert' ) {
                        $data = array(
                            'article_id' => $article->id,
                            'user_id' => $user_id,
                            'role_id' => $role_id
                        );
                        $this->db->insert('article_user_role', $data);
                        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                        $this->trace .= 'add new author ' . "<br/>\n";
                    }
                    elseif ( $action == 'delete' ) {
                        $this->trace .= 'delete it!<br>';
                        $this->db->where('article_id', $article->id)
                                ->where('user_id', $user_id)
                                ->where('role_id', $role_id);
                        $this->db->delete('article_user_role');
                        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                        $this->trace .= 'delete author ' . "<br/>\n";
                    }
                }
            }
        }
        else {
            $this->trace .= 'no changes in credit list ' . "<br/>\n";
        }
    }

    public function update_artists($article, $update_list)
    {
    	$this->trace .= 'update_artists<br/>';
        $this->trace .= 'related_artists: ' . print_r($update_list, TRUE) . '<br>';
        if ( count($update_list) ) {
            foreach ($update_list as $id => $action) {
                $this->trace .= 'working on ' . $id . ' (' . $action . ')<br>';
                if ( $action == 'insert' ) {
                    $data = array(
                        'article_id' => $article->id,
                        'artist_id' => $id
                    );
                    $this->db->insert('article_artist', $data);
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'add new artist ' . "<br/>\n";
                }
                elseif ( $action == 'delete' ) {
                    $this->trace .= 'delete it!<br>';
                    $this->db->where('article_id', $article->id)
                            ->where('artist_id', $id);
                    $this->db->delete('article_artist');
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'delete artist ' . "<br/>\n";
                }
            }
        }
        else {
            $this->trace .= 'no changes in artist list ' . "<br/>\n";
        }
    }

    public function update_topics($article, $update_list)
    {
    	$this->trace .= 'update_topics<br/>';
        $this->trace .= 'topics: ' . print_r($update_list, TRUE) . '<br>';
        if ( count($update_list) ) {
            foreach ($update_list as $id => $action) {
                $this->trace .= 'working on ' . $id . ' (' . $action . ')<br>';
                if ( $action == 'insert' ) {
                    $data = array(
                        'article_id' => $article->id,
                        'topic_id' => $id
                    );
                    $this->db->insert('article_topic', $data);
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'add new topic ' . "<br/>\n";
                }
                elseif ( $action == 'delete' ) {
                    $this->trace .= 'delete it!<br>';
                    $this->db->where('article_id', $article->id)
                            ->where('topic_id', $id);
                    $this->db->delete('article_topic');
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'delete topic ' . "<br/>\n";
                }
            }
        }
        else {
            $this->trace .= 'no changes in topic list ' . "<br/>\n";
        }
    }

    public function update_links($article, $update_list)
    {
    	$this->trace .= 'update_links<br/>';
        $this->trace .= 'links: ' . print_r($update_list, TRUE) . '<br>';
        if ( count($update_list) ) {
            // remove all previous links
            $this->db->where('article_id', $article->id);
            $this->db->delete('article_links');
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            // add new links
            foreach ($update_list as $item) {
                $data = array(
                    'article_id' => $article->id,
                    'link' => $item
                );
                $this->db->insert('article_links', $data);
                $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            }
        }
    }

    public function update_releases($article, $update_list)
    {
    	$this->trace .= 'update_releases<br/>';
        $this->trace .= 'releases: ' . print_r($update_list, TRUE) . '<br>';
        if ( count($update_list) ) {
            foreach ($update_list as $id => $action) {
                $this->trace .= 'working on ' . $id . ' (' . $action . ')<br>';
                if ( $action == 'insert' ) {
                    $data = array(
                        'article_id' => $article->id,
                        'release_id' => $id
                    );
                    $this->db->insert('article_release', $data);
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'add release ' . "<br/>\n";
                }
                elseif ( $action == 'delete' ) {
                    $this->trace .= 'delete it!<br>';
                    $this->db->where('article_id', $article->id)
                            ->where('release_id', $id);
                    $this->db->delete('article_release');
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'delete release ' . "<br/>\n";
                }
            }
        }
        else {
            $this->trace .= 'no changes in release list ' . "<br/>\n";
        }
    }
    
    /*
    select a.id, a.slug, a.title, a.intro, a.category_id, 
                    a.image_file, a.body, a.updated_on, a.published_on, 
                    t.title topic_title
                from article_topic atc
                left join articles a on a.id = atc.article_id
                left join topics t on t.id = atc.topic_id
                */
    public function get_topic_articles($topic_slug, $max = 10, $offset = 0)
    {
	    $this->trace .= 'get_topic_articles<br/>';
        $result = array();
        $this->db->select('id, slug, title, intro, category_id, '
                    . 'image_file, body, updated_on, published_on, '
                    . 'topic_title')
                ->from('expose_exposeorg4325340.topic_articles')
                ->where('status', 'live')
                ->where('topic_slug', $topic_slug)
                ->where('published_on <= CURDATE()')
                ->order_by('published_on', 'desc');
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
//        echo $this->trace; exit;
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $article =  $query->custom_row_object($i++, 'ExArticle');
            if ( $article->is_review() ) {
                $this->get_main_image($article);
                $article->intro = smart_trim($article->body, 200);
            }
            else {
                $article->set_image_path();
            }
            $this->get_credits($article);
            $result[] = $article;
        }
        return $result;
    }
    
    function get_release_year_articles($year, $max = 10, $offset = 0)
    {
    	$this->trace .= 'get_release_year_articles<br/>';
        $result = array();
        $this->db->select('article_id id, release_id, slug, title, intro, category_id, '
                    . 'image_file, body, updated_on, published_on')
                ->from('expose_exposeorg4325340.release_articles')
                ->where('status', 'live')
                ->where('published_on <= CURDATE()')
                ->where('year_released',  $year)
                ->order_by('published_on', 'desc');
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $article =  $query->custom_row_object($i++, 'ExArticle');
            if ( $article->is_review() ) {
                $this->get_main_image($article);
                $article->intro = smart_trim($article->body, 200);
            }
            else {
                $article->set_image_path();
            }
            $this->get_credits($article);
            $result[$article->id] = $article;
        }
        return $result;
    }

    function get_recorded_year_articles($year, $max = 10, $offset = 0)
    {
        $this->trace .= 'get_recorded_year_articles<br/>';
        $result = array();
        $this->db->select('article_id id, release_id, slug, title, intro, category_id, '
            . 'image_file, body, updated_on, published_on')
            ->from('expose_exposeorg4325340.release_articles')
            ->where('status', 'live')
            ->where('published_on <= CURDATE()')
            ->where('year_recorded',  $year)
            ->order_by('published_on', 'desc');
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $article =  $query->custom_row_object($i++, 'ExArticle');
            if ( $article->is_review() ) {
                $this->get_main_image($article);
                $article->intro = smart_trim($article->body, 200);
            }
            else {
                $article->set_image_path();
            }
            $this->get_credits($article);
            $result[$article->id] = $article;
        }
        return $result;
    }

    function get_release_reviews($release_id)
    {
	    $this->trace .= 'get_release_reviews<br/>';
        $result = array();
        $this->db->select('ar.article_id')
                ->from('article_release ar')
                ->where('a.status', 'live')
                ->where('a.published_on <= CURDATE()')
                ->where('a.category_id', 1)
                ->where('ar.release_id', $release_id)
                ->order_by('updated_on', 'desc');
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $result = $query->result_array();
        foreach ($result as &$item) {
            
        }
        
    }
    
    function add_release($article_id, $release_list)
    {
        $this->trace .= 'add_release<br/>';
        if ( $article_id && count($release_list) ) {
            $this->db->trans_start();
            foreach ($release_list as $item) {
                if ( $item > 0 ) { // positive - insert
                    $data = array(
                        'article_id' => $article_id,
                        'release_id' => $item
                    );
                    $this->db->insert('article_release', $data);
                }
                else { // negative - delete
                    $this->db->where('article_id', $article_id)
                            ->where('release_id', substr($item, 1));
                    $this->db->delete('article_release');
                }
            }
            $this->db->trans_complete();
        }
    }
    
}

/* End of file article_model.php */
/* Location: application/models/article_model.php */
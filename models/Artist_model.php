<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Artist Model
 * 
 * Author: Jon Davis
 * 
*/

class Artist_model extends CI_Model
{
    public $trace = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
	    $this->trace = '>> construct artist model<br/>';
    }
   
    function get_list($starter = '', $max = 10, $offset = 0)
    {
	    $this->trace .= 'get_list<br/>';
        $result = array();
        $this->db->select('id, name, display, country_id, slug, image_file, country, '
                    . '(select count(article_id) from article_artist aa where aa.artist_id = a.id) as article_count, '
                    . '(select count(release_id) from release_artist ra where ra.artist_id = a.id) as release_count')
                ->from('expose_exposeorg4325340.artist_list a')
                ->order_by('name');
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        if ($starter) {
            $this->db->where('name >=', $starter);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        foreach ($query->result() as $row) {
            $result[] = $row;
        }
        return $result;
    }
    
    function get_country_list($country = '', $starter = '', $max = 10, $offset = 0)
    {
	    $this->trace .= 'get_list<br/>';
        $result = array();
        $this->db->select('id, name, display, country_id, slug, image_file, country, '
                    . '(select count(article_id) from article_artist aa where aa.artist_id = a.id) as article_count, '
                    . '(select count(release_id) from release_artist ra where ra.artist_id = a.id) as release_count')
                ->from('expose_exposeorg4325340.artist_list a')
                ->where('country_id', $country)
                ->order_by('name');
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        if ($starter) {
            $this->db->where('name >=', $starter);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        foreach ($query->result() as $row) {
            $result[] = $row;
        }
        return $result;
    }

    function get_search_count($search_string = '', $search_type = 'all')
    {
	    $this->trace .= 'get_search_count<br/>';
        $result = array(
            'Total' => 0,
            'Artist' => 0,
            'Release' => 0,
            'Review' => 0,
            'Article' => 0
        );
        $this->db->select('result_type, count(*) item_count')
                ->from('expose_exposeorg4325340.all_search')
                ->group_by('result_type');
        if ( strpos($search_string, ' ') !== FALSE ) { // multiple words
            $terms = explode(' ', $search_string);
            foreach ($terms as $term) {
                $this->db->like('name', $term);
            }
        }
        else {
            $this->db->like('name', $search_string);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        foreach ($query->result() as $row) {
            switch ($row->result_type) {
                case 'Artist' :
                case 'Release' ;
                case 'Review' ;
                    $result[$row->result_type] = $row->item_count;
                    break;
                default :
                    $result['Article'] = $row->item_count;
                    break;
            }
            $result['Total'] += $row->item_count;
        }
        return $result;
    }

    function get_search_results($search_string = '', $search_type = 'all', $max = 10, $offset = 0)
    {
	    $this->trace .= 'get_search_results<br/>';
        $result = array();
        $this->db->select('skey, result_type, slug, display, name, extra, image_path, url')
                ->from('expose_exposeorg4325340.all_search')
                ->order_by('result_type, name');
        if ( strpos($search_string, ' ') !== FALSE ) { // multiple words
            $terms = explode(' ', $search_string);
            foreach ($terms as $term) {
                $this->db->like('name', $term);
            }
        }
        else {
            $this->db->like('name', $search_string);
        }
        if (($max != 0) || ($offset != 0)) {
            $this->db->limit($max, $offset);
        }
        if ( $search_type != 'all' ) {
            if ( $search_type == 'Article' ) {
                $this->db->where_in('result_type', array('Listen and discover','Features','News'));
            }
            else {
                $this->db->where('result_type', $search_type);
            }
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
//        echo $this->trace; exit;
        foreach ($query->result() as $row) {
            $result[$row->skey] = $row;
        }
        return $result;
    }
    
    function get_artist_select_list($max_count = 0, $starter = '', $add_select = FALSE)
    {
	    $this->trace .= 'get_artist_select_list<br/>';
        $result = array();
        if ( $add_select ) {
            $result['0'] = 'Select...';
        }
        $this->db->select('id, name, country_id')
                ->from('expose_exposeorg4325340.artist_list')
                ->order_by('name');
        if ($max_count > 0) {
            $this->db->limit($max_count);
        }
        if ($starter) {
            $this->db->where('name >=', $starter);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
	    foreach ($query->result() as $row) {
            $result[$row->id] = $row->name . ' (' . $row->country_id . ')';
        }
        return $result;
    }
    
    private function get_release_count($artist_id)
    {
        $this->db->select('count(release_id)')
                ->from('expose_exposeorg4325340.release_artist')
                ->where('artist_id', $artist_id);
    }
    
    public function get_count($country = '')
    {
        $this->trace .= 'get_count()<br/>';
        $result = array('count' => 0, 'last-slug' => '');
        $this->db->select('count(*) acount')
                ->from('expose_exposeorg4325340.artists');
        if ( $country ) {
            $this->db->where('country_id', $country);
        }
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->row();
        $result['count'] = $query_result->acount;
        $this->db->select('slug')
                ->from('artists')
                ->order_by('name', 'desc')
                ->limit(1);
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->row();
        $result['last-slug'] = $query_result->slug;
        return $result;
    }

    public function country_count()
    {
        $this->trace .= 'country_count()<br/>';
        $result = array();
        $this->db->select('country_id, country_name, acount')
            ->from('expose_exposeorg4325340.artist_country_count')
            ->order_by('country_name');
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $query_result = $query->row();
        $qresult = $query->result();
        foreach ($qresult as $row) {
            $result[$row->country_id] = $row;
        }
        return $result;
    }
    
    public function get_backlink($base = '', $count_back = 0)
    {
	$this->trace .= 'get_backlink(' . $base . ', ' . $count_back . ')<br/>';
        $result = '';
        if ( $count_back == 0 ) {
            $result = $base;
        }
        else {
            $this->db->select('slug')
                    ->from('expose_exposeorg4325340.artists')
                    ->where('name <', $base)
                    ->order_by('name', 'desc')
                    ->limit($count_back);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            foreach ($query->result() as $row) {
                $result = $row->slug;
            }
            $result = substr($result, 0, -1);
            $this->trace .= 'backlink is ' . $result . '<br/>';
        }
        return $result;
    }
    
    function article_artist_list($max_count = 0, $starter = '')
    {
	$this->trace .= 'article_artist_list<br/>';
        $result = array();
        $this->db->select('a.id, a.name, a.display, c.name country, a.slug, a.image_file, a.country_id, '
                    . '(select count(article_id) from article_artist aa where aa.artist_id = a.id) as article_count, '
                    . '(select count(release_id) from release_artist ra where ra.artist_id = a.id) as release_count')
                ->from('expose_exposeorg4325340.artists a')
                ->group_by('a.id')
                ->having('article_count > 0')
                ->join('countries c', 'c.id = a.country_id', 'left')
                ->order_by('a.name, a.country_id');
        if ($max_count > 0) {
            $this->db->limit($max_count);
        }
        if ($starter) {
            $this->db->where('a.name >=', $starter);
        }
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
	    foreach ($query->result() as $row) {
	        if ($row->display) {
                $result[$row->id] = array(
                    'display' => $row->display,
                    'country_id' => $row->country_id,
                    'country' => $row->country,
                    'article_count' => $row->article_count,
                    'release_count' => $row->release_count,
                    'image_file' => 'artists/' . $row->image_file,
                    'slug' => $row->slug
                );
            }
        }
        return $result;
    }
    
    function get_info($slug = '')
    {
    	$this->trace .= 'get_info<br/>';
        if ( ( $slug == '' ) || ( $slug == '0' ) ) {
            $result = new ExArtist();
        }
        else {
            $this->db->select('display, country_id, url, info, '
                    . 'id, image_file, country, slug, name')
                    ->from('expose_exposeorg4325340.artist_list')
                    ->where('slug', $slug);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
//            echo $this->trace; exit;
            $result = $query->custom_row_object(0, 'ExArtist');
        }
        return $result;
    }
    
    function get_base_info($id = 0)
    {
	$this->trace .= 'get_info<br/>';
        $result = array(
            'id' => $id,
            'name' => '',
            'display' => '',
            'slug' => ''
        );
        if ( $id != 0 ) {
            $this->db->select('id, name, display, slug')
                    ->from('expose_exposeorg4325340.artists')
                    ->where('id', $id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $result = $query->row_array();
        }
        return $result;
    }
    
    /*

CREATE or replace ALGORITHM=UNDEFINED DEFINER=`exposeorg4325340`@`%` SQL SECURITY DEFINER VIEW `artist_articles` AS 
    select aa.artist_id, a.title, a.slug, a.category_id, c.item_name category_name, 
    a.published_on, a.status, a.published_on 
    from expose_exposeorg4325340.article_artist aa 
    join expose_exposeorg4325340.articles a on a.id = aa.article_id 
    join expose_exposeorg4325340.categories c on c.id = a.category_id 
    */
    function get_article_list($id = 0)
    {
	$this->trace .= 'get_article_list<br/>';
        $result = array();
        if ($id > 0) {
            $this->db->select('title, slug, category_id, category__name, '
                        . 'published_on')
                    ->from('expose_exposeorg4325340.artist_articles')
                    ->where('artist_id', $id)
                    ->where('status', 'live')
                    ->where('published_on <= CURDATE()')
                    ->order_by('published_on', 'desc');
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $result = $query->result_array();
        }
        return $result;
    }
    
    function get_release_list($artist)
    {
	    $this->trace .= 'get_release_list<br/>';
        if ( $artist->id ) {
            $artist->clear_release_list();
            $this->db->select('release_id id, display_title, display_artist, '
                        . 'media, year_released, image_file, '
                        . 'catalog_no, year_recorded, label_id, label_name')
                    ->from('expose_exposeorg4325340.artist_release_list')
                    ->where('artist_id', $artist->id)
                    ->order_by('year_recorded desc, title');
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $result = $query->result();
            $i = 0;
            foreach ($result as $row) {
                $release =  $query->custom_row_object($i++, 'ExRelease');
                $artist->release_list[$row->id] = $release;
            }
        }
    }
    
    function fix_slugs()
    {
        $this->trace .= 'fix_slugs<br/>';
        $result = '';
        $artist_list = array();
        $this->db->select('id, name, country_id, slug')
                ->from('artists');
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        $artist_list = $query->result_array();
        foreach ($artist_list as $item) {
            if ($item['country_id'] == '???') {
            $slug = create_unique_slug($item['name'], 'artists');
            }
            else {
            $slug = create_unique_slug($item['name'] . '-' . $item['country_id'],
                'artists');
            }
            $result .= 'update artists set slug = ' . $this->db->escape($slug)
                . ' where id = ' . $this->db->escape($item['id']) . ";\n";
        }
        return $result;
    }
    
    public function update_info($artist, $params)
    {
	    $this->trace .= 'update_info<br/>';
	    $result = array('status' => 'ok');
        if ( $artist->id ) {
            $this->db->where('id', $artist->id);
            $this->db->update('artists', $params);
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        }
        else {
            $this->db->insert('artists', $params);
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        }
	    return $result;
    }

    public function get_country_select_list()
    {
	    $this->trace .= 'get_country_select_list<br/>';
        $result = array(
            '0' => 'Select...'
        );
        $this->db->select('id, name')
                ->from('expose_exposeorg4325340.countries')
                ->order_by('name');
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
	    foreach ($query->result() as $row) {
            $result[$row->id] = $row->name ;
        }
        return $result;
    }
    
}

/* End of file artist_model.php */
/* Location: application/models/artist_model.php */
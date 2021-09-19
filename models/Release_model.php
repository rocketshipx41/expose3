<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Name:  Release Model
 * 
 * Author: Jon Davis
 * 
*/

class Release_model extends CI_Model
{
    
    public $trace = '';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
	    $this->trace = '>> construct release model<br/>';
    }
   
    function get_release_info($release_id = 0)
    {
	    $this->trace .= 'get_release_info(' . $release_id . ')<br/>';
        if ( $release_id == 0 ) {
            $result = new ExRelease();
        }
        else {
            $this->db->select('id, label_id, release_type_id, catalog_no, '
                        . 'title, artist, display_title, display_artist, '
                        . 'media, year_recorded, year_released, image_file, '
                        . 'label_name, label_display, release_type')
                    ->from('expose_exposeorg4325340.release_info')
                    ->where('id', $release_id);
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $result = $query->custom_row_object(0, 'ExRelease');
        }
        return $result;
    }
    
    public function get_release_artists($release)
    {
	    $this->trace .= 'get_release_artists(' . $release->id . ')<br/>';
        if ( $release->id ) {
            $release->clear_artist_list();
            $this->db->select('ra.artist_id id, a.display, a.slug')
                    ->from('expose_exposeorg4325340.release_artist ra')
                    ->join('expose_exposeorg4325340.artists a', 'a.id = ra.artist_id', 'left')
                    ->where('ra.release_id', $release->id)
                    ->order_by('a.name');
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $result = $query->result();
            $i = 0;
            foreach ($result as $row) {
                $artist = $query->custom_row_object($i++, 'ExArtist');
                $release->artist_list[$row->id] = $artist;
            }
        }
    }
    
    function get_article_list($release)
    {
	    $this->trace .= 'get_article_list<br/>';
        $result = array();
        if ( $release->id ) {
            $this->db->select('title, slug, body, category_id, category_name, article_id id, published_on')
                    ->from('expose_exposeorg4325340.release_articles')
                    ->where('release_id', $release->id)
                    ->where('status', 'live')
                    ->where('published_on <= CURDATE()');
            $query = $this->db->get();
            $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
            $qresult = $query->result();
            $i = 0;
            foreach ($qresult as $row) {
                $article = $query->custom_row_object($i++, 'ExArticle');
                $release->article_list[$article->id] = $article;
                $this->get_credits($article);
            }
        }
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

    public function get_release_types()
    {
	    $this->trace .= 'get_release_types()<br/>';
        $result = array(
            '0' => lang('dropdown_select')
        );
        $this->db->select('id, item_name')
                ->from('topics')
                ->where_in('id', array('1', '2', '3'));
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
	    foreach ($query->result_array() as $row) {
            $result[$row['id']] = $row['item_name'];
	    }
        return $result;

    }
    
    public function update_info($release, $update_params)
    {
	    $this->trace .= 'update<br/>';
        $result = array('status' => 'ok');
        $related_artists = $update_params['related_artists'];
        unset($update_params['related_artists']);
	    $this->db->trans_start();
        if ( $release->id > 0 ) {
            $this->db->where('id', $release->id);
            $this->db->update('releases', $update_params);
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        }
        else {
            $this->db->insert('releases', $update_params);
            $release->id = $this->db->insert_id();
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            $this->trace .= 'new id is: ' . $release->id . "<br/>\n";
        }
        // artists
        if ( $related_artists == 'clear' ) {
            $this->db->where('release_id', $release->id);
            $this->db->delete('release_artist');
            $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
            $this->trace .= 'delete artists ' . "<br/>\n";
        }
        elseif ( is_array($related_artists) ) {
            foreach ($related_artists as $id => $action){
                if ($action == 'insert') {
                    $data = array(
                        'release_id' => $release->id,
                        'artist_id' => $id
                    );
                    $this->db->insert('release_artist', $data);
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'add new artist ' . "<br/>\n";
                }
                elseif ($action == 'delete') {
                    $this->db->where('release_id', $release->id)
                            ->where('artist_id', $id);
                    $this->db->delete('release_artist');
                    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
                    $this->trace .= 'delete artist ' . "<br/>\n";
                }
            }
        }
        else {
            $this->trace .= 'no changes in artist list ' . "<br/>\n";
        }
	    $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            $result['status'] = 'error';
        }
        else {
            $result['release_id'] = $release->id;
        }
        return $result;
    }
    
    public function get_list()
    {
	$this->trace .= 'get_list()<br/>';
        $this->db->select('r.id, r.artist, r.title, r.display_artist, '
                . 'r.display_title')
                ->from('expose_exposeorg4325340.releases r')
                ->order_by('artist, title');
        $result = array();
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        if ( $query->num_rows() ) {
            foreach ($query->result() as $row) {
                $result[$row->id] = $row->display_artist . ' - '
                    . $row->display_title;
            }
        }
        return $result;
    }
    
    function fix_slugs()
    {
        $this->trace .= 'fix_slugs<br/>';
        $result = '';
        $artist_list = array();
        $this->db->select('id, title, artist, year_released')
                ->from('releases');
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query()  . "<br/>\n";
        $artist_list = $query->result_array();
        foreach ($artist_list as $item) {
                $slug = create_unique_slug($item['artist'] . '-' . $item['title']
                        . '-' . $item['year_released'],
                'artists');
            $result .= 'update releases set slug = ' . $this->db->escape($slug)
                . ' where id = ' . $this->db->escape($item['id']) . ";\n";
        }
        return $result;
    }
    
    public function get_unassigned($start = '', $max_count = 40)
    {
	    $this->trace .= 'get_unassigned<br/>';
        $this->db->select('r.id, r.display_artist, r.display_title')
                ->from('releases r')
                ->join('release_artist ra', 'ra.release_id = r.id', 'left')
                ->where('ra.artist_id is null')
                ->order_by('r.artist, r.title')
                ->limit($max_count);
        if ( $start ) {
            $this->db->where('r.artist >', $start);
        }
        $result = array();
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        if ( $query->num_rows() ) {
            foreach ($query->result() as $row) {
                $result[$row->id] = $row->display_artist . ' - '
                    . $row->display_title;
            }
        }
	    return $result;
    }
    
    public function bulk_assign_artists($change_list)
    {
	    $this->trace .= 'bulk_assign_artists<br/>';
        $result = 0;
        foreach ($change_list as $item) {
            $this->db->insert('release_artist', $item);
        }
        return $result;
    }
    
    public function get_list_by_label($label_id)
    {
        $this->trace .= 'get_list_by_label<br/>';
        $result = array();
        $this->db->select('display_title, display_artist, media, year_recorded, '
                    . 'year_released, id, image_file, label_id, '
                    . 'label_name, catalog_no')
                ->from('release_info')
                ->where('label_id', $label_id)
                ->order_by('year_released');
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $qresult = $query->result();
        $i = 0;
        foreach ($qresult as $row) {
            $release =  $query->custom_row_object($i++, 'ExRelease');
            $result[$row->id] = $release;
        }
        return $result;
    }
    
    function search($search_value, $max_count = 0, $starter = '')
    {
	$this->trace .= 'search<br/>';
        $result = array();
        $this->db->select('r.display_title, r.display_artist, r.media, r.year_recorded, '
                    . 'r.year_released, r.id release_id, r.image_file, r.label_id, '
                    . 'l.display label_name, r.catalog_no, r.media_count')
                ->from('releases r')
                ->join('labels l', 'l.id = r.label_id', 'left')
                ->like('r.title', $search_value)
                ->order_by('r.artist, r.title, year_released');
        $query = $this->db->get();
	$this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        $result = $query->result_array();
        return $result;
    }
    
}

/* End of file release_model.php */
/* Location: application/models/release_model.php */
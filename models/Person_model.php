<?php

class Person_model extends CI_Model 
{
    public $trace = '';
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
	    $this->trace = '>> construct person model<br/>';
    }
   
    public function get_person_details($id)
    {
	    $this->trace = 'get_user_info<br/>';
        $this->db->select('username, email, active, ip_address, '
                    . 'last_login, id, display_name, article_count')
                ->from('expose_exposeorg4325340.user_list')
                ->where('id', $id);
        $query = $this->db->get();
        $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
        return $query->row();
    }

    public function get_person_list()
    {
	    $this->trace .= 'get_person_list<br/>';
	    $result = array();
	    $this->db->select('id, display_name')
	    	->from('expose_exposeorg4325340.user_list')
	    	->order_by('sort_name');
        $query = $this->db->get();
	    $this->trace .= 'sql: ' . $this->db->last_query() . "<br/>\n";
	    foreach ($query->result() as $row) {
	        if ($row->display_name) {
	        	$result[$row->id] = $row->display_name;
	        }
	    }
	    return $result;
    }

}
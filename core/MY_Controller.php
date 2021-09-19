<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    
    public $page_data = array(
        // layout items
        'page_name' => '',
        'page_title' => '',
        'menu_active' => 'home',
        'left_column_width' => '2',
        'left_side' => 'partials/side_column_blank',
        'center_column_width' => '7',
        'center_view' => '',
        'right_column_width' => '3',
        'right_side' => 'partials/side_column_blank',
        // alert messages
        'incoming_status' => 'ok',
        'status_message' => '',
        'page_alerts' => array(),
        // user info
        'user_id' => '0',
        'user_name' => '',
        'user_group' => '',
        'can_edit' => FALSE,
        // page data useful
        'item_count' => 0,
        'offset' => 0,
        'main_list' => array()
    );
    
    function __construct()
    {
        parent::__construct();
        $this->page_data['trace'] = '>> construct my controller<br/>';

        // global housekeeping
        $this->page_data['site_name'] = $this->config->item('site_name');
        $this->page_data['site_slogan'] = $this->config->item('site_slogan');
        $this->page_data['copyright'] = $this->config->item('copyright');
        $this->page_data['license'] = $this->config->item('license');
        $this->page_data['gtag'] = GTAG;

        // get status messages coming in from session
        if ( $this->session->flashdata('incoming_status') ) {
            $this->page_data['incoming_status'] = $this->session->flashdata('incoming_status');
            $this->page_data['status_message'] = $this->session->flashdata('status_message');
            $this->page_data['trace'] .= 'incoming flashdata: "' 
                    . $this->page_data['status_message'] . '"<br/>';
        }
//        echo $this->page_data['trace']; exit;

        // anything used everywhere?

        // user status
        if ( $this->ion_auth->logged_in()) {
            $user = $this->ion_auth->user()->row();
            $this->page_data['user_name'] = $user->username;
            $this->page_data['user_id'] = $user->id;
            $this->page_data['can_edit'] = TRUE;
            if ( $this->ion_auth->is_admin() ) {
			    $this->page_data['user_group'] = 'admin';
            }
            else {
                $this->page_data['user_group'] = 'staff';
            }
		}
     }

    /**
     * add an alert to be displayed at the top of the page
     * type can be:
     * - error (red)
     * - success (green)
     * - info (blue)
     * - danger (??)
     * 
     * @param type $alert_type
     * @param type $message 
     */
    function add_alert($alert_type, $message)
    {
        $alert_text = '';
        if ( is_array($message)) {
//            $alert_text = print_r($message, TRUE);
            $alert_text = implode('<br/>', $message);
        }
        else {
            $alert_text = $message;
        }
        $this->page_data['page_alerts'][] = array(
            'message' => $alert_text,
            'type' => $alert_type
        );
    }

    function set_flashdata_alert($alert_type, $message)
    {
        $full_message = $message;
        $this->session->set_flashdata(array(
            'status_message' => $full_message,
            'incoming_status' => $alert_type			
        ));
    }

}
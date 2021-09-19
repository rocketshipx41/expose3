<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Releases extends MY_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->page_data['menu_active'] = 'artists';
        $this->page_data['trace'] .= '>> construct releases controller<br/>';
        $this->load->model('Release_model');
    }
    
    function display($release_id = 0)
    {
        // init
        if ( $release_id == 0 ) {
            redirect('');
        }
        
        // get info
        $release = $this->Release_model->get_release_info($release_id);
        $this->Release_model->get_release_artists($release);
        $this->Release_model->get_article_list($release);
        $this->page_data['trace'] .= 'article info: ' . print_r($release->article_list, TRUE)
                . '<br/>';
//        echo $this->page_data['trace']; exit;
        
        // display
        $this->page_data['release'] = $release;
//        $this->page_data['release_info'] = array();
        $this->page_data['trace'] .= $this->Release_model->trace;
        $this->page_data['page_title'] = lang('release_detail_page_title');
        $this->page_data['page_name'] = 'Release info | ' . $release->full_display();
        // echo $this->page_data['trace']; exit;
        $this->page_data['center_view'] = 'release/single_release';
        $this->load->view('layouts/base', $this->page_data);
    }
    
    function edit($release_id = 0, $artist_slug = '')
    {
        // init
        if ( ! $this->page_data['can_edit'] ) {
            redirect('');
        }
        if ( $this->input->post('release-id') ) {
            $release_id = $this->input->post('release-id');
        }
        $this->load->model('Label_model');
        $this->load->model('Artist_model');
            
        // get info
        $release = $this->Release_model->get_release_info($release_id);
        if ( $artist_slug ) {
            $artist = $this->Artist_model->get_info($artist_slug);
            $release->artist_list[$artist->id] = $artist;
            $release->display_artist = $artist->display;
            $release->artist = $artist->name;
            $release->home_artist_slug = $artist_slug;
        }
        else {
            $this->Release_model->get_release_artists($release);
            $artist = $release->guess_primary_artist();
            if ( $artist ) {
                $release->home_artist_slug = $artist->slug;
            }
        }

        // process input
        if ( $this->input->post('release-submit') ) {
            $update_values = $release->process_post_values($this->input->post());
//            echo '<pre>' . print_r($update_values) . '</pre>'; exit;
            if ( count($update_values) ) {
                $update_values['updated_by'] = $this->page_data['user_id'];
                $edit_result = $this->Release_model->update_info($release, $update_values);
                if ( $edit_result['status'] == 'ok' ) {
//                    echo '<pre>' . print_r($release) . '</pre>'; exit;
                    $this->set_flashdata_alert('success', lang('success_message'));
                    if ( $release->home_artist_slug ) {
                        redirect('artists/display/' . $release->home_artist_slug);
                    }
                    else {
                        redirect('releases/display/' . $edit_result['release_id']);
                    }
                }
            }
//                echo '<pre>' . print_r($this->input->post()) . '</pre>'; exit;
        }
        
        // display
        $this->page_data['label_list'] = $this->Label_model->get_select_list(TRUE);
        $this->page_data['release_type_list'] = $this->Release_model->get_release_types();
        $this->page_data['artist_list'] = $this->Artist_model->get_artist_select_list();
        $this->page_data['release'] = $release;
        $this->page_data['trace'] .= $this->Release_model->trace;
        if ( $release_id == 0 ) {
            $this->page_data['page_title'] = lang('release_new_page_title');
            $this->page_data['page_name'] = lang('release_new_page_title');
        }
        else {
            $this->page_data['page_title'] = lang('release_edit_page_title');
            $this->page_data['page_name'] = 'Release info | ' . $release->full_display();
        }
        $this->page_data['left_column_width'] = '1';
        $this->page_data['center_column_width'] = '10';
        $this->page_data['center_view'] = 'release/release_form';
        $this->page_data['right_column_width'] = '1';
        // echo $this->page_data['trace']; exit;
        $this->load->view('layouts/base', $this->page_data);
    }
    
}
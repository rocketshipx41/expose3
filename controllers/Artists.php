<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Artists extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->page_data['trace'] .= '>> construct artists controller<br/>';
        
        $this->page_data['page_name'] = 'Artists';
        $this->page_data['menu_active'] = 'artists';

        $this->load->model('Artist_model');
        $this->load->library('ExArtist');
    }
    
    public function display($artist_slug)
    {
        // init

        // get data
        $artist = $this->Artist_model->get_info($artist_slug);
//        echo '<pre>' . print_r($artist) . '</pre>'; exit;
        $this->Article_model->get_artist_article_list($artist);
        $this->Artist_model->get_release_list($artist);
//        echo '<pre>' . print_r($artist) . '</pre>'; exit;
        $this->page_data['artist'] = $artist;

        // display
        $this->page_data['page_name'] = 'Artist info | ' . $artist->display;
        $this->page_data['page_title'] = lang('artist_detail_page_title');
		$this->page_data['center_view'] = 'artist/single_artist';
		$this->load->view('layouts/base', $this->page_data);    
    }

    public function index($start = '', $offset = 0)
    {
        // init

        // get data
        $this->page_data['main_list'] = $this->Artist_model->get_list($start, 10, $offset);
        if ( ( $start == '' ) && ( count($this->page_data['main_list']) ) ) {
            $start = $this->page_data['main_list'][0]->name;
        }

        // display
        if ( $offset >= 10 ) {
		    $this->page_data['prev_link'] = 'artists/index/' . $start . '/' . ($offset - 10);
        }
        else {
            $this->page_data['prev_link'] = '';
        }
		$this->page_data['next_link'] = 'artists/index/' . $start . '/' . ($offset + 10);
        $this->page_data['page_name'] = 'Artists';
        $this->page_data['list_source'] = 'index';
        $this->page_data['page_title'] = lang('artist_index_page_title');
		$this->page_data['center_view'] = 'artist/artist_list';
		$this->load->view('layouts/base', $this->page_data);    
    }

    public function country($country = '', $start = '', $offset = 0)
    {
        // init
        if ( $country == '' ) {
            redirect('artists/index/0');
        }

        // get data
        $this->page_data['main_list'] = $this->Artist_model->get_country_list($country, $start, 10, $offset);
        if ( ( $start == '' ) && ( count($this->page_data['main_list']) ) ) {
            $start = $this->page_data['main_list'][0]->name;
        }
        $country_name = $this->page_data['main_list'][0]->country;

        // display
        if ( $offset >= 10 ) {
		    $this->page_data['prev_link'] = 'artists/country/' . $country . '/' . $start . '/' . ($offset - 10);
        }
        else {
            $this->page_data['prev_link'] = '';
        }
		$this->page_data['next_link'] = 'artists/country/' . $country . '/' . $start . '/' . ($offset + 10);
        $this->page_data['page_name'] = 'Artists for ' . $country_name;
        $this->page_data['list_source'] = 'country/' . $country;
        $this->page_data['page_title'] = lang('artist_index_page_title') . ' for ' . $country_name;
		$this->page_data['center_view'] = 'artist/artist_list';
		$this->load->view('layouts/base', $this->page_data);    
    }

    public function edit($artist_slug = '')
    {
        // init
        if ( ! $this->page_data['can_edit'] ) {
            redirect('artists/display/' . $artist_slug);
        }
        if ( $this->input->post('artist-slug') ) {
            $artist_slug = $this->input->post('artist-slug');
        }

        // get data
        $artist = $this->Artist_model->get_info($artist_slug);
//        echo '<pre>' . print_r($artist) . '</pre>'; exit;

        // process input
        if ( $this->input->post('artist-submit') ) {
//            echo 'post ' . print_r($this->input->post()) . PHP_EOL . PHP_EOL;
            $update_values = $artist->process_post_values($this->input->post());
            if ( count($update_values) ) {
                $update_values['user_id'] = $this->page_data['user_id'];
                if ( ( $artist->id == '0' ) && ( ! isset($update_values['slug']) ) ) {
                    $update_values['slug'] = create_unique_slug($update_values['name'] . '-'
                            . $update_values['country_id'], 'artists');
                    $artist_slug = $update_values['slug'];
                }
//                echo 'update ' . print_r($update_values); exit;
                $edit_result = $this->Artist_model->update_info($artist, $update_values);
                if ( $edit_result['status'] == 'ok' ) {
                    $this->set_flashdata_alert('success', lang('success_message'));
                    redirect('artists/display/' . $artist_slug);    
                }
//                echo 'update ' . print_r($update_values); exit;
            }
        }

        // display
        $this->page_data['artist'] = $artist;
        $this->page_data['country_list'] = $this->Artist_model->get_country_select_list();
        $this->page_data['page_name'] = 'Artist info';
        $this->page_data['page_title'] = lang('artist_edit_page_title');
        $this->page_data['can_edit'] = TRUE;
        $this->page_data['left_column_width'] = '1';
        $this->page_data['left_side'] = 'partials/side_column_blank';
        $this->page_data['center_column_width'] = '10';
        $this->page_data['center_view'] = 'artist/artist_form';
        $this->page_data['right_column_width'] = '1';
        $this->page_data['right_side'] = 'partials/side_column_blank';
		$this->load->view('layouts/base', $this->page_data);
    }

}

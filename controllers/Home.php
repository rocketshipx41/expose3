<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->page_data['trace'] .= '>> construct welcome controller<br/>';
    }
    
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/home
	 *	- or -
	 * 		http://example.com/index.php/home/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/home/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($offset = 0)
	{
        // init

		// get data
        $this->page_data['main_list'] = $this->Article_model->articles_by_status('live', 'any',
                10, $offset, FALSE);
        $article_count = $this->Article_model->get_count();
        $this->page_data['item_count'] = $article_count['all']->acount;
        $this->page_data['offset'] = $offset;
//        $this->page_data['sidebar_list'] = $this->Article_model->get_most_recent_updates();
		$this->page_data['trace'] = $this->Article_model->trace;
//		echo print_r($this->page_data['main_list'], TRUE); exit;

		// display
        if ( $offset >= 10 ) {
            $this->page_data['prev_link'] = 'home/index/' . ($offset - 10);
        }
        else {
            $this->page_data['prev_link'] = '';
        }
        $this->page_data['next_link'] = 'home/index/' . ($offset + 10);
        $this->page_data['page_name'] = 'Most recent articles';
		$this->page_data['page_title'] = lang('menu_home');
		$this->page_data['menu_active'] = 'home';
		$this->page_data['center_view'] = 'article/review_list';
		$this->load->view('layouts/base', $this->page_data);
	}

	public function about()
    {
		$this->page_data['page_name'] = lang('menu_about');
		$this->page_data['page_title'] = lang('menu_about');
        $this->page_data['menu_active'] = 'about';
		$this->page_data['center_view'] = 'home/about';
		$this->load->view('layouts/base', $this->page_data);
    }

	public function search($search_string = '', $search_type = 'all', $offset = 0)
	{
		// init
        if ( $this->input->post('searchstring') ) {
            $search_string = $this->input->post('searchstring');
        }
		else {
			$search_string = rawurldecode($search_string);
		}
		if ( $search_string == '' ) {
			redirect('');
		}
        if ( $this->input->post('search_type') ) {
            $search_type = $this->input->post('search_type');
        }
		else {
			// use url param
		}
		if ( $search_type == 'all' ) {
			$count_index = 'Total';
		}
		else {
			$count_index = $search_type;
		}
		$this->load->model('Artist_model');

		// get data
		$this->page_data['search_count'] = $this->Artist_model->get_search_count($search_string, 
				$search_type);
		if ( $this->page_data['search_count'][$count_index] > 0 ) {
			$this->page_data['main_list'] = $this->Artist_model->get_search_results($search_string, 
                $search_type, 10, $offset); 
		}
		else {
			$this->page_data['main_list'] = array();
		}
		$this->page_data['trace'] = $this->Artist_model->trace;
//		echo print_r($this->page_data['trace']); exit;

		// display
		$this->page_data['offset'] = $offset;
		$this->page_data['prev_link'] = '';
		$this->page_data['next_link'] = '';
		$filter_links = array(
			1 => array('label' => '', 'link' => ''),
			2 => array('label' => '', 'link' => ''),
			3 => array('label' => '', 'link' => ''),
			4 => array('label' => '', 'link' => ''),
		);
		$filter_count = 1;
		if ( count($this->page_data['main_list']) > 0 ) {
			if ( $offset >= 10 ) {
				$this->page_data['prev_link'] = 'home/search/' . rawurlencode($search_string) . '/' 
					. $search_type . '/' . ($offset - 10);
			}
			if ( $this->page_data['search_count'][$count_index] > ( $offset + 10 ) ) {
				$this->page_data['next_link'] = 'home/search/' . rawurlencode($search_string) . '/' 
					. $search_type . '/' .  ($offset + 10);
			}
			if ( $this->page_data['search_count']['Artist'] > 0 ) {
				if ( $search_type != 'Artist' ) {
					$filter_links[$filter_count++] = array(
						'label' => lang('search_result_artist') . ' ('. $this->page_data['search_count']['Artist'] . ')',
						'link' => 'home/search/' . rawurlencode($search_string) . '/Artist/0'
					);
				}
			}
			if ( $this->page_data['search_count']['Release'] > 0 ) {
				if ( $search_type != 'Release' ) {
					$filter_links[$filter_count++] = array(
                        'label' => lang('search_result_release') . ' ('. $this->page_data['search_count']['Release'] . ')',
						'link' => 'home/search/' . rawurlencode($search_string) . '/Release/0'
					);
				}
			}
			if ( $this->page_data['search_count']['Review'] > 0 ) {
				if ( $search_type != 'Review' ) {
					$filter_links[$filter_count++] = array(
                        'label' => lang('search_result_review') . ' ('. $this->page_data['search_count']['Review'] . ')',
						'link' => 'home/search/' . rawurlencode($search_string) . '/Review/0'
					);
				}
			}
			if ( $this->page_data['search_count']['Article'] > 0 ) {
				if ( $search_type != 'Article' ) {
					$filter_links[$filter_count++] = array(
                        'label' => lang('search_result_article') . ' ('. $this->page_data['search_count']['Article'] . ')',
						'link' => 'home/search/' . rawurlencode($search_string) . '/Article/0'
					);
				}
			}
			if ( $search_type != 'all' ) {
				$filter_links[$filter_count++] = array(
                    'label' => lang('search_result_all') . ' ('. $this->page_data['search_count']['Total'] . ')',
					'link' => 'home/search/' . rawurlencode($search_string) . '/all/0'
				);
			}
		}
		switch ( $search_type ) {
			case 'Artist' :
				$this->page_data['page_title'] = 'Search results for "' . $search_string . '" in Artists';
				$this->page_data['search_total'] = $this->page_data['search_count']['Artist'];
				break;
			case 'Release' :
				$this->page_data['page_title'] = 'Search results for "' . $search_string . '" in Releases';
				$this->page_data['search_total'] = $this->page_data['search_count']['Release'];
				break;
			case 'Review' :
				$this->page_data['page_title'] = 'Search results for "' . $search_string . '" in Reviews';
				$this->page_data['search_total'] = $this->page_data['search_count']['Review'];
				break;
			case 'Article' :
				$this->page_data['page_title'] = 'Search results for "' . $search_string . '" in Articles';
				$this->page_data['search_total'] = $this->page_data['search_count']['Article'];
				break;
			default :
				$this->page_data['page_title'] = 'Search results for "' . $search_string . '"';
				$this->page_data['search_total'] = $this->page_data['search_count']['Total'];
				break;
		}
		$this->page_data['filter_links'] = $filter_links;
		$this->page_data['page_name'] = lang('search_results');
		$this->page_data['menu_active'] = 'home';
		$this->page_data['center_view'] = 'home/search_results';
		$this->load->view('layouts/base', $this->page_data);
	}

	public function person($person_id = 0)
	{
		// init
		$this->load->model('Person_model');

		// get data
		$this->page_data['person_info'] = $this->Person_model->get_person_details($person_id);
        $this->page_data['article_list'] = $this->Article_model->get_author_article_list($person_id, 'live');
//		echo print_r($this->page_data['article_list'], TRUE); exit;

		// display
        $this->page_data['page_name'] = lang('people_detail_page_title');
		$this->page_data['page_title'] = lang('people_detail_page_title');
		$this->page_data['menu_active'] = 'home';
		$this->page_data['center_view'] = 'home/single_person';
		$this->load->view('layouts/base', $this->page_data);
	}

    public function stats()
    {
        // init
        $this->load->model('Artist_model');

        // get data
        $this->page_data['article_count'] = $this->Article_model->get_count();
        $this->page_data['topic_count'] = $this->Article_model->get_topic_count();
        $this->page_data['recording_year_count'] = $this->Article_model->get_recorded_year_count();
        $this->page_data['country_artist_count'] = $this->Artist_model->country_count();

        // display
        $this->page_data['page_name'] = lang('statistics_page_name');
        $this->page_data['page_title'] = lang('statistics_page_name');
        $this->page_data['menu_active'] = 'home';
        $this->page_data['center_view'] = 'home/stats';
        $this->load->view('layouts/base', $this->page_data);
    }
    
}

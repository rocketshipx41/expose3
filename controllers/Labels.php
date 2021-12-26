<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Labels extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->page_data['trace'] .= '>> construct labels controller<br/>';
		$this->load->model('Label_model');
    }

    public function index()
    {
        // init

        // get data
        $this->page_data['main_list'] = $this->Label_model->get_list();
//        echo '<pre>' . print_r($this->page_data['main_list']) . '</pre>'; exit;

        // display
        $this->page_data['page_title'] = lang('label_index_page_title');
		$this->page_data['menu_active'] = '';
		$this->page_data['center_view'] = 'label/label_list';
		$this->load->view('layouts/base', $this->page_data);
    }

    public function display($id = 0, $offset = 0)
    {
        // init
        if ( $id == 0 ) {
            redirect('');
        }
		$this->load->model('Release_model');

        // get data
        $this->page_data['label_data'] = $this->Label_model->get_full($id);
        $this->page_data['release_list'] = $this->Release_model->get_list_by_label($id, $offset);
        $this->page_data['article_list'] = $this->Article_model->get_label_article_list($id);
//        echo '<pre>' . print_r($this->page_data['article_list']) . '</pre>'; exit;

        // display
        $this->page_data['page_title'] = lang('label_detail_page_title');
        $this->page_data['page_name'] = lang('label_detail_page_title') . ' | ' . $this->page_data['label_data']->display;
		$this->page_data['menu_active'] = '';
		$this->page_data['center_view'] = 'label/single_label';
		$this->load->view('layouts/base', $this->page_data);
    }
    
}
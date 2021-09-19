<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Articles extends MY_Controller {
    
    function __construct()
    {
        parent::__construct();
        $this->page_data['trace'] .= '>> construct articles controller<br/>';
//        echo 'article controller'; exit;
    }
    
	public function index($category = 'reviews', $offset = 0)
	{
        // init
//        echo 'articles for ' . $category; exit;

		// get data
        $this->page_data['main_list'] = $this->Article_model->most_recent($category, 
                    10, $offset, FALSE);
        $article_count = $this->Article_model->get_count($category);
        $this->page_data['item_count'] = $article_count[$category]->acount;
		$this->page_data['trace'] = $this->Article_model->trace;

		// display
        if ( $offset >= 10 ) {
		    $this->page_data['prev_link'] = 'articles/index/' . $category . '/' . ($offset - 10);
        }
        else {
            $this->page_data['prev_link'] = '';
        }
        if ( $offset < ( $this->page_data['item_count'] - 10 ) ) {
            $this->page_data['next_link'] = 'articles/index/' . $category . '/' . ($offset + 10);
        }
        else {
            $this->page_data['next_link'] = '';
        }
        $this->page_data['page_name'] = 'Most recent ' . $category;
        $this->page_data['page_title'] = lang('');
		$this->page_data['menu_active'] = $category;
		$this->page_data['center_view'] = 'article/review_list';
		$this->load->view('layouts/base', $this->page_data);
	}

    public function display($slug)
    {
        // init
        $this->page_data['release_list'] = array();
        $related_list = array();
        $this->page_data['roundtable'] = FALSE;

        // get data
        $article = $this->Article_model->get_full($slug);
        if ( ! $article->id ) {
            $this->set_flashdata_alert('info', lang('not'));
            redirect('');
        }
        $this->Article_model->get_credits($article);
        $this->Article_model->get_meta($article);
	    $this->Article_model->get_artists($article);
        $this->Article_model->get_link_list($article);
        $this->Article_model->get_topics($article);
        if ( ( count($article->topic_list) ) && ( $article->category_id == 1 ) ) {
            $remove_intro = TRUE;
            $this->page_data['trace'] .= 'review with topics<br/>';
            foreach ($article->topic_list as $item) {
                if ($item->topic_id == 5) {
                    $remove_intro = FALSE;
                }
            }
            if ( $remove_intro ) {
                $this->page_data['trace'] .= 'not a festival review, intro not needed<br/>';
                $article->intro = '';
            }
        } 
        if ( $this->page_data['user_group'] != 'admin' ) {
            $this->page_data['can_edit'] = $article->user_can_edit($this->page_data['user_id']);
        }       
        if ( $article->is_review() ) {
            $this->load->model('Release_model');
	        $this->Article_model->get_releases($article);
            $this->page_data['trace'] .= 'review of a release(s), look for others<br/>';
//            echo '<pre>' . print_r($article) . '</pre>'; exit;
            foreach ($article->release_list as $release) {
//                echo '<pre>' . print_r($release) . '</pre>'; exit;
                $this->Release_model->get_article_list($release);
                foreach ( $release->article_list as $related ) { // don't include link to self
                    if ( $related->slug != $slug ) {
                        $related_list[$related->id] = $related;
                        $this->page_data['roundtable'] = TRUE;
                    }
                }
            }
//            echo '<pre>' . $this->Article_model->trace . '</pre>'; exit;
//            echo '<pre>' . print_r($article) . '</pre>'; exit;
            $this->page_data['related_list'] = $related_list;
        }
//        echo '<pre>' . $this->Article_model->trace . '</pre>'; exit;
        $this->page_data['image_file'] = '';
        $this->page_data['article'] = $article;

//        echo '<pre>' . print_r($article) . '</pre>'; exit;

        // display
        switch ($article->category_id) {
            case '1' : // review
                $center_view = 'article/single_article';
                $this->page_data['page_title'] = lang('reviews_page_name');
                $this->page_data['page_name'] = lang('reviews_page_name') . ' | ' . $article->title;
                $this->page_data['menu_active'] = 'reviews';
                break;
            case '2' : // news
                $center_view = 'article/feature';
                $this->page_data['page_title'] = lang('news_page_name');
                $this->page_data['page_name'] = lang('news_page_name') . ' | ' . $article->title;
                $this->page_data['menu_active'] = 'news';
                break;
            case '8' : // media
                $center_view = 'article/feature';
                $this->page_data['page_title'] = lang('recommendations_page_name');
                $this->page_data['page_name'] = lang('recommendations_page_name') . ' | ' . $article->title;
                $this->page_data['menu_active'] = 'news';
                break;
            default :
                $center_view = 'article/feature';
                $this->page_data['page_title'] = lang('features_page_name');
                $this->page_data['page_name'] = lang('features_page_name') . ' | ' . $article->title;
                $this->page_data['menu_active'] = 'features';
                break;
        }
		$this->page_data['center_view'] = $center_view;
		$this->load->view('layouts/base', $this->page_data);
    }

    public function topic($topic_slug = '', $offset = 0)
    {
        // init
        if ( $topic_slug == '' ) {
            redirect('');
        }

        // get data
        $this->page_data['main_list'] = $this->Article_model->get_topic_articles($topic_slug, 
                10, $offset);
        $article_count = $this->Article_model->get_topic_count();
        $this->page_data['item_count'] = $article_count[$topic_slug]->acount;
        $this->page_data['offset'] = $offset;
        $this->page_data['topic_slug'] = $topic_slug;
        $this->page_data['offset'] = $offset;
        $this->page_data['trace'] .= $this->Article_model->trace;
        $this->page_data['trace'] .= print_r($this->page_data['main_list'], TRUE) . '<br/>';
        $topic_title = $this->page_data['main_list'][0]->topic_title;

        // display
        if ( $offset >= 10 ) {
		    $this->page_data['prev_link'] = 'articles/topic/' . $topic_slug . '/' . ($offset - 10);
        }
        else {
            $this->page_data['prev_link'] = '';
        }
        if ( $offset < ( $this->page_data['item_count'] - 10 ) ) {
            $this->page_data['next_link'] = 'articles/topic/' . $topic_slug . '/' . ($offset + 10);
        }
        else {
            $this->page_data['next_link'] = '';
        }
        $this->page_data['page_title'] = lang('article_topic_list') . $topic_title;
        $this->page_data['page_name'] = lang('article_topic_list') . $topic_title;
        $this->page_data['center_view'] = 'article/review_list';
        $this->load->view('layouts/base', $this->page_data);
    }

    public function releases($year = 0, $offset = 0)
    {
        // init
        if ( $year == 0 ) {
            redirect('');
        }

        // get data
        $this->page_data['main_list'] = $this->Article_model->get_release_year_articles($year,
                10, $offset);
        $article_count = $this->Article_model->get_release_year_count($year);
        $this->page_data['item_count'] = $article_count[$year]->acount;
        $this->page_data['offset'] = $offset;
		$this->page_data['trace'] = $this->Article_model->trace;
//        echo print_r($this->page_data['main_list'], TRUE); exit;

		// display
        if ( $offset >= 10 ) {
		    $this->page_data['prev_link'] = 'articles/releases/' . $year . '/' . ($offset - 10);
        }
        else {
            $this->page_data['prev_link'] = '';
        }
        if ( $offset < ( $this->page_data['item_count'] - 10 ) ) {
            $this->page_data['next_link'] = 'articles/releases/' . $year . '/' . ($offset + 10);
        }
        else {
            $this->page_data['next_link'] = '';
        }
        $this->page_data['page_title'] = lang('article_release_year_list') . ' ' . $year;
        $this->page_data['page_name'] = 'Reviews for ' . $year . ' releases';
		$this->page_data['center_view'] = 'article/review_list';
		$this->load->view('layouts/base', $this->page_data);
    }

    public function recordings($year = 0, $offset = 0)
    {
        // init
        if ( $year == 0 ) {
            redirect('');
        }

        // get data
        $this->page_data['main_list'] = $this->Article_model->get_recorded_year_articles($year,
            10, $offset);
        $article_count = $this->Article_model->get_recorded_year_count($year);
        $this->page_data['item_count'] = $article_count[$year]->acount;
        $this->page_data['offset'] = $offset;
        $this->page_data['trace'] = $this->Article_model->trace;
//        echo print_r($this->page_data['main_list'], TRUE); exit;

        // display
        if ( $offset >= 10 ) {
            $this->page_data['prev_link'] = 'articles/recordings/' . $year . '/' . ($offset - 10);
        }
        else {
            $this->page_data['prev_link'] = '';
        }
        if ( $offset < ( $this->page_data['item_count'] - 10 ) ) {
            $this->page_data['next_link'] = 'articles/recordings/' . $year . '/' . ($offset + 10);
        }
        else {
            $this->page_data['next_link'] = '';
        }
        $this->page_data['page_title'] = lang('article_recording_year_list') . ' ' . $year;
        $this->page_data['page_name'] = 'Reviews for ' . $year . ' recordings';
        $this->page_data['center_view'] = 'article/review_list';
        $this->load->view('layouts/base', $this->page_data);
    }

    public function issue($issue_no = 0)
    {
        // init
        if ( $issue_no == 0 ) {
            redirect('');
        }

        // get data
        $issue = $this->Article_model->get_issue_details($issue_no, TRUE);
        $this->page_data['main_list'] = $issue->contents;
        $this->page_data['item_count'] = count($this->page_data['main_list']);
        $this->page_data['offset'] = '0';
        $issue->clear_contents();
        $this->page_data['page_issue'] = $issue;
        $this->page_data['trace'] = $this->Article_model->trace;
        $this->page_data['sidebar_list'] = $this->Article_model->get_most_recent_updates();
        $this->page_data['issue_list'] = $this->Article_model->get_issue_details('0', FALSE);

		// display
		$this->page_data['prev_link'] = '';
		$this->page_data['next_link'] = '';
        $this->page_data['page_title'] = lang('issue_available') . $issue_no;
        $this->page_data['page_name'] = lang('issue_available') . $issue_no;
        $this->page_data['page_name'] = lang('issue_no'). ' ' . $issue_no;
		$this->page_data['center_view'] = 'article/review_list';
        $this->page_data['right_side'] = 'partials/side_recent_updates';
		$this->load->view('layouts/base', $this->page_data);
    }
    
	public function future()
	{
        // init
        if ( ! $this->ion_auth->logged_in() ) {
            redirect('');
        }
//        echo 'articles for ' . $category; exit;

		// get data
        $this->page_data['main_list'] = $this->Article_model->get_future_dated(); 
		$this->page_data['trace'] = $this->Article_model->trace;

		// display
        $this->page_data['prev_link'] = '';
		$this->page_data['next_link'] = '';
        $this->page_data['page_name'] = lang('article_future_dated');
        $this->page_data['page_title'] = lang('article_future_dated');
		$this->page_data['menu_active'] = 'home';
		$this->page_data['center_view'] = 'article/review_list';
		$this->load->view('layouts/base', $this->page_data);
	}

	public function drafts()
	{
        // init
        if ( ! $this->ion_auth->logged_in() ) {
            redirect('');
        }
//        echo 'articles for ' . $category; exit;

		// get data
        if ( $this->page_data['user_group'] == 'admin' ) {
            $this->page_data['main_list'] = $this->Article_model->articles_by_status('draft'); 
        }
        else { // not admin - only show user's own
            $this->page_data['main_list'] = $this->Article_model->get_author_article_list($this->page_data['user_id'], 
                    'draft', TRUE); 
        }
		$this->page_data['trace'] = $this->Article_model->trace;

		// display
        $this->page_data['prev_link'] = '';
		$this->page_data['next_link'] = '';
        $this->page_data['page_name'] = lang('article_drafts_page_name');
        $this->page_data['page_title'] = lang('article_drafts_page_name');
		$this->page_data['menu_active'] = 'home';
		$this->page_data['center_view'] = 'article/review_list';
		$this->load->view('layouts/base', $this->page_data);
	}

	public function submissions()
	{
        // init
        if ( ! $this->ion_auth->logged_in() ) {
            redirect('');
        }
//        echo 'articles for ' . $category; exit;

		// get data
        if ( $this->page_data['user_group'] == 'admin' ) {
            $this->page_data['main_list'] = $this->Article_model->articles_by_status('submitted', 'any');
        }
        else { // not admin - only show user's own
            $this->page_data['main_list'] = $this->Article_model->get_author_article_list($this->page_data['user_id'], 
                    'submitted', TRUE); 
        }
		$this->page_data['trace'] = $this->Article_model->trace;

		// display
        $this->page_data['prev_link'] = '';
		$this->page_data['next_link'] = '';
        $this->page_data['page_name'] = lang('article_submissions_page_name');
        $this->page_data['page_title'] = lang('article_submissions_page_name');
		$this->page_data['menu_active'] = 'home';
		$this->page_data['center_view'] = 'article/review_list';
		$this->load->view('layouts/base', $this->page_data);
	}

    public function edit($article_slug = '', $release_id = 0)
    {
        // init
        if ( ! $this->ion_auth->logged_in() ) {
            redirect('');
        }
        if ( $this->input->post('article-slug') ) {
            $article_slug = $this->input->post('article-slug');
        }
        $this->load->model('Artist_model');
        $this->load->model('Release_model');
        $this->load->model('Person_model');

        // get data
        $article = $this->Article_model->get_full($article_slug);
//        echo $this->Article_model->trace; exit;
        if ( $article->id == 0 ) { // new article
            if ( $release_id != 0 ) { // new review for release
                $release = $this->Release_model->get_release_info($release_id);
                $this->Release_model->get_release_artists($release);
                $article->release_list[$release_id] = $release;
                $article->title = $release->display_artist . ' - ' . $release->display_title;
                $article->credit_list['1'][$this->page_data['user_id']] = $this->page_data['user_id'];
                $article->artist_list = $release->artist_list;
                $release->clear_artist_list();
                $article->topic_list[$release->release_type_id] = $release->release_type;
                $article->category_id = 1;
            }
            else { // not a review
                // ??
            }
        }
        else {
            $this->Article_model->get_credits($article);
            $this->Article_model->get_meta($article);
            $this->Article_model->get_artists($article);
            $this->Article_model->get_releases($article);
            $this->Article_model->get_link_list($article);
            $this->Article_model->get_topics($article);
        }
//        echo 'article ' . print_r($article) . PHP_EOL . PHP_EOL; exit;

        // process input
        if ( $this->input->post('article-category') ) {
//            echo 'post ' . print_r($this->input->post()) . PHP_EOL . PHP_EOL; exit;
            $update_count = $article->process_post_values($this->input->post(), 
                    $this->page_data['user_group']);
//            echo '<pre> ' . print_r($article) . '</pre>'; exit;
            if ( $update_count ) {
                $article->update_values['user_id'] = $this->page_data['user_id'];
                if ( ( $article->id == '0' ) && ( ! isset($article->update_values['slug']) ) ) {
                    $article->update_values['slug'] = create_unique_slug($article->update_values['title'] . '-'
                            . $article->update_values['user_id'], 'articles');
                    $article_slug = $article->update_values['slug'];
                }
                $edit_result = $this->Article_model->update_info($article);
//                echo print_r($this->Article_model->trace, TRUE); exit;
                if ( $edit_result['status'] == 'ok' ) {
                    $this->set_flashdata_alert('success', lang('success_message'));
                    redirect('articles/display/' . $article_slug);    
                }
            }
        }

        // lists for dropdowns
        $this->page_data['artist_list'] = $this->Artist_model->get_artist_select_list();
        $this->page_data['person_list'] = $this->Person_model->get_person_list();
        $this->page_data['topic_list'] = $this->Article_model->get_topic_select_list();
        $this->page_data['issue_list'] = $this->Article_model->get_issue_select_list();
        $this->page_data['status_list'] = array(
            'draft' => 'Draft', 
            'submitted' => 'Submitted',
            'live' => 'Live'
        );

        // display
        $this->page_data['article'] = $article;
        $this->page_data['release_id'] = $release_id;
        $this->page_data['page_title'] = lang('article_edit_page_name');
        $this->page_data['page_name'] = lang('article_edit_page_name');
        $this->page_data['left_column_width'] = '1';
        $this->page_data['left_side'] = 'partials/side_column_blank';
        $this->page_data['center_column_width'] = '10';
		$this->page_data['center_view'] = 'article/article_form';
        $this->page_data['right_column_width'] = '1';
        $this->page_data['right_side'] = 'partials/side_column_blank';
		$this->load->view('layouts/base', $this->page_data);
    }

    public function add($category_slug = '')
    {
        // init
        if ( ! $this->ion_auth->logged_in() ) {
            redirect('');
        }
        if ( $this->input->post('category-slug') ) {
            $category_slug = $this->input->post('category-slug');
        }
        if ( $category_slug == '' ) {
            redirect('');
        }
        $this->load->model('Artist_model');
        $this->load->model('Person_model');

        // get data
        $category_id = $this->Article_model->get_category_id($category_slug);
        $article = new ExArticle();
        $article->category_id = $category_id;
        $article->credit_list['1'][$this->page_data['user_id']] = $this->page_data['user_id'];

        // lists for dropdowns
        $this->page_data['artist_list'] = $this->Artist_model->get_artist_select_list();
        $this->page_data['person_list'] = $this->Person_model->get_person_list();
        $this->page_data['topic_list'] = $this->Article_model->get_topic_select_list();
        $this->page_data['issue_list'] = $this->Article_model->get_issue_list();
        $this->page_data['status_list'] = array(
            'draft' => 'Draft', 
            'submitted' => 'Submitted',
            'live' => 'Live'
        );

        // display
        $this->page_data['article'] = $article;
        $this->page_data['release_id'] = 0;
        $this->page_data['page_title'] = lang('article_edit_page_name');
        $this->page_data['page_name'] = lang('article_edit_page_name');
		$this->page_data['center_view'] = 'article/article_form';
		$this->load->view('layouts/base', $this->page_data);
    }

}

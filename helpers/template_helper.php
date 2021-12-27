<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function build_head($site_name, $page_name, $gtag = '', $can_edit = FALSE )
{ 
    $result = '<head>' . PHP_EOL;
    if ( $gtag != '' ) {
        $result .= build_google_analytics($gtag);
    }
    $result .= '<meta charset="utf-8">' . PHP_EOL;
    $result .= '<meta property="og:title" content="' . $page_name . '"/>' . PHP_EOL;
    $result .= '<meta property="og:site_name" content="' . $site_name . '"/>' . PHP_EOL;
    $result .= '<title>' . $site_name . ' | ' . $page_name . '</title>' . PHP_EOL;
    $result .= '<link rel="shortcut icon" href="/assets/img/site/favicon.ico" />' . PHP_EOL;
    $result .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;
    $result .= '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" '
            . 'rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" '
            . 'crossorigin="anonymous">' . PHP_EOL;
    $result .= '<script src="https://kit.fontawesome.com/97d111f6f0.js" crossorigin="anonymous"></script>' . PHP_EOL;
    if ( $can_edit ) {
        $result .= '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css?v3.1" rel="stylesheet" />' . PHP_EOL;
        // load whatever is needed for ckedit
    }
    $result .= '<link rel="stylesheet" href="' . base_url('assets/css/expose3.css') . '">' . PHP_EOL;
    $result .= '</head>' . PHP_EOL;
    return $result;
}

function menu_item($link, $lang, $active = FALSE)
{
    $result = '<li class="nav-item">' . PHP_EOL;
    $attr = 'class="nav-link';
    if ( $active ) $attr .= ' active';
    $attr .= '" aria-current="page"';
    $result .=  anchor($link, lang($lang), $attr) . PHP_EOL;
    $result .= '</li>' . PHP_EOL;
    return $result;
}

/*
 * see https://getbootstrap.com/docs/5.0/components/navbar/
 */
function build_menu($menu_active = 'home', $user_name = '', $user_group = '')
{
    $result = '<nav class="navbar navbar-expand-lg">' . PHP_EOL;
    $result .= '<div class="container-fluid">' . PHP_EOL;
//    $result .= '<a class="navbar-brand" href="#">Navbar</a>' . PHP_EOL;
    $result .= '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" '
            . 'data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" '
            . 'aria-expanded="false" aria-label="Toggle navigation">' . PHP_EOL;
    $result .= '<span class="navbar-toggler-icon"></span>' . PHP_EOL;
    $result .= '</button>' . PHP_EOL;
    $result .= '<div class="collapse navbar-collapse" id="navbarSupportedContent">' . PHP_EOL;
    $result .= '<ul class="navbar-nav me-auto mb-2 mb-lg-0">' . PHP_EOL;

    $result .= menu_item('', 'menu_home', ($menu_active == 'home'));
    $result .= menu_item('articles/index/reviews', 'menu_reviews', ($menu_active == 'reviews'));
    $result .= menu_item('articles/index/features', 'menu_features', ($menu_active == 'features'));
    $result .= menu_item('articles/index/news', 'menu_news', ($menu_active == 'news'));
    $result .= menu_item('articles/index/recommendations', 'menu_recommendations', ($menu_active == 'recommendations'));
    $result .= menu_item('artists/index', 'menu_artists', ($menu_active == 'artists'));
    $result .= menu_item('home/about', 'menu_about', ($menu_active == 'about'));
    $result .= menu_item('articles/index/faqs', 'menu_faq', ($menu_active == 'faqs'));

    if ( $user_name == '' ) {
        $result .= menu_item('auth/login', 'menu_login', FALSE);
    }
    else {
        $result .= '<li class="nav-item dropdown">' . PHP_EOL;
        $result .= '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" '
            . 'data-bs-toggle="dropdown" aria-expanded="false">' . $user_name . '</a>' . PHP_EOL;
        $result .= '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">' . PHP_EOL;
        // dropdown items
        $result .= '<li>' . anchor('articles/add/recommendations', lang('add_recommendation'),
                'class="dropdown-item"') . PHP_EOL;
        $result .= '<li>' . anchor('articles/submissions', lang('menu_submissions'),
                'class="dropdown-item"') . PHP_EOL;
        $result .= '<li>' . anchor('articles/drafts', lang('menu_drafts'),
                'class="dropdown-item"') . PHP_EOL;
        $result .= '<li>' . anchor('articles/future', lang('menu_future'),
                'class="dropdown-item"') . PHP_EOL;
        $result .= '<li>' . anchor('artists/edit/', lang('artist_add'),
                'class="dropdown-item"') . PHP_EOL;
        if ( $user_group == 'admin' ) {
            $result .= '<li><hr class="dropdown-divider"></li>' . PHP_EOL;
            $result .= '<li>' . anchor('auth/index', lang('menu_user_maintenance'),
                    'class="dropdown-item"') . PHP_EOL;
        }
        $result .= '<li><hr class="dropdown-divider"></li>' . PHP_EOL;
        $result .= '<li>' . anchor('auth/change-password', lang('menu_change_password'),
                'class="dropdown-item"') . PHP_EOL;
        $result .= '<li>' . anchor('auth/logout', lang('menu_logout'),
                'class="dropdown-item"') . PHP_EOL;
        $result .= '</ul>' . PHP_EOL;
        $result .= '</li>' . PHP_EOL;
    }
    $result .= '</ul>' . PHP_EOL;
    $result .= form_open('home/search', array('id' => 'search-form', 'class' => 'd-flex'),
            array('search_type' => 'all'));
    $result .= form_input(array('name' => 'searchstring', 'id' => 'searchstring', 'class' => 'form-control mr-sm-1',
            'type' => 'search', 'placeholder' => 'Search', 'aria-label' => 'Search'));
    $result .= '<button class="btn search-btn" type="submit"><i class="fa fa-search"></i></button>' . PHP_EOL;
    $result .= '</form>' . PHP_EOL;
    $result .= '</div>' . PHP_EOL;
    $result .= '</div>' . PHP_EOL;
    $result .= '</nav>' . PHP_EOL;
    return $result;
}

function xbuild_menu($menu_active = 'home', $user_name = '', $user_group = '')
{
    $result = '<nav class="navbar navbar-default navbar-expand-lg">' . PHP_EOL;
    $result .= '<div class="container-fluid">' . PHP_EOL;
    $result .= '<div class="navbar-header">' . PHP_EOL;
    $result .= '<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#expose-navbar">' . PHP_EOL;
    $result .= '<span class="icon-bar"></span>' . PHP_EOL;
    $result .= '<span class="icon-bar"></span>' . PHP_EOL;
    $result .= '<span class="icon-bar"></span> ' . PHP_EOL;
    $result .= '</button>' . PHP_EOL;
    $result .= '</div> <!-- navbar header -->' . PHP_EOL;
    $result .= '<div class="collapse navbar-collapse" id="expose-navbar">' . PHP_EOL;
    $result .= '<ul class="nav navbar-nav" id="menu">' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'home') $result .= ' class="active"';
    $result .= '>';
    $result .= '</li>' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'features') $result .= ' class="active"';
    $result .= '>' . anchor('articles/index/features', lang('menu_features'));
    $result .= '</li>' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'reviews') $result .= ' class="active"';
    $result .= '>' . anchor('articles/index/reviews', lang('menu_reviews'));
    $result .= '</li>' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'news') $result .= ' class="active"';
    $result .= '>' . anchor('articles/index/news', lang('menu_news'));
    $result .= '</li>' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'recommendations') $result .= ' class="active"';
    $result .= '>' . anchor('articles/index/recommendations', lang('menu_recommendations'));
    $result .= '</li>' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'artists') $result .= ' class="active"';
    $result .= '>' . anchor('artists/index', lang('menu_artists'));
    $result .= '</li>' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'about') $result .= ' class="active"';
    $result .= '>' . anchor('home/about', lang('menu_about'));
    $result .= '</li>' . PHP_EOL;
    $result .= '<li';
    if ($menu_active == 'faqs') $result .= ' class="active"';
    $result .= '>' . anchor('articles/index/faqs', lang('menu_faq'));
    $result .= '</li>' . PHP_EOL;
    $result .= '<li>';
    $result .= '</li>' . PHP_EOL;
    $result .= '</ul>' . PHP_EOL;
    $result .= '<ul class="nav navbar-nav navbar-right">' . PHP_EOL;
    if ( $user_name != '' ) {
        $result .= '<li class="dropdown">' . PHP_EOL;
        $result .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">';
        $result .= $user_name . '&nbsp;';
        $result .= '<span class="caret"></span></a>' . PHP_EOL;
        $result .= ' <ul class="dropdown-menu">' . PHP_EOL;
        $result .= '<li>';
        $result .= anchor('articles/add/recommendations', lang('add_recommendation'));
        $result .= '</li>' . PHP_EOL;
        $result .= '<li>';
        $result .= anchor('articles/submissions', lang('menu_submissions'));
        $result .= '</li>' . PHP_EOL;
        $result .= '<li>';
        $result .= anchor('articles/drafts', lang('menu_drafts'));
        $result .= '</li>' . PHP_EOL;
        $result .= '<li>';
        $result .= anchor('articles/future', lang('menu_future'));
        $result .= '</li>' . PHP_EOL;
        $result .= '<li>';
        $result .= anchor('artists/edit/', lang('artist_add'));
        $result .= '</li>' . PHP_EOL;
        if ( $user_group == 'admin' ) {
            $result .= '<li>';
            $result .= anchor('auth/index', lang('menu_user_maintenance'));
            $result .= '</li>' . PHP_EOL;
        }
        $result .= '<li>';
        $result .= anchor('auth/change-password', lang('menu_change_password'));
        $result .= '</li>' . PHP_EOL;
        $result .= '<li>';
        $result .= anchor('auth/logout', lang('menu_logout'));
        $result .= '</li>' . PHP_EOL;
        $result .= '</ul>' . PHP_EOL;
        $result .= '</li>' . PHP_EOL;
    }
    else {
        $result .= '<li>';
        $result .= anchor('auth/login', lang('menu_login'));
        $result .= '</li>' . PHP_EOL;
    }
    $result .= '</ul>' . PHP_EOL;
    $result .= form_open('home/search', array('id' => 'search-form', 'class' => 'navbar-form navbar-right topsearch'),
            array('search_type' => 'all'));
    $result .= form_input(array('name' => 'searchstring', 'id' => 'searchstring', 'class' => 'form-control mr-sm-1',
            'type' => 'search', 'placeholder' => 'Search', 'aria-label' => 'Search'));
    $result .= '<button class="btn search-btn" type="submit"><i class="fa fa-search"></i></button>' . PHP_EOL;
    $result .= '</form>' . PHP_EOL; 
    $result .= '</div> <!-- navbar collapse -->' . PHP_EOL;
    $result .= '</div> <!-- container fluid -->' . PHP_EOL;
    $result .= '</nav>' . PHP_EOL;
    return $result;
}

function build_page_end_scripts($can_edit = FALSE)
{
    $result = '<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" '
            . 'integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" '
            . 'crossorigin="anonymous"></script></script>' . PHP_EOL;
    $result .= '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" '
            . 'integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" '
            . 'crossorigin="anonymous"></script>' . PHP_EOL;
    if ( $can_edit ) {
        $result .= '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>' . PHP_EOL;
        $result .= '<script src="' . base_url('assets/js/ckeditor/ckeditor.js') . '"></script>' . PHP_EOL;
    }
    $result .= '<script src="' . base_url('assets/js/expose3.js') . '"></script>' . PHP_EOL;
    return $result;
}

function get_template_section($file_name)
{
    echo APPPATH . 'views/' . $file_name . '.php';
    include(APPPATH . 'views/' . $file_name . '.php');
}

function build_footer($hit_count = 0, $show_trace = FALSE, $trace = '')
{
    $result = '<div class="row" id="footer">' . PHP_EOL;
    $result .= '<div class="col-sm-6">' . PHP_EOL;
    $result .= '<p>' . PHP_EOL;
    $result .= '<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/">' . PHP_EOL;
    $result .= '<img alt="Creative Commons License" style="border-width:0" ';
    $result .= '     src="http://i.creativecommons.org/l/by-nc-sa/3.0/88x31.png" /></a>' . PHP_EOL;
    $result .= '     This <span xmlns:dct="http://purl.org/dc/terms/" ';
    $result .= '                href="http://purl.org/dc/dcmitype/Text" rel="dct:type">work</span>' . PHP_EOL;
    $result .= '     is licensed under a <a rel="license" ' . PHP_EOL;
    $result .= '            href="http://creativecommons.org/licenses/by-nc-sa/3.0/">Creative ';
    $result .= '         Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License</a>.' . PHP_EOL;
    $result .= '</p>' . PHP_EOL;
    $result .= '</div>' . PHP_EOL;
    $result .= '<div class="col-sm-6">' . PHP_EOL;
    if ( $hit_count ) {
        // display hits
    }
    $result .= '</div> <!-- col -->' . PHP_EOL;
    $result .= '</div> <!-- footer row -->' . PHP_EOL;
    if ( $show_trace) {
        $result .= '<div class="row" class="well">';
        $result .= '<div class="col-sm-12">';
        $result .= '<div class="btn-group" data-toggle="buttons-checkbox">';
        $result .= '<a class="btn collapse-data-btn btn-default" data-toggle="collapse" href="#details">Show details</a>';
        $result .= '</div>';
        $result .= '<div id="details" class="collapse">';
        $result .= '<pre>';
        $result .= $trace;
        $result .= '</pre>';
        $result .= '</div>';
        $result .= '</div>';
        $result .= '</div>';
    }
    return $result;
}

function build_google_analytics($tag)
{
    $result = '<!-- Global site tag (gtag.js) - Google Analytics -->' . PHP_EOL;
    $result .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $tag . '"></script>' . PHP_EOL;
    $result .= '<script>' . PHP_EOL;
    $result .= 'window.dataLayer = window.dataLayer || [];' . PHP_EOL;
    $result .= 'function gtag(){dataLayer.push(arguments);}' . PHP_EOL;
    $result .= "gtag('js', new Date());" . PHP_EOL;
    $result .= "gtag('config', '" . $tag . "');" . PHP_EOL;
    $result .= '</script>' . PHP_EOL;
    return $result;
}

function build_article_list($article_list)
{
    $result = '<ul>' . PHP_EOL;
    foreach ($article_list as $item) {
        $result .= '<li>';
        $result .= $item->category_name . ': ' . anchor('articles/display/' 
                    . $item->slug, $item->title);
        if ( $item->category_id != 5 ) {
            $result .= '<em> (' . credit_display($item->credit_list, 1) . ' '
                    . substr($item->published_on, 0, 10) . ')</em>';
        }
        $result .= '</li>' . PHP_EOL;
    }
    $result .= '</ul>' . PHP_EOL;
    return $result;
}

function build_release_list($release_list, $can_edit = FALSE, $link_label = TRUE)
{
    $result = '<table class="table-bordered release-list">';
    foreach ($release_list as $item) {
        $result .= '<tr class="release-item">' . PHP_EOL;
        $result .= '<td>' . PHP_EOL;
        $result .= '<img src="' . image_url('releases/' . $item->image_file) 
                . '" class="artist-release-art img-fluid" width="220" alt="'
                . $item->display_artist . ' &mdash; ' . $item->display_title
                . '" title="' . $item->display_artist . ' &mdash; ' . $item->display_title
                . '">' . PHP_EOL;
        $result .= '</td>' . PHP_EOL;
        $result .= '<td>' . PHP_EOL;
        $result .= anchor('releases/display/'. $item->id, 
                $item->display_artist . ' &mdash; ' . $item->display_title);
        $result .= '<br/>' . PHP_EOL;
        $result .= release_line($item, $link_label);
        $result .= '</td>' . PHP_EOL;
        if ( $can_edit ) {
            $result .= '<td>' . PHP_EOL;
            $result .= 'ID: ' . $item->id . '<br>' . PHP_EOL;
            $result .= anchor('releases/edit/' . $item->id, lang('release_edit_button'),
                    array('class' => 'btn btn-primary')) . '<br>' . PHP_EOL;
            $result .= anchor('articles/edit/0/' . $item->id, lang('release_review'),
                    array('class' => 'btn btn-success')) . PHP_EOL;
            $result .= '</td>' . PHP_EOL;
        }
        $result .= '</tr>' . PHP_EOL;
    }
    $result .= '</table>';
    return $result;
}

function result_type_time_display($item)
{
    if ( ( $item->result_type == 'Artist' ) || ( $item->result_type == 'Release' ) ) {
        return TRUE;
    }
    else {
        return FALSE;
    }
}

function build_brief_item_list($item_list, $list_id = '')
{
    if ( $list_id ) {
        $result = '<ul id="' . $list_id . '">' . PHP_EOL;
    }
    else {
        $result = '<ul>' . PHP_EOL;
    }
    foreach ($item_list as $item) {
        $result .= '<li>';
        $result .= $item->result_type . ': ' . anchor($item->url, $item->display) . '<br>';
        if ( result_type_time_display($item) ) {
            $result .= '<em>Updated ' . $item->updated . '</em>';
        }
        else {
            $result .= '<em>Published ' .substr($item->updated, 0, 10) . '</em>';
        }
        $result .= '</li>' . PHP_EOL;
    }
    $result .= '</ul>' . PHP_EOL;
    return $result;
}

/*
-- expose_exposeorg4325340.all_search source
CREATE OR REPLACE
ALGORITHM = UNDEFINED VIEW expose_exposeorg4325340.all_search AS
select
    concat('art', a1.id) AS skey,
    'Artist' AS result_type,
    a1.slug AS slug,
    a1.display AS display,
    concat_ws(' ', a1.name, a1.display) AS name,
    c1.name AS extra,
    concat_ws('/', 'artists', a1.image_file) AS image_path,
    concat_ws('/', 'artists', 'display', a1.slug) AS url,
    coalesce(a1.updated, a1.created) AS updated
from
    (expose_exposeorg4325340.artists a1
join expose_exposeorg4325340.countries c1 on
    ((c1.id = a1.country_id)))
union
select
    concat('rel', r2.id) AS skey,
    'Release' AS result_type,
    r2.id AS slug,
    concat_ws(' - ', r2.display_artist, r2.display_title) AS display,
    concat_ws(' ', r2.artist, r2.title, r2.display_artist, r2.display_title) AS name,
    concat_ws(', ', l2.display, r2.year_released) AS extra,
    concat_ws('/', 'releases', r2.image_file) AS image_path,
    concat_ws('/', 'releases', 'display', r2.id) AS url,
    coalesce(r2.updated, r2.created) AS updated
from
    (expose_exposeorg4325340.releases r2
left join expose_exposeorg4325340.labels l2 on
    ((l2.id = r2.label_id)))
union
select
    concat('rev', a3.id) AS skey,
    'Review' AS result_type,
    a3.slug AS slug,
    a3.title AS display,
    concat_ws(' ', r3.artist, r3.title, r3.display_title, r3.display_artist, a3.title) AS name,
    (case
        when (a3.issue_no > 0) then concat('Issue ', a3.issue_no)
        else a3.published_on
    end) AS extra,
    concat_ws('/', 'releases', r3.image_file) AS image_path,
    concat_ws('/', 'articles', 'display', a3.slug) AS url,
    coalesce(a3.updated_on, a3.created_on) AS updated
from
    ((expose_exposeorg4325340.articles a3
join expose_exposeorg4325340.article_release ar3 on
    ((ar3.article_id = a3.id)))
join expose_exposeorg4325340.releases r3 on
    ((r3.id = ar3.release_id)))
where
    ((a3.category_id = 1)
        and (a3.status = 'live')
            and (a3.published_on <= curdate()))
union
select
    concat('art', a4.id) AS skey,
    c4.title AS result_type,
    a4.slug AS slug,
    a4.title AS display,
    a4.title AS name,
    (case
        when (a4.issue_no > 0) then a4.issue_no
        else a4.published_on
    end) AS extra,
    (case
        when (a4.category_id = 4) then concat_ws('/', 'features', a4.image_file)
        else ''
    end) AS image_path,
    concat_ws('/', 'articles', 'display', a4.slug) AS url,
    coalesce(a4.updated_on, a4.created_on) AS updated
from
    (expose_exposeorg4325340.articles a4
join expose_exposeorg4325340.categories c4 on
    ((c4.id = a4.category_id)))
where
    ((a4.category_id in (2, 4))
        and (a4.status = 'live')
            and (a4.published_on <= curdate()))
union
select
    concat('med', a5.id) AS skey,
    c5.title AS result_type,
    a5.slug AS slug,
    a5.title AS display,
    a5.title AS name,
    a5.body AS extra,
    '' AS image_path,
    concat_ws('/', 'articles', 'display', a5.slug) AS url,
    coalesce(a5.updated_on, a5.created_on) AS updated
from
    (expose_exposeorg4325340.articles a5
join expose_exposeorg4325340.categories c5 on
    ((c5.id = a5.category_id)))
where
    ((a5.category_id = 8)
        and (a5.status = 'live')
            and (a5.published_on <= curdate()));

CREATE OR REPLACE
ALGORITHM = UNDEFINED VIEW expose_exposeorg4325340.user_list AS
SELECT 
	u.id, 
	u.ip_address, 
	u.username, 
	up.display_name,
	up.sort_name,
	u.email, 
	u.last_login, 
	u.active, 
	u.first_name, 
	u.last_name, 
	(select count(aur.article_id) from expose_exposeorg4325340.article_user_role aur 
        where aur.user_id = u.id) AS article_count
FROM expose_exposeorg4325340.users u
join expose_exposeorg4325340.user_profiles up on up.user_id = u.id;

CREATE OR REPLACE
ALGORITHM = UNDEFINED VIEW expose_exposeorg4325340.person_articles AS
select
    aur.user_id AS user_id,
    a.title AS title,
    a.slug AS slug,
    a.category_id AS category_id,
    c.item_name AS category_name,
    a.published_on AS published_on,
    a.status AS status,
    a.id AS article_id
from expose_exposeorg4325340.article_user_role aur 
join expose_exposeorg4325340.articles a on
    a.id = aur.article_id
join expose_exposeorg4325340.categories c on
    c.id = a.category_id;
*/
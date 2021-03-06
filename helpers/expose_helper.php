<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * various miscelaneous functions
 */

/**
 *
 * @param type $string
 * @param type $table
 * @param type $field
 * @param type $key
 * @param type $value
 * @return type 
 */
function create_unique_slug($string, $table, $field = 'slug', $key = NULL, $value = NULL)
{
    $t =& get_instance(); 

    $slug = url_title($string);
    $slug = strtolower($slug);
    $slug = remove_accents($slug);
    $i = 0;
    $params = array();
    $params[$field] = $slug;

    if ($key)$params["$key !="] = $value;

    while ( $t->db->where($params)->get($table)->num_rows() ) {
        if ( ! preg_match ('/-{1}[0-9]+$/', $slug ) ) {
            $slug .= '-' . ++$i;
        } 
        else {
            $slug = preg_replace ('/[0-9]+$/', ++$i, $slug );
        }
        $params [$field] = $slug;
    }
    return $slug;
}
	
function credit_display($credit_list, $role_id)
{
    $result = '';
    if (is_array($credit_list)) {
        foreach ($credit_list[$role_id] as $id => $item) {
            if ($result != '') {
                $result .= ', ';
            }
            if (isset($item)) {
                $result .= '<a href="' . site_url('home/person/' . $id) 
                            . '">' . $item . '</a>';
            }
            else {
                $result .= '(??)';
            }
        }
    }
    return $result;
}

function topic_display($topic_list)
{
    $result = '';
    if (is_array($topic_list)) {
        $temp_array = array();
        foreach ($topic_list as $item) {
                $temp_array[$item->topic_slug] = trim(anchor('articles/topic/' . $item->topic_slug, $item->topic_title));
        }
        $result = implode(', ', $temp_array);
    }
    return $result;
}

function artist_display($artist_list)
{
    $result = '';
    if (is_array($artist_list)) {
        foreach ($artist_list as $artist) {
            if ($result != '') {
                $result .= ', ';
            }
            $result .= anchor('artists/display/' . $artist->slug, $artist->display);
        }	
    }
    return $result;
}

function release_line($release, $link_label = TRUE)
{
    $result = '(';
    if ($release->label_name) {
        if ( $link_label ) {
            $result .= anchor('labels/display/' . $release->label_id, $release->label_name) . ' ';
        }
        else {
            $result .= $release->label_name . ' ';
        }
        }
    $result .= $release->catalog_no . ', ';
    if (($release->year_recorded > 0) 
            && ($release->year_recorded != $release->year_released)) {
        $result .= $release->year_recorded . '/';
    }
    $result .= $release->year_released. ', ';
    $result .= $release->media . ')';
    return $result;
}

function smart_trim($src, $max_len)
{
    $result = $src;
    if (strlen($result) > $max_len) {
        $temp = wordwrap($result, $max_len, '|||');
        $temp = explode('|||', $temp);
       $result = array_shift($temp) . '...'; 
   }
   return restore_tags($result);
}

function restore_tags($input) { 
    // Original PHP code by Chirp Internet: www.chirp.com.au 
    // Please acknowledge use of this code by including this header.     
    $opened = array(); 
    // loop through opened and closed tags in order 
    if (preg_match_all("/<(\/?[a-z]+)>?/i", $input, $matches)) { 
        foreach ($matches[1] as $tag) { 
            if (preg_match("/^[a-z]+$/i", $tag, $regs)) { // a tag has been opened 
                if (strtolower($regs[0]) != 'br') $opened[] = $regs[0]; 
            } 
            elseif (preg_match("/^\/([a-z]+)$/i", $tag, $regs)) { 
                // a tag has been closed 
                $temp = array_keys($opened, $regs[1]);
                $temp = array_pop($temp);
                unset($opened[$temp]); 
            } 
        } 
    } // close tags that are still open 
    if ($opened) { 
        $tagstoclose = array_reverse($opened); 
        foreach($tagstoclose as $tag) $input .= "</$tag>"; 
    } 
    return $input; 
}

function image_url($image_file)
{
    return(base_url('assets/img/'. $image_file));
}

/**
* Anchor Image Link
*
* Creates an anchor on a image based on the local URL.
* Based on the CodeIgniters original Anchor Link and Image (img()).
*
* Author(s): Isern Palaus <ipalaus@ipalaus.es>
*
* @access    public
* @param    string    the URL
* @param    string    the image URL
* @param    string    the image alt
* @param    mixed    a attributes
* @param    mixed    img attributes
* @return    string
*/    
if ( ! function_exists('anchor_img'))
{
    function anchor_img($uri = '', $img = '', $alt = '', $anchor_attr = '', $image_attr = '')
    {
        $alt = (string) $alt;
        $imgatt = '';
        if ( ! is_array($uri)) {
            $site_url = ( ! preg_match('!^\w+://! i', $uri)) ? site_url($uri) : $uri;
        }
        else {
            $site_url = site_url($uri);
        }
        if ( ! is_array($img)) {
            //$image = ( ! preg_match('!^w+://! i', $img)) ? site_url($img) : $img;
            $image = image_url($img);
        }
        else {
            $image = site_url($img);
        }
        if ($image_attr) {
            foreach ($image_attr as $k=>$v) {
                $imgatt .= " $k=\"$v\" ";
            }
        }
        if ($alt == '') {
            $alt = $site_url;
        }
        if ($anchor_attr != '') {
            $anchor_attr = _parse_attributes($anchor_attr);
        }
        return '<a href="' . $site_url . '"' . $anchor_attr . '><img src="'
                . $image . '" alt="' . $alt . '"' . $imgatt . '/></a>' . "\n";
    }
}

function mbstring_binary_safe_encoding($reset = false)
{
    static $encodings  = array();
    static $overloaded = null;

    if (is_null($overloaded)) {
        $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);
    }

    if (false === $overloaded) {
        return;
    }

    if (!$reset) {
        $encoding = mb_internal_encoding();
        array_push($encodings, $encoding);
        mb_internal_encoding('ISO-8859-1');
    }

    if ($reset && $encodings) {
        $encoding = array_pop($encodings);
        mb_internal_encoding($encoding);
    }
}

function seems_utf8($str)
{
    mbstring_binary_safe_encoding();
    $length = strlen($str);
    mbstring_binary_safe_encoding(true);
    for ($i = 0; $i < $length; $i++) {
        $c = ord($str[$i]);
        if ($c < 0x80) {
            $n = 0;
        }
        // 0bbbbbbb
        elseif (($c & 0xE0) == 0xC0) {
            $n = 1;
        }
        // 110bbbbb
        elseif (($c & 0xF0) == 0xE0) {
            $n = 2;
        }
        // 1110bbbb
        elseif (($c & 0xF8) == 0xF0) {
            $n = 3;
        }
        // 11110bbb
        elseif (($c & 0xFC) == 0xF8) {
            $n = 4;
        }
        // 111110bb
        elseif (($c & 0xFE) == 0xFC) {
            $n = 5;
        }
        // 1111110b
        else {
                return false;
            }
            // Does not match any model
            for ($j = 0; $j < $n; $j++) {
                // n bytes matching 10bbbbbb follow ?
                if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80)) {
                    return false;
                }

            }
        }
        return true;
    }

    function remove_accents($string)
{
        if (!preg_match('/[\x80-\xff]/', $string)) {
            return $string;
        }

        if (seems_utf8($string)) {
            $chars = array(
                // Decompositions for Latin-1 Supplement
                '??' => 'a', '??'  => 'o',
                '??' => 'A', '??'  => 'A',
                '??' => 'A', '??'  => 'A',
                '??' => 'A', '??'  => 'A',
                '??' => 'AE', '??' => 'C',
                '??' => 'E', '??'  => 'E',
                '??' => 'E', '??'  => 'E',
                '??' => 'I', '??'  => 'I',
                '??' => 'I', '??'  => 'I',
                '??' => 'D', '??'  => 'N',
                '??' => 'O', '??'  => 'O',
                '??' => 'O', '??'  => 'O',
                '??' => 'O', '??'  => 'U',
                '??' => 'U', '??'  => 'U',
                '??' => 'U', '??'  => 'Y',
                '??' => 'TH', '??' => 's',
                '??' => 'a', '??'  => 'a',
                '??' => 'a', '??'  => 'a',
                '??' => 'a', '??'  => 'a',
                '??' => 'ae', '??' => 'c',
                '??' => 'e', '??'  => 'e',
                '??' => 'e', '??'  => 'e',
                '??' => 'i', '??'  => 'i',
                '??' => 'i', '??'  => 'i',
                '??' => 'd', '??'  => 'n',
                '??' => 'o', '??'  => 'o',
                '??' => 'o', '??'  => 'o',
                '??' => 'o', '??'  => 'o',
                '??' => 'u', '??'  => 'u',
                '??' => 'u', '??'  => 'u',
                '??' => 'y', '??'  => 'th',
                '??' => 'y', '??'  => 'O',
                // Decompositions for Latin Extended-A
                '??' => 'A', '??'  => 'a',
                '??' => 'A', '??'  => 'a',
                '??' => 'A', '??'  => 'a',
                '??' => 'C', '??'  => 'c',
                '??' => 'C', '??'  => 'c',
                '??' => 'C', '??'  => 'c',
                '??' => 'C', '??'  => 'c',
                '??' => 'D', '??'  => 'd',
                '??' => 'D', '??'  => 'd',
                '??' => 'E', '??'  => 'e',
                '??' => 'E', '??'  => 'e',
                '??' => 'E', '??'  => 'e',
                '??' => 'E', '??'  => 'e',
                '??' => 'E', '??'  => 'e',
                '??' => 'G', '??'  => 'g',
                '??' => 'G', '??'  => 'g',
                '??' => 'G', '??'  => 'g',
                '??' => 'G', '??'  => 'g',
                '??' => 'H', '??'  => 'h',
                '??' => 'H', '??'  => 'h',
                '??' => 'I', '??'  => 'i',
                '??' => 'I', '??'  => 'i',
                '??' => 'I', '??'  => 'i',
                '??' => 'I', '??'  => 'i',
                '??' => 'I', '??'  => 'i',
                '??' => 'IJ', '??' => 'ij',
                '??' => 'J', '??'  => 'j',
                '??' => 'K', '??'  => 'k',
                '??' => 'k', '??'  => 'L',
                '??' => 'l', '??'  => 'L',
                '??' => 'l', '??'  => 'L',
                '??' => 'l', '??'  => 'L',
                '??' => 'l', '??'  => 'L',
                '??' => 'l', '??'  => 'N',
                '??' => 'n', '??'  => 'N',
                '??' => 'n', '??'  => 'N',
                '??' => 'n', '??'  => 'n',
                '??' => 'N', '??'  => 'n',
                '??' => 'O', '??'  => 'o',
                '??' => 'O', '??'  => 'o',
                '??' => 'O', '??'  => 'o',
                '??' => 'OE', '??' => 'oe',
                '??' => 'R', '??'  => 'r',
                '??' => 'R', '??'  => 'r',
                '??' => 'R', '??'  => 'r',
                '??' => 'S', '??'  => 's',
                '??' => 'S', '??'  => 's',
                '??' => 'S', '??'  => 's',
                '??' => 'S', '??'  => 's',
                '??' => 'T', '??'  => 't',
                '??' => 'T', '??'  => 't',
                '??' => 'T', '??'  => 't',
                '??' => 'U', '??'  => 'u',
                '??' => 'U', '??'  => 'u',
                '??' => 'U', '??'  => 'u',
                '??' => 'U', '??'  => 'u',
                '??' => 'U', '??'  => 'u',
                '??' => 'U', '??'  => 'u',
                '??' => 'W', '??'  => 'w',
                '??' => 'Y', '??'  => 'y',
                '??' => 'Y', '??'  => 'Z',
                '??' => 'z', '??'  => 'Z',
                '??' => 'z', '??'  => 'Z',
                '??' => 'z', '??'  => 's',
                // Decompositions for Latin Extended-B
                '??' => 'S', '??'  => 's',
                '??' => 'T', '??'  => 't',
                // Euro Sign
                '???' => 'E',
                // GBP (Pound) Sign
                '??' => '',
                // Vowels with diacritic (Vietnamese)
                // unmarked
                '??' => 'O', '??'  => 'o',
                '??' => 'U', '??'  => 'u',
                // grave accent
                '???' => 'A', '???'  => 'a',
                '???' => 'A', '???'  => 'a',
                '???' => 'E', '???'  => 'e',
                '???' => 'O', '???'  => 'o',
                '???' => 'O', '???'  => 'o',
                '???' => 'U', '???'  => 'u',
                '???' => 'Y', '???'  => 'y',
                // hook
                '???' => 'A', '???'  => 'a',
                '???' => 'A', '???'  => 'a',
                '???' => 'A', '???'  => 'a',
                '???' => 'E', '???'  => 'e',
                '???' => 'E', '???'  => 'e',
                '???' => 'I', '???'  => 'i',
                '???' => 'O', '???'  => 'o',
                '???' => 'O', '???'  => 'o',
                '???' => 'O', '???'  => 'o',
                '???' => 'U', '???'  => 'u',
                '???' => 'U', '???'  => 'u',
                '???' => 'Y', '???'  => 'y',
                // tilde
                '???' => 'A', '???'  => 'a',
                '???' => 'A', '???'  => 'a',
                '???' => 'E', '???'  => 'e',
                '???' => 'E', '???'  => 'e',
                '???' => 'O', '???'  => 'o',
                '???' => 'O', '???'  => 'o',
                '???' => 'U', '???'  => 'u',
                '???' => 'Y', '???'  => 'y',
                // acute accent
                '???' => 'A', '???'  => 'a',
                '???' => 'A', '???'  => 'a',
                '???' => 'E', '???'  => 'e',
                '???' => 'O', '???'  => 'o',
                '???' => 'O', '???'  => 'o',
                '???' => 'U', '???'  => 'u',
                // dot below
                '???' => 'A', '???'  => 'a',
                '???' => 'A', '???'  => 'a',
                '???' => 'A', '???'  => 'a',
                '???' => 'E', '???'  => 'e',
                '???' => 'E', '???'  => 'e',
                '???' => 'I', '???'  => 'i',
                '???' => 'O', '???'  => 'o',
                '???' => 'O', '???'  => 'o',
                '???' => 'O', '???'  => 'o',
                '???' => 'U', '???'  => 'u',
                '???' => 'U', '???'  => 'u',
                '???' => 'Y', '???'  => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin)
                '??' => 'a',
                // macron
                '??' => 'U', '??'  => 'u',
                // acute accent
                '??' => 'U', '??'  => 'u',
                // caron
                '??' => 'A', '??'  => 'a',
                '??' => 'I', '??'  => 'i',
                '??' => 'O', '??'  => 'o',
                '??' => 'U', '??'  => 'u',
                '??' => 'U', '??'  => 'u',
                // grave accent
                '??' => 'U', '??'  => 'u',
            );

            $string = strtr($string, $chars);
        } else {
            $chars = array();
            // Assume ISO-8859-1 if not UTF-8
            $chars['in'] = "\x80\x83\x8a\x8e\x9a\x9e"
                . "\x9f\xa2\xa5\xb5\xc0\xc1\xc2"
                . "\xc3\xc4\xc5\xc7\xc8\xc9\xca"
                . "\xcb\xcc\xcd\xce\xcf\xd1\xd2"
                . "\xd3\xd4\xd5\xd6\xd8\xd9\xda"
                . "\xdb\xdc\xdd\xe0\xe1\xe2\xe3"
                . "\xe4\xe5\xe7\xe8\xe9\xea\xeb"
                . "\xec\xed\xee\xef\xf1\xf2\xf3"
                . "\xf4\xf5\xf6\xf8\xf9\xfa\xfb"
                . "\xfc\xfd\xff";

            $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

            $string              = strtr($string, $chars['in'], $chars['out']);
            $double_chars        = array();
            $double_chars['in']  = array("\x8c", "\x9c", "\xc6", "\xd0", "\xde", "\xdf", "\xe6", "\xf0", "\xfe");
            $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
            $string              = str_replace($double_chars['in'], $double_chars['out'], $string);
        }

        return $string;
    }

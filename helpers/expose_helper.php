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
    return(base_url('assets/img/' . $image_file));
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
                'ª' => 'a', 'º'  => 'o',
                'À' => 'A', 'Á'  => 'A',
                'Â' => 'A', 'Ã'  => 'A',
                'Ä' => 'A', 'Å'  => 'A',
                'Æ' => 'AE', 'Ç' => 'C',
                'È' => 'E', 'É'  => 'E',
                'Ê' => 'E', 'Ë'  => 'E',
                'Ì' => 'I', 'Í'  => 'I',
                'Î' => 'I', 'Ï'  => 'I',
                'Ð' => 'D', 'Ñ'  => 'N',
                'Ò' => 'O', 'Ó'  => 'O',
                'Ô' => 'O', 'Õ'  => 'O',
                'Ö' => 'O', 'Ù'  => 'U',
                'Ú' => 'U', 'Û'  => 'U',
                'Ü' => 'U', 'Ý'  => 'Y',
                'Þ' => 'TH', 'ß' => 's',
                'à' => 'a', 'á'  => 'a',
                'â' => 'a', 'ã'  => 'a',
                'ä' => 'a', 'å'  => 'a',
                'æ' => 'ae', 'ç' => 'c',
                'è' => 'e', 'é'  => 'e',
                'ê' => 'e', 'ë'  => 'e',
                'ì' => 'i', 'í'  => 'i',
                'î' => 'i', 'ï'  => 'i',
                'ð' => 'd', 'ñ'  => 'n',
                'ò' => 'o', 'ó'  => 'o',
                'ô' => 'o', 'õ'  => 'o',
                'ö' => 'o', 'ø'  => 'o',
                'ù' => 'u', 'ú'  => 'u',
                'û' => 'u', 'ü'  => 'u',
                'ý' => 'y', 'þ'  => 'th',
                'ÿ' => 'y', 'Ø'  => 'O',
                // Decompositions for Latin Extended-A
                'Ā' => 'A', 'ā'  => 'a',
                'Ă' => 'A', 'ă'  => 'a',
                'Ą' => 'A', 'ą'  => 'a',
                'Ć' => 'C', 'ć'  => 'c',
                'Ĉ' => 'C', 'ĉ'  => 'c',
                'Ċ' => 'C', 'ċ'  => 'c',
                'Č' => 'C', 'č'  => 'c',
                'Ď' => 'D', 'ď'  => 'd',
                'Đ' => 'D', 'đ'  => 'd',
                'Ē' => 'E', 'ē'  => 'e',
                'Ĕ' => 'E', 'ĕ'  => 'e',
                'Ė' => 'E', 'ė'  => 'e',
                'Ę' => 'E', 'ę'  => 'e',
                'Ě' => 'E', 'ě'  => 'e',
                'Ĝ' => 'G', 'ĝ'  => 'g',
                'Ğ' => 'G', 'ğ'  => 'g',
                'Ġ' => 'G', 'ġ'  => 'g',
                'Ģ' => 'G', 'ģ'  => 'g',
                'Ĥ' => 'H', 'ĥ'  => 'h',
                'Ħ' => 'H', 'ħ'  => 'h',
                'Ĩ' => 'I', 'ĩ'  => 'i',
                'Ī' => 'I', 'ī'  => 'i',
                'Ĭ' => 'I', 'ĭ'  => 'i',
                'Į' => 'I', 'į'  => 'i',
                'İ' => 'I', 'ı'  => 'i',
                'Ĳ' => 'IJ', 'ĳ' => 'ij',
                'Ĵ' => 'J', 'ĵ'  => 'j',
                'Ķ' => 'K', 'ķ'  => 'k',
                'ĸ' => 'k', 'Ĺ'  => 'L',
                'ĺ' => 'l', 'Ļ'  => 'L',
                'ļ' => 'l', 'Ľ'  => 'L',
                'ľ' => 'l', 'Ŀ'  => 'L',
                'ŀ' => 'l', 'Ł'  => 'L',
                'ł' => 'l', 'Ń'  => 'N',
                'ń' => 'n', 'Ņ'  => 'N',
                'ņ' => 'n', 'Ň'  => 'N',
                'ň' => 'n', 'ŉ'  => 'n',
                'Ŋ' => 'N', 'ŋ'  => 'n',
                'Ō' => 'O', 'ō'  => 'o',
                'Ŏ' => 'O', 'ŏ'  => 'o',
                'Ő' => 'O', 'ő'  => 'o',
                'Œ' => 'OE', 'œ' => 'oe',
                'Ŕ' => 'R', 'ŕ'  => 'r',
                'Ŗ' => 'R', 'ŗ'  => 'r',
                'Ř' => 'R', 'ř'  => 'r',
                'Ś' => 'S', 'ś'  => 's',
                'Ŝ' => 'S', 'ŝ'  => 's',
                'Ş' => 'S', 'ş'  => 's',
                'Š' => 'S', 'š'  => 's',
                'Ţ' => 'T', 'ţ'  => 't',
                'Ť' => 'T', 'ť'  => 't',
                'Ŧ' => 'T', 'ŧ'  => 't',
                'Ũ' => 'U', 'ũ'  => 'u',
                'Ū' => 'U', 'ū'  => 'u',
                'Ŭ' => 'U', 'ŭ'  => 'u',
                'Ů' => 'U', 'ů'  => 'u',
                'Ű' => 'U', 'ű'  => 'u',
                'Ų' => 'U', 'ų'  => 'u',
                'Ŵ' => 'W', 'ŵ'  => 'w',
                'Ŷ' => 'Y', 'ŷ'  => 'y',
                'Ÿ' => 'Y', 'Ź'  => 'Z',
                'ź' => 'z', 'Ż'  => 'Z',
                'ż' => 'z', 'Ž'  => 'Z',
                'ž' => 'z', 'ſ'  => 's',
                // Decompositions for Latin Extended-B
                'Ș' => 'S', 'ș'  => 's',
                'Ț' => 'T', 'ț'  => 't',
                // Euro Sign
                '€' => 'E',
                // GBP (Pound) Sign
                '£' => '',
                // Vowels with diacritic (Vietnamese)
                // unmarked
                'Ơ' => 'O', 'ơ'  => 'o',
                'Ư' => 'U', 'ư'  => 'u',
                // grave accent
                'Ầ' => 'A', 'ầ'  => 'a',
                'Ằ' => 'A', 'ằ'  => 'a',
                'Ề' => 'E', 'ề'  => 'e',
                'Ồ' => 'O', 'ồ'  => 'o',
                'Ờ' => 'O', 'ờ'  => 'o',
                'Ừ' => 'U', 'ừ'  => 'u',
                'Ỳ' => 'Y', 'ỳ'  => 'y',
                // hook
                'Ả' => 'A', 'ả'  => 'a',
                'Ẩ' => 'A', 'ẩ'  => 'a',
                'Ẳ' => 'A', 'ẳ'  => 'a',
                'Ẻ' => 'E', 'ẻ'  => 'e',
                'Ể' => 'E', 'ể'  => 'e',
                'Ỉ' => 'I', 'ỉ'  => 'i',
                'Ỏ' => 'O', 'ỏ'  => 'o',
                'Ổ' => 'O', 'ổ'  => 'o',
                'Ở' => 'O', 'ở'  => 'o',
                'Ủ' => 'U', 'ủ'  => 'u',
                'Ử' => 'U', 'ử'  => 'u',
                'Ỷ' => 'Y', 'ỷ'  => 'y',
                // tilde
                'Ẫ' => 'A', 'ẫ'  => 'a',
                'Ẵ' => 'A', 'ẵ'  => 'a',
                'Ẽ' => 'E', 'ẽ'  => 'e',
                'Ễ' => 'E', 'ễ'  => 'e',
                'Ỗ' => 'O', 'ỗ'  => 'o',
                'Ỡ' => 'O', 'ỡ'  => 'o',
                'Ữ' => 'U', 'ữ'  => 'u',
                'Ỹ' => 'Y', 'ỹ'  => 'y',
                // acute accent
                'Ấ' => 'A', 'ấ'  => 'a',
                'Ắ' => 'A', 'ắ'  => 'a',
                'Ế' => 'E', 'ế'  => 'e',
                'Ố' => 'O', 'ố'  => 'o',
                'Ớ' => 'O', 'ớ'  => 'o',
                'Ứ' => 'U', 'ứ'  => 'u',
                // dot below
                'Ạ' => 'A', 'ạ'  => 'a',
                'Ậ' => 'A', 'ậ'  => 'a',
                'Ặ' => 'A', 'ặ'  => 'a',
                'Ẹ' => 'E', 'ẹ'  => 'e',
                'Ệ' => 'E', 'ệ'  => 'e',
                'Ị' => 'I', 'ị'  => 'i',
                'Ọ' => 'O', 'ọ'  => 'o',
                'Ộ' => 'O', 'ộ'  => 'o',
                'Ợ' => 'O', 'ợ'  => 'o',
                'Ụ' => 'U', 'ụ'  => 'u',
                'Ự' => 'U', 'ự'  => 'u',
                'Ỵ' => 'Y', 'ỵ'  => 'y',
                // Vowels with diacritic (Chinese, Hanyu Pinyin)
                'ɑ' => 'a',
                // macron
                'Ǖ' => 'U', 'ǖ'  => 'u',
                // acute accent
                'Ǘ' => 'U', 'ǘ'  => 'u',
                // caron
                'Ǎ' => 'A', 'ǎ'  => 'a',
                'Ǐ' => 'I', 'ǐ'  => 'i',
                'Ǒ' => 'O', 'ǒ'  => 'o',
                'Ǔ' => 'U', 'ǔ'  => 'u',
                'Ǚ' => 'U', 'ǚ'  => 'u',
                // grave accent
                'Ǜ' => 'U', 'ǜ'  => 'u',
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

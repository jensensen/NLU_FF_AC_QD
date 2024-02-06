<?php
/******************************************************************
* NLU_FF_AC_QD -> v1.9.9 of Feb 5, 2024
* for versions of phpwcms
* 1.9.33 (release date: 2022/02/23), backward compatibility: needs PHP 7.x
* and latest
* 1.10.x / needs PHP 8.x
* https://github.com/slackero/phpwcms/commit/4ec6be9b2b92b3247995fb6fa5179ba12ff3bfdc
* #################################################################
* @AUTHOR [virt.]:	Jensensen, INSPIRED by 
* @AUTHOR [real]:	Knut Heermann aka flip-flop
* @AUTHOR [real]:	FUNCTION by Oliver Georgi
* #################################################################
* @copyright Copyright (c) 2008–2024 jensensen (jbr/LH/DE)
* #################################################################
* CONDITION:	FREE || leckmichandefurtoderscheissdiewandan;
* LICENSE:		∀ |&#8704;| &forall;
* LICENSE:		https://opensource.org/licenses/GPL-2.0 GNU GPL-2
* #################################################################
* SUMMARY:
* Works like NAV_LIST_UL but displays the number of articles
* --> of each site level. Example ==> Products (17)
* #################################################################
* ### README: github
* https://github.com/jensensen/NLU_FF_AC_QD/blob/master/README.md
* ### README: Forum
* http://forum.phpwcms.org/viewtopic.php?p=100208#p100208
* http://forum.phpwcms.org/viewtopic.php?f=8&t=17891
* basics: http://forum.phpwcms.org/viewtopic.php?t=12165
* #################################################################
* TAG:			{NLU_FF_AC_QD:F,0....}
*				Use it in your templates, CPs or elsewhere.
* #################################################################
* LOCATION:		/template/inc_script/frontend_render/rt_NLU_FF_AC_QD.php
* REQUIREMENT:	$phpwcms['allow_ext_render']  = 1; //SEE: conf.inc.php
* ****************************************************************/
// obligate check for phpwcms constants
if (!defined('PHPWCMS_ROOT')) {
   die("You Cannot Access This Script Directly, Have a Nice Day.");
}
/******************************************************************
* ### PARAMETER SET UP ### !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! ###
******************************************************************/
// Article Count Wrapper
$acw_before = " <span>(";
$acw_after = ")</span>";

/******************************************************************
* ### !!!!!!!!!!! ### NO NEED TO EDIT BELOW ### !!!!!!!!!!!!!!! ###
******************************************************************/

// based on original function buildCascadingMenu in /include/inc_front/front.func.inc.php
function buildCascMenuCountArticles($parameter='', $counter=0, $param='string') {
    /*
        @string $parameter:
            menu_type,
            start_id,
            max_level_depth,
            class_path|ul_class_level1|ul_class_level2|...,
            class_active_li|class_active_a,
            ul_id_name,
            wrap_ul_div(0 = off, 1 = <div>, 2 = <div id="">, 3 = <div class="navLevel-0">),
            wrap_link_text(<em>|</em>),
            articlemenu_start_level|articlemenu_list_image_size (WxHxCROP OR WxHxCROP)|_
            articlemenu_use_text (take text from: description:MAXLEN OR menutitle:MAXLEN OR teaser:MAXLEN OR teaser:HTML)|_
            articlemenu_position (inside|outside)|_
            <custom>[TEXT]{TEXT}[/TEXT][IMAGE]<img src="{IMAGE}" alt="{IMAGE_NAME}">[/IMAGE]</custom>
    */

    if($param === 'string') {

        $parameter      = explode(',', is_array($parameter) && isset($parameter[1]) ? $parameter[1] : $parameter);
        $menu_type      = empty($parameter[0]) ? '' : strtoupper(trim($parameter[0]));

        $unfold         = 'all';
        $ie_patch       = false; // unused at the moment
        $create_css     = false;
        $parent         = false; // do not show parent link
        $articlemenu    = false; // do not show category's article titles as menu entry
        $bootstrap      = false; // bootstrap dropdown style
        $onepage        = IS_ONEPAGE_TEMPLATE; // render menu links as id anchor <a href=#alias>
        $onepage_every  = false; // ToDo!
        $hide_first     = false;

        /**
         * P = Show parent level
         * B = Bootstrap compatible rendering
         * A = Articles as menu items
         * AH = Articles as menu items, hide first (avoid double link because of parent structure level)
         * F = Folded, unfold only active level
         * HCSS = Sample horizontal CSS based menu
         * VCSS = Sample vertical CSS based menu
         **/
        switch($menu_type) {

            case 'B':       $bootstrap      = true;
                            break;

            case 'BAH':     $hide_first     = true;
            case 'BA':      $bootstrap      = true;
            case 'A':       $articlemenu    = true;
                            break;

            case 'PBAH':    $hide_first     = true;
            case 'PBA':     $bootstrap      = true;
            case 'PA':      $articlemenu    = true;
            case 'P':       $parent         = true;
                            break;

            case 'PB':      $parent         = true;
                            $bootstrap      = true;
                            break;

                            // vertical, active path unfolded
            case 'FPAH':    $hide_first     = true;
            case 'FPA':     $articlemenu    = true;
            case 'FP':      $parent         = true;
            case 'F':       $unfold         = 'active_path';
                            break;

            case 'FAH':     $hide_first     = true;
            case 'FA':      $articlemenu    = true;
                            $unfold         = 'active_path';
                            break;

            case 'HCSSP':   $parent     = true;
            case 'HCSS':    $create_css = true;
                            break;

            case 'VCSSP':   $parent     = true;
            case 'VCSS':    $create_css = true;
                            break;
        }

        $start_id       = empty($parameter[1]) ? 0  : intval($parameter[1]);
        $max_depth      = empty($parameter[2]) ? 0  : intval($parameter[2]);
        $path_class     = empty($parameter[3]) ? '' : trim($parameter[3]);
        $active_class   = empty($parameter[4]) ? '' : trim($parameter[4]);
        $level_id_name  = empty($parameter[5]) ? '' : trim($parameter[5]);
        $wrap_ul_div    = empty($parameter[6]) ? 0  : intval($parameter[6]);
        $amenu_options  = array(
            'enable'        => false,
            'hide_first'    => $hide_first,
            'image'         => false,
            'text'          => false,
            'width'         => 0,
            'height'        => 0,
            'crop'          => 0,
            'textlength'    => 0,
            'position'      => 'outside',
            'template'      => '<span class="amenu-extended">[IMAGE]<img src="[%IMAGE%]" alt="[%IMAGE_NAME%]" />[/IMAGE][TEXT]<span class="p">[%TEXT%]</span>[/TEXT]</span>'
        );
        if($path_class) {
            $path_class = explode('|', $path_class);
            foreach($path_class as $key => $class_name) {
                $path_class[$key] = trim($class_name);
            }
        } else {
            $path_class = array(0 => '');
        }
        if($active_class) {
            $active_class       = explode('|', $active_class, 2);
            $active_class[0]    = trim($active_class[0]);
            $active_class[1]    = empty($active_class[1]) ? '' : trim($active_class[1]);
        } else {
            $active_class       = array(0 => '', 1 => '');
        }
        if($wrap_ul_div > 3) {
            $wrap_ul_div = 2;
        } elseif($wrap_ul_div < 0) {
            $wrap_ul_div = 0;
        }
        $wrap_link_text = empty($parameter[7]) ? array(0 => '', 1 => '') : explode('|', trim($parameter[7]), 2);
        if(empty($wrap_link_text[1])) {
            $wrap_link_text[1] = '';
        }
        if(empty($parameter[8])) {
            $amenu_level = 0;
        } else {
            $parameter[8]   = explode('|', $parameter[8]);
            $amenu_level    = intval($parameter[8][0]);
            if(!empty($parameter[8][1]) && ($parameter[8][1] = trim($parameter[8][1]))) { // articlemenu_list_image_size
                $parameter[8][1] = explode('x', $parameter[8][1]);
                $amenu_options['width']     = intval($parameter[8][1][0]); // width
                $amenu_options['height']    = empty($parameter[8][1][1]) ? 0 : intval($parameter[8][1][1]); // height
                $amenu_options['crop']      = empty($parameter[8][1][2]) ? 0 : 1; // crop
                $amenu_options['enable']    = true;
                $amenu_options['image']     = true;
            }
            if(!empty($parameter[8][2]) && ($parameter[8][2] = trim($parameter[8][2]))) { // articlemenu_use_text
                $parameter[8][2]    = explode(':', $parameter[8][2]);
                $parameter[8][2][0] = strtolower(trim($parameter[8][2][0]));
                if($parameter[8][2][0] == 'description' || $parameter[8][2][0] == 'menutitle' || $parameter[8][2][0] == 'teaser') { // default is description
                    $amenu_options['text'] = $parameter[8][2][0];
                    if(empty($parameter[8][2][1])) {
                        $amenu_options['textlength'] = 0;
                    } elseif($parameter[8][2][0] == 'teaser' && strtoupper($parameter[8][2][1]) == 'HTML') {
                        $amenu_options['textlength'] = 'HTML';
                    } else {
                        $amenu_options['textlength'] = intval($parameter[8][2][1]); // set max text length
                    }
                    $amenu_options['enable'] = true;
                }
            }
            if($amenu_options['enable'] && !empty($parameter[8][3]) && ($parameter[8][3] = trim($parameter[8][3])) && strtolower($parameter[8][3]) == 'inside') { // articlemenu_position
                $amenu_options['position'] = 'inside';
            }
            if($amenu_options['enable'] && !empty($parameter[8][4])) { // template
                $amenu_options['template'] = str_replace(array('[%', '%]'), array('{', '}'), $parameter[8][4]);
            }
        }

        $parameter = array(
             0 => $menu_type,
             1 => $start_id,
             2 => $max_depth,
             3 => $path_class,
             4 => $active_class,
             5 => $level_id_name,
             6 => $wrap_ul_div,
             7 => $wrap_link_text,
             8 => $unfold,
             9 => $ie_patch,
            10 => $create_css,
            11 => $amenu_level,
            12 => array(
                'articlemenu'   => $articlemenu,
                'level_id'      => $start_id
            ),
            13 => $bootstrap,
            14 => $onepage,
            15 => $onepage_every
        );

        if($articlemenu) {
            $parameter[12]['class_active']          = $active_class;
            $parameter[12]['wrap_title_prefix']     = $wrap_link_text[0];
            $parameter[12]['wrap_title_suffix']     = $wrap_link_text[1];
            $parameter[12]['item_prefix']           = "\t";
            $parameter[12]['item_suffix']           = '';
            $parameter[12]['sort']                  = 'level';
            $parameter[12]['item_tag']              = 'li';
            $parameter[12]['wrap_tag']              = '';
            $parameter[12]['attribute_wrap_tag']    = '';
            $parameter[12]['class_item_link']       = $GLOBALS['template_default']['classes']['navlist-link-class'];
            $parameter[12]['class_item_tag']        = $GLOBALS['template_default']['classes']['navlist-asub_no'];
            $parameter[12]['class_first_item_tag']  = $GLOBALS['template_default']['classes']['navlist-asub_first'];
            $parameter[12]['class_last_item_tag']   = $GLOBALS['template_default']['classes']['navlist-asub_last'];
            $parameter[12]['return_format']         = 'array';
            $parameter[12]['articlemenu_options']   = $amenu_options;
        }

    } else {

        $menu_type      = $parameter[0];
        $start_id       = $parameter[1];
        $max_depth      = $parameter[2];
        $path_class     = $parameter[3];
        $active_class   = $parameter[4];
        $level_id_name  = $parameter[5];
        $wrap_ul_div    = $parameter[6];
        $wrap_link_text = $parameter[7];
        $unfold         = $parameter[8];
        $ie_patch       = $parameter[9];
        $create_css     = $parameter[10];
        $amenu_level    = $parameter[11];
        $bootstrap      = $parameter[13];
        $onepage        = $parameter[14];
        $onepage_every  = $parameter[15];
        $parent         = false; // do not show parent link

    }

    $li             = '';
    $ali            = '';
    $ul             = '';
    $TAB            = str_repeat('  ', $counter);
    $_menu_type     = strtolower($menu_type);
    $max_depth      = $max_depth == 0 || $max_depth - 1 > $counter;
    $x              = 0;
    $items          = array();
    $last_item      = 0;

    foreach($GLOBALS['content']['struct'] as $key => $value) {

        if( _getStructureLevelDisplayStatus($key, $start_id) ) {
            $items[$key] = $key;
            $last_item++;
        }

    }

    foreach($items as $key) {
// -------------------------------------- WORKING -----------------
        // thank you OG
        // count number of articles in each category level
        $sql = "SELECT COUNT(*) ";
        $sql .= "FROM ".DB_PREPEND."phpwcms_article ";
        $sql .= 'WHERE article_cid=' . $key . ' AND article_deleted=0 AND article_public=1 ';
        $sql .= 'AND article_aktiv=1 AND article_begin<NOW() AND ';
        $sql .= 'IF(article_archive_status=1, 1, article_end>NOW())';
// ----------------------------------------------------------------
        // thank you flip-flop
        $how_many_articles = _dbCount($sql);

        $li_ul      = '';
        $li_class   = array();
        $bs_toggle  = false;

        if($max_depth && ($unfold == 'all' || ($unfold == 'active_path' && isset($GLOBALS['LEVEL_KEY'][$key]))) ) {
            $parameter[1]   = $key;
//          $li_ul          = buildCascadingMenu($parameter, $counter+1, 'param_is_array');
            $li_ul	    = buildCascMenuCountArticles($parameter, $counter+1, 'param_is_array'); // jensensen
        }

        $li .= $TAB.'   <li';

        if($level_id_name) {
            $li .= ' id="li_'.$level_id_name.'_'.$key.'"';
        }
        if($li_ul) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_ul'];
            if($bootstrap) {
                $li_class[] = $GLOBALS['template_default']['classes']['navlist-bs-dropdown'];
                $bs_toggle  = true;
            }
        } elseif(getHasSubStructureStatus($key)) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_no'];
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_ul_true'];
        } else {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_no'];
        }

        $li_a_title = html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);
        $li_a_class = array(
            $GLOBALS['template_default']['classes']['navlist-link-class']
        );
        if($active_class[1] && $key == $GLOBALS['aktion'][0]) {
            $li_a_class[] = $active_class[1]; // set active link class
        }
        if($bs_toggle) {
            $li_a_class[]   = $GLOBALS['template_default']['classes']['navlist-bs-dropdown-toggle'];
            $bs_data_toggle = ' ' . $GLOBALS['template_default']['attributes']['navlist-bs-dropdown-data'];
            $bs_caret       = $GLOBALS['template_default']['attributes']['navlist-bs-dropdown-caret'];
        } else {
            $bs_data_toggle = '';
            $bs_caret       = '';
        }
        if($bootstrap && $GLOBALS['template_default']['classes']['navlist-bs-link']) {
            $li_a_class[] = $GLOBALS['template_default']['classes']['navlist-bs-link'];
        }
        $li_a_class = ' class="' . implode(' ', get_unique_array($li_a_class)) . '"';
        $li_a  = get_level_ahref($key, $li_a_class.' title="'.$li_a_title.'"'.$bs_data_toggle);
        $li_a .= $wrap_link_text[0] . $li_a_title . $bs_caret . $wrap_link_text[1];

        if($path_class[0] && isset($GLOBALS['LEVEL_KEY'][$key])) {
            $li_class[] = $path_class[0];
        }
        if($active_class[0] != '' && $key == $GLOBALS['aktion'][0]) {
            $li_class[] = $active_class[0];
        }
        if($x === 0) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_first'];
        }

        $x++;

        if($x === $last_item) {
            $li_class[] = $GLOBALS['template_default']['classes']['navlist-sub_last'];
        }
        $li_class[] = $GLOBALS['content']['struct'][$key]['acat_class'];
        $li .= ' class="' . implode(' ', get_unique_array($li_class)) .'"';
//        $li .= '>' . $li_a . '</a>';
	$li .= '>' . $li_a . $GLOBALS['acw_before'] . $how_many_articles . $GLOBALS['acw_after'] . '</a>'; // jensensen
        $li .= $li_ul.'</li>'.LF;
    }

    // show article menu
    if($parameter[12]['articlemenu'] && $amenu_level <= $counter) {

        $parameter[12]['level_id']      = $start_id;
        $parameter[12]['item_prefix']   = $TAB.$TAB.$TAB;

        $ali = getArticleMenu($parameter[12]);

        if(count($ali) > 1) {
            $li .= implode(LF, $ali) . LF;
            $ali = $TAB;
        } else {
            $ali = '';
        }

    }

    // also check if $parent
    if($li || ($parent && isset($GLOBALS['content']['struct'][$start_id]))) {

        switch($wrap_ul_div) {
            case 1:     $ul = LF.$TAB.'<div>';
                        $close_wrap_ul = '</div>'.LF.$TAB;
                        break;
            case 2:     $ul = LF.$TAB.'<div id="ul_div_'.$start_id.'">';
                        $close_wrap_ul = '</div>'.LF.$TAB;
                        break;
            case 3:     $ul = LF.$TAB.'<div class="'.$GLOBALS['template_default']['classes']['navlist-navLevel'].$counter.'">';
                        $close_wrap_ul = '</div>'.LF.$TAB;
                        break;
            default:    $ul = '';
                        $close_wrap_ul = '';
        }
        $ul .= LF . $TAB . $ali . '<ul';
        if($level_id_name) {
            $ul .= ' id="'.$level_id_name.'_'.$start_id.'"';
        }

        $ul_class = empty($path_class[$counter+1]) ? '' : $path_class[$counter+1];
        if(isset($GLOBALS['LEVEL_KEY'][$start_id]) && $counter && isset($path_class[0])) {
            $ul_class .= ' ' . $path_class[0];
        }
        if($bootstrap && $counter) {
            $ul_class = 'dropdown-menu '.$ul_class;
        }
        $ul_class = trim($ul_class);
        if($ul_class) {
            $ul .= ' class="' . $ul_class . '"';
        }
        $ul .= '>'.LF;

        if($parent && isset($GLOBALS['content']['struct'][$start_id])) {
            $ul .= LF . $TAB.'   <li';
            if($level_id_name) {
                $ul .= ' id="li_'.$level_id_name.'_'.$start_id.'"';
            }
            $li_class = array(
                $GLOBALS['content']['struct'][$start_id]['acat_class'],
                $GLOBALS['template_default']['classes']['navlist-sub_parent']
            );
            if($active_class[0] != '' && $start_id == $GLOBALS['aktion'][0]) {
                $li_class[] = $active_class[0];
            }
            $ul .= ' class="' . implode(' ', get_unique_array($li_class)) . '">';

            $link_text  = html_specialchars($GLOBALS['content']['struct'][$start_id]['acat_name']);
            $link_class = array(
                $GLOBALS['template_default']['classes']['navlist-link-class']
            );
            if($active_class[1] && $start_id == $GLOBALS['aktion'][0]) {
                $link_class[] = $active_class[1]; // set active link class
            }
            if($bootstrap && $GLOBALS['template_default']['classes']['navlist-bs-link']) {
                $link_class[] = $GLOBALS['template_default']['classes']['navlist-bs-link'];
            }
            $link_class = ' class="' . implode(' ', get_unique_array($link_class)) . '"';
            $ul .= get_level_ahref($start_id, $link_class.' title="'.$link_text.'"');
            $ul .= $wrap_link_text[0] . $link_text . $wrap_link_text[1];
            $ul .= '</a></li>'.LF;
        }

        $ul .= $li;
        $ul .= $TAB . $ali . '</ul>' . LF . $TAB . $close_wrap_ul;

        if($create_css && empty($GLOBALS['block']['custom_htmlhead'][$menu_type][$counter])) {

            if($counter) {

                $tmp_css  = '    .'.$_menu_type.'_menu ul li:hover '.str_repeat('ul ', $counter) .'ul { display: none; }'.LF;
                $tmp_css .= '    .'.$_menu_type.'_menu ul '.str_repeat('ul ', $counter) .'li:hover ul { display: block; }';
                $GLOBALS['block']['custom_htmlhead'][$menu_type][$counter] = $tmp_css;

            } else {  //if($counter == 0) {

                $GLOBALS['block']['custom_htmlhead'][$menu_type][-9]  = LF.'  <style type="text/css">'.LF.SCRIPT_CDATA_START;
                $GLOBALS['block']['custom_htmlhead'][$menu_type][-8]  = '    @import url("'.TEMPLATE_PATH.'inc_css/specific/nav_list_ul_'.$_menu_type.'.css");';

                $GLOBALS['block']['custom_htmlhead'][$menu_type][-5]  = '    .'.$_menu_type.'_menu ul ul { display: none; }';
                $GLOBALS['block']['custom_htmlhead'][$menu_type][-4]  = '    .'.$_menu_type.'_menu ul li:hover ul { display: block; }';

                ksort($GLOBALS['block']['custom_htmlhead'][$menu_type]);
                $GLOBALS['block']['custom_htmlhead'][$menu_type][]   = SCRIPT_CDATA_END.LF.'  </style>';
                $GLOBALS['block']['custom_htmlhead'][$menu_type]   = implode(LF, $GLOBALS['block']['custom_htmlhead'][$menu_type]);

                $ul = '<div class="'.$_menu_type.'_menu">'.$ul.'</div>';

            }

        }

    }

    return $ul;
}

if(!empty($content["all"]) && !(strpos($content["all"],'{NLU_FF_AC_QD:')===false)) {
	$content["all"] = preg_replace_callback('/\{NLU_FF_AC_QD:(.*?)\}/', 'buildCascMenuCountArticles', $content["all"]);  
}

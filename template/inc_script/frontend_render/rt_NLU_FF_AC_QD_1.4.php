<?php
/******************************************************************
* NLU_FF_AC_QD for phpwcms --> v1.4.5+
* Date: Jan. 21, 2010
*
* SUMMARY:
* Works like NAV_LIST_UL but displays the number of articles
* --> of each site level. Example ==> Products (17)
*
* AUTHOR [virt.]:	Jensensen
*					INSPIRED by Knut Heermann aka flip-flop
*					FUNCTION by Oliver Georgi
* README: Forum
* http://forum.phpwcms.org/viewtopic.php?p=100208#p100208
* http://forum.phpwcms.org/viewtopic.php?f=8&t=17891
*
* README: Wiki
* http://www.phpwcms-howto.de/wiki/doku.php/english/phpwcms_replacer_rts/frontend_render/nav_list_ul-article-count
* http://www.phpwcms-howto.de/wiki/doku.php/deutsch/ersetzer_rts/frontend_render/nav_list_ul-article-count
*
* TAG:			{NLU_FF_AC_QD:F,0....}
*				Use it in your templates, CPs or elsewhere.
*
* VERSION:		1.4
* CONDITION:	FREE || leckmichandefurtoderscheissdiewandan;
* LICENCE:		∀ |&#8704;| &forall;
*
* LOCATION:		/template/inc_script/frontend_render/rt_NLU_FF_AC_QD_1.4.php
* REQUIREMENT:	$phpwcms['allow_ext_render']  = 1; //SEE: conf.inc.php
*
* NAV_LIST_UL basics: http://forum.phpwcms.org/viewtopic.php?t=12165
*
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

// original funtion buildCascadingMenu in /include/inc_front/front.func.inc.php
function buildCascMenuCountArticles($parameter='', $counter=0, $param='string') {

	// @string $parameter = "menu_type, start_id, max_level_depth, class_path, class_active,
	// ul_id_name, wrap_ul_div(0 = off, 1 = <div>, 2 = <div id="">, 3 = <div class="navLevel-0">),
	// wrap_link_text(<em>|</em>, articlemenu_start_level)"

	if($param == 'string') {

		$parameter 		= explode(',', $parameter);
		$menu_type		= empty($parameter[0]) ? '' : strtoupper(trim($parameter[0]));

		$unfold 		= 'all';
		$ie_patch		= false; // unused at the moment
		$create_css 	= false;
		$parent			= false; // do not show parent link
		$articlemenu	= false; // do not show category's article titles as menu entry

		switch($menu_type) {

							// show parent level too
			case 'PA':		$articlemenu	= true;
			case 'P':		$parent			= true;
							break;

							// vertical, active path unfolded
			case 'FPA':		$articlemenu	= true;
			case 'FP':		$parent			= true;
			case 'F':		$unfold			= 'active_path';
							break;
							
			case 'FA':		$articlemenu	= true;
							$unfold			= 'active_path';
							break;
							
							// horizontal, all levels unfolded, add special code for horizontal flyout menu
			case 'HCSSP':	$parent		= true;
			case 'HCSS':	$create_css	= true;
							break;

							// horizontal, all levels unfolded, add special code for vertical flyout menu
			case 'VCSSP':	$parent		= true;
			case 'VCSS':	$create_css = true;
							break;

		}

		$start_id		= empty($parameter[1]) ? 0  : intval($parameter[1]);
		$max_depth		= empty($parameter[2]) ? 0  : intval($parameter[2]);
		$path_class 	= empty($parameter[3]) ? '' : trim($parameter[3]);
		$active_class	= empty($parameter[4]) ? '' : trim($parameter[4]);
		$level_id_name	= empty($parameter[5]) ? '' : trim($parameter[5]);
		$wrap_ul_div	= empty($parameter[6]) ? 0  : intval($parameter[6]);
		if($wrap_ul_div > 3) {
			$wrap_ul_div = 2;
		} elseif($wrap_ul_div < 0) {
			$wrap_ul_div = 0;
		}
		$wrap_link_text	= empty($parameter[7]) ? array(0 => '', 1 => '') : explode('|', trim($parameter[7]), 2);
		if(empty($wrap_link_text[1])) {
			$wrap_link_text[1] = '';
		}
		$amenu_level	= empty($parameter[8]) ? 0 : intval($parameter[8]);

		$parameter		= array(	 0 => $menu_type, 		 1 => $start_id, 		 2 => $max_depth,
									 3 => $path_class,		 4 => $active_class, 	 5 => $level_id_name,
									 6 => $wrap_ul_div,		 7 => $wrap_link_text,	 8 => $unfold,
									 9 => $ie_patch,		10 => $create_css,		11 => $amenu_level,
									12 => array('articlemenu' => $articlemenu, 'level_id' => $start_id)
							);
		
		if($articlemenu) {
			$parameter[12]['class_active']			= $active_class;
			$parameter[12]['wrap_title_prefix']		= $wrap_link_text[0];
			$parameter[12]['wrap_title_suffix']		= $wrap_link_text[1];
			$parameter[12]['item_prefix']			= "\t";
			$parameter[12]['item_suffix']			= '';
			$parameter[12]['sort']					= 'level';
			$parameter[12]['item_tag']				= 'li';
			$parameter[12]['wrap_tag']				= '';
			$parameter[12]['attribute_wrap_tag']	= '';
			$parameter[12]['class_item_tag']		= 'asub_no';
			$parameter[12]['class_first_item_tag']	= 'asub_first';
			$parameter[12]['class_last_item_tag']	= 'asub_last';
			$parameter[12]['return_format']			= 'array';
		}
	
	} else {

		$menu_type		= $parameter[0];
		$start_id		= $parameter[1];
		$max_depth		= $parameter[2];
		$path_class 	= $parameter[3];
		$active_class	= $parameter[4];
		$level_id_name	= $parameter[5];
		$wrap_ul_div	= $parameter[6];
		$wrap_link_text	= $parameter[7];
		$unfold			= $parameter[8];
		$ie_patch		= $parameter[9];
		$create_css 	= $parameter[10];
		$amenu_level	= $parameter[11];
		
		$parent			= false;		// do not show parent link

	}

	$li				= '';
	$ul				= '';
	$TAB			= str_repeat('	', $counter);
	$_menu_type		= strtolower($menu_type);
	$max_depth		= ($max_depth == 0 || $max_depth-1 > $counter) ? true : false;
	$x				= 0;

	foreach($GLOBALS['content']['struct'] as $key => $value) {

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

		if( _getStructureLevelDisplayStatus($key, $start_id) ) {

			$li_ul 		= '';
			$li_class	= '';
			$li_ie		= '';
			$li_a_title	= html_specialchars($GLOBALS['content']['struct'][$key]['acat_name']);
			
			$li_a  = get_level_ahref($key, ' title="'.$li_a_title.'"');
			$li_a .= $wrap_link_text[0] . $li_a_title . $wrap_link_text[1];

			if($max_depth && ($unfold == 'all' || ($unfold == 'active_path' && isset($GLOBALS['LEVEL_KEY'][$key]))) ) {
				$parameter[1]	= $key;
//				$li_ul			= buildCascadingMenu($parameter, $counter+1, 'param_is_array');
				$li_ul			= buildCascMenuCountArticles($parameter, $counter+1, 'param_is_array'); // jensensen
			}

			$li .= $TAB.'	<li';

			if($level_id_name) {
				$li .= ' id="li_'.$level_id_name.'_'.$key.'"';
			}
			if($li_ul) {
				$li_class	= 'sub_ul';
			} else {
				$li_class	= getHasSubStructureStatus($key) ? 'sub_no sub_ul_true' : 'sub_no';
			}
			if($path_class != '' && isset($GLOBALS['LEVEL_KEY'][$key])) {
				$li_class .= ' '.$path_class;
				$li_class  = trim($li_class);
			}
			if($active_class != '' && $key == $GLOBALS['aktion'][0]) {
				$li_class = trim($li_class.' '.$active_class);
			}

			$li .= ' class="' . $li_class . ( $x==0 ? ' sub_first' : '' ) .'"';

//			$li .= '>' . $li_a . '</a>';
			$li .= '>' . $li_a . $GLOBALS['acw_before'] . $how_many_articles . $GLOBALS['acw_after'] . '</a>'; // jensensen

			$li .= $li_ul.'</li>'.LF; // remove $li_ul from this line of code if $ie_patch is used
			
			$x++;
		}
	}

	// show article menu
	if($parameter[12]['articlemenu'] && $amenu_level <= $counter) {
		
		$parameter[12]['level_id']		= $start_id;
		$parameter[12]['item_prefix']	= $TAB;

		$ali = getArticleMenu( $parameter[12] );
		
		if(count($ali) > 1) {
		
			$li .= implode(LF, $ali) . LF;
			
		}
		
	}	
	
	// also check if $parent
	if($li || ($parent && isset($GLOBALS['content']['struct'][$start_id]))) {

		switch($wrap_ul_div) {
			case 1:		$ul = LF.$TAB.'<div>';
						$close_wrap_ul = '</div>'.LF.$TAB;
						break;
			case 2:		$ul = LF.$TAB.'<div id="ul_div_'.$start_id.'">';
						$close_wrap_ul = '</div>'.LF.$TAB;
						break;
			case 3:		$ul = LF.$TAB.'<div class="navLevel-'.$counter.'">';
						$close_wrap_ul = '</div>'.LF.$TAB;
						break;
			default:	$ul = '';
						$close_wrap_ul = '';
		}
		$ul .= LF.$TAB.'<ul';
		if($level_id_name) {
			$ul .= ' id="'.$level_id_name.'_'.$start_id.'"';
		}
		if(isset($GLOBALS['LEVEL_KEY'][$start_id]) && $path_class) {
			$ul .= ' class="'.$path_class.'"';
		}
		$ul .= '>'.LF;
		
		if($parent && isset($GLOBALS['content']['struct'][$start_id])) {
		
			$ul .= LF;
			$ul .= $TAB.'	<li';
			if($level_id_name) {
				$ul .= ' id="li_'.$level_id_name.'_'.$start_id.'"';
			}
			$li_class	= 'sub_parent';
			if($path_class != '' && isset($GLOBALS['LEVEL_KEY'][$start_id])) {
				$li_class .= ' '.$path_class;
				$li_class  = trim($li_class);
			}
			if($active_class != '' && $start_id == $GLOBALS['aktion'][0]) {
				$li_class = trim($li_class.' '.$active_class);
			}
			$ul .= ' class="'.$li_class.'">';
			
			$link_text = html_specialchars($GLOBALS['content']['struct'][$start_id]['acat_name']);
			
			$ul .= get_level_ahref($start_id, ' title="'.$link_text.'"');
			$ul .= $wrap_link_text[0] . $link_text . $wrap_link_text[1];
			$ul .= '</a></li>'.LF;
					
		}
		
		$ul .= $li;
		$ul .= $TAB . '</ul>' . LF . $TAB . $close_wrap_ul;

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
	$content["all"] = preg_replace('/\{NLU_FF_AC_QD:(.*?)\}/e', 'buildCascMenuCountArticles("$1");', $content["all"]);  
}
?>
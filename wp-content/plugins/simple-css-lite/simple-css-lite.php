<?php
/*
Plugin Name: Simple CSS Lite
Author: Simple Mama Blog Design
Author URI: http://www.simplemama.com/simple-css/
External: http://www.simplemama.com/simple-css/
Plugin URI: http://www.simplemama.com/simple-css/
Demo: http://www.simplemama.com/simple-css/
External: http://www.simplemama.com/simple-css/
Description: Simple CSS Lite is a FREE plugin that provides options to customize background, text, and hover colors of the PageLines navigation menu. Works with both the Navigation and BrandNav sections. If you find this plugin useful, please consider making a donation at <a href="http://www.simplemama.com/simple-css/" target="_blank">Simple Mama</a>.
Version: 1.4
Tags: extension
PageLines: true

This plugin was original coded by Simon @PageLines for PlatformPro 1.5.
I have recoded this for PageLines 2.1+ and added a couple extra features.
*/

/***** THE OPTIONS *****/
class SimpleCSSLite {
	function __construct() {
		add_filter('pagelines_options_array', array(&$this, 'simple_CSS_lite'));
	}
	function simple_CSS_lite($options) {
		if( defined( 'THEMENAME'  ) && 'PageLines' == THEMENAME  ) {
		$options_array['simple_CSS_lite'] = array(// this is the tab title
			'custom-nav-colors'	=> array(
				'layout'	=> 'interface',
				'type'		=> 'color_multi',
				'title'		=> 'Navigation Colors',
				'shortexp'	=> 'This section controls the background, text, and hover colors of the navigation menu. Works with both the Navigation and BrandNav sections. See "More Info" below for a description of each option.',
				'exp'		=> '<strong>Text Color of Menu Items:</strong> Color of the menu text, including child menu items.<br /><br /><strong>Text Color of Menu Items on Hover:</strong> Hover color of the menu text, including child menu items.<br /><br /><strong>Text Color of Active Menu Item:</strong> Color of the active menu item text, including active item submenu.<br /><br /><strong>Background Color of Menu Items:</strong> Background color of the menu items, including child menu items.<br /><br /><strong>Background Color of Menu Items on Hover:</strong> Background hover color of the menu items, including child menus items.<br /><br /><strong>Background Color of Active Menu Item:</strong> Background color of the active menu item, including active item submenu.<br /><br /><strong>Navigation Bar Background Color:</strong> Spans the entire length of your navigation bar including the search bar.',
				'docslink'	=> 'http://www.simplemama.com/simple-css/', 
				'vidtitle'	=> 'View Documentation',
				'selectvalues'	=> array(
					'primarytext'	=> array(//menu text color
						'default'		=> '',
						'inputlabel'	=> 'Text Color of Menu Items',
						'css_prop'		=> 'color',
						'selectors'		=> '#page .sf-menu li a, #page ul.sf-menu a:focus, #page .sf-menu a:hover, #page .sf-menu a:active, #page .main-nav a',
					),
					'primarytexthov'	=> array(//menu text HOVER color + active menu text color
						'default'		=> '',
						'inputlabel'	=> 'Text Color of Menu Items on Hover',
						'css_prop'		=> 'color',
						'selectors'		=> '#page .main-nav li a:hover, #page .main-nav .current-menu-ancestor .current_page_item a, #page .main-nav li.current-menu-ancestor ul a:hover, #page .main-nav .current-page-ancestor .current_page_item a, #page .main-nav li.current-page-ancestor ul a:hover',
					),
					'activeitemcol'	=> array(//current menu item fixes
						'default'	=> '',
						'inputlabel'	=> 'Text Color of Active Menu Item',
						'css_prop'	=> 'color',
						'selectors'	=> '#page .main-nav .current-menu-ancestor a, #page .main-nav li.current-menu-ancestor ul a, #page .main-nav li.current_page_item a, #page .main-nav li.current-menu-item a, #page .main-nav li.current_page_parent a, #page .sf-menu li li, #page .sf-menu li li li, #page .main-nav li:hover, #page .main-nav .current-page-ancestor a, #page .main-nav li.current-page-ancestor ul a',
					),
					'primaryul'		=> array(//menu bg color
						'default'		=> '',
						'inputlabel'	=> 'Background Color<br />of Menu Items',
						'css_prop'		=> 'background',
						'selectors'		=> '#page .sf-menu li a, #page ul.sf-menu a:focus, #page .sf-menu a:hover, #page .sf-menu a:active, #page .main-nav a',
					),
					'primaryulhov'	=> array(//menu bg HOVER color
						'default'		=> '',
						'inputlabel'	=> 'Background Color<br />of Menu Items on Hover',
						'css_prop'		=> 'background',
						'selectors'		=> '#page .main-nav li a:hover, #page .main-nav .current-menu-ancestor .current_page_item a, #page .main-nav li.current-menu-ancestor ul a:hover, #page .main-nav .current-page-ancestor .current_page_item a, #page .main-nav li.current-page-ancestor ul a:hover',
					),
					'activeitembg'	=> array(//current menu item fixes
						'default'	=> '',
						'inputlabel'	=> 'Background Color<br />of Active Menu Item',
						'css_prop'	=> 'background',
						'selectors'	=> '#page .main-nav .current-menu-ancestor a, #page .main-nav li.current-menu-ancestor ul a, #page .main-nav li.current_page_item a, #page .main-nav li.current-menu-item a, #page .main-nav li.current_page_parent a, #page .sf-menu li li, #page .sf-menu li li li, #page .main-nav li:hover, #page .main-nav .current-page-ancestor a, #page .main-nav li.current-page-ancestor ul a',
					),
					'navbar'	=> array(//complete nav container
						'default'		=> '',
						'inputlabel'	=> 'Navigation Bar Background Color',
						'css_prop'		=> 'background',
						'selectors'		=> '#page .navigation_wrap',
					)
				)
			),
		);
		return array_merge($options, $options_array);
	} else {
		return $options;
	}
	}
} //class end
/***** ADD THE OPTION TO PL *****/
new SimpleCSSLite;
<?php
// Setup  -- Probably want to keep this stuff... 

/**
 * Hello and welcome to Base! First, lets load the PageLines core so we have access to the functions 
 */	
require_once( dirname(__FILE__) . '/setup.php' );
	
add_action('pagelines_head', 'add_less' );
//add_action('pagelines_head', 'add_fonts');

function add_less() {

	?>
	<link rel='stylesheet' id='less-css'  href='<?php bloginfo('stylesheet_directory'); ?>/style.less' type='text/css' media='all' />
	<?php 
}

function add_fonts() {
	?>
	<link href='http://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<?php
}

function exclude_category( $query ) {
    if (is_home()) {
        $query->set( 'cat', '-361' );
    }
}
//add_action( 'pre_get_posts', 'exclude_category' );
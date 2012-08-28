<?php
/*
Plugin Name: MU Syndication Updater Assistant
Plugin URI: http://projects.radgeek.com/
Description: Generates a list of sites using FeedWordPress for syndication
Version: 2012.0825
Author: Charles Johnson
Author URI: http://radgeek.com/
License: GPL
*/

class MUSyndicationUpdater {
	function __construct () {
		add_action('template_redirect', array($this, 'template_redirect')); 
	} /* MUSyndicationUpdater::__construct () */

	function template_redirect () {
		if (isset($_REQUEST['update_feedwordpress_list']) and '1'==$_REQUEST['update_feedwordpress_list']) :
			$blogs = get_blog_list(0, 'all');

			$out = array();
			
			foreach ($blogs as $blog) :
				$details = get_blog_details($blog['blog_id']);
				switch_to_blog($blog['blog_id']);

				if (intval($details->public) > 0) :
					if (class_exists('FeedWordPress')) :
						$links = FeedWordPress::syndicated_links();
					else :
						$links = array();
					endif;

					if (count($links) > 0) :
						$out[] = $details->siteurl;
					endif;
				endif;
			endforeach;

			if (count($out) > 0) :
				header("HTTP/1.1 200 OK");
			else :
				header("HTTP/1.1 404 Not Found");
			endif;
			
			$fmt = (isset($_REQUEST['format']) ? $_REQUEST['format'] : 'text/plain');
			switch ($fmt) :
			case 'text/plain':
				header("Content-type: ".$fmt);
				$split = (isset($_REQUEST['split']) ? $_REQUEST['split'] : "\n");
				$start = (isset($_REQUEST['start']) ? $_REQUEST['start'] : "---\n");
				$end = (isset($_REQUEST['end']) ? $_REQUEST['end'] : "\n---\n");
				print $start;
				print implode($split, $out);
				print $end;
				break;
			case 'application/json' :
				header("Content-type: ".$fmt);
				print json_encode($out);	
				break;
			endswitch;
			exit;
		endif;
	}
}

$musu = new MUSyndicationUpdater;


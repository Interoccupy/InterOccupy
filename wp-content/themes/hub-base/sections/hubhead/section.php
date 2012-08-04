<?php
/*
	Section: Hub Head
	Author: Greggsky
	Author URI: 
	Description: Shows the hub title and image
	Class Name: InteroccHubHead
	Cloning: true
	Workswith: templates, main, header, morefoot
*/

/**
 * Callout Section
 *
 * @package PageLines Framework
 * @author PageLines
 */
class InteroccHubHead extends PageLinesSection {

	var $tabID = 'callout_meta';

	/**
	* Section template.
	*/
 	function section_template() {
	 	
	 	$hubhome = get_bloginfo('url');
		$call_title = of_get_option ( 'hub-title' );
		$call_sub = of_get_option ( 'short-desc' );
		$call_img = of_get_option ( 'hub-image' );
		$call_action_text = (ploption('pagelines_callout_action_text', $this->oset)) ? ploption('pagelines_callout_action_text', $this->oset) : __('Start Here', 'pagelines'); ?>
		
		<?php $offsitelinks = '[pl_buttongroup]'; ?>
		<?php if ( of_get_option ( 'hub-website' ) ) $offsitelinks .= '<a href="' . of_get_option ( 'hub-website' ) . '" class="btn btn-info" target="_blank">Website<span class="offsite"></span></a>'; ?>
		<?php if ( of_get_option ( 'hub-facebook' ) ) $offsitelinks .= '<a href="' . of_get_option ( 'hub-facebook' ) . '" class="btn btn-info" target="_blank">Facebook Page<span class="offsite"></span></a>'; ?>
		<?php if ( of_get_option ( 'hub-facebook-group' ) ) $offsitelinks .= '<a href="' . of_get_option ( 'hub-facebook-group' ) . '" class="btn btn-info" target="_blank">Facebook Group<span class="offsite"></span></a>'; ?>
		<?php if ( of_get_option ( 'hub-twitter' ) ) $offsitelinks .= '<a href="http://www.twitter.com/' . of_get_option ( 'hub-twitter' ) . '" class="btn btn-info" target="_blank">Twitter<span class="offsite"></span></a>'; ?>
		<?php if ( of_get_option ( 'hub-forum' ) ) $offsitelinks .= '<a href="' . of_get_option ( 'hub-forum' ) . '" class="btn btn-info">Discuss</a>'; ?>
		<?php if ( of_get_option ( 'hub-classifieds' ) ) $offsitelinks .= '<a href="' . of_get_option ( 'hub-classifieds' ) . '" class="btn btn-info" target="_blank">Help Wanted!<span class="offsite"></span></a>'; ?>
		<?php if ( of_get_option ( 'contact-email' ) ) $offsitelinks .= '<a href="mailto:' . of_get_option ( 'contact-email' ) . '" class="btn btn-info">Email<span class="offsite"></span></a>'; ?>
		<?php if ( of_get_option ( 'hub-list' ) ) $offsitelinks .= '<a href="' . of_get_option ( 'hub-list' ) . '" class="btn btn-info">Mailing List</a>'; ?>
		<?php $offsitelinks .= '[/pl_buttongroup]'; 

		$styling_class = 'with-callsub';
		
		$alignment = ploption('pagelines_callout_align', $this->oset);

		//$call_align = 'rtimg';	

		if($call_title || $call_img){ ?>
			
<?php if($alignment == 'center'): ?>
<div class="callout-area fix callout-center <?php echo $styling_class;?>">
	<div class="callout_text">
		<div class="callout_text-pad">
			<?php $this->draw_text($call_title, $call_sub, $call_img); ?>
		</div>
	</div>
	<div class="callout_action <?php echo $call_align;?>">
		<?php $this->draw_action($call_link, $target, $call_img, $call_btheme, $call_btext); ?></a>
	</div>
	
</div>
<?php else: ?>
<div class="callout-area media fix <?php echo $styling_class;?>">
	<div class="callout_action img <?php echo $call_align;?>">
		<?php $this->draw_action($call_link, $target, $call_img, $call_btheme, $call_btext); ?>
	</div>
	<div class="callout_text bd">
		<div class="callout_text-pad">
			<h2 class="callout_head hubhead"><a href="<?php echo $hubhome; ?>"><?php echo $call_title; ?></a></h2>
			<div class="connect">
				<?php echo do_shortcode($offsitelinks); ?>
			</div>
			<p class="callout_sub subhead"><?php echo $call_sub; ?></p>
		
		</div>
	</div>
</div>
<?php endif; ?>
<?php

		} else
			echo setup_section_notify($this, __('Set Callout page options to activate.', 'pagelines') );
			
	}
	
	function draw_action($call_link, $target, $call_img, $call_btheme, $call_btext){
		if( $call_img )
			printf('<div class="callout_image"><a %s href="%s" ><img src="%s" /></a></div>', $target, $call_link, $call_img);
		else 
			printf('<a %s class="btn btn-%s btn-large" href="%s">%s</a> ', $target, $call_btheme, $call_link, $call_btext);
		
	}
	
	function draw_text($call_title, $call_sub, $call_img){
		printf( '<h2 class="callout_head %s">%s</h2>', (!$call_img) ? 'noimage' : '', $call_title);
		
		if($call_sub)
			printf( '<p class="callout_sub subhead %s">%s</p>', (!$call_img) ? 'noimage' : '', $call_sub);
	}
	
}

<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses 'foghorn'.  If the identifier changes, it'll appear as if the options have been reset.
 * 
 */

function optionsframework_option_name() {
	
	$optionsframework_settings = get_option('optionsframework');
	$optionsframework_settings['id'] = 'foghorn';
	update_option('optionsframework', $optionsframework_settings);
	
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the "id" fields, make sure to use all lowercase and no spaces.
 *  
 */

function optionsframework_options() {
		
	// If using image radio buttons, define a directory path
	$imagepath =  get_bloginfo('template_url') . '/images/';
	
	// Options array	
	$options = array();
		
	$options[] = array( "name" => __('General Settings','foghorn'),
                    	"type" => "heading");
						
	$options[] = array( "name" => __('Custom Logo','foghorn'),
						"desc" => __('Upload a logo for your site. 150px x 150px standard','foghorn'),
						"id" => "logo",
						"type" => "upload");
												
	$options[] = array( "name" => __('Override logo dimensions and text','foghorn'),
						"desc" => __('Use custom logo which includes text and may be greater than 150px wide (height is still 150px max.','foghorn'),
						"id" => "logo_override",
						"std" => "0",
						"type" => "checkbox");
						
	$options[] = array( "name" => __('Display Site Tagline','foghorn'),
						"desc" => __('Display the site tagline under the site title.','foghorn'),
						"id" => "tagline",
						"std" => "0",
						"type" => "checkbox");
	
	$options[] = array( "name" => __('Menu Position','foghorn'),
						"desc" => __('Check to display the menu underneath the logo and floated left.  Good for long menus.','foghorn'),
						"id" => "menu_position",
						"std" => "0",
						"type" => "checkbox");
						
	$options[] = array( "name" => __('Layout','foghorn'),
						"desc" => __('Select a site layout: sidebar right, sidebar left, or no sidebar.','foghorn'),
						"id" => "layout",
						"std" => "layout-2cr",
						"type" => "images",
						"options" => array(
						'layout-2cr' => $imagepath . '2cr.png',
						'layout-2cl' => $imagepath . '2cl.png',
						'layout-1c' => $imagepath . '1col.png',)
						);
						
	$options[] = array( "name" => __('Custom Footer Text','foghorn'),
						"desc" => __('Custom text for the footer of your theme.','foghorn'),
						"id" => "footer_text",
						"std" => __( 'Powered by ', 'foghorn' ) . '<a href="http://www.wordpress.org">WordPress</a> ' . __( 'and ', 'foghorn' ) . '<a href="https://github.com/devinsays/foghorn">' . __( 'Foghorn', 'foghorn' ) . '</a>',
						"type" => "textarea");
/////////// ADDED //////////////

	$options[] = array(
		'name' => __('Hub Settings', 'options_check'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Hub Title', 'options_check'),
		'desc' => __('Enter title for Hub, full name', 'options_check'),
		'id' => 'hub-title',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Hub Handle', 'options_check'),
		'desc' => __('Enter the handle for the hub: no spaces. ex. bankjustice', 'options_check'),
		'id' => 'hub-handle',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Hub Mailing List Link', 'options_check'),
		'desc' => __('Enter URL for mailing list signup', 'options_check'),
		'id' => 'hub-list',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('CiviCRM ID', 'options_check'),
		'desc' => __('Enter ID of CiviCRM list', 'options_check'),
		'id' => 'civi-list',
		'std' => '',
		'type' => 'text');
		
	$wp_editor_settings_contact = array(
		'wpautop' => true, // Default
		'textarea_rows' => 3,
		'tinymce' => array( 'plugins' => 'wordpress' )
	);

	$options[] = array(
		'name' => __('Contact Info', 'options_check'),
		'desc' => __( 'More than just info and press contacts', 'options_check' ),
		'id' => 'contact-person',
		'type' => 'editor',
		'settings' => $wp_editor_settings_contact );

	$options[] = array(
		'name' => __('Contact Email', 'options_check'),
		'desc' => __('Contact Email', 'options_check'),
		'id' => 'contact-email',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Press Email', 'options_check'),
		'desc' => __('Press Email', 'options_check'),
		'id' => 'press-email',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => __('Donate Link', 'options_check'),
		'desc' => __('full url of donate page', 'options_check'),
		'id' => 'donate-link',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => __('Volunteer Link', 'options_check'),
		'desc' => __('full url of volunteer page', 'options_check'),
		'id' => 'volunteer-link',
		'std' => '',
		'type' => 'text');
			
	$options[] = array(
		'name' => __('Public Phone #', 'options_check'),
		'desc' => __('Phone number that is publically shared', 'options_check'),
		'id' => 'contact-phone',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Short Description', 'options_check'),
		'desc' => __('In 30 words or less...', 'options_check'),
		'id' => 'short-desc',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => __('Hub Image', 'options_check'),
		'desc' => __('Upload an image for your hub', 'options_check'),
		'id' => 'hub-image',
		'type' => 'upload');

	/**
	 * For $settings options see:
	 * http://codex.wordpress.org/Function_Reference/wp_editor
	 *
	 * 'media_buttons' are not supported as there is no post to attach items to
	 * 'textarea_name' is set by the 'id' you choose
	 */

	$wp_editor_settings = array(
		'wpautop' => true, // Default
		'textarea_rows' => 5,
		'tinymce' => array( 'plugins' => 'wordpress' )
	);

	$options[] = array(
		'name' => __('Full hub description', 'options_check'),
		'desc' => __( '', 'options_check' ),
		'id' => 'full-desc',
		'type' => 'editor',
		'settings' => $wp_editor_settings );

	$options[] = array(
		'name' => __('Carousel Background', 'options_check'),
		'desc' => __('Color behind header images', 'options_check'),
		'id' => 'color-header-bg',
		'std' => '',
		'type' => 'color' );

	$options[] = array(
		'name' => __('Input Checkbox', 'options_check'),
		'desc' => __('Regular call?', 'options_check'),
		'id' => 'regular-call',
		'std' => '1',
		'type' => 'checkbox');

	$options[] = array(
		'name' => __('Hub Tools', 'options_check'),
		'type' => 'heading');

	$options[] = array(
		'name' => __('Hub Facebook Page', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'hub-facebook',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Hub Twitter', 'options_check'),
		'desc' => __('Enter Twitter handle ONLY', 'options_check'),
		'id' => 'hub-twitter',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Social Media Additions', 'options_check'),
		'desc' => __( 'Whatever else you would like under Follow Us', 'options_check' ),
		'id' => 'hub-social',
		'type' => 'editor',
		'settings' => $wp_editor_settings_contact );

	$options[] = array(
		'name' => __('Call Information', 'options_check'),
		'desc' => __('Basics about call dates and times', 'options_check'),
		'id' => 'call-info',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Hub Web Page', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'hub-website',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('O.NET Forum', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'hub-forum',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('O.NET Wiki', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'hub-wiki',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('O.NET Classifieds', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'hub-classifieds',
		'std' => '',
		'type' => 'text');
		

	$options[] = array(
		'name' => __('Header Images', 'options_check'),
		'type' => 'heading');
		
	$options[] = array(
		'name' => __('Image #1', 'options_check'),
		'desc' => __('Upload an image', 'options_check'),
		'id' => 'header-1',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image #1 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-1-link',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Image #2', 'options_check'),
		'desc' => __('Upload an image', 'options_check'),
		'id' => 'header-2',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image #2 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-2-link',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Image #3', 'options_check'),
		'desc' => __('Upload an image', 'options_check'),
		'id' => 'header-3',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image #3 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-3-link',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Image #4', 'options_check'),
		'desc' => __('Upload an image', 'options_check'),
		'id' => 'header-4',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image #4 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-4-link',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Image #5', 'options_check'),
		'desc' => __('Upload an image', 'options_check'),
		'id' => 'header-5',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image #5 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-5-link',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Image #6', 'options_check'),
		'desc' => __('Upload an image', 'options_check'),
		'id' => 'header-6',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image #6 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-6-link',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => __('Image #7', 'options_check'),
		'desc' => __('Upload an image', 'options_check'),
		'id' => 'header-7',
		'type' => 'upload');

	$options[] = array(
		'name' => __('Image #7 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-7-link',
		'std' => '',
		'type' => 'text');

	$options[] = array(
		'name' => __('Image #8 Link', 'options_check'),
		'desc' => __('Enter full URL, including http://', 'options_check'),
		'id' => 'header-8-link',
		'std' => '',
		'type' => 'text');
	

	return $options;
}
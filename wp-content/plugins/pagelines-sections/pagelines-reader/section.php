<?php
/*
	Section: Reader
	Author: PageLines
	Description: A js powered 'reader' interface. Useful for FAQs or documentation.
	Version: 2.1.0
	Class Name: plReader	
	Workswith: content
	Demo: http://www.pagelines.com/pricing/?porthole=true&section_id=pagelines-reader&section_name=Reader#pagelines-reader
*/

class plReader extends PageLinesSection {


	var $taxID = 'pl-reader-sets';
	var $ptID = 'pl-reader';

	function section_persistent(){
		/* 
			Create Custom Post Type 
		*/
			$args = array(
					'label' 			=> __('Readers', 'pagelines'),  
					'singular_label' 	=> __('Reader', 'pagelines'),
					'description' 		=> 'For creating tabs in reader section layouts.',
					'menu_icon'			=> $this->icon, 
				);
				
			$taxonomies = array(
				$this->taxID => array(	
						"label" 			=> __('Reader Sets', 'pagelines'), 
						"singular_label" 	=> __('Reader Set', 'pagelines'), 
					)
			);
			
			$columns = array(
				"cb" 					=> "<input type=\"checkbox\" />",
				"title" 				=> "Title",
				"Reader-description" 	=> "Text",
				"Reader-sets" 			=> "Reader Sets"
			);
		
			$this->post_type = new PageLinesPostType($this->ptID, $args, $taxonomies, $columns, array( &$this, 'column_display'));

				
	}

	function getting_started(){
		ob_start();
		
		?>
		
		<strong>Congratulations on using the Reader section on your site. Here are the steps to get started:</strong> 
		<ol>
			<li><strong>Add Reader Posts</strong> Visit the reader custom post type and <a href="<?php echo admin_url('post-new.php?post_type='.$this->ptID);?>">add some reader docs</a> for use w/ this section.</li>
			<li><strong>Add &amp; Select Reader Sets</strong> Add <a href="<?php echo admin_url('edit-tags.php?taxonomy='.$this->taxID.'&post_type='.$this->ptID);?>">Reader sets</a> (i.e. categories) to Reader posts. Select a set for this page, or it will default to showing all.</li>
			<li><strong>Setup Reader Meta</strong> For each page you want to use this section, you'll need to configure some basic options. This is done through this panel.</li>
			
		</ol>
		
		<?php 
		return ob_get_clean();
	}

	function section_optionator( $settings ){
		
		$settings = wp_parse_args($settings, $this->optionator_default);
	
		$tab = array(
			
			'pl_reader_head' => array(
				'type' 			=> 'text',				
				'title' 		=> 'Reader Title',
				'shortexp' 		=> 'Your Reader Title, shown in first line',
				'inputlabel'	=> 'Add Reader Title'
			),
			'pl_reader_fhead' => array(
				'type' 			=> 'text',				
				'title' 		=> 'Reader Footer Text',
				'shortexp' 		=> 'Your Reader footer text, shown in the bottom of the Reader',
				'inputlabel'	=> 'Add Reader Footer text'
			),
			'pl_reader_dtab_head' => array(
				'type' 			=> 'text',		
				'title' 		=> 'Default Tab Title',
				'shortexp' 		=> 'The text header shown on the default tab.',
				'inputlabel'	=> 'Default Tab Title'
			),
			'pl_reader_dtab_text' => array(
				'type' 			=> 'textarea',				
				'title' 		=> 'Default Tab Text',
				'shortexp' 		=> 'The text/html shown on the default tab for the reader.',
				'inputlabel'	=> 'Default Tab Text'
			),
			'pl_reader_set' => array(
				'type' 			=> 'select_taxonomy',
				'taxonomy_id'	=> $this->taxID,				
				'title' 		=> 'Select Reader Set To Show',
				'shortexp'		=> 'If you are using the Reader section, select the Reader set you would like to show on this page.',
				'inputlabel'	=> 'Select Reader Set'
			)
			
			
		);
		
		$tab = $this->add_getting_started($tab);
			
		$metatab_settings = array(
				'id' 		=> 'pl_reader_meta',
				'name'	 	=> 'Reader',
				'icon' 		=> $this->icon,
				'clone_id'	=> $settings['clone_id'], 
				'active'	=> $settings['active']
			);
		
		register_metatab($metatab_settings, $tab, $this->class_name);
			
	}

	function section_head() { ?>
		
		<script type="text/javascript">
		/*<![CDATA[*/
			function getReader(id, nav){
				jQuery('#answerlist li.readeranswer').hide();
				jQuery(id).fadeIn('fast');
				jQuery('#questionlist li span').removeClass('readerselected');
				jQuery(nav).addClass('readerselected');
	        }
			jQuery(document).ready(function(){
				if(jQuery("#loadreader").val() != ""){
					var Reader = jQuery("#loadreader").val(); 
					getReader("#reader-"+Reader, "#nav-"+Reader+" span");
				}
			});
		/*]]>*/</script>

<?php }

   function section_template() {    

		$entries = $this->get_the_posts();
	
		if( empty($entries) ){
			echo setup_section_notify($this, __('Add Some Reader Posts To Get Started', 'pagelines'), admin_url('post-new.php?post_type='.$this->ptID), __('Add Post', 'pagelines'));
			return;
		}
		
		$header = (ploption('pl_reader_head', $this->oset)) ? ploption('pl_reader_head', $this->oset) : false;
		$footer = (ploption('pl_reader_fhead', $this->oset)) ? ploption('pl_reader_fhead', $this->oset) : false;
		
		$dtab_title = (ploption('pl_reader_dtab_head', $this->oset)) ? ploption('pl_reader_dtab_head', $this->oset) : __('Reader', 'pagelines');
		$dtab_text = (ploption('pl_reader_dtab_text', $this->oset)) ? ploption('pl_reader_dtab_text', $this->oset) : __('Welcome! To get started, just select a tab on the left.', 'pagelines');
		
		$load_Reader = (isset($_GET['reader'])) ? $_GET['reader'] : '';


printf('<input type="hidden" id="loadreader" value="%s"  />', $load_Reader);


	
?>		
<div class="pl_reader">	
	<?php
	
	if($header)
		printf('<div class="readerheader" onClick="getReader(\'#reader-%s\', this);"><h4>%s</h4></div>', 1, $header);
	
	?>
	<div class="pl_reader_container fix">

		<div class="readernav">
			<div class="readernav-pad">
				<ul id="questionlist" class="styled-list">
<?php 			$ecount = 2;

 				foreach($entries as $reader){
					printf('<li id="nav-%1$s"><span onClick="getReader(\'#reader-%1$s\', this);">%2$s</a></li>', $ecount, $reader->post_title);
		 			$ecount++; 
				}
			?>
				</ul>
			</div>
		</div>
		<div class="reader-content-container">
			<div class="reader-content-container-pad">
				<div class="reader-content">
					<div class="reader-content-pad">
						<ul id="answerlist" class="readeranswer styled-list">
			<?php 
	
							printf('<li id="reader-1" class="readerdefault readeranswer"><h2>%s</h2><p>%s</p></li>', $dtab_title, $dtab_text);
					
					
							$ecount = 2;
							foreach($entries as $reader) : ?>
							<li id="reader-<?php echo $ecount;?>" class="readeranswer">
								<?php
						 
									printf('<div class="fquestion"><h3 class="Readerhead">%s</h3></div>',$reader->post_title);
									printf('<div class="fanswer">%s%s</div>', apply_filters( 'the_content', do_shortcode($reader->post_content) ), pledit($reader->ID)); 
									$ecount++;
							
									?>
							</li>
			<?php 	 	 endforeach;	?>
						</ul>

					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>

	</div>
	<?php if($footer) printf('<div class="readerfooter">%s</div>', $footer); ?>
</div>
<?php  }


	function get_the_posts(){
		global $post;
		
		if(!isset($this->entries)){
					
			$query_args = array('post_type' => $this->ptID, 'orderby' =>'ID');
		
			if( ploption( 'pl_reader_set', $this->oset ) && isset($post->ID))
				$query_args[ $this->taxID ] = ploption( 'pl_reader_set', $this->oset );
				
			
			$query_args['showposts'] = 30;
			
			$my_query = new WP_Query($query_args);
		
		 	$this->entries = $my_query->posts;
			
		 	if(is_array($this->entries)) 
				return $this->entries;
			else 
				return array();
			
		} else {
			return $this->entries;
		}
	
	}


	function column_display($column){
		global $post;

		switch ($column){
			case "Reader-description":
				the_excerpt();
				break;
			case $this->taxID:
				echo get_the_term_list($post->ID, $this->taxID, '', ', ','');
				break;
		}
	}
}



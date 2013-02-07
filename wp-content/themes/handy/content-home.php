<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Foghorn
 * @since Foghorn 0.1
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php 
		$home_content = '[sws_2_column title="" rightmargin="20px"]';
		$home_content .= get_the_content();
		$home_content .= '[/sws_2_column] [sws_2_columns_last title=""]';
		$home_content .= '[sws_ui_tabs ui_theme="ui-smoothness" size="100%"][tabs_panel title="All News"][social-media-mashup count="10"][/tabs_panel][/sws_ui_tabs]';
		$home_content .= '[/sws_2_columns_last]';
		echo do_shortcode($home_content); 
		?>
        <?php edit_post_link( __( 'Edit', 'foghorn' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->

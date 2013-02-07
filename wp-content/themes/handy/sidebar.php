<?php
/**
 * The Sidebar containing the main widget area.
 *
 * @package WordPress
 * @subpackage Foghorn
 * @since Foghorn 0.1
 */
?>
		<div id="secondary" class="widget-area" role="complementary">
			
			<div class="textwidget">
				<?php if ( of_get_option('donate-link',false) ) { ?><a href="<?php echo of_get_option('donate-link'); ?>" class="button">Donate</a><?php } ?>
				<?php if ( of_get_option('volunteer-link',false) ) { ?><a href="<?php echo of_get_option('volunteer-link'); ?>" class="button">Volunteer</a><?php } ?>
				<?php if ( of_get_option('hub-list',false) ) { ?><a href="<?php echo of_get_option('hub-list'); ?>" class="button">Join Mailing List</a><?php } ?>
			</div>

			<?php if ( of_get_option('civi-list',true) ) { ?>
			<aside id="text-4" class="widget widget_text">
				<h1 class="widget-title">Email Subscription</h1>
				<div class="textwidget"><form class="email-form" action="http://crm.interoccupy.net/civicrm/profile/create?gid=<?php echo of_get_option('civi-list'); ?>&amp;reset=1" method="post" name="Edit" id="Edit">
					<div><input name="postURL" type="hidden" value="<?php echo esc_url( home_url( '/' ) ); ?>subscribed/"><input name="cancelURL" type="hidden" value="http://crm.interoccupy.net/civicrm/profile?reset=1&amp;gid=<?php echo of_get_option('civi-list'); ?>"><input name="add_to_group" type="hidden" value="32"><input name="_qf_default" type="hidden" value="Edit:cancel">
					</div><script type="text/javascript" src="http://crm.interoccupy.net/sites/all/modules/civicrm/js/Common.js"></script>
					<div id="crm-container" lang="en" xml:lang="en">
						<div class="form-layout-compressed">
							<h4 class="form-title"><b>Stay informed, subscribe</b></h4>
							<div id="editrow-email-3">
								<div class="edit-value content"><input maxlength="64" size="30" name="email-3" type="text" id="email-3" class="form-text big required" placeholder="Enter email"></div>
							</div>
						</div><!-- end form-layout-compressed for last profile -->
						<span class="crm-button crm-button-type-next crm-button_qf_Edit_next"><input class="form-submit default button btnSmall" accesskey="S" name="_qf_Edit_next" value="Submit" type="submit" id="_qf_Edit_next"></span>
					</div>
					</form>
				</div>
			</aside>
			<?php } ?>


			<div class="textwidget">
				<div class="start-widget">
				<h4><b>Contact us:</b></h4>	
				<?php if ( of_get_option('contact-person',false) ) { ?><p><?php echo of_get_option('contact-person'); ?></p><?php } ?>
			<?php if ( of_get_option('contact-email',false) ) { ?><p><span>Info:</span><a href="mailto:<?php echo of_get_option('contact-email'); ?>"> <?php echo of_get_option('contact-email'); ?></a></p> <?php } ?>
			<?php if ( of_get_option('press-email',false) ) { ?><p><span>Press:</span><a href="mailto:<?php echo of_get_option('press-email'); ?>"> <?php echo of_get_option('press-email'); ?></a></p> <?php } ?>
				</div>
			</div>
			
			<div class="textwidget">
				<div class="start-widget"><h4><b>Follow us:</b></h4>	<?php if ( of_get_option('hub-twitter',false) ) { ?><a class="socialgram" href="http://www.twitter.com/<?php echo of_get_option('hub-twitter'); ?>" target="_blank">&#62217;</a><?php } ?> <?php if ( of_get_option('hub-facebook',false) ) { ?><a class="socialgram" href="<?php echo of_get_option('hub-facebook'); ?>" target="_blank">&#62220;</a><?php } ?> </p>
				<?php if ( of_get_option('hub-social',false) ) { ?><p><?php echo of_get_option('hub-social'); ?></p><?php } ?>
				</div>
			</div>
			
			<?php if ( ! dynamic_sidebar( 'sidebar' ) ) : ?>


			<?php endif; // end sidebar widget area ?>
		</div><!-- #secondary .widget-area -->


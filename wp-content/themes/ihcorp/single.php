<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage IHCorp
 * @since Twenty Twelve 1.0
 */

get_header();

// draw featured image up top
the_post_thumbnail( array(1020,360), array('class' => 'featuredimage'));

?>
	
	<div class='content_area'>
		<div class='two_thirds'>
			<?php
				while (have_posts()) { the_post(); the_content(); }
			?>
		</div>
	
		<div class='one_third last'>
			<div class='shortcuts box'>
				<div class='icon home'></div>
				<h3>Shortcuts</h3>
				<ul class='custombullet'>
					<li><a href='#'>Rent Payments</a></li>
					<li><a href='#'>Maintenance Request</a></li>
					<li><a href='#'>My Account</a></li>
					<li><a href='#'>Technical Support</a></li>
				</ul>
			</div>
			<div class='apply_now box'>
				<div class='icon applynow'></div>
				<h3>Apply now</h3>
				<ul class='custombullet'>
					<li><a href='#'>Qualification Requirements</a></li>
					<li><a href='#'>Create An Account</a></li>
					<li><a href='#'>Existing Applicants</a></li>
					<li><a href='#'>Technical Support</a></li>
				</ul>
				<p>
					Call us at <b>800.339.RENT</b> or apply online.
				</p>
				<input class='apply_now_button' type='button' name='apply now' value='apply now'>
			</div>
		</div>
	</div>

<?php require_once('footer.php'); ?>

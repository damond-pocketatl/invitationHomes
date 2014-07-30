<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage IHCorp
 * @since Twenty Twelve 1.0
 */

require_once('header.php'); ?>

<div class='content_area'>
		<div class='two_thirds'>
			<h2 class="entry-title">This is somewhat embarrassing, isn't it?</h2>
			<p>It seems we can't find what you're looking for.</p>
		</div>
	
		<div class='one_third last boxes'>
			<?php if ( dynamic_sidebar('contentpage-1') ) : else : endif; ?>
		</div>
	</div>

<?php require_once('footer.php'); ?>
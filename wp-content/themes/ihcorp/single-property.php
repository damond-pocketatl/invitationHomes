<?php
/**
 * The Template for displaying all single "property" posts.
 *
 * @package WordPress
 * @subpackage IHCorp
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div id="primary">
		<div id="content" role="main">



			<?php while ( have_posts() ) : the_post(); ?>

				<p>This page is not available.</p>

				<?php
				// If we are in a loop we can get the post ID easily

				$p = get_post_meta( $post->ID, 'propdetails', true ); 
				//print_r($p);

				?>

				<?php //get_template_part( 'content-single', get_post_format() ); ?>

			<?php endwhile; // end of the loop. ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_footer(); ?>
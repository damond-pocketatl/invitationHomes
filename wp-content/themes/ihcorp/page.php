<?php
/**
 * The Template for displaying generic content page
 *
 * @package WordPress
 * @subpackage IHCorp
 * @since Twenty Twelve 1.0
 */

$h1t = pull_meta('h1-text');
$h1_text = $h1t['h1-text'];
$headerurl = $h1t['header-url'];

require_once('header.php');

if($headerurl) :
print '<a href="'.$headerurl.'">';
endif;
// draw featured image up top
the_post_thumbnail( array(1020,360), array('class' => 'featuredimage'));
if($headerurl) :
print '</a>';
endif;
?>
	
	<div class='content_area'>
		<div class='two_thirds'>
			<?php
				while (have_posts()) { the_post(); the_content(); }
			?>
		</div>
	
		<div class='one_third last boxes'>
			<?php if ( dynamic_sidebar('contentpage-1') ) : else : endif; ?>
		</div>
	</div>

<?php require_once('footer.php'); ?>

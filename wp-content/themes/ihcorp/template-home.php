<?php
/*
Template Name: Home Page
*/

$h1t = pull_meta('h1-text');
$h1_text = $h1t['h1-text'];

require_once('header.php');

// grab content for the 3 home page boxes
$boxes = pull_meta('home_page_options');

?>

	<!-- search widget -->
	<div class="search">
		<div class="search-inner">
			<div class="highlight"><h2>Find a New Home</h2></div>
				<select id='location_choose'>
					<option selected value=''>Choose your location...</option>
					<option value='atlanta-ga'>Atlanta, GA</option>
					<option value='charlotte-nc'>Charlotte, NC</option>
					<option value='chicago-il'>Chicago, IL</option>
					<option value='inland-empire-ca'>Inland Empire, CA</option>
					<option value='jacksonville-fl'>Jacksonville, FL</option>
					<option value='las-vegas-nv'>Las Vegas, NV</option>
					<option value='los-angeles-county-ca'>Los Angeles County, CA</option>
					<option value='miami-fl'>Miami, FL</option>
					<option value='minneapolis-mn'>Minneapolis, MN</option>
					<option value='northern-california'>Northern California</option>
					<option value='orlando-fl'>Orlando, FL</option>
					<option value='phoenix-az'>Phoenix, AZ</option>
					<option value='seattle-wa'>Seattle, WA</option>
					<option value='tampa-fl'>Tampa, FL</option>
					<option value='ventura-county-ca'>Ventura County, CA</option>
				</select>
			<div class="homeimg">
			  <img src='<?php echo get_option('siteurl');?>/wp-content/uploads/2013/09/ih-homecallout-update1.jpg'>
			</div>
			<div class='copy'>
				<p class='callus'>Call us today</p>
				<p class='phone_number'>(800) 339-RENT</p>
			</div>
		</div><!-- /search-inner -->
	</div><!-- /search -->

	<div id="slider">
		<ul class="rslides">
		<?php

			// load all the slides from the custom post type
			$args = array(
				'post_type' => 'home_page_image',
				'orderby' => 'date',
				'order' => 'DESC'
			);
			$p = new WP_Query( $args );
			if( $p->have_posts() ) {

				// loop through each image and add it to the slider
				while( $p->have_posts() ) {
					$p->the_post();

					

					?>
					
					<li>
						<?php if($boxes['link-url']) { ?>
							<a href='<?php echo $boxes['link-url'] ?>'>
						<?php }
						
						the_post_thumbnail('full');		
						
						if(!$boxes['text-over-image']) { ?>
							<div class="rslides-content">
								<h2><?php the_title(); ?></h2>
								<?php the_content(); ?>
							</div>
						<?php } ?>

						<?php if($boxes['link-url']) { ?>
							</a>
						<?php } ?>

					</li>

					<?php
				}
			}
			
			// while we're here, get the featured properties, too
			$args = array('post_type' => 'property',
						'meta_key' => 'featured-property',
						'meta_value' => 'on',
						'posts_per_page' => -1,
						'orderby' => 'rand'
			);
			$myposts = get_posts($args);
			$featured = array();

			foreach( $myposts as $pst ) :
				$deets = array_pop(get_post_meta( $pst->ID, 'propdetails', true ));
				$deets['thumb'] = get_the_post_thumbnail($pst->ID, 'property-thumb');
				if(!$deets['thumb']) $deets['thumb'] = "<img src='".get_option('siteurl')."/wp-content/themes/ihcorp/img/imagenotavail.png'>";
				$featured[] = $deets;
			endforeach;

		?>

		</ul>
	</div><!-- /slider -->
	
	<!-- three center boxes -->
	<div id='centerboxes'>
		<?php if ( dynamic_sidebar('home-3') ) : else : endif; ?>
	</div><BR>

	<h3 class="highlight"><span>Featured Homes</span></h3>

	<ul class="listing">
		<li class="one_third first">
			<?php $f = $featured[0]; include('propertyblock.php'); ?>
		</li>
		
		<li class="one_third second">
			<?php $f = $featured[1]; include('propertyblock.php'); ?>
		</li>

		<li class="one_third last">
			<?php $f = $featured[2]; include('propertyblock.php'); ?>
		</li>
		
		<li class="one_third fourth">
			<?php $f = $featured[3]; include('propertyblock.php'); ?>
		</li>

		<li class="one_third fifth">
			<?php $f = $featured[4]; include('propertyblock.php'); ?>
		</li>
		
		<li class="one_third last">
			<?php $f = $featured[5]; include('propertyblock.php'); ?>
		</li>
	</ul><!-- /listing -->

	<div id='seoblurb'>
		<?php if(have_posts()) : while(have_posts()) : the_post();
		the_content();
		endwhile; endif; ?>
	</div>

<?php $onHomePage=1; require_once('footer.php'); ?>

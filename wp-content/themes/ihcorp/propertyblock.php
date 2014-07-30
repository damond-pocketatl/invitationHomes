<?php if ($f) { ?>
<?php setup_postdata($pst); ?>
	<div class="propblock">
	<a href="<?php echo $f['url-to-property-detail-page'] ?>">
		<h6><?php echo $f['city'] ?>, <?php echo $f['state'] ?></h6>
		<span class="price">$<?php echo $f['rent'] + 0 ?></span>
	    <?php echo $f['thumb'] ?>					
		<?php if(stristr($f['community-amenities'], 'pool')) echo '<span class="featured">Featured</span>'; ?>
		<ul class="listing-info">
			<li class="listing-info-beds"><?php echo $f['bedrooms'] ?> Beds</li>
			<li class="listing-info-baths"><?php echo $f['bathrooms'] ?> Baths</li>
			<li class="listing-info-area"><?php if($f['square-footage']) echo number_format((int)$f['square-footage'], 0); ?> ft<sup>2</sup></li>
		</ul>
	</a>
	<!-- <?php 
	the_time('F jS, Y');
	?> -->
	</div>
<?php } ?>
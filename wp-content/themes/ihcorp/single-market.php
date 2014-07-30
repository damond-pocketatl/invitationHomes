<?php

/*
// get all the markets (taxonomy) that go with this market
echo "\n\n\ngetting terms for this market, post id ".$post->ID."\n\n";

$terms = get_the_terms($post->ID, 'market');

echo "here is a dump of the taxonomy:\n\n";
print_r($terms);
die();
*/


// this gets metainfo that we have stored with this market-- like SEO text
// $s = get_post_meta( $post->ID, 'market-details', true );
// print_r($s); die();
$p = pull_meta('market-details');
$p['latlng'] = get_geocode_latlng($post->ID);

// get promo zone info
$promo = pull_meta('promo_zone');

// get seo options
$seo_options = pull_meta('seo_options');

$h1_text = $seo_options['h1-text-hidden'];

// get all the markets (taxonomy) that go with this market
// echo "\n\n\ngetting terms for this market, post id ".$post->ID."\n\n";

$terms = get_the_terms($post->ID, 'market');

// walk through taxonomy and see if any of them have a parent-- if so, that's the one we want
// print_r($terms);
foreach($terms as $t) { if ($t->parent) break; }
$thisMarket = $t->slug;
$thisMarketId = $t->term_id;

require_once('header.php');

//echo "\n\n\nthis market is $thisMarket \n"; die();

// gather up all the properties for this market -- only those "Vacant Unrented Ready" OR featured
$args = array(
	'post_type' => 'property',
	'market' => $thisMarket,
	'posts_per_page' => -1,
	'order' => 'rand',
	'orderby' => 'post_date',
	);

$querystr = "
    SELECT wposts.*, meta1.meta_value
    FROM $wpdb->posts wposts, $wpdb->postmeta meta1, $wpdb->term_relationships tax
    WHERE wposts.ID = meta1.post_id

    AND meta1.meta_key = 'propdetails'
    AND meta1.meta_value LIKE '%Vacant%'

	AND tax.object_id = wposts.ID AND tax.term_taxonomy_id = $thisMarketId

    AND wposts.post_status = 'publish'
    AND wposts.post_type = 'property'
    ORDER BY RAND()
 ";


$querystr = "
	SELECT wposts.*
	  FROM $wpdb->posts wposts, $wpdb->term_relationships tax
	INNER
	  JOIN ( SELECT post_id
	           FROM $wpdb->postmeta wpostmeta
	          WHERE (
						(
							  ( wpostmeta.meta_key = 'propdetails' AND wpostmeta.meta_value LIKE '%Vacant%')
			               OR ( wpostmeta.meta_key = 'featured' AND wpostmeta.meta_value = 'on' )
						)
	               )
	         GROUP
	             BY post_id
	         ) AS t
	    ON t.post_id = wposts.ID
	 WHERE wposts.post_status = 'publish'
	   AND wposts.post_type = 'post'
	   AND tax.object_id = wposts.ID AND tax.term_taxonomy_id = $thisMarketId
	ORDER BY RAND()
        
";



$querystr = "
	SELECT T2.*, T1.featured, T1.latlng FROM
	(
	    SELECT post_id, SUM(IF((meta_key='propdetails' AND meta_value LIKE '%Vacant%') OR ((meta_key='featured' AND meta_value='on')),1,0)) AS Keeper
	    , MAX(IF(meta_key = 'featured-property',meta_value,NULL)) AS featured
	    , MAX(IF(meta_key = 'martygeocoderlatlng',meta_value,NULL)) AS latlng
	    FROM wp_postmeta 
	    GROUP BY post_id
	    HAVING Keeper>0
	) AS T1
	JOIN
		wp_posts AS T2
		ON T1.post_id=T2.ID
		AND T2.post_type = 'property'
		AND T2.post_status = 'publish'
	JOIN
		wp_term_relationships AS T3
		ON T2.ID=T3.object_id AND T3.term_taxonomy_id = $thisMarketId
	GROUP BY T2.ID
	
        ORDER BY RAND()
";

$properties = $wpdb->get_results($querystr, OBJECT);

$featured = $property = array();


//print_r($properties); die();

echo "<div style='display: none;'>";

$count = 1;

// go thru each property
global $pst;
foreach( $properties as $pst ) :
	$deets = array_pop(get_post_meta( $pst->ID, 'propdetails', true ));
	// $deets['latlng'] = get_geocode_latlng($pst->ID);
	$deets['thumb'] = get_the_post_thumbnail($pst->ID, 'property-thumb');
	if(!$deets['thumb']) $deets['thumb'] = "<img src='/".get_option('siteurl')."/wp-content/themes/ihcorp/img/imagenotavail.png'>";
	// $deets['featured'] = get_post_meta( $pst->ID, 'featured-property', true );

	// keep track of it if it's featured
	if($pst->featured == 'on') $featured[] = $deets;

	if($pst->latlng) {
		// draw div info box for each property, so it pops up on Google Map
		echo "
			<div class='mapitem' id='map-item".$count++."'>
				<a href='".$deets['url-to-property-detail-page']."'><div>";
                                        if($pst->featured == 'on') { 
                                        echo "<p class='featmaplist'>Featured Listing</p>";
                                        }
                echo "
                                        <p>
						<b>".$deets['address']."<br>".$deets['city'].', '.$deets['state'].' '.$deets['zip-code']."</b>
					</p>
					<ul class='listing-info'>
						<li class='listing-info-beds'>".$deets['bedrooms']." Beds</li>
						<li class='listing-info-baths'>".$deets['bathrooms']." Baths</li>
						<li class='listing-info-area'>".$deets['square-footage']." ft<sup>2</sup></li>
						<li class='listing-info-rent'><strong>$".number_format((int)$deets['rent'], 0)."</strong></li>
					</ul>
				</div></a>
			</div>
		";
	}
endforeach;
echo "</div>";

$market = get_the_title();

?>

	<div id='marketpromo'>
		<h2><?php echo $promo['headline'] ? $promo['headline'] : "RENTAL HOMES IN ".strtoupper($market) ?></h2>
		<?php if($promo['image']) { echo wp_get_attachment_image( $promo['image'], 'full' ); } ?>
		<?php echo $promo['body-copy'] ?>
		<?php if($promo['button-title']) {
			echo "<a href='".$promo['button-url']."'><input type='button' value='".$promo['button-title']."'></a>";
		} ?>
	</div>
	<div id='googlemap'><div id='realgooglemap'></div></div>
	
	<h3 class="highlight"><span>Featured Homes in <?php echo $market ?></span></h3>

	<div class='two_thirds'>
		<ul class="listing">
			<li class="one_half">
				<?php $f = $featured[0]; include('propertyblock.php'); ?>
				<?php if(!$f) { print 'No featured listings available in this market.'; } ?>
			</li>

			<li class="one_half last">
				<?php $f = $featured[1]; include('propertyblock.php'); ?>
			</li>
		
			<li class="one_half">
				<?php $f = $featured[2]; include('propertyblock.php'); ?>
			</li>
		
			<li class="one_half last">
				<?php $f = $featured[3]; include('propertyblock.php'); ?>
			</li>
		</ul><!-- /listing -->
	</div>
	<div class='one_third last formholder'>
		<div class='formbox'>
			<div id='form_success' class='form_result'>
				Thank you!
				<br><br>
				We'll be in touch with you soon.
			</div>
			<div id='form_fail' class='form_result'></div>
			<form id='myform' name='myform' method='post' action='#'>
				<p class='formleader'>
					Request More Details
				</p>
				<div class='firstname_block'>
					<label class='firstname' for='firstname'>
						first name
					</label>
					<div class='field firstname small'>
						<input type='text' placeholder='Required' name='f[firstname]' id='firstname' required>
					</div>
				</div>
				<div class='lastname_block'>
					<label class='lastname' for='lastname'>
						last name
					</label>
					<div class='field lastname small'>
						<input type='text' name='f[lastname]' id='lastname'>
					</div>
				</div>
				<label class='email clear' for='email'>
					email
				</label>
				<div class='field'>
					<input type='text' placeholder='Required' name='f[email]' id='email' required>
				</div>
				<label for='phone'>
					phone number
				</label>
				<div class='field'>
					<input type='text' name='f[phone]' id='phone'>
				</div>
				<label for='comments'>
					comments
				</label>
				<div class='field'>
					<textarea maxlength='255' name='f[comments]' id='comments'></textarea>
				</div>
<?php //format market for email address
switch($market) {
  case 'Atlanta, GA':
    $marketemail = 'AtlantaLeasing';
    break;
  case 'Charlotte, NC':
    $marketemail = 'CharlotteLeasing';
    break;
  case 'Chicago, IL':
    $marketemail = 'ChicagoLeasing';
    break;
  case 'Inland Empire, CA':
    $marketemail = 'InlandEmpireLeasing';
    break;
  case 'Jacksonville, FL':
    $marketemail = 'JacksonvilleLeasing';
    break;
  case 'Las Vegas, NV':
    $marketemail = 'LasVegasLeasing';
    break;
  case 'Los Angeles County, CA':
    $marketemail = 'LACountyLeasing';
    break;
  case 'Ventura County, CA':
    $marketemail = 'LACountyLeasing';
	break;
  case 'Miami, FL':
    $marketemail = 'MiamiLeasing';
    break;
  case 'Minneapolis, MN':
    $marketemail = 'MinneapolisLeasing';
    break;
  case 'Northern California':
    $marketemail = 'SacramentoLeasing';
    break;
  case 'Orlando, FL':
    $marketemail = 'OrlandoLeasing';
    break;
  case 'Phoenix, AZ':
    $marketemail = 'PhoenixLeasing';
    break;
  case 'Seattle, WA':
    $marketemail = 'SeattleLeasing';
    break;
  case 'Tampa, FL':
    $marketemail = 'TampaLeasing';
    break;
  default:
    $marketemail = "CustomerService";
    break;
}
?>
				<input type='hidden' name='f[marketemail]' value='<?php echo $marketemail ?>'>
				<input type='hidden' name='f[market]' value='<?php echo $market ?>'>
				<input type='submit' value='send'>

			</form>
		</div>
                <div class="formbutton">
                                <p>
					Call for more info
				</p>
				<p class='phone'>(800) 339-RENT</p>
                </div>
	</div>

	<div id='seoblurb'>
		<?php echo $p['seo-text'] ?>
		<?php if($p['read-more-link'] && trim($p['read-more-link']) != 'http://') { ?>
			<div><a href='<?php echo $p['read-more-link'] ?>'>Read more</a></div>
		<?php } ?>
	</div>

<?php $onMarketPage=1; require_once('footer.php'); ?>
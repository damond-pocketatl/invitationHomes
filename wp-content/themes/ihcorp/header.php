<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage IHCorp
 * @since Twenty Twelve 1.0
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE; Chrome=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">
	 
	<title><?php wp_title(); ?></title>
	<?php wp_head(); ?>
    <link rel="favicon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico">
	<link rel="icon" type="image/x-icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico">
	<link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/img/favicon.ico">
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style.css">
	<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/custom.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,400italic,700italic">
		
	<meta name="google-site-verification" content="hnCWd_WxgFLBvCzIF9wFJ8phBkcR0kmD-Ym9Bt6UMJY" />
	<!--[if lt IE 9]><link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/ie8.css"><![endif]-->

	<script src="<?php bloginfo('template_directory'); ?>/js/respond.min.js"></script>
	<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	
<script>
        var _gaq = _gaq || [];
  		_gaq.push(['_setAccount', 'UA-35067868-1'],['_trackPageview']); //production code
  		_gaq.push(['_setDomainName', 'invitationhomes.com']);
  		_gaq.push(['_setAllowLinker', true]);
  		_gaq.push(['_trackPageview']);

  		(function() {
  		  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
  		  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
  		  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  		})();
</script>
		
</head>

<body id="home" >
<!-- RadiumOne code begin -->
<script type="text/javascript">
<!--
document.write('<img src="'+("https:"==document.location.protocol?"https:":"http:")+"//rs.gwallet.com/r1/pixel/x15521"+"r"+Math.round(1E7*Math.random())+'" width="1" height="1" border="0" alt=""/>');
//-->
</script>
<noscript>
<img src="https://rs.gwallet.com/r1/pixel/x15521"/>
</noscript>
<!-- RadiumOne code end -->
<!-- Undertone code -->
<script type='text/javascript'>
    var p=location.protocol=='https:'?'https:':'http:';
    var r=Math.floor(Math.random()*999999);
    document.write('<img src="' + p + '//ads.undertone.com/f?pid=48494&cb=' + r +'" alt="" style="display:none;" border="0" height="1" width="1" />');
</script>
<noscript>
<img src="https://ads.undertone.com/f?pid=48494&cb=[timestamp]" style="display: none;" width="0" height="0" alt="" />
</noscript>
<!-- end Undertone code -->

 <?php //include( TEMPLATEPATH . '/inc/notice-bar.php' ); ?>
 
	<header>
		<div class="header-inner">
			<a href='/'><h1><?=$h1_text ?></h1></a>

			<?php wp_nav_menu( array( 'theme_location' => 'subnav' ) ); ?>

			<div id="navholder">
				<div id="nav">
					<ul>
						<li>
							<a href="#locations" id="current">Locations</a>
							<?php wp_nav_menu( array( 'theme_location' => 'locations' ) ); ?>
						</li>
						<li>
							<a href="/apply-now/">Apply Now</a>
							<?php wp_nav_menu( array( 'theme_location' => 'applynow' ) ); ?>
						</li>
						<li>
							<a href="/resident-services/">Resident Services</a>
							<?php wp_nav_menu( array( 'theme_location' => 'residentservices' ) ); ?>
						</li>
					</ul>
				</div>
			</div>
			<div id='searchholder'>
				<p><span>Search Homes Now</span></p>
				
				<div id="searchbox"><span style="display: none;"><?php echo get_the_title(); ?></span>
					<input type="text" id='search_market' name="search_market" value="" placeholder="City, ST" />
					<ul>
						<li>
							Price
							<ul id='search_price'>
								<li class='selected' value=''>Any</li>
								<li value='0-599'>less than $600</li>
								<li value='600-899'>$600 - $899</li>
								<li value='900-1199'>$900 - $1199</li>
								<li value='1200-1599'>$1200 - $1599</li>
								<li value='1600-1999'>$1600 - $1999</li>
								<li value='2000-2499'>$2000 - $2499</li>
								<li value='2500-'>$2500 and above</li>
							</ul>
						</li>
						<li>
							Bed
							<ul id='search_beds'>
								<li class='selected' value='-1'>Any</li>
								<li value='2'>2</li>
								<li value='3'>3</li>
								<li value='4'>4+</li>
							</ul>
						</li>
						<li>
							Bath
							<ul id='search_baths'>
								<li class='selected' value='-1'>Any</li>
								<li value='1'>1</li>
								<li value='2'>2</li>
								<li value='3'>3+</li>
							</ul>
						</li>
					</ul>
					<div class='mobile'>
						<span>Price:</span>
						<select id='search_price2'>
							<option class='selected' value=''>Any</option>
							<option value='0-599'>less than $600</option>
							<option value='600-899'>$600 - $899</option>
							<option value='900-1199'>$900 - $1199</option>
							<option value='1200-1599'>$1200 - $1599</option>
							<option value='1600-1999'>$1600 - $1999</option>
							<option value='2000-2499'>$2000 - $2499</option>
							<option value='2500-'>$2500 and above</option>
						</select>
						<span>Beds:</span>
						<select id='search_beds2'>
							<option class='selected' value='-1'>Any</option>
							<option value='2'>2</option>
							<option value='3'>3</option>
							<option value='4'>4+</option>
						</select>
						<span>Bath:</span>
						<select id='search_baths2'>
							<option class='selected' value='-1'>Any</option>
							<option value='1'>1</option>
							<option value='2'>2</option>
							<option value='3'>3+</option>
						</select>
					</div>
					<input id='searchsubmit' type='submit' value=''>
				</div>
			</div>
		</div><!-- /header-inner -->
	</header>

	<div id="wrapper">


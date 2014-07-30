<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage IHCorp
 * @since Twenty Twelve 1.0
 */
if($onMarketPage) {
$marklatlng = $p['latlng']; 
?>
<script>
(function(h,a,e,f,g,b,c,d){h[g]=h[g]||{};(h[g].info=h[g].info||[]).push(d);(h[g].g=h[g].g||function(m,i,j,n,k,l){k=a.createElement(m);l=m=="script"?a.getElementsByTagName("head")[0]:(a.documentElement||a.body);if(typeof i=="function"){i(k,j)}else{k[i]=j}k.src=n;l.appendChild(k)})(e,b,c,f)})(window,document,"script","https://t.invoc.us/fp/r.js","___vo","async",1,"NDAyfDJBRUUyOHw3MTQ1fDB8MHw2fDI5RjA4MkVE");
</script>
<?php
}
?>
	</div><!-- /wrapper -->

	<div class="footer">
		<div class="footer-inner">
			<div class="one_fourth">
				<h3>Homes for Rent</h3>
				<div class='twocollist'>
					<?php wp_nav_menu( array( 'theme_location' => 'footerlocations' ) ); ?>
				</div>
			</div>

			<div class="one_fourth">

				<h3>Services</h3>
				<div class='twocollist'>
					<?php wp_nav_menu( array( 'theme_location' => 'footerservices' ) ); ?>
				</div>

			</div>

			<div class="one_fourth">

				<h3>Sell Us Your Property</h3>
				<div class='twocollist'>
				       <?php wp_nav_menu( array( 'theme_location' => 'footerseller' ) ); ?>
				</div>
<!--
				<p>
					Headquarters<br>
					901 Main Street, Suite 4700<br>
					Dallas, TX 75202 <br>
					<a href="mailto:info@invitationhomes.com">info@invitationhomes.com</a><br>
					<b>800.339.7368</b>
				</p>
-->
			</div>

			<div class="one_fourth last">
				<h3>Stay Connected</h3>
			
				<ul id="social">
					<li><a href="http://www.facebook.com/InvitationHomesWeb" id="facebook">Facebook</a></li>
					<li><a href="https://twitter.com/InvitationHomes" id="twitter">Twitter</a></li>
					<li><a href="https://plus.google.com/u/0/113168798825211107373/posts" id="google">Google Plus</a></li>
					<li><a href="http://www.linkedin.com/company/2780388?trk=tyah" id="linkedin">LinkedIn</a></li>
				</ul>			
			</div>

		</div><!-- /footer-inner -->

		<div class="footer-inner" style='clear:both;'>
			<div class='toprule-holder'>
				<div class='toprule'>
					<div class="one_third">
					    <a href="http://portal.hud.gov/hudportal/HUD?src=/program_offices/fair_housing_equal_opp" target="_blank"><div class='eqhomeslogo'></div></a>
					</div>
					<div class="two_thirds last">
					    <?php if ( dynamic_sidebar('footerterms') ) : else : endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div><!-- /footer -->

	<!-- JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/css_browser_selector.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/responsiveslide.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/scripts.js"></script>
	
	<script type="text/javascript">
$(function() {

    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
	item.label = item.label.replace(', United States', '');
	item.value = item.value.replace(', United States', '');
    item.label = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
    return $("<li></li>")
            .data("item.autocomplete", item)
            .append("<a>" + item.label + "</a>")
            .appendTo(ul);
    };

	$("#search_market").autocomplete({
		source: function (request, response) {
		 $.getJSON(
			"http://gd.geobytes.com/AutoCompleteCity?callback=?&filter=US&q="+request.term,
			function (data) {
			 response(data);
			}
		 );
		},
		minLength: 3,

		open: function () {
		 $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
		},
		close: function () {
		 $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
		}
	});
	$("#search_market").autocomplete("option", "delay", 100);
});
</script>

    <?php if($onHomePage) { ?>
		<script>
			$(document).ready(function() {
				$('#location_choose').change(function() {
					v = $('#location_choose :selected').attr('value');
					if(v.length) {
						window.location.href = '/market/'+v;
					}
				});
				
			});
		</script>
	<?php } ?>

	<?php if($onMarketPage) {
		// if we're on the market page, load the Google Maps API
		 ?>
		 <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSzfw12ZB4Zfh85uiLEUob7IGsBn5dHWQ">
    </script>

		
		<script>
			// build an array of locations
			var markers = [
					<?php
					// walk through each property and write JS to mark the location
					$end = count($properties);

					$count = 1;
					$pars = array("(", ")");
					foreach($properties as $k => $p) {
					    $idx == 1;
						$p = (array)$p;
						if($p['latlng']) {
						    $newlatlng = str_replace($pars, "", $p['latlng']);
						    list($part1, $part2) = explode(',', $newlatlng);
							echo "\n";
							echo "{"."\n";
							  echo "    'lat': '".$part1."', "."\n";
							  echo "    'lng': '".$part2."', "."\n";
							  if($p['featured'] == 'on') {
								echo "    'icon': '".get_option('siteurl')."/wp-content/themes/ihcorp/img/featured_property_icon.png',"."\n";
							  } else {
							 	echo "    'icon': '".get_option('siteurl')."/wp-content/themes/ihcorp/img/property_icon.png',"."\n";
							  }
							echo "    'info': document.getElementById(\"map-item".$count++."\")"."\n";
                            echo "}, ";
						}
						++$idx;
					}
					?>
					
			];

			// walk through list of locations we just made and ask google to draw markers
			window.onload = function () {
			
			  var resizeTimer;
			  $(window).resize(function() {
			    clearTimeout(resizeTimer);
			    resizeTimer = setTimeout(doMapResize, 100);
			  });

			  function doMapResize() {
				// resize google map if width of window changed
				w = $(window).width();
				if(w > 820) {
					if(w < 1040) {
						new_width = w-357;
					} else {
						new_width = 705;
					}
				} else { new_width = 'auto'; }
				$('#googlemap').css('width', new_width);
				google.maps.event.trigger(map, "resize");
			  };
				
			  // create the map itself
			  var mapOptions = {
					zoom: <?php echo $p['map-zoom-level'] ? $p['map-zoom-level'] : 10; ?>, 
					center: new google.maps.LatLng<?php echo $marklatlng ? $marklatlng : '(20.8165975, -156.92731930000002)' ?>, 
					mapTypeId: google.maps.MapTypeId.ROADMAP 
			  };
				  
			  var map = new google.maps.Map(document.getElementById("googlemap"), mapOptions);
			  var infowindow = new google.maps.InfoWindow();
              var i = 0;
				  
			  var interval = setInterval(function () {
			    var data = markers[i];
				var myLatlng = new google.maps.LatLng(data.lat, data.lng);
				var ico = data.icon;
                var marker = new google.maps.Marker({
                      position: myLatlng,
                      map: map,
					  icon: ico,
                      animation: google.maps.Animation.DROP
                });
				
				(function (marker, data) {
				  google.maps.event.addListener(marker, 'click', (function(marker, i) {
				    return function() {
					  infowindow.setContent(data.info);
					  infowindow.open(map, marker);
					}
				  })(marker, i));
                })(marker, data);
                i++;
                if (i == markers.length) {
                  clearInterval(interval);
                }
              }, 20);
			  
			  doMapResize();

			}

		</script>
	<?php }
	 
	/*
	$("#search_market").autocomplete({
      source: function (request, response) {
        $.ajax({
            url: "http://ws.geonames.org/searchJSON?country=US",
            dataType: "jsonp",
            data: {
                featureClass: "P",
                style: "full",
                maxRows: 12,
                name_startsWith: request.term
            },
            success: function (data) {
                response($.map(data.geonames, function (item) {
                    return {
						label: item.name + ", " + item.adminCode1,
				        value: item.name + ", " + item.adminCode1
                    }
                }));
            }
        });
      },
      minLength: 2
    });
	*/
	
	if($onMarketPage OR $onContactPage) {	
	?>

	<script>
			$(document).ready(function() {

			// ajax for submitting contact form
		    var $form = $('#myform');

			$form.validate({
				debug: true,
				rules: {
					//'f[phone]': { required: true, phoneUS: true },
					'f[firstname]': { required: true },
					'f[email]': { required: true, email: true }
				},
				submitHandler: function(f) {
		            $.ajax({
		                type: 'POST',
		                url: '<?php bloginfo('template_directory'); ?>/<?php if($onMarketPage) { echo 'market-form'; } else { echo 'maintenance-form'; } ?>.ajax.php',
		                data: $form.serialize(),
		                success: function (response) {
							//if process.php returned 1/true (send mail success)
							if (response == 'Success') {
								_gaq.push(['_trackPageview','/market-lead-form.php']);
								$form.hide();
								$('#form_success').fadeIn('slow');
							} else {
								// failure
								$('#form_fail').html(response);
								$('#form_fail').fadeIn('slow').delay(6000).fadeOut('slow');
							}
						}
					});
			    }
			});

		});
	</script>
	<?php } ?>


<script>

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

 

  ga('create', 'UA-49415632-1', '54.85.168.180');

  ga('send', 'pageview');

 

</script>

</body>
</html>
<?php
/*
Template Name: Contact page
*/
?>

<?php
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
	
		<div class='one_third last formholder contactpage'>
			
			<div class='formbox'>
				<div id='form_success' class='form_result'>
					Thank you!
					<br><br>
					We'll be in touch with you soon.
				</div>
				<div id='form_fail' class='form_result'></div>
				<form id='myform' name='myform' method='post' action='#'>
					<p class='formleader'>Customer Service</p>
					<ul>
					  <li>Can't find an answer to your question?</li>
					  <li>Have a concern or complaint?</li>
					</ul>
					<!--
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
						<input type='text' placeholder='Required' name='f[phone]' id='phone' required>
					</div>
					<label for='request'>
						request
					</label>
					<div class='field'>
						<textarea maxlength='255' name='f[request]' id='request'></textarea>
					</div>

					<input type='hidden' name='f[market]' value='<?php echo $market ?>'>
					<input type='submit' value='send'>
						-->
					<a href="http://www.formstack.com/forms/?1544103-sYq44cAl5k" class="maintlink">Submit Here</a>	

				</form>
			</div>
			
			<?php if ( dynamic_sidebar('contentpage-1') ) : else : endif; ?>

		</div>
	</div>

<?php $onContactPage = 1; require_once('footer.php'); ?>

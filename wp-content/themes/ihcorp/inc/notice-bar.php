<style>

div#notice {
	width: 100%;
	position: fixed; 
	top: 0px;  
	z-index: 9999999;
	margin-bottom: 10px;
	background: #fce620; /* Old browsers */
	background: -moz-linear-gradient(top, #fce620 0%, #e5ca34 100%); /* FF3.6+ */
	background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fce620), color-stop(100%,#e5ca34)); /* Chrome,Safari4+ */
	background: -webkit-linear-gradient(top, #fce620 0%,#e5ca34 100%); /* Chrome10+,Safari5.1+ */
	background: -o-linear-gradient(top, #fce620 0%,#e5ca34 100%); /* Opera 11.10+ */
	background: -ms-linear-gradient(top, #fce620 0%,#e5ca34 100%); /* IE10+ */
	background: linear-gradient(to bottom, #fce620 0%,#e5ca34 100%); /* W3C */
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fce620', endColorstr='#e5ca34',GradientType=0 ); /* IE6-9 */
	}

div#notice-inner {max-width:1020px; margin: 0 auto; padding:5px 0;}
	
span#notice-icon img {float: left; height: 40px; margin-right: 20px;}	

html {margin-top: 30px !important;}
	
</style>


<!-- add this before the body tag -->

<?php //include (TEMPLATEPATH . '/inc/notice-bar.php'); ?>

<div id="notice">
	<div id="notice-inner"><span id="notice-icon"><img src="/wp-content/themes/ihcorp/img/Warning.png"></span>
		The Invitation Homes website will undergo scheduled maintenance on Thursday, July 24, 2014 starting at 11pm ET for up to 2 hours. During this time, current and future IH Residents may have difficulty accessing their accounts or experience unexpected errors. Thank you for your patience and please check back later.
	</div>
</div>	

<!-- End Notice Box -->
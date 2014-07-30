<?php

$errors = array();

if($_REQUEST['f']) {

	// check out user submission
	$form = $_REQUEST['f'];
	
	function trim_value($v) { return trim($v); }
	array_walk($form, 'trim_value');

	// check that required fields are there
	if(!$form['firstname']) $errors[] = 'Please enter your first name.';
	if(!$form['email']) $errors[] = 'Please enter your email address.';

	// kill spaces, hyphens, etc from phone number
	$form['phone'] = preg_replace('/[^0-9]/', '', trim($form['phone']));

	if(!$form['phone'] OR strlen($form['phone']) != 7 && strlen($form['phone']) != 10) {
		$errors[] = 'Please enter your phone number, with area code.';
	}

	if ($form['email'] && !preg_match("/[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/", $form['email'])) {  
		$errors[] = 'Please check your email address-- it looks wrong.';
	}

	if ($errors) {
		$result = '';
		foreach($errors AS $errstr) {
			$result .= '<p>'.$errstr."</p>";
		}
		echo $result;			// echo it back to AJAX caller, to show the user the error message

	} else {
		// everything is looking good, store the info in the db

		// set up a few more special fields
		$form['ipaddress'] = $_SERVER["REMOTE_ADDR"];
		$form['create_date'] = 'now()';
		$form['modify_date'] = 'now()';

		// build and execute SQL query
		$validfields = array(
			'firstname' => 255,
			'lastname' => 255,
			'email' => 64,
			'phone' => 32,
			'request' => 255,
			'ipaddress' => 16,
			'create_date' => 16,
			'modify_date' => 16
		);
		
		foreach($validfields as $f => $len) {
			if($form[$f]) $form[$f] = substr($form[$f], 0, $len);

			// start to build html email now
			$msgadd .= ($form[$f] == 'now()') ? '' : '
				<tr>
				      <td width=165 align=right valign=top bgcolor=#FFFFFF><strong>'.$f.':</strong></td>
				      <td width=565 align=left valign=top bgcolor=#FFFFFF>' . $form[$f] . '</td>
				</tr>
			';
		}

		// assemble the html message
		$subject = 'Maintenance Request from '.$form['firstname'].' '.$form['lastname'];	
		$message = stripslashes('
			<strong><font style=color:#CC3300>Maintenance Request</font></strong><br>
			<table width=708 border=0 cellpadding=2 cellspacing=1 bgcolor=#CCCCCC>
				'.$msgadd.'
			</table>
		');

		// send email
		$to = 'jeremiah.lewis@gmail.com';
		$from = 'contactform@invitationhomes.com';

		$headers  = "From: $from \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		// send the mail
		$result = @mail($to, $subject, $message, $headers);
		// echo "DEBUG\n\nTo: $to\nSubject: $subject\n$headers\n".trim($message);

		echo "Success";
	}
}

?>
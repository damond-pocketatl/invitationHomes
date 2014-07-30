<?php

	// on IH production server
	$hostname = 'localhost';		// specify host, i.e. 'localhost'
	$user = 'root';		// specify username
	$pass = 'sdkjU87*kss';			// specify password
	$dbase = 'invhomes_wpih_test';	// specify database name

$connection = mysql_connect($hostname, $user, $pass) or die ("Can't connect to MySQL");
$db = mysql_select_db($dbase, $connection) or die ("Can't select database.");
mysql_select_db($dbase) or die(mysql_error());

$errors = array();

if($_REQUEST['f']) {

	// check out user submission
	$form = $_REQUEST['f'];
	
	function trim_value($v) { return trim($v); }
	array_walk($form, 'trim_value');

	// check that required fields are there
	if(!$form['firstname']) $errors[] = 'Please enter your first name.';
	if(!$form['email']) $errors[] = 'Please enter your email address.';

	if(!$form['market']) die();		// if market field isn't there, just bail, it's something weird

	// kill spaces, hyphens, etc from phone number
	$form['phone'] = preg_replace('/[^0-9]/', '', trim($form['phone']));

	if(!$form['phone'] OR strlen($form['phone']) != 7 && strlen($form['phone']) != 10) {
		$errors[] = 'Please enter your phone number, with area code.';
	}

	if ($form['email'] && !preg_match("/[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/", $form['email'])) {  
		$errors[] = 'Please check your email address-- it looks wrong.';
	}

	// be sure there's not a duplicate in the db already
	if(!$errors) {
		$s = 'SELECT COUNT(*) FROM leads WHERE email = "'.mysql_real_escape_string(trim($form['email'])).'"';
		if(trim($form['phone'])) $s .= ' OR phone = "'.mysql_real_escape_string(trim($form['phone'])).'"';
		$res = mysql_query($s);
		$row = mysql_fetch_row($res);
		if($row[0]) {
			$errors[] = "It looks like you've already sent an inquiry. We'll be in touch soon!";
		}
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
		$sql = 'INSERT INTO leads SET ';
		$sqladd = $msgadd = '';
		$validfields = array(
			'firstname' => 255,
			'lastname' => 255,
			'email' => 64,
			'phone' => 32,
			'comments' => 255,
			'market' => 32,
			'ipaddress' => 16,
			'create_date' => 16,
			'modify_date' => 16
		);
		
		foreach($validfields as $f => $len) {
			if($form[$f]) $form[$f] = substr($form[$f], 0, $len);
			if($sqladd) $sqladd .= ', ';
			$sqladd .= $f.' = ';
			$sqladd .= ($form[$f] != 'now()') ? "'".mysql_real_escape_string($form[$f])."'" : $form[$f];

			// start to build html email now
			$msgadd .= ($form[$f] == 'now()') ? '' : '
				<tr>
				      <td width=165 align=right valign=top bgcolor=#FFFFFF><strong>'.$f.':</strong> </td>
				      <td width=565 align=left valign=top bgcolor=#FFFFFF>' . $form[$f] . '</td>
				</tr>
			';
			
		}
		
		$finalsql = $sql.$sqladd;
		$res = mysql_query($finalsql);

        $dbresult = ($res) ? 'Successfully wrote record to database.' : '<b>Error writing to database: '.mysql_error().'<br>SQL statement:</b> '.$finalsql;
	
		// assemble the html message
		$subject = 'Market Lead Submission';	
		$message = stripslashes('
			<strong><font style=color:#CC3300>Market Lead Submission from '.$form['firstname'].' '.$form['lastname'].'</font></strong><br>
			<p>'.$dbresult.'</p>
			<table width=708 border=0 cellpadding=2 cellspacing=1 bgcolor=#CCCCCC>
				'.$msgadd.'
			</table>
		');

		// send email

		// TO MAKE A MARKET-BASED 'to' ADDRESS:
		$to = $form['marketemail'].'@IHRent.com';	// i.e. 'PhoenixLeasing@invitationhomes.com'
		//$to = 'jself@ihrent.com';
		//$from = $to;
		$from = 'webmaster@invitationhomes.com';

		$headers  = "From: $from \r\n";
		$headers .= "Bcc: jeremiah.lewis@gmail.com \r\n";
		
		$headers .= "Reply-To: $from \r\n";
		//$headers .= "Reply-To: jeremiah.lewis@gmail.com \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

		// send the mail
		$result = @mail($to, $subject, $message, $headers);
		// echo "DEBUG\n\nTo: $to\nSubject: $subject\n$headers\n".trim($message);

		/*
		// commented out - the user doesn't need to know anything about sending email
		
		if(!$result) {
			echo "There was a problem sending email, but your entry was saved in our database. You will be contacted soon.";   
	    } else {
			echo "Success";
	    }
		*/

		echo "Success";

	}
}

?>
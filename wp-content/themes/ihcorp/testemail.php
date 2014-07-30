<?php

// test to see if email works on the server

error_reporting(E_ALL);

$to = 'em@themepark.com';

echo "<h1>Sending test email to $to</h1>";

mail($to, 'Test email', 'Here is a test email message.', 'From: eric@ericmueller.org');

?>

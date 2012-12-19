<?php
require_once('dbLink.php');
require_once('UserLogon.php');

$dbl = new dbLink();
$ul = new userLogon($dbl->getLink());

if($ul->isLoggedIn())
	echo "Welcome, " . $ul->getUserName() . "!";
else
	echo "Welcome, guest!";
?>
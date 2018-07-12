<?php
	//Connect to database. The username and password are X'd out
	//because this is a good habit for security.
	$h = 'pearl.ils.unc.edu';
	$u = 'XXXXXXXX';
	$p = 'XXXXXXXXX';
	$dbname = 'XXXXXXXX_db';
	$db = new mysqli($h, $u, $p, $dbname);
	if (mysqli_connect_errno())
	{
		echo "Error! Connection failed! D: D:/n";
		exit();
	}
?>
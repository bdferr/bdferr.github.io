<?php
	//connect to database:
	$h = 'pearl.ils.unc.edu';
	$u = 'ferrerih';
	$p = 'mht3vMAww';
	$dbname = 'ferrerih_db';
	$db = new mysqli($h, $u, $p, $dbname);
	if (mysqli_connect_errno())
	{
		echo "Error! Connection failed! D: D:/n";
		exit();
	}
?>
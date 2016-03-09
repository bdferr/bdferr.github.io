<?php
	//connect to database:
	$h = 'pearl.ils.unc.edu';
	$u = 'webdb16';
	$p = 'ph25cMun5';
	$dbname = 'webdb16';
	$db = new mysqli($h, $u, $p, $dbname);
	if (mysqli_connect_errno())
	{
		echo "Error! Connection failed! D: D:/n";
		exit();
	}
?>
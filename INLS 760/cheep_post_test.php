<?php 
session_start();

$stmt = "";
$user_id = "1234567890";
$cheep_text = "test cheep text from test file :D";
	$h = 'pearl.ils.unc.edu';
	$u = 'webdb16';
	$p = 'ph25cMun5';
	$dbname = 'webdb16';
	$db = mysqli_connect($h, $u, $p, $dbname);
	if (!$db)
	{
		echo "Error! Connection failed! D: D:/n";
		exit();
	}
$insert = "insert into cheeps(cheep_text, user_id, created_date) values(?, ?, Now())";
	if($stmt = mysqli_prepare($db, $insert))
	{
		mysqli_stmt_bind_param($stmt, "si", $cheep_text, $user_id);
		echo "Preparation successful!!";
		//mysqli_stmt_bind_result($stmt);
		mysqli_stmt_execute($stmt);
	}
	else
	{
		echo "Statement preparation failed! Click <a href=\"home.php\">here</a> to return to the main page.<br />";
	}
	?>
	
	
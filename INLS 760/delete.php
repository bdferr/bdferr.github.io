<?php
session_start();
echo <<<_END
	<html>
	<head>
	<title>
	Delete
	</title>
	<link rel="stylesheet" type="text/css" href="ferrerih_p3_stylesheet.css">
	</head>
	<body>
	<h1>Top secret delete page</h1>
	<hr>
_END;

require_once "ferrerih_p3_access.php";
if ($_SESSION['usertype'] != "admin")
{
	echo "<section class=\"login\">Stop! You have attempted to delete a cheep 
	without having administrator privileges. This aggression will not stand, man.<section class=\"login\">";
}
else
{
	$cheep_id = $_GET['delete'];
	$deletequery = "delete from cheeps where cheep_id = $cheep_id";
	if ($result = mysqli_query($db, $deletequery))
	{
		echo "<section class=\"login\">Success! You have deleted cheep " . $cheep_id . ".<section class=\"login\">";
	}
}
	echo "</body></html>";
?>
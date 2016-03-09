<?php
session_start();
//title and stylesheet reference:
echo <<<_END
	<html>
	<head>
	<title>
	Admin
	</title>
	<link rel="stylesheet" type="text/css" href="ferrerih_p3_stylesheet.css">
	</head>
	<body>
	<h1>Top secret admin page</h1>
	<hr>
_END;

//connect to database:
require_once "ferrerih_p3_access.php";
//$_SESSION['usertype'] = "admin";
if (isset($_SESSION['usertype']))
	{
	if ($_SESSION['usertype'] != "admin")
	{
		echo "<section class=\"login\">Stop! You are mischievously attempting to access a page which is for administrators only!</section>";
	}
	else
	{
		$query = "select firstname, lastname, username, cheep_id, cheeps.created_date as created_date, cheep_text 
		from users, cheeps order by cheeps.created_date";
	}
	if($result = mysqli_query($db, $query))
	{
		//print_r(mysqli_fetch_assoc($result));
		echo "<section class = \"login\">";
		while($row = mysqli_fetch_assoc($result))
		{
			$cheep_id = $row['cheep_id'];
			$cfirstname = $row['firstname']; 
			$clastname = $row['lastname'];
			$cusername = $row['username'];
			$created_date = $row['created_date'];
			$cheep_text = $row['cheep_text'];
			echo $cfirstname . " $clastname " . "@" . $cusername . "-" . $created_date . "<br />";
			echo "$cheep_text <br />";
			echo "<a href=\"delete.php?delete=$cheep_id\">Delete forever</a><br />";
		}
	}
	else
	{
		echo "Error! Query for cheeps failed!";
	}
	echo "<a href=\"home.php\">Click here to return to the land of the living.</a><br />";
	echo "</section>";
}
else
{
	echo "<section class=\"login\">Somehow a user type has not been set, so you will not be able to do anything.</section><br />";
}
	echo "</body></html>";

?>
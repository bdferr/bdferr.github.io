<?php
session_start();
$cheep_text = "";
$insert = "";
$stmt = "";
$num_rows = "";

echo <<<_END
<html>
<head>
<title>
Post cheep
</title>
<link rel="stylesheet" type="text/css" href="ferrerih_p3_stylesheet.css">
</head>
<body>
<h1>Post cheep</h1>
<hr>
_END;

//connect to the database:
require_once "ferrerih_p3_access.php";

//this is my all-purpose class to make all of my text more readable, despite its name:
echo "<section class=\"login\">";
if (isset($_SESSION['loggedin']))
{
	//prepare a statement to save the cheep to the cheeps table
	if (!get_magic_quotes_gpc())
	{
		$cheep_text = htmlentities(stripslashes(strip_tags($_POST['cheep'])));
	}
	else
	{
		$cheep_text = htmlentities(strip_tags($_POST['cheep']));
	}
	//this line confirms that the text was received, not that it was added to the database:
	echo "You have submitted the following text: $cheep_text <br />";
	$insert = "insert into cheeps(user_id, cheep_text, created_date) values(?, ?, ?)";
	$user_id = $_SESSION['loggedin'];
	//I just cannot understand why nothing past this point functions; I can't even get an error message.
	//I am attempting to use object-oriented programming because I got the same total lack of results when following the non-object-oriented examples
	//in the slides and all of the suggestions I found when searching online were OO.
	if ($stmt = $db->prepare($insert))
	{
		$stmt->bind_param('iss', $user_id, $cheep_text, now());
		$stmt->execute();
		$num_rows = $stmt->affected_rows;
		printf("Rows inserted: %d", $num_rows);
		echo "<br />";
		$stmt->close();
		$db->close();
		echo <<<_END
		Great success! You have inserted the following cheep: $cheep_text. Click <a href="home.php">here</a> to return to the main page.
		</section>
		</body>
		</html>
_END;
	}
	else 
	{
		echo "Statement preparation failed! Click <a href=\"home.php\">here</a> to return to the main page.<br /></body>
		</html>";
	}
}
else
{
	echo <<<_END
	You are not logged in. Click <a href="login.php">here</a> to log in.
	</section>
	<br />
	</body>
	</html>
_END;
}
?>
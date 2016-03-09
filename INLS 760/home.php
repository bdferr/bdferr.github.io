<?php
session_start();
$following = "";
$following_query = "";
$following_result = "";
$followedby = "";
$followedby_query = "";
$followedby_result = "";
$username = "";
$firstname = "";
$user_id = "";
$matching = "";

	echo <<<_END
		<html>
		<head>
		<title>
		Cheeps Home
		</title>
		<script type="text/javascript">
		function mycounter()
		{
			document.getElementById("testingdiv").innerHTML = 141 - document.getElementById("cheep").value.length;
		}
		</script>
		<link rel="stylesheet" type="text/css" href="ferrerih_p3_stylesheet.css">
		</head>
		<body>
		<h1>Cheeps</h1>
		<hr>
		<section>
_END;
if (!isset($_SESSION['loggedin']))
{
	echo <<<_END

	You are not logged in. Click <a href="login.php">here</a> to log in.
	<br />
	</section>
	</body>
	</html>
_END;
}

else
{
	//print_r($_SESSION);
	//get variables from $_SESSION array, which earlier got them from the $_POST array from login.php:
	
	$user_id = $_SESSION['loggedin'];
	if (isset($_SESSION['username']))
	{
		$username = $_SESSION['username'];
	}
	if (isset($_SESSION['firstname']))
	{	
		$firstname = $_SESSION['firstname'];
	}

	//connect to database:
	require_once "ferrerih_p3_access.php";

	echo "<div class=\"left\">";
	//check how many users I am following:
	$following_query = "select count(follows_id) as count from follows where user_id = \"$user_id\"";
	$following_result = mysqli_query($db, $following_query);
	if (!$following_result)
	{
		echo "<span>Error! Following count failed!</span> <br />";
	}
	else
	{
		$following = mysqli_fetch_assoc($following_result);
		$following = $following['count'];
	}
	
	//check how many users I am followed by:
	$followedby_query = "select count(follows.user_id) as count from follows, users where follows.follows_id = users.user_id and users.user_id = \"$user_id\"";
	$followedby_result = mysqli_query($db, $followedby_query);
	if (!$followedby_result)
	{
		echo "<span>Error! Followed by count failed!</span> <br />";
	}

	else
	{
		$followedby = mysqli_fetch_assoc($followedby_result);
		$followedby= $followedby['count'];
	}
	echo "</div>";
	
	echo <<<_END
	<div class="right">
	Hello, $firstname. (<a href="login.php?endsession=yes">logout</a>)
	(<a href="admin.php">admin</a>)<br />
	Following $following / Followed by $followedby
	</div>
	<div class="clear">
	Characters remaining: <span id ="testingdiv">141</span><br />
	<form name="testing" method="post" action="post_cheep.php">
	<textarea name="cheep" id="cheep" rows="3" cols="50" maxLength="141" onkeyup="mycounter()">
	</textarea><br />
	<input type="submit" value="Post">
	</form>
	</div>
	<div class="left">
	<h2>Search cheeps:</h2>
	Matching text: <br />
	<form method="post" action="home.php">
	<input type="text" name="matching" size="40"> <br />
	<input type="radio" name="users" value="allusers" checked> All users <br />
	<input type="radio" name="users" value="followedusers"> Only users I follow <br />
	<input type="submit" value="search">
	</form>
	<form method="post" action="home.php">
	<input type="hidden" name="latest" value="showlatest">
	<input type="submit" value="clear search terms">
	</form>
	</div>
	<div class="right">
	<h2>Recent cheeps:</h2><br />
	</div>
	<div class="cheeps">
_END;
	//here I search for matching text:
	if (isset($_POST['users']) and isset($_POST['matching']))
	{
		$matching = htmlentities(strip_tags($_POST['matching']));
		//here I check to see which type of user the user asked for the cheeps of,
		//and produce a query accordingly:
		if($_POST['users'] == "followedusers")
		{
			$query = "select firstname, lastname, username, cheeps.created_date as created_date, cheep_text 
			from users, cheeps, follows where match(cheep_text) against (\"$matching\") 
			and users.user_id = cheeps.user_id and follows.user_id = \"$user_id\" and follows_id = users.user_id order by cheeps.created_date limit 3";
		}
		if ($_POST['users'] == "allusers")
		{
			$query = "SELECT firstname, lastname, username, cheep_text, cheeps.created_date
			FROM users, cheeps WHERE MATCH(cheep_text) AGAINST (\"$matching\") and users.user_id = cheeps.user_id
			order by cheeps.created_date limit 3"; 
		}
	}
	//This displays the latest three cheeps when there is no matching text query:
	else
	{
		$query = "select firstname, lastname, username, cheeps.created_date as created_date, cheep_text 
		from users, cheeps where users.user_id = cheeps.user_id order by cheeps.created_date desc limit 3";
	}
	//for the moment I am using the same window to display recent cheeps as to display
	//any cheeps which might show up from the matching text search:
	if($result = mysqli_query($db, $query))
	{
		while($row = mysqli_fetch_assoc($result))
		{
			$cfirstname = $row['firstname']; 
			$clastname = $row['lastname'];
			$cusername = $row['username'];
			$created_date = $row['created_date'];
			$cheep_text = $row['cheep_text'];
			echo $cfirstname . " $clastname " . "@" . $cusername . "-" . $created_date . "<br />";
			echo "$cheep_text <br />";
		}
	}
	else
	{
		echo "Error! Query for latest three cheeps failed!";
	}
	echo "</div>
	</section>
	</body>
	</html>";

	
}
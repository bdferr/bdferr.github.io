<?php
session_start();
$username = "";
$query = "";
$password = "";
$firstname = "";
//if the user has just logged out from home.php:
if (isset($_GET['endsession']))
{
	if ($_GET['endsession'] == "yes")
	{
	unset($_SESSION['loggedin']);
	$_POST = array();
	$_SESSION = array();
	//session_destroy();
	}
	//here I am trying to make sure that it does not attempt to log out repeatedly
	//if I reload while it is already logged out:
	unset($_GET['endsession']);
}

echo <<<_END
	<html>
	<head>
	<title>
	Login
	</title>
	<link rel="stylesheet" type="text/css" href="ferrerih_p3_stylesheet.css">
	</head>
	<body>
	<h1>Login</h1>
	<section class="login">
_END;

//connect to database:
require_once "ferrerih_p3_access.php";

//use sha1(), a hashing algorithm, when checking username and password?

//if a username and password have already been submitted:
//check to see that both passwords submitted match:
if (isset($_POST['username']) and isset($_POST['password']) and $_POST['password'] == $_POST['password2'])
{
	//take the username & password values from the $_POST array and encrypt the submitted password:
	if (get_magic_quotes_gpc())
	{
		$username = htmlentities($_POST['username']);
		$password = sha1(htmlentities($_POST['password']));
	}
	else
	{
		$username = htmlentities(strip_tags($_POST['username']));
		$password = sha1(htmlentities(strip_tags($_POST['password'])));
	}
	
	//this is where I search the database, confirming that the username/password pair is correct:
	$query = "select user_id, username, firstname, password, usertype from users where username = \"$username\"
	and password = \"$password\"";
	if($result = mysqli_query($db, $query))
	{
		//this assumes the result will only have one line, which makes sense since there should be
		//only a single username-password pair which matches:
		$array = mysqli_fetch_assoc($result);
		$username = $_SESSION['username'] = $array['username'];
		$firstname = $_SESSION['firstname'] = $array['firstname'];
		$_SESSION['loggedin'] = $array['user_id'];
		$_SESSION['usertype'] = $array['usertype'];
	}
	else
	{
		echo "Danger! Your username and/or password is incorrect! <br />
		I will not tell you which one(s) are wrong for fear of enabling your nefarious hacking attempts.";
	}
}
elseif (isset($_POST['password']) and isset($_POST['password2']) and $_POST['password'] != $_POST['password2'])
{
	echo "Try again! Your passwords do not match.";
}

if (isset($_SESSION['loggedin']))
{
	echo "Welcome, " . $firstname . "! ";
	echo "<a href=\"home.php\">Click here to get to the homepage!</a>";
}
else
{
echo <<<_END
	<form method="post" action="login.php">
	Username: <input type="text" size="20" name="username" required><br />
	Password: <input type="password" size="20" name="password" required><br />
	Confirm password: <input type="password" size="20" name="password2" required>
	<br />
	<input type="submit" value="Log In">
	</form>
_END;
}
echo "</section>
	</body>
	</html>";
?>
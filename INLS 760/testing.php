
		<html>
		<head>
		<title>
		Cheeps Home
		</title>
		<script>
		function mycounter()
		{
			document.getElementById('testingdiv').innerHTML = document.getElementById('cheep').value;
			document.getElementById('counter').value = document.getElementById('cheep').value.length;
		}
		</script>
		</head>
		<body>
		<div id ="testingdiv">What now?</div><br />
		<form name="testing" method="post" action="testing.php">
		<input type="text" id="counter" size="3" value="test"><br />
		<textarea onblur="mycounter()" name="cheep" id="cheep" rows="3" cols="50" maxLength="141">
		</textarea>
		<input type="submit" value="Submit">
		</form>
		</body>
		</html>

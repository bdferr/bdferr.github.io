<?php
require_once "ferrerih_p3_access_altered.php";

	if (isset($_POST['fieldtext']))
	{
		//take the search text from the post array,
		//then use it to form a query and run the query:
		$fieldtext = htmlentities(strip_tags($_POST['fieldtext']));
		$suggestionquery = "select * from keywords where phrase like \"%$fieldtext%\" limit 10";
		if ($suggestionresult = mysqli_query($db, $suggestionquery))
		{
			while($suggestion = mysqli_fetch_assoc($suggestionresult))
			{
				print_r($suggestion[phrase]);
				echo "<br />";
			}
		}
		else
		{
			echo "Error! Query failed." . mysqli_errno();
		}
	}
	else
	{
		echo "No text detected!";
	}
?>
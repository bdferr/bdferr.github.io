<?php
/*This script is called by typing anything into the search box 
on the ferrerih_p4-results.php page, not counting delete or backspace.
It provides suggestions, which will be displayed beneath the search box, 
from a set of keywords.
Last updated 7/11/2018.*/
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
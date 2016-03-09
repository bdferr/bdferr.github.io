<?php//Extra credit section:
//If I run this as a separate file, it simply does not accept anything at any point;
//I get an "unexpected something" error no matter what I comment out.

$authorquery = 'select authors from p2records';

//Now I query for all the authors in the whole table:

$authorresult = mysqli_query($db, $authorquery);
if ($authorresult)
	{while($row = mysqli_fetch_assoc($authorresult)) //For every row of the set of authors retrieved:
		{
		$row = implode($row); //This turns the row, which is an array at first, into a string.
		//echo $row;
		$author = explode(" ", $row); 
		//The above turns the author field into an array, but this time with each word associated with a different key.
		//print_r($author);
		$and_location = array_keys($author, "and");
		//print_r($and_location);
		//I have to make allowances for the unfortunate "Jr}" and "al" which end two of the last names
		//but are of course not legitimate last names.
		//This design is assuming there can be only one occurrence of either of these in an author field,
		//which is true for the current data.
		if ($al_location = array_search("al", $author))
			{//echo $al_location;
			unset($author[$al_location]);
			}
		if ($jr_location = array_search("Jr}", $author))
			{//echo $jr_location;
			unset($author[$jr_location]);
			}
			//and while I am at it I could simply remove all non-alphabetical characters from the names except for hyphens:
			//$lastname = preg_replace("/[^a-zA-Z\-]/", "", $lastname);
		if ($and_location) //If "and" is contained in the newly created $author array,
			{
			$and_location = $and_location[0]; //find the first of the "ands"
			$lastname = $author[$and_location - 1];
			//I get the index position for the first "and," and select the value which is immediately before the "and":
			}
		else
			//If there is no "and," I need to get the last element of this $author array.
			{
			$lastname = array_pop($author);
			}
			//echo $lastname . "</br>";
			//now I need to add it to an array:
			$lastnamelist[] = $lastname;
			
			$update_query = "UPDATE p2records SET lastname = \"$lastname\" WHERE authors LIKE \"$row%\"";
			//I added the "LIKE ... %" to deal with the two names which have been truncated at the ends.
			if ($update_result = mysqli_query($db, $update_query))
				{echo $update_result . "<br />";}
			else
				{echo "Error! Update query failed! D: D: <br />";
				}
		}
	}
else //If you get no result from the query:
	{
	echo "Error! Author query failed! D: D:";
	exit();
	}
//In either case, this will be the first author's last name.
?>
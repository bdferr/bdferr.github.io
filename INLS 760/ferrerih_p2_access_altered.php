<?php
$h = 'pearl.ils.unc.edu';
$u = 'ferrerih';
$p = 'mht3vMAww';
$dbname = 'ferrerih_db';
$db = mysqli_connect($h, $u, $p, $dbname);
if (!$db)
{
	echo "Error! Connection failed! D: D:/n";
	exit();
}
	//If I run the following as a separate file, it simply does not accept any variable at any point;
//I get an "unexpected T_VARIABLE error no matter what I comment out.
$authorquery = 'select authors from p2records';

//Now I query for all the authors in the whole table:
$authorresult = mysqli_query($db, $authorquery);
if ($authorresult)
	//For every row of the set of authors retrieved:
{	while($row = mysqli_fetch_assoc($authorresult)) 
	{
		//This turns the row, which is an array at first, into a string:
		$row = implode($row); 
		$author = explode(" ", $row); 
		//The above turns the author field into an array, but this time with each word associated with a different key.
		$and_location = array_keys($author, "and");
		//I have to make allowances for the unfortunate "Jr}" and "al" which end two of the last names
		//but are of course not legitimate last names.
		//This design is assuming there can be only one occurrence of either of these in an author field,
		//which is true for the current data.
		if ($al_location = array_search("al", $author))
		{	
			unset($author[$al_location]);
		}
		if ($jr_location = array_search("Jr}", $author))
		{
			unset($author[$jr_location]);
		}
		if ($and_location) //If "and" is contained in the newly created $author array,
		{
			$and_location = $and_location[0]; //find the first of the "ands"
			$lastname = $author[$and_location - 1];
			//I get the index position for the first "and," and select the value which is immediately before the "and":
		}
			//If there is no "and," I need to get the last element of this $author array.
			//In either case, this will be the first author's last name.
		else
		{
			$lastname = array_pop($author);
		}
			//now I need to add it to an array:
			$lastnamelist[] = $lastname;
			
			$update_query = "UPDATE p2records SET lastname = \"$lastname\" WHERE authors LIKE \"$row%\"";
			//I added the "LIKE ... %" to deal with the two names which have been truncated at the ends.
			if (!$update_result = mysqli_query($db, $update_query))
			{
				echo "Error! Update query failed! D: D: <br />";
			}
	}
	}
//If you get no result from the query:
else 
	{
	echo "Error! Author query failed! D: D:";
	exit();
	}
	
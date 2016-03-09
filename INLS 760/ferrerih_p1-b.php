<?php

$file_r_ray = file("mydata.txt");
//The following initializes the array to be used as an unsorted list of the appropriate names and dates:
$sortable = array();

//This gets rid of an error message for the lack of a timezone re: the mktime() function:
date_default_timezone_set('UTC');

//This is the earliest date at which an employee should have started working
//in order for them to have their information displayed:
$min_time = date_create_from_format("d-M-Y", "1-Jan-1998");
//echo date_format($min_time, "j F, Y") . "\n";

foreach ($file_r_ray as $line)
{
	//Here I am exploding each line into an array, using the comma as a delimiter:
	$fields = explode(",", $line);
	//$printable_test3 = $fields[5];
	//echo $printable_test3;

	/*I know I need to convert the dates in the files into timestamps before I can format them to display,
	or even effectively compare them with the date 1-Jan-98. I adapted the following from a stackoverflow.com
	thread to convert strings to timestamps:*/

	//list($day, $month, $year) = explode("-", "$fields[2]");
	//do I need a new function, perhaps strtotime()?
	 $timestamp = date_create_from_format("d-M-y", $fields[2]);//I am turning the date in the source data into a variable which should then be in the same format as the given minimum date (1-Jan-1998).
	 //echo date("F d, Y", $timestamp). " \n"; /*this is only a test*/
	 //echo "$day " . "$month " . "$year \n"; /*so is this!*/
	//Here are my if conditions:
	if ($fields[5] == "good\n" or $fields[5] == "excellent\n")
		{
		//$printable_test1 = $fields[5];//This is a test
		//echo $printable_test1 . "\n";//This is still a test
				
			if ($fields[4] >= 60000)
			{
				//$printable_test2 = $fields[4];
				//echo $printable_test2 . "\n"; //This and the line above are a test too;
	
				if ($timestamp >= $min_time)
					{
					$forarray = $fields[1] . "\t" . date_format($timestamp, "j F, Y");
					//echo $forarray . "\n"; /*This is only a test.*/
					//It was not specified whether I should have leading zeroes in my day numbers,
					//so I was not sure whether to use d or j as the day specifier.
					//I think leading zeroes are stylish, as this resembles the format used on a digital clock.
					//The following should populate an unsorted array with the names and starting dates of every qualifying line:
					$sortable[]  = $forarray;
					}
			}
		}
		
}

//The following should  print the results from the unsorted array, without any keys:
//foreach($sortable as $awesomeline)
//echo $awesomeline . "\n";

//The following should sort the array and print the results, again minus the keys:

sort($sortable);
$sorted = $sortable;
foreach($sorted as $bestline)
echo $bestline . "\n";
?>
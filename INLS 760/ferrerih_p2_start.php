<?php //ferrerih_p2_browse.php
//I am using "mysqli" for all of the commands that would otherwise contain "mysql" here
//since the "mysql"-prefixed commands are deprecated.
require "ferrerih_p2_access.php";//Note: change the path for this to improve security.
//The above (connecting to mysql) does not need to be repeated every time the results displayed change,
//so here it is in a separate file. I am not sure about the database selection command below.

mysqli_select_db(p2records) or die('Error! Failed to select database! D: D:');

//Here I am initializing variables according to the textbook's advice to do this for security reasons:
$offset = 0;
$increase = 0;
$decrease = 0;
$first = 0;
$last = 0;
$query = "";
$superquery = "";
//I am not sure about the correct way to initialize a resource, rather than a string or array.
$result = "";
$superresult = "";
require ferrerih_p2_browse.php;
?>
<?php
//last edited 2/26/2016
require_once "ferrerih_p3_access_altered.php";
//take the videoid from the post array,
if (isset($_POST['videoid']))
{
	$videoid = $_POST['videoid'];
}
else
{
	echo "Error! Failed to pass videoid.";
}
//then use it to form a query and run the query:
$videoidquery = "select title, genre, keywords, duration, color, sound, sponsorname from p4records where videoid = $videoid";
if ($detailsresult = mysqli_query($db, $videoidquery))
{
	$videodetails = mysqli_fetch_assoc($detailsresult);
}
else
{
	echo "Error! Query failed.";
}
//search for the position of the final semicolon in the set of keywords
$lastmatch = strrpos($videodetails['keywords'], ";");
//strip it from the string
$videodetails['keywords'] = substr($videodetails['keywords'], 0, $lastmatch);
//add a space after each remaining semicolon
$videodetails[keywords] = str_replace(";", "; ", $videodetails[keywords]);

echo "<b>$videodetails[title]</b><br />
	<b>Genre</b>: $videodetails[genre] <br />
	<b>Keywords</b>: $videodetails[keywords] <br />
	<b>Duration:</b> $videodetails[duration] <br />
	<b>Color:</b> $videodetails[color] <br />
	<b>Sound:</b> $videodetails[sound] <br />
	<b>Sponsor:</b> $videodetails[sponsorname] <br />";
?>
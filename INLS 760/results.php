<?php

$sqlquery = "";
$videoresult = "";
$imgsrc = "";
$videolink = "";
$suggestion = "";
$suggestionresult = "";
$searchtext = "";

require_once "ferrerih_p3_access_altered.php";

echo <<<_END
<html>
<head>
<title>
Open Video
</title>
<link rel="stylesheet" type="text/css" href="ferrerih_p4_stylesheet.css">
_END;
// I get an error about converting a mysqli object to a string when I try to.
//Also variables in PHP start with $ and variables in Javascript do not, 
//so I am not sure if a variable I use in Javascript can then be used in PHP.

//alternatively, fieldtext can be inserted into an array, 
//and the array named in the key-value section of the post request.

echo <<<_END
<script src ="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js">
</script>
<script>
fieldtext = ""
suggestion = ""
suggestionquery = ""
videoid = ""
$(document).ready(function()
{	
	$("searchform").keyup(function()
	{
		fieldtext = $("searchtext").val()
		$.post("keyword-suggestions.php", {fieldtext: fieldtext})
		$("suggestions").load("keyword-suggestions.php")
	});
	$("video").mouseenter(function()
	{
		videoid = this.attr("videoid")
		$.post("result-details.php", {videoid: videoid})
		$("details").load("result-details.php")
	});
	$("video").mouseleave(function()
	{
		$("details").text("")
	})
})
</script>
</head>
_END;
//Here is the title and the search form:
echo <<<_END
<body>
<div>
<h1>Open Video</h1><br />
</div>
<div class="left">
<form name="thebestform" action="results.php" method="get">
<input type="text" id="searchform" name="searchtext" value="enter text here"">
<input type="submit" value="Search">
</form>
_END;

//if the user has already clicked the search button and thus there is a search text value
//in the $_GET array, take this value and make it a global variable:
if (isset($_GET['searchtext']))
{
	$searchtext = $_GET['searchtext'];
	//use this variable to prepare a query to select videos with:
	$sqlquery = "select * from p4records where match(title, description, keywords) against (\"$searchtext\")";
	$videoresult = mysqli_query($db, $sqlquery);
}

//here is the suggestions box:
echo "Suggestions: <br /><br />
<div id = \"suggestions\" class=\"normal\">";

echo "</div>
</div>
<div class=\"center\">";
echo "Showing results for:  $searchtext <br /><br />"; 

if ($videoresult)
{
	while ($video = mysqli_fetch_assoc($videoresult))
	{
		$imagesrc = "http://www.open-video.org/surrogates/keyframes/" . $video[videoid]  . "/" . $video[keyframeurl] ; 
		$videolink = "http://www.open-video.org/details.php?videoid=" . $video[videoid] ;
		$description = $video[description];
		//if the length of the description is longer than 200 characters, truncate it and add an ellipsis
		//so that it does not end with something silly like "space shut":
		if (iconv_strlen($description) > 200)
		{
			$description = substr("$description", 0, 200) . "...";
		}
		if ($video[creationyear])
		{
			echo "<div name =\"video\" videoid=\"$video[videoid]\"><a href=\"$videolink\"><img src=\"$imagesrc\"></a> <b> 
			<a href=\"$videolink\">$video[title]</a> ($video[creationyear])</b><br /> $description</div>";
		}
		else
		{
			echo "<div name =\"video\" videoid=\"$video[videoid]\"><a href=\"$videolink\"><img src=\"$imagesrc\"></a> <b> 
			<a href=\"$videolink\">$video[title]</a></b><br /> $description</div>";
		}
	}
}

echo "</div>";
//require_once "result-details.php";
echo "<div id=\"details\" class=\"right\">";
echo "</div>
</section>
</body>
</html>";

?>
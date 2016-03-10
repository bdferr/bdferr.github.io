<?php
// this may still be accessible at http://ils.unc.edu/~ferrerih/lect2/ferrerih_p4_results.php?searchtext=people
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
	Open Video Search
	</title>
	<meta name="viewport" content="initial-scale=1.0, width=device-width">
	<meta name="author" content="Brendan Ferreri-Hanberry">
    <meta name="description" content="Last modified 3/9/2016"> 
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
	<!--Bootstrap-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<!--my stylesheet-->
	<link rel="stylesheet" type="text/css" href="ferrerih_p4_stylesheet.css">
_END;

echo <<<_END
<script>
var fieldtext = "";
var suggestion = "";
var suggestionquery = "";
var videoid = "";
_END;
/*I am using keypress() rather than keydown() here.
Keypress(), unlike keydown(), ignores keys such as delete and backspace.
This could be good in that if I am simply deleting a word, I might not want
shorter component strings of that word to be sent as queries for suggestions.
On the other hand if I were to enter a two-word query and then delete one word of it,
using keypress() as I am doing means that I would then not get suggestions
for just the first word.*/

echo <<<_END
$(document).ready(function()
{	
	$("#searchform").keypress(function()
	{
		console.log("keypress");
		fieldtext = $(this).val();
		console.log("fieldtext assigned");
		$.post("keyword-suggestions.php",
			{"fieldtext": fieldtext},
			function(data, status)
			{
				$("#suggestions").html(data).show();
			}
		);
	});
	$(".video").mouseenter(function()
	{
		videoid = $(this).attr("videoid");
		$.post("result-details.php", 
			{"videoid": videoid}, 
			function(data, status)
			{
				$("#details").html(data).show();
			}
		);
	});
	$(".video").mouseleave(function()
	{
		$("#details").text("").hide();
	});
});
</script>
</head>
_END;
//Here is the title and the search form:
echo <<<_END
<body>
	<div>
	<h1>Open Video Search</h1><br />
	</div>
	<div class="left">
		<form role="form" name="thebestform" action="ferrerih_p4_results.php" method="get">
		<input type="text" id="searchform" name="searchtext" value="enter text here"">
		<input type="submit" value="Search">
	</form>
_END;

//if the user has already clicked the search button (after entering something)
//and thus there is a search text value in the $_GET array,
//take this value and make it a global variable:
if (isset($_GET['searchtext']))
{
	if (get_magic_quotes_gpc())
	{
		echo "Magic quotes are being gotten!";
    	$searchtext = stripslashes($_GET['searchtext']);
		//if you uncomment the mysqli lines and replace $_GET['searchtext'] with $searchtext, 
		//$searchtext will mysteriously become a blank string
	    //$searchtext = mysqli_real_escape_string($searchtext);
	}
	//else
	//{
	    //$searchtext = mysqli_real_escape_string($_GET['searchtext']);
	//}
	$searchtext = htmlentities(strip_tags($_GET['searchtext']));
	//use this variable to prepare a query to select videos with:
	$sqlquery = "select * from p4records where match(title, description, keywords) against (\"$searchtext\")";
	$videoresult = mysqli_query($db, $sqlquery);
}

//here is the suggestions box:
echo "Suggestions: <br /><br />
	<div id = \"suggestions\" class=\"suggestions\">
	</div>
</div>";
//Here are the video results:
echo "<div class=\"center\">";
echo "<h2>Showing results for:  $searchtext <br /><br /></h2>"; 
//put in something to deal with empty results
if ($videoresult)
{
	if (mysqli_num_rows($videoresult) == 0)
	{
	echo "No results for this query!";
	}
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
			echo "<div class =\"video\" videoid=\"$video[videoid]\">
				<h2><a href=\"$videolink\"><img src=\"$imagesrc\"></a> 
				<a href=\"$videolink\">$video[title]</a> ($video[creationyear])</h2>$description<br />
			</div>";
		}
		else
		{
			echo "<div class =\"video\" videoid=\"$video[videoid]\">
				<h2><a href=\"$videolink\"><img src=\"$imagesrc\"></a> 
				<a href=\"$videolink\">$video[title]</a></h2>$description<br />
			</div>";
		}
	}
}

echo "</div>";
//here is the video details section which shows up on mouseover:
echo "<div id=\"details\" class=\"right\"></div>";
echo <<<_END
	<footer class="clear">
		<br />
		<div>
			<ul>
				<li class="navbar"><address><a href="mailto:skepticism9@gmail.com">Contact Me</a></address></li>
				<li class="navbar"><a href="main-page-2.html">Main Site</a></li>
			</ul>
		</div>
		<br />
		<div>
			<a href="http://jigsaw.w3.org/css-validator/check/referer">
				<img style="border:0;width:88px;height:31px"
					src="https://jigsaw.w3.org/css-validator/images/vcss"
					alt="Valid CSS!" />
			</a>
		</div>
		<br />
		<div>Last updated on 3/9/2016.</div>
	</footer>
</body>
</html>
_END;
?>
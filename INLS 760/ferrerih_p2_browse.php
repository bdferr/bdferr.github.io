<?php 
//This file should be accessible at http://ils.unc.edu/~ferrerih/lect2/ferrerih_p2_browse.php
require "ferrerih_p2_access_altered.php";

if (isset($_GET['sortby'])) //If the user has clicked on one of the links to specify what to sort by:
{
	$sortby = $_GET['sortby'];
}
else
{
	$sortby = "itemnum"; //The default value to sort by is the item number, i.e. the primary key.
}
//Here I check to see if the user has already supplied input on where exactly the results
//should start, by clicking the "next" and "previous" links:
if (isset($_GET['offset']))
{
	$offset = $_GET['offset'];
}
else
{
	$offset = 0;
}
//This is for the purpose of displaying the correct first number
//in the phrase "showing records $first - $nextoffset" or "showing records $first - $last":
$first = $offset + 1;
//Do something about appearances of { and } such as Dean E. McLaughlin and Roeland P. {van der Marel}
//or R. Timmermans and Th A. Rijken and J. J. {de Swart} ? I did this at one point somewhere...

//I am including a query to count the table's rows in order to later determine whether
//what is currently displayed in the HTML is the last page of this whole set of rows or not
//for navigation purposes.
$count = 0;
$countquery = "select count(*) from p2records";
$countresult = mysqli_query($db, $countquery);
$count_a = mysqli_fetch_assoc($countresult);
$count = $count_a['count(*)'];
$lastpageestimate = ($count/25);
$lastpagenumber = intval($lastpageestimate);
if ($count%25 == 0)
{
	$lastpagenumber -= 1;
}
$lastoffset = $lastpagenumber*25;
//I am checking whether ($count%25 = 0) because in the current case, there are 225 records,
//but the last page of records is still page 8; if I do not specify that there must be a remainder of $count/25,
//the last page would be considered page 9, which would not make sense as there would be no results on this page.
echo <<<_END
	<html>
	<head>
		<title>Journal Articles</title>
		<meta name="viewport" content="initial-scale=1.0, width=device-width">
 		<meta name="author" content="Brendan Ferreri-Hanberry">
        <meta name="description" content="Last modified 2/23/2016"> 

		<!-- jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<!-- Latest compiled JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<!--Bootstrap-->
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<!--my stylesheet-->
		<link rel="stylesheet" type="text/css" href="ferrerih_p2_stylesheet.css">		
	</head>
	<body>
	<div class="container">
		<header>
		<h1>Scientific Journal Articles</h1>
		
		<div class="dropdown">
			<button class="btn btn-primary btn-lg active dropdown-toggle" type="button" data-toggle="dropdown">Sort by 
				<span class="caret">
				</span>
			</button>
			<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
				<li><a href="ferrerih_p2_browse.php?sortby=lastname&offset=$offset">Author</a></li>
				<li><a href="ferrerih_p2_browse.php?sortby=lastname&itemnum=$offset">Item Number</a></li>
				<li><a href="ferrerih_p2_browse.php?sortby=title&offset=$offset">Title</a></li>
				<li><a href="ferrerih_p2_browse.php?sortby=publication&offset=$offset">Publication</a></li>
				<li><a href="ferrerih_p2_browse.php?sortby=year&offset=$offset">Year</a></li>
			</ul>
        </div>
		
	    <br />
	    </header>
_END;

$query = "select * from p2records order by $sortby limit $offset, 25";
$result = mysqli_query($db, $query);
if ($result)
{

	//The data from the file:
	echo "
	    <section class=\"records\">
		<h2>Now sorting by $sortby</h2>";
	while($row = mysqli_fetch_assoc($result))
	{	
		$url = $row['url'];
		echo $row['authors'] . ". " .
		"<i><a href=\"$url\">" . $row['title'] . "</a></i>. " .
		$row['publication'] . ", " .
		$row['year'] . "." . "<br /> <br />";
	}

	//Begin navigation section:
	if($offset == 0) //If you are on the first page of results, so the first result is visible:
	{
		$nextoffset = 25;

        echo <<<_END
		<i>Showing records $first - $nextoffset of $count.</i> <br />

			<ul class="nav">
				<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=$nextoffset&sortby=$sortby">Next &gt; </a></li>
				<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=$lastoffset&sortby=$sortby">Last &gt; &gt;</a></li>
			</ul>

		</section>
		<footer class="clear">
		<br />
		<div>
    		<ul>
				<li class="footernavbar"><address><a href="mailto:skepticism9@gmail.com">Contact Me</a></address></li>
				<li class="footernavbar"><a href="http://ils.unc.edu/~ferrerih/web-dev-project/main-page-2.html">Main Site</a></li>
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
		<div>
		</body>
		</html>
_END;
	}
	//If you are on the last page of results, so the last result is visible:
	else if ($offset == ($lastpagenumber*25))
	{
		$last = $count;
		$previousoffset = $offset - 25;
        echo <<<_END
        		<i>Showing records $first - $last of $count.</i> <br />

					<ul class="nav">
						<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=0&sortby=$sortby"> &lt; &lt; First</a></li>
						<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=$previousoffset&sortby=$sortby">&lt; Previous</a></li>
					</ul>

				</section>
		<footer class="clear">
			<br />
			<div>
				<ul class="nav">
				<li class="footernavbar"><address><a href="mailto:skepticism9@gmail.com">Contact Me</a></address></li>
				<li class="footernavbar"><address><a href="http://ils.unc.edu/~ferrerih/web-dev-project/main-page-2.html">Main Site</a></address></li>
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
		</div>
		</body>
		</html>
_END;
	}
	//If you are on neither the first nor the last page of results,
	//I want the previous button to reduce the offset by 25, while the next button increases the offset by 25:
	else
	{
		$nextoffset = $offset + 25;
		$previousoffset = $offset - 25;
        echo <<<_END
		<i>Showing records $first - $nextoffset of $count.</i> <br />

						<ul class="nav">
							<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=0&sortby=$sortby"> &lt; &lt; First</a></li>
							<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=$previousoffset&sortby=$sortby">&lt; Previous</a></li>
							<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=$nextoffset&sortby=$sortby">Next &gt;</a>
							<li class="othernavbar"><a href="ferrerih_p2_browse.php?offset=$lastoffset&sortby=$sortby">Last &gt; &gt;</a>
						</ul>

					</section>
		<footer class="clear">
    	    <br />
			<div>
			<ul>
				<li class="footernavbar"><address><a href="mailto:skepticism9@gmail.com">Contact Me</a></address></li>
				<li class="footernavbar"><address><a href="http://ils.unc.edu/~ferrerih/web-dev-project/main-page-2.html">Main Site</a></address></li>
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
        </div>
        </body>
	    </html>
_END;
	}
}
	//If you get no result from the query:
else 
	{
		echo "Error! Query failed! D: D:";
		exit();
	}
?>
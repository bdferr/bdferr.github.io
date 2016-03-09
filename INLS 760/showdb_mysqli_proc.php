<?php
	echo "SW Cantina Products<p>";
	$h = 'pearl.ils.unc.edu';
	$u = 'webdb16';
	$p = 'ph25cMun5';
	$dbname = 'webdb16';
	$db = mysqli_connect($h,$u,$p,$dbname);
	if (mysqli_connect_errno()) {
		echo "Problem connecting: " . mysqli_connect_error();
		exit();
	}
	$query = "select count(*) from swcantina where price > 30";
	if($result = mysqli_query($db,$query)) {
	echo $row;
		while ($row = mysqli_fetch_assoc($result))
		{
		echo $row;
	//		echo $row['pname'] . " ($" . $row['price'] . ") -- "
	//			. $row['pdesc'];
	//		echo "<p>";
	//	}
	mysqli_close($db)}
?>


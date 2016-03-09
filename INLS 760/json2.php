<?php
 $courses = array(
 '523'=>'DB Systems',
 '760'=>'WebDB',
 '509'=>'Info Retrieval',
 'other'=> array(
		'9000' => "Not a real class",
		'9001' => "It's over 9000!",
		'9002' => "Still not a real class"
			));
echo json_encode($courses);
?>
<?php
	$date = (isset($_GET["date"]) && is_numeric($_GET["date"]) ? $_GET["date"] : date("md"));
	$month = substr($date, 0 , 2);
	// IMAGE DIRECTORY is this directory
	$imagesDir = dirname(__FILE__) . "/images/";
	// CHECK FOR IMAGES DISPLAYING FOR TODAY ONLY
	$images = glob($imagesDir . "today_" . '*' . $date . '.{jpg,jpeg,png,gif}', GLOB_BRACE);
	if (empty($images)) {
		$images = array_merge(
			glob($imagesDir . 'monthly_*' . $date . '.{jpg,jpeg,png,gif}', GLOB_BRACE),
			glob($imagesDir . 'monthly_*' . $month . '[0-9][0-9].{jpg,jpeg,png,gif}', GLOB_BRACE),
			glob($imagesDir . 'monthly_*' . $month . '[0-9][0-9].{jpg,jpeg,png,gif}', GLOB_BRACE),
			glob($imagesDir . 'monthly_*' . $month . '[0-9][0-9].{jpg,jpeg,png,gif}', GLOB_BRACE),
			glob($imagesDir . 'any_*' . $date . '.{jpg,jpeg,png,gif}', GLOB_BRACE),
			glob($imagesDir . 'any_*' . $month . '[0-9][0-9].{jpg,jpeg,png,gif}', GLOB_BRACE),
			glob($imagesDir . 'any_*.{jpg,jpeg,png,gif}', GLOB_BRACE)
		);
	}
	// DAILY SEED CREATION
	$seed = md5($date);
	srand((int)$seed);
	$random_array_index = rand() % count($images);
	// SHUFFLE ARRAY
	$reversed_seed = strrev((string)$seed);
	mt_srand((int)$reversed_seed);
	$order = array_map(create_function('$val', 'return mt_rand();'), range(1, count($images)));
	array_multisort($order, $images);
	// GRAB IMAGE
	$remoteImage = $images[$random_array_index];
	// DEBUG
	//print print_r($images) . "<br>";
	//print $date . "<br>";
	//print $seed . "<br>"; 
	//print $random_array_index . "<br>";
	//print $remoteImage . "<br>";
	//die();
	$imginfo = getimagesize($remoteImage);
	header("Content-type: {$imginfo['mime']}");
	readfile($remoteImage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title><?= $title ?></title>
	<?php for ($i = 0; $i < count($css); ++$i) { 
		echo sprintf('<link rel="stylesheet" href="%s">', $css[$i]);
	} ?>
</head>
<body>
	<?php
	echo $header;

	echo $contents;

	echo $footer;

	if (isset($_SESSION['notif'])) {
		echo $_SESSION['notif'];
		unset($_SESSION['notif']);
	}
	
	for ($i = 0; $i < count($js); ++$i) { 
		echo sprintf('<script defer async src="%s"></script>', $js[$i]);
	} ?>
</body>
</html>
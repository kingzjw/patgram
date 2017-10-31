<?php

$currentUrl = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];

$urls = [];

$urls[] = [
	'href' => "http://".$_SERVER['HTTP_HOST']."/user/index.php",
	'value' => '<i class="fa fa-home"></i> Overview'
];

$urls[] = [
	'href' => "http://".$_SERVER['HTTP_HOST']."/user/profile.php",
	'value' => '<i class="fa fa-user"></i> Profile'
];

$urls[] = [
	'href' => "http://".$_SERVER['HTTP_HOST']."/user/myclass.php",
	'value' => '<i class="fa fa-book"></i> My Class'
];

?>

<div id="sidebar">
<nav class="nav nav-default">
<ul class="nav nav-pills nav-stacked">
<?php
foreach ($urls as $url) {
	if($url['href'] == $currentUrl){
		echo '<li class="active"><a href="'.$url['href'].'"><span>'.$url['value'].'</span></a></li>';
	}
	else {
		echo '<li><a href="'.$url['href'].'"><span>'.$url['value'].'</span></a></li>';
	}
}
?>
</ul>
</nav>
</div>
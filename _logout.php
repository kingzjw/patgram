<?php

include_once $_SERVER['DOCUMENT_ROOT'].'/include/common.php';

unset($_SESSION["userId"]);
unset($_SESSION["firstName"]);

header("location: http://".$_SERVER["HTTP_HOST"]."/index.php");

?>
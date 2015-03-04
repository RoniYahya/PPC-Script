<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

$aid=getSafePositiveInteger('id');
$url=$mysql->echo_one("select link from ppc_public_service_ads where id='$aid'");
	header("Location: $url");
	exit(0);
?>
<?php
$script_version="6.0";
$patch_applied="";
$script_mode="live"; 
//MYSQL Class
include("mysql.cls.php");
$mysql=new mysql($mysql_server,$mysql_username,$mysql_password,$mysql_dbname);

include("paging.cls.php"); 

$paging=new paging();
	
include_once(substr(dirname(__FILE__),0,strrpos(dirname(__FILE__),DIRECTORY_SEPARATOR))."/functions.inc.php"); 
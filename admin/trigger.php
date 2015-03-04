<?php
include_once("config.inc.php");
if(!isset($_COOKIE['inout_admin']))
{
	header("Location:index.php");
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location:index.php");
	exit(0);
}

//$trig_res=mysql_query("drop trigger adserver_parentstatus_updation");

$t_parentstatus_updation=0;
$trig_res=mysql_query("show triggers");
while($trig_row=mysql_fetch_array($trig_res))
{
//print_r($trig_row);
if($trig_row['Trigger']=='adserver_parentstatus_updation')
	$t_parentstatus_updation=1;
}

if($t_parentstatus_updation==0)
{

$micon=new mysqli($mysql_server, $mysql_username, $mysql_password ,$mysql_dbname);
$trigger="CREATE  TRIGGER  `adserver_parentstatus_updation` AFTER UPDATE ON `nesote_inoutscripts_users` 
FOR EACH ROW BEGIN
 UPDATE `ppc_users` SET parent_status = NEW.status WHERE common_account_id = NEW.id;
 UPDATE `ppc_publishers` SET parent_status = NEW.status WHERE common_account_id = NEW.id;
END;";
$micon->multi_query($trigger); 
echo $micon->error;
$micon->close();
}

?>
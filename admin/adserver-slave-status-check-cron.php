<?php
include_once("config.inc.php");
error_reporting(1);
$ini_error_status=ini_get('error_reporting');

$master_data="";	
$result=mysql_query("select id,server_url,name,min_range,max_range,status from server_configurations where srv_type='2'");
	
if(mysql_num_rows($result) >0)
{
while($row=mysql_fetch_row($result))
{


if($fp_master=fopen($row[1]."slave-status-check-cron.php","r")) 
{
	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);
    fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-status-check-cron.php");//."&status=".$status
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 




if($master_data ==1)
{
mysql_query("update server_configurations set status='100' where id='$row[0]'");
servercheck($row[2],$row[1],$row[3],$row[4],'100');
}
else if($master_data ==110)
{
mysql_query("update server_configurations set status='110' where id='$row[0]'");
servercheck($row[2],$row[1],$row[3],$row[4],'110');
}
else if($master_data ==101)
{
mysql_query("update server_configurations set status='101' where id='$row[0]'");
servercheck($row[2],$row[1],$row[3],$row[4],'101');
}
else if($master_data ==100)
{
mysql_query("update server_configurations set status='100' where id='$row[0]'");
servercheck($row[2],$row[1],$row[3],$row[4],'100');
}
else if($master_data ==111)
{
mysql_query("update server_configurations set status='111' where id='$row[0]'");
}
else
{
mysql_query("update server_configurations set status='000' where id='$row[0]'");
servercheck($row[2],$row[1],$row[3],$row[4],'000');
}

}

}
//servercheck($row[2],$row[1],$row[3],$row[4],'100');
function servercheck($ServerName,$ServerUrl,$minRange,$maxRange,$sstatus)
{
global $admin_general_notification_email;
global $ppc_engine_name;
global $email_encoding;
global $timezone_change;

$tmformat=dateTimeFormat(time());

$msg = <<< EOB
Hello,

The following server was found not functioning at $tmformat ($timezone_change).

Server Name		  : $ServerName
Server Url 		  : $ServerUrl
Server Range      : $minRange - $maxRange
Server Status	  : $sstatus

An automatic fix would be attempted but we recommend that you check this server from manage server page for more details.

Best Regards,
$ppc_engine_name

EOB;
		
xMail($admin_general_notification_email, "$ppc_engine_name - Server status notification", $msg, $admin_general_notification_email, $email_encoding);



}


?>
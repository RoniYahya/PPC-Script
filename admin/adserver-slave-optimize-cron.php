<?php
include_once("config.inc.php");
error_reporting(1);
$ini_error_status=ini_get('error_reporting');

$master_data="";	
$result=mysql_query("select id,server_url,name from server_configurations where srv_type='2' and status='111'");
	
if(mysql_num_rows($result) >0)
{
while($row=mysql_fetch_row($result))
{


if($fp_master=fopen($row[1]."slave-optmize-cron.php","r")) 
{
	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);
    fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-optmize-cron.php");
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 




}

}

?>
<?php
include_once("config.inc.php");
//error_reporting(E_ALL);
$ini_error_status=ini_get('error_reporting');



$master_data="";		
$result=mysql_query("select id,server_url,name from server_configurations where srv_type='2' and status='111'");
	
if(mysql_num_rows($result) >0)
{

while($row=mysql_fetch_row($result))
{
echo "<br><br>Visit Back Up Of Slave Server ".$row[2]."<br>";


$s_result=mysql_query("select e_time,last_id,status from statistics_updation where task='visit_back_update_slave_".$row[0]."'");
		

    $s_row=mysql_fetch_row($s_result);
	//$status=$s_row[2];
	$times=$s_row[0];
	
if($times == 0)
{	
$time=time();
$times=mktime(date("H",$time)-1,0,0,date("m",$time),date("d",$time),date("y",$time));

}

	
	
	
	// if(!mysql_query("update statistics_updation set status=1 where task='visit_back_update_slave_".$row[0]."'"))
	// {
    // echo "<br>Visit backup updation failed<br>update statistics_updation set status=1 where task='visit_back_update_slave_".$row[0]."'";
	// break;

    // }
	 


//if($fp_master=fopen($row[1]."slave-hourly-visit-update-cron.php?time=".$times."&status=".$status,"r")) 
if($fp_master=fopen($row[1]."slave-hourly-visit-update-cron.php?time=".$times,"r")) 
{



	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);

	
 fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-hourly-visit-update-cron.php?time=".$times);//."&status=".$status
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 

if($master_data !="")
{
$exp_mdata=explode('-',$master_data);





if(count($exp_mdata) >2)
echo "<br>The requested URL was not found on this server<br>";
else
{

if($exp_mdata[0] != "Error")
{


echo "<br>Currently update data for ".$times." (".date("m d Y H i s",$times).") - ".$exp_mdata[0]." (".date("m d Y H i s",$exp_mdata[0]).")"."<br>";

mysql_query("update statistics_updation set e_time='".$exp_mdata[0]."',status=2 where task='visit_back_update_slave_".$row[0]."'");



}


echo $exp_mdata[1]."<br>";

}

}
else 
echo "<br>Failed to connect to Slave Server ".$row[2]."<br>";

}


}	
	


?>
<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php

include("config.inc.php");
include("../extended-config.inc.php");  
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

if($script_mode=="demo")
	{
		include("admin.header.inc.php");
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}

set_time_limit(0);
$msg="";
$servername=trim($_POST['servername']);
$serverurl=trim($_POST['serverurl']);


phpSafe($servername);
phpSafeUrl($serverurl);


$minrange=trim($_POST['minrange']);
$maxrange=trim($_POST['maxrange']);
phpsafe($minrange);
phpsafe($maxrange);



$srvtype=trim($_POST['srvtype']);
phpsafe($srvtype);

if($minrange =="")
$minrange=0;
if($maxrange =="")
$maxrange=0;


if(!is_numeric($minrange))
$msg="Please enter positive number for  range start value!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
else if(!is_numeric($maxrange))
$msg="Please enter positive number for  range end value  !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
else if($minrange != 0 && $maxrange <= $minrange)
$msg="Range end value must be greater than range start value !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  


else if($maxrange <= 0)
$msg="Please enter positive number for  range end value  !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  


else if($srvtype == 1 && $mysql->total("server_configurations","srv_type='$srvtype'")>0)
$msg="Only one application server can be configured. You have already configured one!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		 
		  
else if($srvtype != 1 && $mysql->total("server_configurations","srv_type=1")!=1)		  
$msg="You must configure an application server first!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  

else if( ($already_in_server = $mysql->echo_one("select name from server_configurations where min_range<='$minrange' and max_range >='$minrange' ")) && $already_in_server!='')		
$msg="The start value of range already falls in the server : $already_in_server "." <a href=\"javascript:history.back(-1);\">Go Back</a>";  

else if( ($already_in_server = $mysql->echo_one("select name from server_configurations where min_range<='$maxrange' and max_range >='$maxrange' ")) && $already_in_server!='')		
$msg="The end value of range already falls in the server : $already_in_server "." <a href=\"javascript:history.back(-1);\">Go Back</a>";  

else if($servername!="" && $serverurl!="")
{
	
	      
		 
				
		 
		  		  
		  if($mysql->total("server_configurations"," name='$servername' or  server_url='$serverurl' ")>0)
		  {
				$msg="Same server configuration already exists !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		  }
		  
		  /*
		  else if($mysql->total("server_configurations"," max_range <='$maxrange' or max_range <='$minrange'")>0)
		  {
				$msg="This range already configured !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		  }
		  else if($mysql->total("server_configurations"," min_range >='$minrange' and min_range >= '$maxrange'")>0)
		  {
				$msg="This range already configured !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		  }
		  */
		  
		  
		  else
		  {
		  
		  
		  
if($fp_master=fopen($serverurl."slave-status-check-repair.php","r")) 
{

	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);
    fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $serverurl."slave-status-check-repair.php");
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 

if($master_data!=111 && $master_data!=110 && $master_data!=101 && $master_data!=100 )
$status='000';
else
{
if($master_data ==111)	
$status='111';
else if($master_data ==110)	
$status='110';
else if($master_data ==101)	
$status='101';
else if($master_data ==100)	
$status='100';
	
}		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
			   mysql_query("INSERT INTO `server_configurations`(`id`,`name`,`server_url`,`min_range`,`max_range`,`srv_type`,`status`) VALUES ('','$servername','$serverurl','$minrange','$maxrange','$srvtype','$status')");
			   
			   
			   $last_id=$mysql->echo_one("SELECT LAST_INSERT_ID() ");
			  /* if($srvtype ==1)
			   {
			   mysql_query("INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'advertiser_impression_hourly_master_".$last_id."', 0, 2, 0);");
			   mysql_query("INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'publisher_impression_hourly_master_".$last_id."', 0, 2, 0);");
			   
			   }*/
			  
			   
			   
			   if($srvtype ==2)
			   {
			   mysql_query("INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'advertiser_impression_hourly_slave_".$last_id."', 0, 2, 0);");
			   mysql_query("INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'publisher_impression_hourly_slave_".$last_id."', 0, 2, 0);");
			   
			   
			   
			   mysql_query("INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'visit_back_slave_".$last_id."', 0, 2, 0);");
			   
			   mysql_query("INSERT INTO `statistics_updation` ( `task`, `e_time`, `status`, `last_id`) VALUES ( 'visit_back_update_slave_".$last_id."', 0, 2, 0);");
			   
			   }
			   
			   header("Location:manage-loadbalance.php");
			   exit(0);
			  
				
		 }
		 echo mysql_error();	
}
else
{
	$msg="Please fill the necessary fields !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";
}
include("admin.header.inc.php");
?>

  <br />
  <span class="already"><?php echo $msg;?> </span> 


<br />
<br />


<?php include("admin.footer.inc.php"); ?>
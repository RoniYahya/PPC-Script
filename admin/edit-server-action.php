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



set_time_limit(0);





$url=urldecode($_POST['url']);


$msg="";
$id=$_POST['id'];


$servername=trim($_POST['servername']);
$serverurl=trim($_POST['serverurl']);


phpSafe($servername);
phpSafeUrl($serverurl);


$srvtype=trim($_POST['srvtype']);


$minrange=trim($_POST['minrange']);
$maxrange=trim($_POST['maxrange']);

if($minrange =="")
$minrange=0;
if($maxrange =="")
$maxrange=0;




if(!is_numeric($minrange))
{



 include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="Please enter positive range start value !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 



}
else if(!is_numeric($maxrange))
{


 include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="Please enter positive range end value  !"." <a href=\"javascript:history.back(-1);\">Go Back</a>"; ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 



} 
else if($minrange != 0 && $maxrange <= $minrange)
{


 include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="Range end value must be grater than range start value !"." <a href=\"javascript:history.back(-1);\">Go Back</a>"; ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 



}


else if($maxrange <= 0)
{


 include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="Please enter positive range end value  !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 



}

else if($srvtype == 1 && $mysql->total("server_configurations","srv_type='$srvtype' and id<>'$id'")>0)
{

 include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="You have already configured one application server!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";   ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 




}


else if( ($already_in_server = $mysql->echo_one("select name from server_configurations where min_range<='$minrange' and max_range >='$minrange'  and id<>'$id'")) && $already_in_server!='')		
{
		$msg="The start value of range already falls in the server : $already_in_server "." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		 include("admin.header.inc.php");
		  echo "<br><br>";?>
		 <span class="already"><?php echo $msg;?> </span> 
		 <?php
		  echo "<br><br>";
		  include("admin.footer.inc.php"); 
}
else if( ($already_in_server = $mysql->echo_one("select name from server_configurations where min_range<='$maxrange' and max_range >='$maxrange' and id<>'$id'")) && $already_in_server!='')		
{
		$msg="The end value of range already falls in the server : $already_in_server "." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		 include("admin.header.inc.php");
		  echo "<br><br>";?>
		 <span class="already"><?php echo $msg;?> </span> 
		 <?php
		  echo "<br><br>";
		  include("admin.footer.inc.php"); 
}



else if($servername!="" && $serverurl!="")
{

	
		  if($mysql->total("server_configurations","id<>'$id'  and (name='$servername' or  server_url='$serverurl')")>0)
		  {
		   include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="Same server configuration already exists !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
			
		  }
		  /*
		  else if($mysql->total("server_configurations","id<>'$id'  and  (max_range >='$maxrange' or max_range <='$minrange') ")>0)               //change range code
		  {
		   include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="This range already configured !"." <a href=\"javascript:history.back(-1);\">Go Back</a>"; ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
			
		  }  
		  else if($mysql->total("server_configurations","id<>'$id'  and  (min_range >='$minrange' or min_range <= '$maxrange') ")>0)               //change range code
		  {
		   include("admin.header.inc.php");
		      echo "<br><br>";
				$msg="This range already configured !"." <a href=\"javascript:history.back(-1);\">Go Back</a>"; ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
			
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
		  
		  
		  
		  
		  
		  
		  
		  	mysql_query("update server_configurations set name='$servername', server_url='$serverurl',min_range='$minrange',max_range='$maxrange',srv_type='$srvtype',status='$status' where id='$id'");
				//echo mysql_error();
			header("Location:$url");
			exit(0);
			 
		 }
		 echo mysql_error();	
}
else
{
	 include("admin.header.inc.php");
	  echo "<br><br>";
	$msg="Please fill the necessary fields !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	 <span class="already"><?php echo $msg;?> </span> 
	 <?php 
	 echo "<br><br>";
	 include("admin.footer.inc.php"); ?>
	 <?php
}
?>
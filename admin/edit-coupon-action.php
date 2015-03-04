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





$url=urldecode($_GET['url']);





$msg="";
$id=$_POST['id'];
$code=trim($_POST['gift_code']);
$amount=trim($_POST['g_amount']);
$date=trim($_POST['e_date']);




$type=trim($_POST['c_type']);
$times=trim($_POST['c_times']);
$name=trim($_POST['c_name']);




phpSafe($type);
phpSafeUrl($times);;
phpSafe($name);



phpSafe($code);
phpSafeUrl($amount);;
phpSafe($date);

$str=explode("/",$date);
$strstring=mktime(0,0,0,$str[0],$str[1]+1,$str[2]);

if($code!="" && $amount!="" && $date!="" && $times!=""&& $name!="")
{
		if(!is_numeric($amount) || $amount<=0)
			{
				include("admin.header.inc.php");
					  echo "<br><br>";
				$msg="Amount is invalid !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
				 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php");   
			}
		elseif(!is_numeric($times) || $times<0)
			{
				include("admin.header.inc.php");
					  echo "<br><br>";
				$msg="No. of times is invalid !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
				 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php");   
			}
	
		  elseif($mysql->total("gift_code","id<>'$id'  and coupon_code='$code'")>0)
		  {
			   		include("admin.header.inc.php");
					  echo "<br><br>";
					$msg="Coupon Code already exists !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
			
		  }
		  		  elseif(time()>$strstring)
			{
					include("admin.header.inc.php");
					  echo "<br><br>";
					 $msg="Please check the expiry date !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  ?>
					 <span class="already"><?php echo $msg;?> </span> 
					 <?php
					  echo "<br><br>";
					  include("admin.footer.inc.php"); 
			 }

		  else
		  {
				mysql_query("update gift_code set coupon_code='$code', amount='$amount', expirydate='$strstring' ,name='$name',no_times='$times' where id='$id'");
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
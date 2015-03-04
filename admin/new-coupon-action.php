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







$msg="";
$code=trim($_POST['gift_code']);
$amount=trim($_POST['g_amount']);
$date=trim($_POST['e_date']);



$type=trim($_POST['c_type']);
$times=trim($_POST['c_times']);
$name=trim($_POST['c_name']);



phpSafe($type);
phpSafe($times);;
phpSafe($name);




phpSafe($code);
phpSafe($amount);;
phpSafe($date);

$str=explode("/",$date);
$strstring=mktime(0,0,0,$str[0],$str[1]+1,$str[2]);






if($code!="" && $amount!="" && $date!="" && $type!=""&& $times!=""&& $name!="" )
{
	
			if(!is_numeric($amount) || $amount<=0)
			{
				$msg="Amount is invalid !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
			}
			elseif(!is_numeric($times) || $times<0)
			{
				$msg="No. of times is invalid !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
			}
		  elseif($mysql->total("gift_code"," coupon_code='$code' ")>0)
		  {
				$msg="Same coupon code already exists !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
		  }
		  elseif(time()>$strstring)
			{
			 $msg="Please check the expiry date !"." <a href=\"javascript:history.back(-1);\">Go Back</a>";  
			 }
			else 
		  {
			  mysql_query("INSERT INTO `gift_code`(`id`,`name`,`coupon_code`,`status`,`type`,`amount`,`expirydate`,`no_times`) VALUES ('','$name','$code','1','$type','$amount','$strstring','$times')");
			   header("Location:manage-coupons.php");
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
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
      <td align="center" scope="row">&nbsp;</td>
    </tr>
  </table>
  <br />
  <span class="already"><?php echo $msg;?> </span> 


<br />
<br />









<?php include("admin.footer.inc.php"); ?>
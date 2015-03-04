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


include_once("admin.header.inc.php");?><?php 
$msg="";
$credit=$_POST['fund'];
$comment=trim($_POST['comment']);
phpSafe($credit);
phpSafe($comment);
$aid=$_POST['aid'];
phpSafe($aid);
$url=urldecode($_POST['url']);
if($credit=="" ||$credit<=0 || !is_numeric($credit) || $_POST['mode']=="select")
{

	if($_POST['mode']=="select" || $_POST['mode']=="")
	$msg.="Please select the fund mode!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";
	else
	$msg.="Please fill in a valid amount and click 'Add' button!"." <a href=\"javascript:history.back(-1);\">Go Back</a>";?>
	<br>
	 <span class="already"><?php echo $msg;?> </span>
	 <br>
	 <br>
	
<?php
}
else
{
	
	if($_POST['mode']=="account")
	{
	mysql_query("insert into advertiser_bonus_deposit_history values('0','$aid','$credit',1,'$comment',".time().",'0','0','0')");
	mysql_query("update ppc_users set accountbalance=accountbalance+$credit where uid='$aid'");

//*************************


$balance=mysql_query("select accountbalance from ppc_users where uid='$aid'");
$balancerow=mysql_fetch_row($balance);
if($balancerow[0]>=$advertiser_minimum_account_balance)
{
mysql_query("update ppc_users set balancestatus=0 where uid='$aid'");

}


//**************************

	}

//echo $balancerow[0];
//echo $advertiser_minimum_account_balance;



	if($_POST['mode']=="bonus")
	{
	mysql_query("insert into advertiser_bonus_deposit_history values('0','$aid','$credit',0,'$comment',".time().",'0','0','0')");
	mysql_query("update ppc_users set bonusbalance=bonusbalance+$credit where uid='$aid'");
	}
	?>
	<span class="inserted"><br><?php echo "Advertiser account has been credited successfully.";?></span>
	<strong><br/><br />
<a href="<?php echo $url; ?>">Click here to go back to the page you were viewing</a></strong><br>
<br>
<?php }
?>
 <?php include("admin.footer.inc.php"); ?>

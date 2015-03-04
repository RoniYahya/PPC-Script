<?php 

/*--------------------------------------------------+
|													 |
| Copyright © 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/



?>
<?php

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

include_once("admin.header.inc.php");
$id=$_REQUEST['id'];
//$status=$_REQUEST['status'];
phpsafe($id);

$req_res=mysql_query("select * from ppc_publisher_payment_hist where id='$id'");
$req_row=mysql_fetch_array($req_res);

if(!$req_row)

{
?>
	<span class="already"><br>
	<?php echo "Invalid request.";?></span>
	<a href="javascript:history.back(-1);">Go Back</a>           <br>
<?php	
	include("admin.footer.inc.php"); 
	die;
}

$status=$req_row['status'];
$type=$req_row['paymode'];


	if($status!=0)
	
	{
	?>
		<span class="already"><br>
		<?php echo "Only pending requests can be approved.";?></span>
		<a href="javascript:history.back(-1);">Go Back</a>           <br>
	<?php	
		include("admin.footer.inc.php"); 
		die;
	}


$urlstr="?status=0";
$str="pending";


if($type==0)
{
$urlstr.="&type=c";
$str.=" check";
}
if($type==1)
{
$urlstr.="&type=p";
$str.=" paypal";
}	
if($type==2)
{
$urlstr.="&type=b";
$str.=" bank";
}	
if($type==3)
{
$urlstr.="&type=t";
$str.=" transfer";
}	

//$urlstr="";
//$str="all";
//if($status==0)
//{
//}
$payerinfo=$_POST['payerinfo'];
$txid=$_POST['txid'];
//$type=$_POST['type'];
phpSafe($payerinfo);
phpSafe($txid);
phpSafe($type);
if($payerinfo==""||$txid=="")
{
	?>
	<span class="already"><br>
	<?php echo "Please check whether you filled all mandatory fields !";?></span>
	<a href="javascript:history.back(-1);">Go Back And Modify</a>           <br>
	<?php
}
else if(!isValidEmail($payerinfo) && $type==1)
{
	?>
	<span class="already"><br>
	<?php echo "Please check whether the email is valid !";?></span>
	<a href="javascript:history.back(-1);">Go Back And Modify</a>           <br>
	<?php
}
else
{
	mysql_query("update ppc_publisher_payment_hist set  txid ='$txid',payerdetails='$payerinfo', status =1,processdate='".time()."' where id='$id'");
	?>
	<br>
	Request status has been successfully changed.
	<a href="ppc-publisher-withdrawal-history.php<?php echo $urlstr;?>">View <?php echo " ".$str." "; ?>withdrawals</a> <br>
	<br>
	<?php 
}
?>
<?php include("admin.footer.inc.php"); ?>

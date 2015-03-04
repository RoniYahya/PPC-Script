<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();
$comid=$mysql->echo_one("select common_account_id from ppc_users where uid='$uid'");
if($comid!=0)
{
header("Location:ppc-user-control-panel.php");
exit(0);	
}
$type=$_POST['radconf'];

$pid=$_POST['pubid'];

$email=$_POST['email'];
$country=trim($_POST['country']);
$domain=trim($_POST['domain']);
$firstname=trim($_POST['firstname']);
$lastname=trim($_POST['lastname']);
$phone_no=trim($_POST['phone_no']);
$address=trim($_POST['address']);
$username=trim($_POST['username']);
$taxid=trim($_POST['taxidentification']);
phpSafe($type);
phpSafe($pid);
phpSafe($email);
phpSafe($country);
phpSafe($domain);
phpSafe($firstname);
phpSafe($lastname);
phpSafe($phone_no);
phpSafe($address);
phpSafe($taxid);
if($email=="" || $country=="" || $domain=="" || $firstname=="" || $lastname=="" || $address=="" || $phone_no==""||$username=="")
{
header("Location:show-message.php?id=1001");
exit(0);
}
$uid=$user->getUserID();
if($user->emailExists($email,$uid))
{
	header("Location:show-message.php?id=5019");
	exit(0);
}
if(!($user->isValidEmail($email)))
			{
				header("Location:show-message.php?id=6009");
				exit(0);
			}
		if(!isPositiveInteger($phone_no))
		{
		header("Location:show-message.php?id=1032");
		exit(0);
		}
	if(!checkSpace($domain))
		{
		header("Location:show-message.php?id=1031");
		exit(0);
		}
	if(!isDomain($domain))
	{
		header("Location:show-message.php?id=1030");
		exit(0);
	}
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid=$pid");
	if($commonid!=0)
	{
		header("Location:show-message.php?id=8895");
		exit(0);
	}
	$uid=$user->getUserID();
	$main1=mysql_query("select * from nesote_inoutscripts_users where username='$username'");
	$main=mysql_num_rows($main1);
	$pub=mysql_query("select * from ppc_users where username='$username' and  uid!='$uid'");
	$publ=mysql_num_rows($pub);
	$memail1=mysql_query("select * from nesote_inoutscripts_users where email='$email' ");
	$memail=mysql_num_rows($memail1);
	$aemail1=mysql_query("select * from ppc_users where email='$email' and  uid!='$uid'");
	$aemail=mysql_num_rows($aemail1);
	$pemail1=mysql_query("select * from ppc_publishers where email='$email' and uid!='$pid'");
	$pemail=mysql_num_rows($pemail1);
if($type==1)
{
	$publisher1=mysql_query("select * from ppc_publishers where username='$username' and uid!='$pid'");
	$publisher=mysql_num_rows($publisher1);	
	if(($publisher==0) &&($main==0)&&($publ==0))
	{

$advertiser=mysql_query("select * from ppc_users where uid='$uid'");
$adve=mysql_fetch_row($advertiser);
$password=$adve[2];

	
	if(($pemail==0) && ($aemail==0) && ($memail==0))
	{
		
$name=$firstname." ".$lastname;
	
	mysql_query("BEGIN");
		if(!mysql_query("insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$username','$password','$name','$adve[3]','$adve[7]','1')"))
		{
			$flag=1;
		}
		 $lastid=mysql_insert_id();
		if(!mysql_query("update ppc_publishers set username='$username',password='$password',email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',phone_no='$phone_no',address='$address',common_account_id='$lastid',parent_status='1',taxidentification='$taxid' where uid='$pid'"))
		{
			$flag=1;
		}
	if(!mysql_query("update ppc_users set  email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',address='$address',phone_no='$phone_no',username='$username',password='$password',common_account_id='$lastid',parent_status='1',taxidentification='$taxid' where uid='$uid'"))
	
{
	$flag=1;
}

	if($flag==1)
{
		mysql_query("ROLLBACK");
		header("Location:show-message.php?id=1004");
	exit(0);
}
else
{
	mysql_query("COMMIT");
	setcookie("io_username",$username,0,'/');
setcookie("io_password",$password,0,'/');
	setcookie("io_type",md5("Common"),0,'/');
$pubrid=$mysql->echo_one("select rid from ppc_publishers where uid='$pid'");
if($pubrid==$uid)
{
	mysql_query("update ppc_publishers set rid='0' where uid='$pid'");
}
   header("Location:show-success.php?id=9992&page=control-panel.php");
	exit(0);  
}
	
	}
	else
	{
		header("location:show-message.php?id=5019");
exit;
	}
	}else //already existed username
	{
	header("location:show-message.php?id=1027");
exit;
	}
	}

else  //type==2
{
$advertiser=mysql_query("select * from ppc_publishers where username='$username' and uid!='$pid'");
$adve=mysql_num_rows($advertiser);
$publisher1=mysql_query("select * from ppc_publishers where uid='$pid'");
 $publisher=mysql_fetch_row($publisher1);

$password=$publisher[2];

if(($adve==0) &&($main==0)&&($publ==0))
	{
		
		if(($pemail==0) && ($aemail==0) && ($memail==0))
	{
	$name=$firstname." ".$lastname;
mysql_query("BEGIN");
		if(!mysql_query("insert into nesote_inoutscripts_users(`username`, `password`, `name`, `email`, `joindate`, `status`) values ('$username','$publisher[2]','$name','$publisher[3]','$publisher[7]','1')"))
		{
			$flag=1;
		}
		 $lastid=mysql_insert_id();
if(!mysql_query("update ppc_publishers set username='$username',password='$password',email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',phone_no='$phone_no',address='$address',common_account_id='$lastid',parent_status='1',username='$username',taxidentification='$taxid' where uid='$pid'"))
		{
			$flag=1;
		}
if(!mysql_query("update ppc_users set email='$email',country='$country',domain='$domain',firstname='$firstname',lastname='$lastname',address='$address',phone_no='$phone_no',username='$username',common_account_id='$lastid',parent_status='1',password='$password',taxidentification='$taxid' where uid='$uid'"))	
{
$flag=1;
}

if($flag==1)
{
		mysql_query("ROLLBACK");
		header("Location:error-message.php?id=1004");
	exit(0);
}
else
{
	mysql_query("COMMIT");
	setcookie("io_username",$username,0,'/');
setcookie("io_password",$password,0,'/');
	setcookie("io_type",md5("Common"),0,'/');
		$pubrid=$mysql->echo_one("select rid from ppc_publishers where uid='$pid'");
if($pubrid==$uid)
{
	mysql_query("update ppc_publishers set rid='0' where uid='$pid'");
}
	 header("Location:show-success.php?id=9992&page=control-panel.php");
	exit(0); 
}
	
	}
	else
	{
		header("location:show-message.php?id=5019");
exit;
	}
	}
else  //already existed username
{
header("location:error-message.php?id=1027");
exit;

}	

	}

eval('?>'.$template->getPage().'<?php ');
?>
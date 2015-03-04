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

if($script_mode=="demo")
	{
 include_once("admin.header.inc.php");
		echo "<br>This feature is disabled in demo version<br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}

$lid=$_GET['id'];
//echo "select id from ad_logo_details where id=$lid";exit;
$adcount1=mysql_query("select id,ad_logos_name from ad_logos_details where id=$lid");
$row=mysql_fetch_row($adcount1);

//echo "select id,logo_name from ad_logo_details where id=$lid"; exit;
$adcount=mysql_num_rows($adcount1);

//$text=$_POST['text'];
//$url=urldecode( $_GET['url']);
//$subject=$_POST['subject'];
//if(get_magic_quotes_gpc())
//	{
//	$text=stripslashes($text);
//	$subject=stripslashes($subject);
//	}
//$email=$mysql->echo_one("select email from ppc_users where uid=$uid");
//
// if($script_mode!="demo")
// {
//
//include("class.Email.php");
//
//$message = new Email($email, $admin_general_notification_email, $subject, '');
//$message->Cc = ''; 
//$message->Bcc = ''; 
//
//$message->SetHtmlContent(nl2br($text));  
//$message->Send();
//
//	}



if($adcount!=0)
{


 unlink("../ad_logos/".$row[1]);
mysql_query("delete from ad_logos_details where id=$lid");
}


//mysql_query("delete from ad_logo_details where id=$lid ");
header("Location:manage_logos.php");
exit;
/*echo "Status of AdLogo changed.";?>
</span><a href="javascript:history.back(-1);"><strong>Go Back</strong></a>*/


//header("location: $url");
//exit(0);
?>
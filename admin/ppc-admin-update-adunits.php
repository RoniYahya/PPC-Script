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

$id=$_POST['id'];
phpSafe($id);	
$adunit_name=$_POST['adunit_name'];
$adlang=$_POST['language'];

$adult_status=$_POST['adult_status'];


phpSafe($adlang);	
phpSafe($adunit_name);	
phpSafe($adult_status);

//echo $adunit_name;
	if($script_mode=="demo")
	{ 
		if($id==27 )
		{
		include_once("admin.header.inc.php");
		echo "<br><span class=\"already\">You cannot edit this ad unit in demo. <a href=\"javascript:history.back(-1)\">Go Back</a></span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
		}
	}

$credit_border_color=$_POST['uc'];
if(trim($credit_border_color)=="" || trim($credit_border_color)=="{credit_border_color}" ) //fix for some erroneous blocks
{
	$res1=mysql_query("select pad.*,cad.* from ppc_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$id'");
	$res=mysql_fetch_array($res1);
	$credit_border_color=$res['credit_text_border_color'];//from parent
}


$tc=$_POST['color1'];
	if($tc=="")
		{
		$tc="#009933";
		}

$dc=$_POST['color2'];
	if($dc=="")
		{
		$dc="#FFFFFF";
		}
$gc=$_POST['color3'];
	if($gc=="")
		{
		$gc="#0F0F0F";
		}
$bc=$_POST['color4'];
	if($bc=="")
		{
		$bc="#000099";
		}

$border_type=$_POST['border_type'];


//$credit_text=$_POST['credit_text'];
$credit_text=$_POST['cc'];


$cr_type=0;
$cr_type=$mysql->echo_one("select credittype from ppc_publisher_credits where id='$credit_text'");
if($border_type == 0 && $cr_type ==1)
$border_type =1;






//sticky ad


if(!isset($_POST['ad_type_sticky']))
	{
	$ad_type_sticky=0;
	}
else
	{
	$ad_type_sticky=$_POST['ad_type_sticky'];
	}
if(!isset($_POST['sticky_ad_pos']))
	{
	$sticky_ad_pos=0;
	}
else
	{
	$sticky_ad_pos=$_POST['sticky_ad_pos'];
	}

if(!isset($_POST['scroll_ad']))
	{
	$scroll_ad=0;
	}
else
	{
	$scroll_ad=$_POST['scroll_ad'];
	}
	$ad_type_sticky=$_POST['ad_type_sticky'];
	if($ad_type_sticky==2)
	{
	
	$sticky_ad_pos=10;
	}
//	print_r($_POST);
$users_resolution1=$_POST['users_resolution1'];
//echo $users_resolution."sdsdf";
//exit(0);
$users_resolution2=$_POST['users_resolution2'];	
	
/*
if(!isset($_REQUEST['sticky_ad_alignment']))
	{
	$sticky_ad_alignment=0;
	}
else
	{
	$sticky_ad_alignment=$_REQUEST['sticky_ad_alignment'];
	}
*/
//sticky ad
//print_r($_POST);
//echo "credit_border_color=$credit_border_color;bc=$bc;gc=$gc;tc=$tc;dc=$dc;id=$id;border_type=$border_type";
mysql_query("update `ppc_custom_ad_block` set name='$adunit_name', title_color='$tc', desc_color='$dc', url_color='$gc', bg_color='$bc',credit_color='$credit_border_color', bordor_type='$border_type',credit_text='$credit_text',scroll_ad=$scroll_ad,adlang='$adlang',adult_status='$adult_status' where id=$id;");
//echo "update `ppc_custom_ad_block` set name='$adunit_name', title_color='$tc', desc_color='$dc', url_color='$gc', bg_color='$bc',credit_color='$credit_border_color', bordor_type='$border_type',credit_text=$credit_text where id=$id;";

$ini_error_status=ini_get('error_reporting');
if($ini_error_status!=0)
{
	echo mysql_error();
	}
	
	
$inid=$mysql->echo_one("select bid from ppc_custom_ad_block where id=$id;");
$intype=$mysql->echo_one("select ad_type from ppc_ad_block where id='$inid'");

if($intype!=7 && $intype!=6 )
{
header("location:ppc-admin-modify-ad-unit.php?id=$id&ad_type_sticky=$ad_type_sticky&ad_pos=$sticky_ad_pos&users_resolution1=$users_resolution1&users_resolution2=$users_resolution2");

//header("location:ppc-admin-modify-ad-unit.php?id=$id");
exit(0);
}

else
{
header("location:ppc-admin-modify-inline-ad-unit.php?id=$id");
//header("location:ppc-admin-modify-peel-ad-unit.php?id=$id");
exit(0);

}
?>
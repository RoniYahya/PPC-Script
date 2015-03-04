<?php
//print_r($_POST);
include("config.inc.php");
if(!isset($_COOKIE['inout_admin']))
{
	header("Location: index.php");
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location: index.php");
	exit(0);
}

	$wap_status=trim($_POST['device']);	
if($wap_status==0)
{
	$table="ppc_ad_block";
	$wap_st="";
	$id=$_POST['select_ad_block'];
}
else
{
	$table="wap_ad_block";
	$wap_st="and wapstatus='1'";
		$id=$_POST['select_ad_block1'];
}


$adtype=$mysql->echo_one("select ad_type from $table where id=$id");

$temp=$_POST['tracking_name'];
$adlang=$_POST['language'];
$adult_status=$_POST['adult_status'];

if($_POST['tracking_name'])
	{
	$tracking_name=trim($_POST['tracking_name']);	
	
	}
else{
	 $t=time();
$tracking_name="AdUnit_".date("M_j_Y_g_i_s_a",$t);
	}
	$adlang=trim($_POST['language']);	

	phpSafe($wap_status);
phpSafe($adlang);	
phpSafe($tracking_name);

$result=mysql_query("select * from $table where id=$id");
$as=mysql_fetch_row($result);


$res=mysql_query("insert into ppc_custom_ad_block (`pid`,`bid`,`name`,`title_color`,`desc_color`,`url_color`,`bg_color`,`credit_color`,`bordor_type`,`status`,`credit_text`,`wapstatus`,`adlang`,`adult_status`) values (0, '$id' , '$tracking_name',' $as[8]', '$as[11]', '$as[14]', '$as[15]', '$as[17]', '$as[22]',1,'$as[33]',$wap_status,'$adlang','$adult_status');");	
$re1=mysql_query("select max(id) from ppc_custom_ad_block where pid='0' $wap_st");
$re=mysql_fetch_row($re1);
$customid=$re[0];
//echo "<br>tracking name: $tracking_name";
//
if($adtype!=7 && $adtype!=6)
{
	if($wap_status==0)
	{
header("location: ppc-admin-modify-ad-unit.php?id=$customid");
exit(0);
	}
	else
	{
		header("location:ppc-admin-modify-wap-ad-unit.php?id=$customid");
exit(0);
	}
}
else
{
header("location: ppc-admin-modify-inline-ad-unit.php?id=$customid");
exit(0);

}
?> 
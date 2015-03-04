<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
//include_once("messages.".$client_language.".inc.php");
$adlang=$_POST['language'];
if($adlang=="")
{
	$adlang=0;
}
else
{
	$adlang=$_POST['language'];
}
//if($adlang==0)
//{
//$lang_code=$client_language;
//}
//else
//{
//$lang_code=$mysql->echo_one("select code from adserver_languages where id='$adlang'");
//}
//include("messages.".$lang_code.".inc.php");

if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();

if(isset($_POST['wapadd']))
$id=getSafePositiveInteger('wapadd','p');

$adtype=$mysql->echo_one("select ad_type from wap_ad_block where id=$id");


if(isset($_POST['name'])&&trim($_POST['name'])!="")
$name=$_POST['name'];
else
{
	$t=time();
	$name="AdUnit_".date("M_j_Y_g_i_s_a",$t);
	
}

if($site_targeting==1) 
{

if(isset($_POST['site']))
$siteid=getSafePositiveInteger('site','p');

}

//print_r($_POST);
phpSafe($adlang);
phpSafe($name);
$aa=mysql_query("select title_color,desc_color,url_color,bg_color,credit_text_border_color,border_type,credit_text from wap_ad_block  where id='$id' and status='1' and allow_publishers='1'");
$as=mysql_fetch_row($aa);
$res=mysql_query("insert into ppc_custom_ad_block values('0','$uid','$id','$name','$as[0]','$as[1]','$as[2]','$as[3]','$as[4]','$as[5]','1','','$as[6]',0,'1','$adlang',0,-1)");	
$re1=mysql_query("select max(id) from ppc_custom_ad_block where pid='$uid' and wapstatus='1'");
$re=mysql_fetch_row($re1);
$customid=$re[0];
if($site_targeting==1) 
{

mysql_query("insert into ppc_site_adunit values('0','$customid','$siteid')"); 

}
if($adtype!=7 && $adtype!=6)
{
header("location:ppc-edit-wap-ad-unit.php?customid=$customid");
}
else
{
header("location:ppc-edit-inline-adblock.php?customid=$customid");
}
?> 
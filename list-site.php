<?php
ob_start();
//sleep(1);
include("extended-config.inc.php");
include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
//include_once("messages.".$client_language.".inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");



$user=new User("ppc_publishers");

if(!($user->validateUser()))
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

//includeClass("Template");

$template=new Template();
$template->loadTemplate("publisher-templates/list-site.tpl.html");

$kid=0;
$id=0;
$type=1;//listing



$uid=$user->getUserID();




if(isset($_GET['site']))
	{
	$site=$_GET['site']; 
	
	phpSafeUrl($site);
	$site=RemoveCharacters($site);
	
	//echo "select * from ppc_publishing_urls where pid=$uid and url='".$site."'";
	$result1 = mysql_query("select * from ppc_publishing_urls where pid=$uid and url='".$site."'");
	$num1=mysql_num_rows($result1);
	if(!isDomain($site))
		{
			$template->setValue("{ETYPE1}",3);
		}
	else if($num1>0)
		{
		$template->setValue("{ETYPE1}",1);
	    }
	else
	{
	

   
	$time= time();
	
	mysql_query("INSERT INTO `ppc_publishing_urls` (`id`, `pid`, `url`, `status`, `description`, `create_time`, `twt_ac`, `twt_ac_no`, `face_ac`, `face_ac_no`, `page_link`, `feed`, `feed_no`, `rank`) VALUES ('0', '$uid', '$site', '-1', '0', $time, '0', '0', '0', '0', '0', '0', '0', '0')");
	}
}




$selected_colorcode=array();
foreach ($color_code as $key=>$value)
{
	if($key==$color_theme)
	{
		$selected_colorcode=$value;
		break;
	}
}
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
/*if($type!=3)
 {
 $output_str="<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">  ";
 }
 else
 {
 $output_str="";
 }*/



//echo "jjjj"; exit;
//echo "select * from ppc_publishing_urls where pid=$uid order by create_time desc";
	$result = mysql_query("select * from ppc_publishing_urls where pid=$uid order by create_time DESC");
	$num=mysql_num_rows($result);
	if($num==0)
    {
	 $template->setValue("{ETYPE}",2);
	}
	$template->setValue("{NUM}",$num);
	//$output_str.="<table width='100%'  border='0' align='left' cellpadding='0' cellspacing='0'>";
	$template->setValue("{MESSAGESS}","");
	if($num>0)
	{
		$url="ppc-new-publishing-url.php";
		$template->setValue("{URL}",$url);
		//$output_str.= "<tr>
		//    <td colspan='4'>&nbsp;</td>
		//  </tr>";
		//echo "select * from ppc_publishing_urls where pid=$uid  order by create_time desc";
		$template->openLoop("SITE","select * from ppc_publishing_urls where pid=$uid  order by create_time DESC");
		$template->setLoopField("{LOOP(SITE)-ID}","id");
		$template->setLoopField("{LOOP(SITE)-URL}","url");
		$template->setLoopField("{LOOP(SITE)-STATUS}","status");
		$template->closeLoop();
	}
	

// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
//echo $output_str;
//echo $template->getPage();die;
eval('?>'.$template->getPage().'<?php ');

?>
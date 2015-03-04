<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("common-templates/ppc-support-desk.tpl.html");
$str="";
$user=new User();
$type=$_COOKIE['io_type'];

//if($type=="")
//{
//header("Location:show-message.php?id=1006");
//exit(0);
//}

if($type==md5("publisher"))
{

		$user=new User("ppc_publishers");
		$userid=$user->getUserID();
		//$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where uid=$userid");
		if(!($user->validateUser()))
		{
		
			header("Location:publisher-show-message.php?id=1006");
			exit(0);
		}

			$template->includePage("{TABS}","publisher-loggedin-header.php");

}
if($type==md5("advertiser"))
{
	
	$user=new User("ppc_users");
$userid=$user->getUserID();
		//$commonid=$mysql->echo_one("select common_account_id from ppc_users where uid=$userid");
	if(!($user->validateUser()))
	{
		header("Location:show-message.php?id=1006");
		exit(0);
	}

			$template->includePage("{TABS}","advertiser-loggedin-header.php");	

}
if($type==md5("Common"))
{
		$user=new User("nesote_inoutscripts_users","id");
		//$commonid=$user->getUserID();
if(!($user->validateUser()))
	{
		header("Location:show-message.php?id=1006");
		exit(0);
	}
		$template->includePage("{TABS}","common-loggedin-header.php");
}
$template->includePage("{FOOTER}","common-footer.php");

$form=new Form("newTicket","ppc-support-desk-action.php".$str);

$form->isNotNull("subject",$template->checkmsg(6029));
$form->isNotNull("body",$template->checkmsg(6030));

$template->setValue("{COMID}",$commonid);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkComMsg(3012)));
$template->setValue("{HIDDENNAME}",$form->addHiddenField("name",$user->getUsername()));
$template->setValue("{HIDDENEMAILID}",$form->addHiddenField("email",$user->getEmailID()));


//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkComMsg(0001));                                              
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$emess50=$template->checkComMsg(3011);
$emess150=str_replace("{ENAME}",$template->checkComMsg(0001),$emess50);
$template->setValue("{EMESS51}",$emess150);

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>

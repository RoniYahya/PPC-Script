<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
include("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("ppc-templates/ppc-edit-logo.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$user=new User("ppc_users");

if(!($user->validateUser()))
{

header("Location:show-message.php?id=1006");
exit(0);
}

if($portal_system==1)
{
	//redirect  this page to portal corrensponding page
}
$id=$_GET['id'];

$user=new User("nesote_inoutscripts_users", "id");
if($user->getUsername()=="demouser" && $script_mode=="demo")
{
header("Location:error-message.php?id=6076");
exit(0);
}

$res=mysql_query("select id,name,ad_logos_name from ad_logos_details where id=$id and user_status='2'");
$row=mysql_fetch_row($res);
   $template->setValue("{ID}","$row[0]"); 
   $template->setValue("{NAME}","$row[1]"); 
   $template->setValue("{IMAGE}","$row[2]");
  $template->setValue("{PATH}",$GLOBALS['ad_logos_folder']);
   
$form=new Form("ppcNewAd","ppc-edit-logo-action.php");
$form->isNotNull("name","Logo name cannot be null.");



$sub="<input name=\"submit\" id=\"submit\" type=\"submit\"  value=\"Submit\">";

$template->setValue("{SUBMIT}",$sub);

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));  
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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>
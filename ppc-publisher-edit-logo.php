<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("publisher-templates/ppc-publisher-edit-logo.tpl.html");
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_publishers");
$pubid=$user->getUserID();
 $id=$_GET['id'];


if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}

$res=mysql_query("select id,name,ad_logos_name from ad_logos_details where id=$id and user_status='1' and cid=$pubid");
$row=mysql_fetch_row($res);
   $template->setValue("{ID}","$row[0]"); 
   $template->setValue("{NAME}","$row[1]"); 
   $template->setValue("{IMAGE}","$row[2]");
  $template->setValue("{PATH}",$GLOBALS['ad_logos_folder']);
   
$form=new Form("ppcNewAd","ppc-publisher-edit-logo-action.php");
$form->isNotNull("name","Logo name cannot be null.");



$sub="<input name=\"submit\" id=\"submit\" type=\"submit\"  value=\"Submit\">";

$template->setValue("{SUBMIT}",$sub);

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));    
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
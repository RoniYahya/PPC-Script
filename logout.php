<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Template");
if($_COOKIE['io_type']==md5("Common"))
{
$user=new User("nesote_inoutscripts_users","id");
$user->logOut();
}
if($_COOKIE['io_type']==md5("advertiser"))
{
$user=new User("ppc_users");
$user->logOut();
}
if($_COOKIE['io_type']==md5("publisher"))
{
$user=new User("ppc_publishers");
$user->logOut();
}

$template=new Template();
$template->loadTemplate("common-templates/logout.tpl.html");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");

//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
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
$edtmess=$template->checkComMsg(1074);
$edtmess1=str_replace("{ENAME}",$template->checkComMsg(0001),$edtmess);
$template->setValue("{EDTMESS}",$edtmess1);

$edtmess5=$template->checkComMsg(1075);
$edtmess15=str_replace("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"",$edtmess5);
$template->setValue("{EDTMESS5}",$edtmess15);
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');
?>
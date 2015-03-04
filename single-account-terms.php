<?php 
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
includeClass("Template");
$template=new Template();


$template->loadTemplate("common-templates/single-account-terms.tpl.html");
$template->includePage("{FOOTER}","common-footer.php");
if($single_account_mode!=1)
{
	header('location: index.php');
}
$ty=$_GET['type'];
$lang_code=$client_language;
if($client_language=='')
$lang_code='en';
include_once("locale/common-template-".$lang_code."-inc.php");
if($ty==1)
{
$ty=1;
$template->setValue("{NAME}","Publisher");


}
elseif($ty==0)
{
$ty=0;
$template->setValue("{NAME}","Advertiser");



}
else
$ty=0;
$ename=$template->checkComMsg(0001);
$aa=str_replace("{ENGINE_NAME}",$common_message[0001],$mysql->echo_one("select terms from terms_and_conditions where type=$ty"));
$aa=str_replace("{ADV_LOGIN_PATH}",$server_dir."ppc-user-login.php",$aa);
$template->setValue("{TERMS}",nl2br($aa)); 


$template->setValue("{ENGINE_NAME}",$common_message[0001]);                                                     
//$template->setValue("{ADV_LOGIN_PATH}",$server_dir."ppc-user-login.php");    

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


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);         
eval('?>'.$template->getPage().'<?php ');

?>
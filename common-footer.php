<?php
global $color_code;
global $color_theme;
global $ppc_engine_name;
global $affiliate_id;
global $page_width;

$tmp=new Template();
$tmp->loadTemplate("common-templates/common-footer.tpl.html");
$tmp->setValue("{ENGINE_NAME}",$tmp->checkComMsg(0001));

$tmp->setValue("{HOME}","index.php");
$tmp->setValue("{AD}","ppc-user-login.php");
$tmp->setValue("{PB}","ppc-publisher-control-panel.php");
$tmp->setValue("{CON}","ppc-contact-us.php");

$tmp->setValue("{YEAR}",date("Y"));

if($affiliate_id=="")
{
	$server=$_SERVER['HTTP_HOST'];
	if(substr($server,0,4)=="www.")
		$server=substr($server,4);
		
//	$tmp->setValue("{AFFILIATE}",$server);

	$editmess3=str_replace("{AFFILIATE}",$server,$tmp->checkComMsg(1057));
    $tmp->setValue("{EDITMESS1}",$editmess3);

		
}
else
{
//	$tmp->setValue("{AFFILIATE}",);
	
	$editmess3=str_replace("{AFFILIATE}",$affiliate_id,$tmp->checkComMsg(1057));
    $tmp->setValue("{EDITMESS1}",$editmess3);
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
$editmess=$tmp->checkComMsg(1071);
$editmess1=str_replace("{YEAR1}",date("Y"),$editmess);
$editmess2=str_replace("{ENAME}",$tmp->checkComMsg(0001),$editmess1);
$tmp->setValue("{EDITMESS}",$editmess2);


$editmsg18=$template->checkComMsg(1122);
$editmsg19=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg18);
$tmp->setValue("{EDTMSG6}",$editmsg19);
$editmsg108=$template->checkComMsg(1123);
$editmsg109=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg108);
$tmp->setValue("{EDTMSG7}",$editmsg109);


$tmp->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$tmp->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$tmp->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

$tmp->setValue("{PAGEWIDTH}",$page_width); 

eval('?>'.$tmp->getPage().'<?php ');
?>
<?php

include("extended-config.inc.php");  

include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
$template=new Template();

$template->loadTemplate("publisher-templates/ppc-my-adunits.tpl.html");
//include_once("messages.".$client_language.".inc.php");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}


$url=urlencode($_SERVER['REQUEST_URI']);


$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$uid=$user->getUserID();
$device=trim($_POST['device']);
phpSafe($device);
if(!isset($_POST['device']))
$device=0;



		$device=intval($_POST['device']);
		if($device==1)
			{
				$wap_str="and  wapstatus=1";
				$wap_str1="and a.wapstatus=1";
				$tab_str="wap_ad_block";
				$red_file="ppc-edit-wap-ad-unit.php";
				$template->setValue("{PAGEDIR}","ppc-edit-wap-ad-unit.php");
			}
			else
			{
				$wap_str="";
                $wap_str1="";
                $tab_str="ppc_ad_block";
                 $red_file="ppc-edit-adblock.php";
                 $template->setValue("{PAGEDIR}","ppc-edit-adblock.php");
			}
					
	
$pageno=1;

if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;

//echo "select count(*) from ppc_custom_ad_block a,$tab_str b where a.bid=b.id and a.pid='$uid' and a.status='1' $wap_str1 order by a.id DESC";
$total=$mysql->echo_one("select count(*) from ppc_custom_ad_block a,$tab_str b where a.bid=b.id and a.pid='$uid' and a.status='1' $wap_str1 order by a.id DESC");

$p=$paging->page($total,$perpagesize,"","ppc-my-adunits.php");

$template->setValue("{HEADER}","$p");
$string="";
$beg=(($pageno-1)*$perpagesize+1);
//$end=$pageno*$perpagesize;

if($total>=1) 
{

 if(($pageno*$perpagesize)<=$total) 
 {
// $string.=$template->checkPubMsg(6053)."<span class=\"inserted\">".$pageno*$perpagesize."</span>".$template->checkPubMsg(6054)."<span class=\"inserted\">".$total."</span>"; 
 $end=$pageno*$perpagesize;
 }
  else 
  {
 //$string.=$template->checkPubMsg(6053)."<span class=\"inserted\">".$total."</span>".$template->checkPubMsg(6054)."<span class=\"inserted\">".$total."</span>"; 
  $end=$total;
  }
}

$template->setValue("{REDFILE}",$red_file);
$combined_msg=$template->checkPubMsg(8934);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);

$template->setValue("{SHOW_STATISTICS}",$msg_replace2);

$template->openLoop("ADS","select a.id ,a.name,b.width,b.height,b.ad_type,b.ad_block_name from ppc_custom_ad_block a,$tab_str b where a.bid=b.id and a.pid='$uid' and a.status='1' $wap_str1 order by a.id DESC  LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
//echo "select a.id ,a.name,b.width,b.height,b.ad_type,b.ad_block_name from ppc_custom_ad_block a,$tab_str b where a.bid=b.id and a.pid='$uid' and a.status='1' $wap_str1 order by a.id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;

$template->setLoopField("{LOOP(ADS)-ID}","id");
$template->setLoopField("{LOOP(ADS)-NAME}","name");
$template->setLoopField("{LOOP(ADS)-WIDTH}","width");
$template->setLoopField("{LOOP(ADS)-HEIGHT}","height");
$template->setLoopField("{LOOP(ADS)-TYPE}","ad_type");
$template->setLoopField("{LOOP(ADS)-PARENT_NAME}","ad_block_name");
$template->closeLoop();

$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001));  

$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8941));
$template->setValue("{ENGINE_TITLE}",$engine_title);
   
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
$template->setValue("{URL}",$url);
$template->setValue("{MSG}",$template->checkPubMsg(6070));
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width); 
$template->setValue("{ENCODING}",$ad_display_char_encoding);     //
eval('?>'.$template->getPage().'<?php ');

?>

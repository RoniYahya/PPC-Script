<?php
/*--------------------------------------------------+
 |													 |
 | Copyright � 2006 http://www.inoutscripts.com/  
 | All Rights Reserved.								 |
 | Email: contact@inoutscripts.com                    |
 |                                                    |
 +---------------------------------------------------*/
?><?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("Form");
includeClass("User");
//include_once("messages.".$client_language.".inc.php");
$template=new Template();

$template->loadTemplate("ppc-templates/ppc-find-ad.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
$template->setValue("{CURR}",$currency_symbol);



$form=new Form("Find","ppc-find-ad.php");
$user=new User("ppc_users");
$uid=$user->getUserID();
if(!($user->validateUser()))
{
header("Location:show-message.php?id=1006");
exit(0);
}
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(7021)));
$total=$mysql->echo_one("select count(*) from ppc_ads where uid='$uid'");

if(isset($_POST['id'])|| isset($_GET['id']))
{
	$template->setValue("{DIS}","1");
	$id=getSafePositiveInteger('id');//$_REQUEST['id'];
	$template->setValue("{ID}",$id);
	$adtype=$mysql->echo_one("select adtype from ppc_ads where id='$id'");
	$wap_flag=$mysql->echo_one("select wapstatus from ppc_ads where id='$id'");
	
	
	         if($wap_flag==1)
              {
              	$wap_string='and wapstatus=1';
              	$name='Wap';
              	
              }
              else
              {
              	$wap_string='and wapstatus=0';
              	$name='';
              }
              
              $template->setValue("{WAP_FLAG}",$wap_flag); 
              $template->setValue("{WAP_NAME}",$name); 
 
// $budget_period (monthly ,daily)

loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];

if($budget_period==1)
{
	$x=strftime("%B",time());
	$PERIOD=$template->checkAdvMsg(6090)." ".$x;
	//$template->setValue("{PERIOD}",$PERIOD);
	
}
else if($budget_period==2)
{
	$PERIOD=$template->checkAdvMsg(7041);
	//$template->setValue("{PERIOD}",$PERIOD);
}
	
	if($adtype==0)
	{
		
		$template->setValue("{TITLE}",$mysql->echo_one("select title from ppc_ads where id='$id'"));
		$template->setValue("{URL}",$mysql->echo_one("select link from ppc_ads where id='$id'"));
		$template->setValue("{DURL}",$mysql->echo_one("select displayurl from ppc_ads where id='$id'"));
		$template->setValue("{SUMMARY}",$mysql->echo_one("select summary from ppc_ads where id='$id'"));
		$template->setValue("{AMOUNTUSED}",$mysql->echo_one("select amountused from ppc_ads where id='$id'"));
		$template->setValue("{STATUS}",$mysql->echo_one("select status from ppc_ads where id='$id'"));
		$template->setValue("{PSTATUS}",$mysql->echo_one("select pausestatus from ppc_ads where id='$id'"));
		$template->setValue("{SUMARY}","0");
		
	}
	else if($adtype==2)
	{
		
		$template->setValue("{TITLE}",$mysql->echo_one("select title from ppc_ads where id='$id'"));
		$template->setValue("{URL}",$mysql->echo_one("select link from ppc_ads where id='$id'"));
		$template->setValue("{DURL}",$mysql->echo_one("select displayurl from ppc_ads where id='$id'"));
		$template->setValue("{SUMMARY}",$mysql->echo_one("select summary from ppc_ads where id='$id'"));
		$template->setValue("{AMOUNTUSED}",$mysql->echo_one("select amountused from ppc_ads where id='$id'"));
		$template->setValue("{STATUS}",$mysql->echo_one("select status from ppc_ads where id='$id'"));
		$template->setValue("{PSTATUS}",$mysql->echo_one("select pausestatus from ppc_ads where id='$id'"));
		$bsize=$mysql->echo_one("select bannersize from ppc_ads where id='$id'");
		$template->setValue("{BSIZE}",$bsize);
		$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$bsize'");
		$template->setValue("{CAT_WT}",$catalog_width);
		$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$bsize'"); 
		$template->setValue("{CAT_HT}",$catalog_height);
		
		
		
		
		
		
		$template->setValue("{CONTENTTYPE}",$mysql->echo_one("select contenttype from ppc_ads where id='$id'"));
		
	
		$cnt_tp=$mysql->echo_one("select contenttype from ppc_ads where id='$id'");
		$hd_links=$mysql->echo_one("select hardcodelinks from ppc_ads where id='$id'");
		
		if($cnt_tp =="swf")
		{
		
	
		
      
		
		$template->setValue("{RESHT}",$catalog_height);
        $template->setValue("{RESWT}",$catalog_width);
		
		$lnks=$mysql->echo_one("select link from ppc_ads where id='$id'");
		
		$strHardLinks="";

if($hd_links >0)
{
for($i=0;$i<$hd_links;$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$lnks."';";
$strHardLinks.="flashvars.atar".($i+1)."='_blank';";
}
$template->setValue("{HARDLINKS}",$strHardLinks);
}
else
{
$template->setValue("{HARDLINKS}",$strHardLinks);
}
		
		
		
		
		
		
		
		}
		else
		{
		$template->setValue("{CONTENTTYPE}","");
		$template->setValue("{HARDLINKS}","");
		$template->setValue("{RESHT}","");
        $template->setValue("{RESWT}","");
		
		
		}
		
		
		
		
		
		
		
		
		

		$template->setValue("{SUMARY}","2");
	}
	else
	{
		$template->setValue("{TITLE}",$mysql->echo_one("select title from ppc_ads where id='$id'"));
		$template->setValue("{URL}",$mysql->echo_one("select link from ppc_ads where id='$id'"));
		$template->setValue("{SUMMARY}",$mysql->echo_one("select summary from ppc_ads where id='$id'"));
		$template->setValue("{AMOUNTUSED}",$mysql->echo_one("select amountused from ppc_ads where id='$id'"));
		$template->setValue("{STATUS}",$mysql->echo_one("select status from ppc_ads where id='$id'"));
		$template->setValue("{PSTATUS}",$mysql->echo_one("select pausestatus from ppc_ads where id='$id'"));
		
		
		$template->setValue("{CONTENTTYPE}",$mysql->echo_one("select contenttype from ppc_ads where id='$id'"));
		
	
		$cnt_tp=$mysql->echo_one("select contenttype from ppc_ads where id='$id'");
		$hd_links=$mysql->echo_one("select hardcodelinks from ppc_ads where id='$id'");
		
		if($cnt_tp =="swf")
		{
		
		$brsz=$mysql->echo_one("select bannersize from ppc_ads where id='$id'");
		$resht=$mysql->echo_one("select height from banner_dimension where id='$brsz'");
        $reswt=$mysql->echo_one("select width from banner_dimension where id='$brsz'");
		
		$template->setValue("{RESHT}",$resht);
        $template->setValue("{RESWT}",$reswt);
		
		$lnks=$mysql->echo_one("select link from ppc_ads where id='$id'");
		
		$strHardLinks="";

if($hd_links >0)
{
for($i=0;$i<$hd_links;$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$lnks."';";
$strHardLinks.="flashvars.atar".($i+1)."='_blank';";
}
$template->setValue("{HARDLINKS}",$strHardLinks);
}
else
{
$template->setValue("{HARDLINKS}",$strHardLinks);
}
		
		
		
		
		
		
		
		}
		else
		{
		$template->setValue("{CONTENTTYPE}","");
		$template->setValue("{HARDLINKS}","");
		$template->setValue("{RESHT}","");
        $template->setValue("{RESWT}","");
		
		
		}
		
		
		$template->setValue("{SUMARY}","1");	
		
	}
	$url=urlencode("ppc-find-ad.php?id=$id");
}
else
{
	$template->setValue("{DIS}","0");
	$template->setValue("{ID}","0");
	$template->setValue("{STATUS}","0");
	$template->setValue("{PSTATUS}","0");
	$template->setValue("{SUMARY}","0");
	$url=urlencode("ppc-find-ad.php");
}
$amt_use=$mysql->echo_one("select amountused from ppc_ads where id='$id'");
$amt_used=moneyFormat($amt_use);
$edit_mes=$template->checkAdvMsg(8954);
$edit_mes1=str_replace("{AMOUNT1}",$amt_used,$edit_mes);
$edit_mes2=str_replace("{PERIOD1}",$PERIOD,$edit_mes1);
	$template->setValue("{EDITMESSAGE1}",$edit_mes2);
$template->openLoop("ADS","select id from ppc_ads where uid='$uid' order by id");
$template->setLoopField("{LOOP(ADS)-ID}","id");
$template->closeLoop();
$template->setValue("{MONTH}",strftime("%B",time()));
$template->setValue("{REDIRECTURL}",$url); 
 
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

$template->setValue("{BANNERS}",$GLOBALS['banners_folder']);
$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     

eval('?>'.$template->getPage().'<?php ');

?>
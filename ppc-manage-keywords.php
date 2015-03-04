<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");
$template=new Template();
$template->loadTemplate("ppc-templates/ppc-manage-keywords.tpl.html");
//include_once("messages.".$client_language.".inc.php");
if($ad_keyword_mode==2){ 
header("Location:manage-ads.php");
exit(0);
}




$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
 

$user=new User("ppc_users");
$uid=$user->getUserID();
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}


$form=new Form("NewKeywords","");
$id=$_GET['id'];
if(!myAd($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}

$form->isPositive("maxcv1",$template->checkmsg(6025));


$form->isOverMin("maxcv1",$min_click_value,$template->checkmsg(6028));

$form->isNotNull("kwd1",$template->checkmsg(6026));


$pass=false;
if(isset($_GET['pass']))
$pass="true";
else
	$pass="false";
	
	
	///////////////////wap

if(isset($_GET['wap']))
{
$wap_flag=$_GET['wap'];
}
else 
{
	$wap_flag=0;
}
phpsafe($wap_flag);


//$wap_flag=0;
if($wap_flag==1)
{
	$wap_string=' and wapstatus=1 ';
	$wap_name='Wap';
	
	
	$title_length=$GLOBALS['wap_title_length'];
	$durl_length=$GLOBALS['wap_url_length'];
	$desc_length=$GLOBALS['wap_desc_length'];
	
}

else
{
	
	$wap_string='and wapstatus=0';
	$wap_name='';
    
    $title_length= $ad_title_maxlength;
	$durl_length= $ad_displayurl_maxlength;
	$desc_length=$ad_description_maxlength;
	
	
}
$search_keywords=$_REQUEST['kwd1'];
phpSafe($search_keywords);
//echo $search_keywords;
$template->setValue("{SEA_KEY}",$search_keywords);
$template->setValue("{WAP_FLAG}",$wap_flag);
$template->setValue("{WAP_NAME}",$wap_name);
$template->setValue("{MAXTITLE}",$title_length);
$template->setValue("{MAXDESC}",$desc_length);
$template->setValue("{MAXDURL}",$durl_length);

$template->setValue("{FORMSTART}",$form->formStart().$form->addHiddenField("wap",$wap_flag));
$template->setValue("{FORMCLOSE}",$form->formClose());

//////////////////////////////////////wap
	
$emess=$template->checkAdvMsg(8955);
$emess10=str_replace("{WAP1}",$wap_name,$emess);
$template->setValue("{EMESS}",$emess10);
$emess11=$template->checkAdvMsg(8956);
$emess110=str_replace("{WAP1}",$wap_name,$emess11);
$template->setValue("{EMESS1}",$emess110);
$template->setValue("{IDFIELD}",$form->addHiddenField("id",$id));
$template->setValue("{PASSFLD}",$form->addHiddenField("pass",$pass));
//$template->setValue("{KWD1}",$form->addTextArea("kwd1","","30","4"));
$template->setValue("{MAXCV1}",$form->addTextBox("maxcv1",$min_click_value,4,4));
$template->setValue("{PASS}",$pass);

$result=mysql_query("select title,link,summary,displayurl,status,amountused,pausestatus,adtype,bannersize,contenttype,hardcodelinks from ppc_ads where id='$id' $wap_string");
$row=mysql_fetch_row($result);
$template->setValue("{DURL}",$row[3]);
$template->setValue("{ID}",$id);

$template->setValue("{TITLE}",$row[0]);
$template->setValue("{URL}",$row[1]);
$template->setValue("{SUMMARY}",$row[2]);
$template->setValue("{STATUS}",$row[4]);
$template->setValue("{AMOUNTUSED}",$row[5]);
$template->setValue("{PSTATUS}","$row[6]");
$template->setValue("{ADTYPE}",$row[7]);
$template->setValue("{MIN_CLK_VAL}",$min_click_value);
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol);
$money_fmt=moneyFormat($row[5]);
loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];




//************************************************** Changes For Flash Ads *************************************

if(($row[7]==1 || $row[7]==2) && $row[9]=="swf")
{
$template->setValue("{CONTENTTYPE}",$row[9]);
$strHardLinks="";

if($row[10] >0)
{
for($i=0;$i<$row[10];$i++)
{
$strHardLinks.="flashvars.alink".($i+1)."='".$row[1]."';";
$strHardLinks.="flashvars.atar".($i+1)."='_blank';";
}
$template->setValue("{HARDLINKS}",$strHardLinks);
}
else
{
$template->setValue("{HARDLINKS}",$strHardLinks);
}

if($row[7]==1)
{
$resht=$mysql->echo_one("select height from banner_dimension where id='$row[8]'");
$reswt=$mysql->echo_one("select width from banner_dimension where id='$row[8]'");
}
else if($row[7]==2)
{

$resht=$mysql->echo_one("select height from catalog_dimension where id='$row[8]'");
$reswt=$mysql->echo_one("select width from catalog_dimension where id='$row[8]'");

}



$template->setValue("{RESHT}",$resht);
$template->setValue("{RESWT}",$reswt);
}
else
{
$template->setValue("{CONTENTTYPE}","");
$template->setValue("{HARDLINKS}","");
$template->setValue("{RESHT}","");
$template->setValue("{RESWT}","");

}
//************************************************** Changes For Flash Ads *************************************








if($budget_period==1)
{
	$x=strftime("%B",time());
	$PERIOD=$template->checkAdvMsg(6090)." ".$x;
	$template->setValue("{PERIOD}",$PERIOD);
	
}
else if($budget_period==2)
{
	$PERIOD=$template->checkAdvMsg(7041);
	$template->setValue("{PERIOD}",$PERIOD);
}
//$template->setValue("{url}",$url); 
$emess12=$template->checkAdvMsg(8954);
$emess102=str_replace("{AMOUNT1}",$money_fmt,$emess12);
$emess105=str_replace("{PERIOD1}",$PERIOD,$emess102);
$template->setValue("{EMESS105}",$emess105);
 $template->setValue("{BSIZE}",$row[8]);
//$template->setValue("{BKWD1}",$form->addHiddenField("bkwd1",));

$catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row[8]'");
 $template->setValue("{CAT_WT}",$catalog_width);
$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[8]'"); 
 $template->setValue("{CAT_HT}",$catalog_height);

$total=$mysql->echo_one("select count(*) from ppc_keywords where aid=$id and keyword!='$keywords_default'");


$template->setValue("{BUNAME}",$template->checkAdvMsg(7003));

//$template->setValue("{SUBMIT}","<input type=\"button\" class=\"submit\" name=\"button\" value=\"Add Keywords !\" onClick=\"return showKeywords(this.value)\" onblur=\"showtab()\">");
//$template->setValue("{SUBMIT}",$form->addButton($template->checkAdvMsg(7003),"return showKeywords(this.value)"));
$template->setValue("{CANCEL}",$form->addButton($template->checkAdvMsg(7030),"return cancelEdit(this.value)"));
//$template->setValue("{TABS}",$template->includePage($server_dir."ppc-page-header.php"));
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
$editurl="ppc-manage-target-locations.php?id=$id&wap=$wap_flag&pass=$pass";
$cdmess=$template->checkAdvMsg(8990);
$cdmess1=str_replace("{URL1}",$editurl,$cdmess);
$template->setValue("{CDMESS}",$cdmess1);
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$template->setValue("{BANNERS}",$GLOBALS['banners_folder']);

$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     
eval('?>'.$template->getPage().'<?php ');

?>
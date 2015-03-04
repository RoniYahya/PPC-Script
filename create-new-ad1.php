<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_users");

if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}


$userid=$user->getUserID();
$account=$mysql->echo_one("select accountbalance from ppc_users where uid=$userid");
$bonus=$mysql->echo_one("select bonusbalance from ppc_users where uid=$userid");
if($account<=0 && $bonus <=0)
{
header("Location:show-message.php?id=1023");
exit(0);
}

$template=new Template();

$template->loadTemplate("ppc-templates/create-new-ad.tpl.html");

$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");


$form1=new Form("ppcNewAd","ppc-new-ad-action.php");

$form1->isNotNull("language",$template->checkmsg(9999));
$form1->isNotNull("title",$template->checkmsg(6012));
$form1->isNotNull("summary",$template->checkmsg(6014));
$form1->isNotNull("displayurl",$template->checkmsg(6034));
$form1->isNotNull("url",$template->checkmsg(6013));
$form1->isNotNull("maxamount",$template->checkmsg(6015));
$form1->isPositive("maxamount",$template->checkmsg(6015));
if($ad_keyword_mode==2){
$form1->isNotNull("maxclkamount",$template->checkmsg(8005));
$form1->isPositive("maxclkamount",$template->checkmsg(8005));
$form1->isOverMin("maxclkamount",$min_click_value,$template->checkmsg(6028));
}

$sub="<input name=\"submit\"  type=\"submit\" onclick=\"return check()\" value=".$template->checkAdvMsg(8887).">";
$template->setValue("{SUBMIT}",$sub);
$template->setValue("{FORMSTART}",$form1->formStart());
$template->setValue("{FORMCLOSE}",$form1->formClose());

//************textad


	$title_length=$ad_title_maxlength;
	$durl_length=$ad_displayurl_maxlength;
	$desc_length=$ad_description_maxlength;



$urlp.="<select name=\"url1\" id=\"proto\"  dir='ltr'>";
			
				
		$urlp.="<option value=\"http\" selected>http://</option>";
		
		 	$urlp.="<option value=\"https\">https://</option>";
		
$urlp.="</select>";
$template->setValue("{URLP}",$urlp);	

loadsettings("ppc_new");
 $client_lan=$_COOKIE['language'];
if($client_lan=="")
{
	$client_lan=$mysql->echo_one("select id from  adserver_languages where code='".$client_language."'");
}
else
{
	$client_lan=$_COOKIE['language'];
}

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");
//		echo mysql_num_rows($res);
		$ctrstr.="<select name=\"language\" id=\"language\"  dir='ltr'>";
			
				
				while($row=mysql_fetch_row($res))
	{
		 if($client_lan==$row[0])
		 {
		$ctrstr.="<option value=\"$row[0]\" selected=selected>$row[1]</option>";
		 }
		 else
		 {
		 	
		 	$ctrstr.="<option value=\"$row[0]\" >$row[1]</option>";
		 }
		
	}
	$ctrstr.="<option value=\"0\" >Any Languages</option>";
$ctrstr.="</select>";
				
	$template->setValue("{LANGUAGE}",$ctrstr);		
	
if($ad_keyword_mode==2){	
 //29-10-2009
 $template->setValue("{MAXCLKAMOUNT}",$form1->addTextBox("maxclkamount",$min_click_value,"5",5));
 $suggested_clk_val= getSuggestedValue($keywords_default,0,$min_click_value,$revenue_booster,$revenue_boost_level);

 //echo $suggested_clk_val;
 $sugclick_msg=$template->checkAdvMsg(8919);
 $sug_msg=str_replace("{SUGGVALUE}",$suggested_clk_val,$sugclick_msg);
  $template->setValue("{SUGVAL1}",$sug_msg);
 //29-10-2009
 }
 if($budget_period==1)
{
	$template->setValue("{BUDGET}",$template->checkAdvMsg(7037));
	$template->setValue("{BUDGET_UNIT}",$template->checkAdvMsg(7039));
	$budget_mess=$template->checkAdvMsg(7039);
}
else if($budget_period==2)
{
	$template->setValue("{BUDGET}",$template->checkAdvMsg(7038));
	$template->setValue("{BUDGET_UNIT}",$template->checkAdvMsg(7040));
	$budget_mess=$template->checkAdvMsg(7040);
}
$sd_mess=$template->checkAdvMsg(8961);
$sd_mess1=str_replace("{BUDGET1}",$budget_mess,$sd_mess);
$template->setValue("{SDMESS}",$sd_mess1);
 $template->setValue("{MAXTITLE}",$title_length);
$template->setValue("{MAXDESC}",$desc_length);

$template->setValue("{MAXDURL}",$durl_length);
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{TITLEFIELD}",$form1->addTextBox("title","",40,$title_length));
$template->setValue("{URL}",$form1->addTextBox("url","",51,500)); // DB should be able to accept till 500
$template->setValue("{DISPLAYURL}",$form1->addTextBox("displayurl","",60,$durl_length)); // DB should be able to accept till 500
$template->setValue("{SUMMARY}",$form1->addTextbox("summary","",60,$desc_length));
$template->setValue("{MAXAMOUNT}",$form1->addTextBox("maxamount",$default_admaxamount,"5",5));
 $template->setValue("{WAPMAXTITLE}",$wap_title_length);

$template->setValue("{WAPDISURL}",$GLOBALS['wap_url_length']);
$template->setValue("{WAPSUM}",$GLOBALS['wap_desc_length']);
//*********************
//********************BannerAd*****************

$form2=new Form("ppcNewBannerAd","ppc-new-image-ad-action.php");

$form2->isNotNull("language",$template->checkmsg(9999));
$form2->isNotNull("url2",$template->checkmsg(6013));
$form2->isNotNull("banner",$template->checkmsg(6033));
$form2->isNotNull("maxamount",$template->checkmsg(6015));
$form2->isPositive("maxamount",$template->checkmsg(6015));
if($ad_keyword_mode==2){
$form2->isNotNull("maxclkamount",$template->checkmsg(8005));
$form2->isPositive("maxclkamount",$template->checkmsg(8005));
$form2->isOverMin("maxclkamount",$min_click_value,$template->checkmsg(6028));
 $template->setValue("{MAXCLKAMOUNT}",$form2->addTextBox("maxclkamount",$min_click_value,"5",5));
 $suggested_clk_val= getSuggestedValue($keywords_default,0,$min_click_value,$revenue_booster,$revenue_boost_level);
  $sugclick_msg=$template->checkAdvMsg(8919);
 $sug_msg=str_replace("{SUGGVALUE}",$suggested_clk_val,$sugclick_msg);
  $template->setValue("{SUGVAL}",$sug_msg);
 //$template->setValue("{SUGVAL}",$suggested_clk_val);
}
$result=mysql_query("select id,width,height,file_size from  banner_dimension where wap_status=0 order by id");
//$r=mysql_fetch_row($result);
//echo $r[0];





$selectstring="<select name=\"bannersize\" id=\"bannersize\"  dir='ltr'>";

	while($row=mysql_fetch_row($result))
	{
		$selectstring.="<option value=\"$row[0]\"> ( $row[1] x $row[2] ) - Maxsize: $row[3] KB</option>";
	}
$selectstring.="</select>";
$template->setValue("{BANNERSIZE}",$selectstring);


$result1=mysql_query("select id,width,height,file_size from  banner_dimension where wap_status=1 order by id");
$selectstring1="<select name=\"bannersize1\" id=\"bannersize\"  dir='ltr'>";
	while($row1=mysql_fetch_row($result1))
	{
		$selectstring1.="<option value=\"$row1[0]\"> ( $row1[1] x $row1[2] ) - Maxsize: $row1[3] KB</option>";
	}
$selectstring.="</select>";
$template->setValue("{BANNERSIZE1}",$selectstring1);

$template->setValue("{URL2}",$form2->addTextBox("url2","",51,500));


$template->setValue("{FORMSTART1}",$form2->formStart());
$template->setValue("{FORMCLOSE1}",$form2->formClose());


//**********************bannerad..*******************

//*******************catalog ad****************************************//

$form3=new Form("ppcNewCatalogAd","ppc-new-catalog-ad-action.php");
$form3->isNotNull("language",$template->checkmsg(9999));
$form3->isNotNull("banner",$template->checkmsg(9002));

$form3->isNotNull("displayurl3",$template->checkmsg(6012));
$form3->isNotNull("summary3",$template->checkmsg(9003));
$form3->isNotNull("url3",$template->checkmsg(9005));
$form3->isNotNull("maxamount",$template->checkmsg(6015));
$form3->isPositive("maxamount",$template->checkmsg(6015));
$template->setValue("{FORMSTART2}",$form3->formStart());
$template->setValue("{FORMCLOSE2}",$form3->formClose());
$result3=mysql_query("select id,width,height,filesize from catalog_dimension where wapstatus=0 order by id ");
$selectstring3="<select name=\"nersiz\" id=\"bannersiz\"  dir='ltr' >";
	while($row3=mysql_fetch_row($result3))
	{
		$selectstring3.="<option value=\"$row3[0]\"> $row3[1] x $row3[2]  - Maxsize: $row3[3] KB</option>";
	}
$selectstring3.="</select>";

$template->setValue("{BANNERSIZE2}",$selectstring3);


$result4=mysql_query("select id,width,height,filesize from catalog_dimension where  wapstatus=1 order by id ");
$selectstring4="<select name=\"nersiz1\" id=\"bannersiz\"  dir='ltr' >";

	while($row4=mysql_fetch_row($result4))
	{
		$selectstring4.="<option value=\"$row4[0]\"> $row4[1] x $row4[2]  - Maxsize: $row4[3] KB</option>";
	}
$selectstring4.="</select>";

$template->setValue("{BANNERSIZE3}",$selectstring4);

$template->setValue("{DISPLAYURL3}",$form3->addTextBox("displayurl3","",60,$durl_length));
$template->setValue("{SUMMARY3}",$form3->addTextbox("summary3","",60,$desc_length));
$template->setValue("{URL3}",$form3->addTextBox("url3","",51,500));

$btime="";
	$res=mysql_query("select *  from time_hour order by code");
	//echo mysql_num_rows($res);
	$btime.="<select name=\"beg_time\" id=\"beg_time\">";
	while($row=mysql_fetch_row($res))
	{
		if($beg_time==$row[0])
		$btime.="<option value=\"$row[0]\" selected>$row[1]</option>";
		else
		$btime.="<option value=\"$row[0]\">$row[1]</option>";
	}
	$btime.="</select>";

$template->setValue("{BTIME}",$btime);

$etime="";
	$res=mysql_query("select *  from time_hour order by code");
	//echo mysql_num_rows($res);
	$etime.="<select name=\"end_time\" id=\"end_time\">";
	while($row=mysql_fetch_row($res))
	{
		if($beg_time==$row[0])
		$etime.="<option value=\"$row[0]\" selected>$row[1]</option>";
		else
		$etime.="<option value=\"$row[0]\">$row[1]</option>";
	}
	$etime.="</select>";

$template->setValue("{ETIME}",$etime);



//*********************//

//$template->setValue("{SUBMIT}",$form1->addSubmit($template->checkAdvMsg(8887)));
//$template->setValue("{SUBMIT1}",$form2->addSubmit($template->checkAdvMsg(8887)));
//$template->setValue("{SUBMIT2}",$form3->addSubmit($template->checkAdvMsg(8887)));
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
$template->setValue("{JSMES}",$template->checkmsg(8890)); 
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');
?>
<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
//include_once("messages.".$client_language.".inc.php");

$user=new User("ppc_users");
$template=new Template();

$template->loadTemplate("ppc-templates/ppc-edit-catalog-ad.tpl.html");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}



$id=getSafePositiveInteger('id','g');
if(!myAd($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}



loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];
         // $budget_period (monthly ,daily)


$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
 
 
$form=new Form("ppcEditAd","ppc-edit-catalog-ad-action.php");


///////////////////wap
/*
if(isset($_GET['wap']))
{
$wap_flag=$_GET['wap'];
}
else 
{
	$wap_flag=0;
}
phpsafe($wap_flag);*/


//******************** time targetting********************************//

//$form->isNotNull("popup_container",$message[6092]);
//$form->isNotNull("popup_container1",$message[6093]);
//$form->Compare("popup_container","popup_container1",-1,$message[6094]);
//////////////////////

$target_result=mysql_query("select date_tar_s,date_tar_e,time_tar_s,time_tar_e,day_tar_s,day_tar_e,date_flg,time_flg,day_flg,adult_status from time_targeting where aid=$id");
 if(mysql_num_rows($target_result)==0)
 {
   $template->setValue("{CHECKEDFIRST}","checked=\"checked\""); 
   $template->setValue("{CHECKEDSECOND}",""); 
   $template->setValue("{CHECKEDTHIRD}",""); 
   $template->setValue("{DURATIONALERT}",""); 
  $template->setValue("{SDATE}",""); 
  $template->setValue("{EDATE}",""); 
  
  $template->setValue("{CHECKEDMINUS}",""); 
  $template->setValue("{CHECKEDZERO}",""); 
  $template->setValue("{CHECKEDPOSITIVE}",""); 
   
 
 }
 else if(mysql_num_rows($target_result)>0)
 {
 	
 $tar_row=mysql_fetch_row($target_result);

 if($tar_row[6]==0)
 {
   $template->setValue("{CHECKEDFIRST}",""); 
   $template->setValue("{CHECKEDSECOND}",""); 
   $template->setValue("{CHECKEDTHIRD}",""); 
 
  $template->setValue("{CHECKEDMINUS}",""); 
  $template->setValue("{CHECKEDZERO}","checked=\"checked\""); 
  $template->setValue("{CHECKEDPOSITIVE}",""); 
  
  
  $tar_sdate=date("m/d/Y",$tar_row[0]);
  $tar_edate=date("m/d/Y",$tar_row[1]);
  $template->setValue("{SDATE}",$tar_sdate); 
  $template->setValue("{EDATE}",$tar_edate); 
  $template->setValue("{DURATIONALERT}",""); 
 
 }
 else
 {
   $template->setValue("{CHECKEDFIRST}",""); 
   $template->setValue("{CHECKEDSECOND}",""); 
   $template->setValue("{CHECKEDTHIRD}",""); 
 
  $template->setValue("{CHECKEDMINUS}",""); 
  $template->setValue("{CHECKEDZERO}",""); 
  $template->setValue("{CHECKEDPOSITIVE}","checked=\"checked\""); 
  
  $template->setValue("{SDATE}",""); 
  $template->setValue("{EDATE}",""); 
  $template->setValue("{DURATIONALERT}","Note : Valid from Ad approval date."); 
  

 }
 
 }
 
 
 
//******************** time targetting********************************//



$result=mysql_query("select bannersize,id,link,maxamount,summary,displayurl,title,adlang,wapstatus,name,beg_time,end_time from ppc_ads where id=$id ");

$row=mysql_fetch_array($result);

$wap_flag=$row["wapstatus"];

if($wap_flag==1)
{
	$wap_string='and wapstatus=1';
	$wap_string2='where wapstatus=1';
	$wap_name='Wap';
	
	
	$durl_length=$GLOBALS['wap_url_length'];
	$desc_length=$GLOBALS['wap_desc_length'];
	
}

else
{
	$wap_string='and wapstatus=0';
	$wap_string2='where wapstatus=0';
	$wap_name='';
    
	$durl_length= $ad_displayurl_maxlength;
	$desc_length=$ad_description_maxlength;
	
	
}
$form->isNotNull("adname",$template->checkmsg(8890));
$form->isNotNull("language",$template->checkmsg(9999));
$form->isNotNull("bannersize",$template->checkmsg(9001));
$form->isNotNull("summary",$template->checkmsg(9003));
$form->isNotNull("displayurl",$template->checkmsg(9004));
$form->isNotNull("url",$template->checkmsg(9005));
$form->isNotNull("maxamount",$template->checkmsg(6015));
$form->isPositive("maxamount",$template->checkmsg(6015));
if($ad_keyword_mode==2)
{
	$form->isNotNull("maxclkamount",$template->checkmsg(8005));
	$form->isPositive("maxclkamount",$template->checkmsg(8005));
	$form->isOverMin("maxclkamount",$min_click_value,$template->checkmsg(6028));
}
$template->setValue("{WAP_FLAG}",$wap_flag);
$template->setValue("{WAP_NAME}",$wap_name);

$template->setValue("{MAXDESC}",$desc_length);
$template->setValue("{MAXDURL}",$durl_length);
$edt_mess5=$template->checkAdvMsg(8964);
$edt_mess15=str_replace("{WAP1}",$wap_name,$edt_mess5);
$template->setValue("{EDTMSG}",$edt_mess15);
$edt_mess50=$template->checkAdvMsg(8965);
$edt_mess150=str_replace("{WAP1}",$wap_name,$edt_mess50);
$template->setValue("{EDTMSG1}",$edt_mess150);
$template->setValue("{FORMSTART}",$form->formStart().$form->addHiddenField("wap",$wap_flag));
$template->setValue("{FORMCLOSE}",$form->formClose());



$adult_status=$mysql->echo_one("select adult_status from ppc_ads where id='$id'");
if($adult_status==1)
{
$adult="No  <input type=\"radio\" name=\"adult_status\" value=\"0\" id=\"adult_status_no\">&nbsp;&nbsp;
                   Yes  <input type=\"radio\" name=\"adult_status\" value=\"1\" id=\"adult_status_yes\"  checked=\"checked\">";

}
else
{

$adult="No  <input type=\"radio\" name=\"adult_status\" value=\"0\" id=\"adult_status_no\"  checked=\"checked\">&nbsp;&nbsp;
                   Yes  <input type=\"radio\" name=\"adult_status\" value=\"1\" id=\"adult_status_yes\">";

}

$template->setValue("{ADULT}",$adult);


//////////////////////////////////////wap





$result=mysql_query("select adlang from ppc_ads  where id='$id'");
$df=mysql_fetch_row($result);

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");
//		echo mysql_num_rows($res);
		$ctrstr.="<select name=\"language\" id=\"language\"  dir='ltr' >";
		$ctrstr.="<option value=\"0\">Any Languages</option>";
				
				while($row1=mysql_fetch_row($res))
	{
		
		if($df[0]==$row1[0])
		{
		$ctrstr.="<option value=\"$row1[0]\" selected>$row1[1]</option>";
		}
		else
		{
		$ctrstr.="<option value=\"$row1[0]\">$row1[1]</option>";	
		}
	}
$ctrstr.="</select>";
				
	$template->setValue("{LANGUAGE}",$ctrstr);	
	
$result1=mysql_query("select id,width,height,filesize from catalog_dimension $wap_string2 order by id ");
$selectstring="<select name=\"bannersize\" id=\"bannersize\"  dir='ltr'>";
$selectstring.="<option value=\"".$row['bannersize']."\" selected> - Change Banner Size - </option>";
	while($row1=mysql_fetch_row($result1))
	{
	if($row['bannersize']==$row1[0])
	{
		
		$selectstring.="<option value=\"$row1[0]\" selected> $row1[1] x $row1[2] - Maxsize: $row1[3] KB</option>";
	}
	else
		$selectstring.="<option value=\"$row1[0]\" > $row1[1] x $row1[2] - Maxsize: $row1[3] KB</option>";
	}
$selectstring.="</select>";

 //checcking gudget period is monthly or daily
if($budget_period==1)
{
	$template->setValue("{BUDGET}",$template->checkAdvMsg(7037));
	$template->setValue("{BUDGET_UNIT}",$template->checkAdvMsg(7039));
	$budgetmess=$template->checkAdvMsg(7039);
}
else if($budget_period==2)
{
	$template->setValue("{BUDGET}",$template->checkAdvMsg(7038));
	$template->setValue("{BUDGET_UNIT}",$template->checkAdvMsg(7040));
	$budgetmess=$template->checkAdvMsg(7040);
}






 



$template->setValue("{DUMMYLINK}",$form->addHiddenField("dummylink",$row["link"]));
$template->setValue("{DUMMYSUMMARY}",$form->addHiddenField("dummysummary",$row["summary"]));
$template->setValue("{DUMMYDISPLAYURL}",$form->addHiddenField("dummydisplayurl",$row["displayurl"]));
$template->setValue("{DUMMYADULT}",$form->addHiddenField("dummyadult",$row["adult_status"]));
//$template->setValue("{DUMMYBANNERSIZE}",$form->addHiddenField("dummybannersize",$row["bannersize"]));
//$template->setValue("{DUMMYTITLE}",$form->addHiddenField("dummytitle",$row["title"]));

$beg_time=$row["beg_time"];
$end_time=$row["end_time"];


$template->setValue("{ADNAME}",$form->addTextBox("adname",$row["name"],40));

$template->setValue("{IDFIELD}",$form->addHiddenField("id",$row["id"]));
$template->setValue("{BANNERSIZE}",$selectstring);
$template->setValue("{URL}",$form->addTextBox("url",$row['link'],45,500)); 
$template->setValue("{DISPLAYURL}",$form->addTextBox("displayurl",$row['displayurl'],60,$durl_length)); 
$template->setValue("{SUMMARY}",$form->addTextbox("summary",$row['summary'],60,$desc_length));


$template->setValue("{MAXAMOUNT}",$form->addTextBox("maxamount",$row['maxamount'],"5",5));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(7001)));

if($ad_keyword_mode==2){
	$is_default=$mysql->echo_one("select count(*) from ppc_keywords where keyword='$keywords_default' and aid='$id'");
		if($is_default==0 && $keywords_default!="")
			{
				mysql_query("insert into ppc_keywords values('0','$id','$userid','$keywords_default','$min_click_value','$auto_keyword_approve','".time()."','$min_click_value');");
			}
	
	 $clk_val=$mysql->echo_one("select maxcv from ppc_keywords where aid=$id and keyword='$keywords_default'");
	// echo "select maxcv from ppc_keywords where aid=$id and keyword=$keywords_default.....$clk_val";
	 $template->setValue("{MAXCLKAMOUNT}",$form->addTextBox("maxclkamount",$clk_val,"5",5));
	 $suggested_clk_val= getSuggestedValue($keywords_default,0,$min_click_value,$revenue_booster,$revenue_boost_level);
	// $template->setValue("{SUGVAL}",$suggested_clk_val);
	  $edt_mess25=$template->checkAdvMsg(8919);
$edt_mess120=str_replace("{SUGGVALUE}",$suggested_clk_val,$edt_mess25);

 $template->setValue("{SUGVAL}",$edt_mess120);
}

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));  
$template->setValue("{ID}",$id);                                               
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}


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
		if($end_time==$row[0])
		$etime.="<option value=\"$row[0]\" selected>$row[1]</option>";
		else
		$etime.="<option value=\"$row[0]\">$row[1]</option>";
	}
	$etime.="</select>";

$template->setValue("{ETIME}",$etime);


$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

$edt_mess17=$template->checkAdvMsg(8961);
$edt_mess18=str_replace("{BUDGET1}",$budgetmess,$edt_mess17);
$template->setValue("{BDT_UNIT}",$edt_mess18);
 $edt_mess20=$template->checkAdvMsg(8966);
$edt_mess21=str_replace("{VALUE1}",$durl_length,$edt_mess20);
 $template->setValue("{EDT_MESS}",$edt_mess21);
 
  $edt_mess200=$template->checkAdvMsg(8966);
$edt_mess201=str_replace("{VALUE1}",$desc_length,$edt_mess200);
 $template->setValue("{EDT_MESS1}",$edt_mess201);
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  
eval('?>'.$template->getPage().'<?php ');
?>
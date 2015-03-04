<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
includeClass("User");
includeClass("Form");
includeClass("Template");

$template=new Template();
//include_once("messages.".$client_language.".inc.php");
$template->loadTemplate("ppc-templates/ppc-manage-target-locations.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");
 
$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$form=new Form("NewLocations","ppc-new-target-locations-action.php");
$id=$_GET['id'];

if(!myAd($id,$user->getUserID(),$mysql))
{
header("Location:show-message.php?id=5010");
exit(0);
}

$pass=$_GET['pass'];
phpSafe($pass);

$wap_flag=$_GET['wap'];
phpSafe($wap_flag);

$template->setValue("{WAP_FLAG}",$wap_flag);


//{LOOP(ADS)-STARTLOOP}title,link,summary
$result=mysql_query("select title,link,summary,displayurl,adtype,status,amountused,pausestatus,bannersize,contenttype,hardcodelinks from ppc_ads where id='$id'");
$row=mysql_fetch_row($result);
$adtype=$row[4];
//print_r($row);
$template->setValue("{FORMSTART}",$form->formStart().$form->addHiddenField("pass",$pass).$form->addHiddenField("wap",$wap_flag));
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{IDFIELD}",$form->addHiddenField("id",$id));

loadsettings("ppc_new");
$budget_period=$GLOBALS['budget_period'];

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

$template->setValue("{PASS}",$pass);

$template->setValue("{STATUS}",$row[5]);
$template->setValue("{AMOUNTUSED}",$row[6]);
$template->setValue("{PSTATUS}",$row[7]);
$template->setValue("{ADTYPE}",$adtype);
$template->setValue("{ID}",$id);
$template->setValue("{DURL}",$row[3]);
$money_fmt=moneyFormat($row[6]) ;
$emess=$template->checkAdvMsg(8954);
$emess1=str_replace("{AMOUNT1}",$money_fmt,$emess);
$emess2=str_replace("{PERIOD1}",$PERIOD,$emess1);
$template->setValue("{EMESS2}",$emess2); 
if($currency_format=="$$")
$template->setValue("{CURRENCY_SYMBOL}",$system_currency); 
else
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 

$template->setValue("{TITLE}",$row[0]);
$template->setValue("{URL}",$row[1]);
$template->setValue("{SUMMARY}",$row[2]);

 $template->setValue("{BSIZE}",$row[8]);
 $catalog_width=$mysql->echo_one("select width from catalog_dimension where id='$row[8]'");
 $template->setValue("{CAT_WT}",$catalog_width);
$catalog_height=$mysql->echo_one("select height from catalog_dimension where id='$row[8]'"); 
 $template->setValue("{CAT_HT}",$catalog_height);



//************************************************** Changes For Flash Ads *************************************

if(($row[4]==1 || $row[4]==2) && $row[9]=="swf")
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

if($row[4]==1)
{
$resht=$mysql->echo_one("select height from banner_dimension where id='$row[8]'");
$reswt=$mysql->echo_one("select width from banner_dimension where id='$row[8]'");
}
else if($row[4]==2)
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










$ctrstr="";
		$res=mysql_query("select code,name  from  location_country where code not in ('A1','A2','AP','EU') order by name ");
		
		//echo mysql_num_rows($res);
			while($row=mysql_fetch_row($res))
			{
				$ctrstr.="<option value=\"$row[0]\">$row[1]</option>";
			} 
		
$template->setValue("{COUNTRYFIELD}",$ctrstr);

$count=$mysql->total("ad_location_mapping","adid='$id' and country<>'00'");




//echo $count;
$template->setValue("{COUNT}",$count);   
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


$result=mysql_query("select country from ad_location_mapping where adid='$id' AND country<>'00'");
$extraparam="";
while($row=mysql_fetch_row($result))
{
	$extraparam.=$row[0].",";
}
$extraparam_orig=$extraparam;

if($extraparam!="")
{
	$extraparam=substr($extraparam,0,-1);
	$extraparam=explode(',',$extraparam);
//	print_r($extraparam);
	$i=count($extraparam);
	$opt_str="";
	if($i>0)
	{
		foreach($extraparam as $key=>$value)
				{
					$opt_str.='<option value="'.$value.'">';
					$extraparam[$key]=$mysql->echo_one("select name from  location_country where code='$value'");
					$opt_str.=$extraparam[$key].'</option>';
				}	
	}
}	
		//echo htmlentities($opt_str);
		
$arabic_code=$mysql->echo_one("select id from adserver_languages where code='ar'");
if($arabic_code==$_COOKIE['language'])
{
	$f1="javascript:removefield(document.getElementById('fields2'));";
	$f2="javascript:addfield(document.getElementById('fields1'));";
}
else
{
	$f1="javascript:addfield(document.getElementById('fields1'));";
	$f2="javascript:removefield(document.getElementById('fields2'));";
}
$template->setValue("{F1}",$f1);
$template->setValue("{F2}",$f2);
$template->setValue("{OPTIONS}",$opt_str);
$template->setValue("{ORIGINAL}",$extraparam_orig);  
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");

$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(7024)));
$template->setValue("{PAGEWIDTH}",$page_width);    
$template->setValue("{ENCODING}",$ad_display_char_encoding);    
	   $template->setValue("{BANNERS}",$GLOBALS['banners_folder']);   
	   $emess50=$template->checkAdvMsg(8953);
$emess150=str_replace("{ENAME}",$template->checkAdvMsg(0001),$emess50);
$template->setValue("{EMESS51}",$emess150); 
          
eval('?>'.$template->getPage().'<?php ');

?>
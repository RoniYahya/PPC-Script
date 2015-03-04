<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");
$template=new Template();

$template->loadTemplate("publisher-templates/ppc-edit-wap-ad-unit.tpl.html");
//include_once("messages.".$client_language.".inc.php");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}
$uid=$user->getUserID();



$customid=getSafePositiveInteger('customid');
$val=$mysql->echo_one("select count(*) from ppc_custom_ad_block cad  where id='$customid' and pid='$uid' and wapstatus='1'");

if($val==0)
{
header("Location:publisher-show-message.php?id=1020");
exit(0);
}
if(isset($_POST['scroll_ad']))
	{
	$scroll_ad=$_POST['scroll_ad'];
	}
else
	{
	$scroll_ad=0;
	}


if(isset($_POST['adult_status']))
	{
	$adult_status=$_POST['adult_status'];
	}
else
	{
	$adult_status=0;
	}



if(isset($_POST['flag'])&& ($_POST['flag']==2))
{

	$tc=$_POST['color1'];
		if($tc=="")
			{
			$tc="#000099";
			}
	$dc=$_POST['color2'];	
		if($dc=="")
			{
			$dc="#0F0F0F";
			}
	$uc=$_POST['color3'];	
		if($uc=="")
			{
			$uc="#009933";
			}
	$bg=$_POST['color4'];	
		if($bg=="")
			{
			$bg="#FFFFFF";
			}
	$cc=$_POST['cc'];	
	$name=$_POST['name'];
	$adlang=$_POST['language'];
	$bt=$_POST['border_type'];
	phpSafe($name);
	phpSafe($tc);
	phpSafe($dc);
	phpSafe($uc);
	phpSafe($bg);
	phpSafe($cc);
	phpSafe($bt);
	phpSafe($adlang);
		/*$resultstring="";
		$restricted="";
		$result=mysql_query("select id from ppc_restricted_sites order by id ASC");
		$i=0;
		while($row=mysql_fetch_row($result))
		{
				if(isset($_POST[ "Lis".$row[0]]))
					 $resultstring.=$row[0].",";
				$i+=1;
		} 
		if( $resultstring!="")
		{
			$resultstring = substr($resultstring, 0, -1);
		}
		*/
		$resultstring="";
		
		
		
		if(trim($_POST['ctr'])!="")
		{
				$resultstring=substr(trim($_POST['ctr']),0,-1);
		
		
		}
		phpSafe($resultstring);



		if(trim($cc)=="" || trim($cc)=="{credit_border_color}" ) //fix for some erroneous blocks
			$cc=$mysql->echo_one("select  pad.credit_text_border_color  from wap_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$customid' and cad.wapstatus='1'");//$res[17];//from parent
		mysql_query("update ppc_custom_ad_block set name='$name',title_color='$tc',desc_color='$dc',url_color='$uc',bg_color='$bg',credit_color='$cc',bordor_type='$bt',ppc_restricted_sites='$resultstring',scroll_ad=$scroll_ad,adlang='$adlang',adult_status='$adult_status' where id='$customid' and wapstatus='1'");	

}

$res1=mysql_query("select pad.*,cad.*,pad.credit_text as padtext,cad.title_color as tcolor,cad.desc_color as dcolor,cad.url_color as ucolor,cad.credit_text as ctext from wap_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$customid' and cad.wapstatus='1'");
$res=mysql_fetch_array($res1);










//sticky ad
if(!isset($_POST['sticky_ad_pos']))
	{
	$ad_pos=0;
	}
else
	{
	$ad_pos=$_POST['sticky_ad_pos'];
	}

if(!isset($_POST['ad_type']))
	{
	$ad_type=0;
	$ad_pos=0;
	}
else
	{
	$ad_type=$_POST['ad_type'];
	if($ad_type==0)
		$ad_pos=0;
	}
//echo "ad_type=$ad_type...ad_pos=$ad_pos";
//exit(0);
//echo $ad_pos;

if(isset($_POST["users_resolution1"]))
	$screen_width = $_POST["users_resolution1"];
$screen_width=$screen_width/2;
//print_r($_POST);
if(isset($_POST["users_resolution2"]))
	$screen_height = $_POST["users_resolution2"];
$screen_height=$screen_height/2;

$iframe_width=$res['width'];
$iframe_height=$res['height'];

$horiz_center=($screen_width- ($iframe_width/2))."px";
$ver_center=($screen_height- ($iframe_height/2))."px";

$frame_val="";
$stickyflag="false";
		switch($ad_pos)
		{
		case 1:
			$frame_val="<div  id=\"fl813691\"  style=\"z-index:900;position:fixed;_position: absolute;left:0;top:0;width:".$iframe_width."px\" >";
			$stickyflag=true;
			break;
			case 2:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:".$horiz_center.";top:0;width:".$iframe_width."px\" >";
			$stickyflag=true;
			break;
			case 3:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;top:0;right:0;width:".$iframe_width."px \" >";
			$stickyflag=true;
			break;
			
			case 7:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:0;bottom:0;width:".$iframe_width."px\" >";
			$stickyflag=true;
			break;
			case 8:
		//	echo "inside switch";
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:".$horiz_center.";bottom:0;width:".$iframe_width."px\" >";
		//	echo $frame_val;			
			$stickyflag=true;
			break;
			case 9:
			$frame_val="<div  id=\"fl813691\" style=\"z-index:900;position:fixed;_position: absolute;right:0;bottom:0;width:".$iframe_width."px\" >";
			$stickyflag=true;
			break;
			default:
			break;
		}
//echo $stickyflag."ddd";exit(0);
//sticky ad
//scroll ad


$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$i=0;
$form=new Form("NewAddblock1","ppc-edit-wap-ad-unit.php?customid=".$customid);
$form->isNotNull("name",$template->checkmsg(6074));
//$form->isNotNull("language",$message[9999]);

$result1=mysql_query("select adlang from ppc_custom_ad_block  where id='$customid'");
$df=mysql_fetch_row($result1);

$des1=mysql_query("select id,language,code from adserver_languages  where status='1'");
//		echo mysql_num_rows($res);
		$lang.="<select name=\"language\" id=\"language\" >";
		
				
	while($db=mysql_fetch_row($des1))
	{
		
	$lang.="<option value=\"$db[0]\"";

	
		if($df[0]==$db[0])
		{
	
	$lang.="selected";
		}
		
	$lang.=">$db[1]</option>";
	
	
	}
	$lang.="<option value=\"0\" "; 	
	if($df[0]==0)
		{
	
	$lang.="selected";
		}
	
$lang.=">Any language</option></select>";
				
				
				
//************************5.4**************************************//

if($site_targeting==1) 
{
//echo $res['sid'];
	
	
	$psa=$mysql->echo_one("select siteid from ppc_site_adunit where auid='$customid'");
	$site=$mysql->echo_one("select url from ppc_publishing_urls where id='$psa'");
	$template->setValue("{SITENAME}",$site);

	
}

//************************5.4**************************************//				
				
				
				
				
	$template->setValue("{LANGUAGE}",$lang);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());

$template->setValue("{CUSTOMID}",$customid);
//$template->setValue("{server_dir}",$server_dir);
$template->setValue("{FRAME}",$frame_val);
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7022)));

//$res1=mysql_query("select pad.*,cad.* from wap_ad_block pad,ppc_custom_ad_block cad  where pad.id=cad.bid and cad.id='$customid'");
//$res=mysql_fetch_row($res1);
$template->setValue("{ID}",$res['id']);
$template->setValue("{WIDTH}",$res['width']);
//$template->setValue("{HEIGHT}",$res['height']+15);
$template->setValue("{HEIGHT}",$res['height']);


$template->setValue("{HEIGHT1}",$res['height']);
$template->setValue("{TYPE}",$res['ad_type']);
$template->setValue("{ORIENTATION}",$res['orientaion']);
$template->setValue("{NO_OF_TEXT_ADS}",$res['no_of_text_ads']);
$template->setValue("{ALLOW_TITLE_COLOR}",$res['allow_title_color']);
$template->setValue("{ALLOW_DESC_COLOR}",$res['allow_desc_color']);
$template->setValue("{ALLOW_DISP_URL_COLOR}",$res['allow_disp_url_color']);
$template->setValue("{ALLOW_BG_COLOR}",$res['allow_bg_color']);
$template->setValue("{ALLOW_CREDIT_COLOR}",$res['allow_credit_color']);
$template->setValue("{ALLOW_BORDER_TYPE}",$res['allow_bordor_type']);
$template->setValue("{CNAME}",$res['name']);

$template->setValue("{NO_OF_CATALOG_ADS}",$res['no_of_catalog_ads']);

//$template->setValue("{CBORDER_TYPE}",$res['bordor_type']);


if($res['allow_bordor_type'] == 0)
$template->setValue("{CBORDER_TYPE}",$res['border_type']);
else
$template->setValue("{CBORDER_TYPE}",$res['bordor_type']);






$template->setValue("{CREDITTEXT}",$res['padtext']);

$adb_val="ads_".md5($ppc_engine_name);
$template->setValue("{ADB_VAL}",$adb_val);

$template->setValue("{AD_TYPE}",$ad_type);
$template->setVAlue("{STICKY_POS}",$ad_pos);
$template->setValue("{STICKY_FLAG}",$stickyflag);
$scroll_ad=$mysql->echo_one("select scroll_ad from ppc_custom_ad_block where id='$customid' and wapstatus='1'");
$adult_status=$mysql->echo_one("select adult_status from ppc_custom_ad_block where id='$customid'");
$template->setValue("{SCROLL}",$scroll_ad);

if($adult_status==1)
{

$adult="No  <input type=\"radio\" name=\"adult_status\" value=\"0\" id=\"adult_status_no\" >&nbsp;&nbsp;
                   Yes  <input type=\"radio\" name=\"adult_status\" value=\"1\" id=\"adult_status_yes\" checked=\"checked\">";

}
else
{

$adult="No  <input type=\"radio\" name=\"adult_status\" value=\"0\" id=\"adult_status_no\" checked=\"checked\">&nbsp;&nbsp;
                   Yes  <input type=\"radio\" name=\"adult_status\" value=\"1\" id=\"adult_status_yes\">";

}
//echo "$adult";

$template->setValue("{ADULT}",$adult);



// 
$io_eng=$_SERVER['HTTP_HOST'];
if(substr($io_eng,0,4)=="www.")
	$io_eng=str_replace("www.","",$io_eng);
$io_eng=str_replace(" ","_",$io_eng);
$io_eng=str_replace(".","_",$io_eng);
$io_eng=str_replace("-","_",$io_eng);
$io_eng=removeNumbers($io_eng);

$template->setValue("{IO_ADSERVER}",$io_eng);

$template->setValue("{IO_ADSERVER_DOL}","$".$io_eng);

$template->setValue("{SERVER_DIR}",$server_dir);

$template->setValue("{TCOLOR}",$res['tcolor']);
$template->setValue("{DCOLOR}",$res['dcolor']);
$template->setValue("{UCOLOR}",$res['ucolor']);
$template->setValue("{BCOLOR}",$res['bg_color']);
//if($df[0]==0)
//{
//	$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
//	$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
//$parid=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='$lanid'");
//$fl=1;
//}
//else
//{
// $parid=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='$df[0]'");
//}
//if($parid==0)
//{
//if($fl==1)
//	{
//		$df[0]=$lanid;
//	}
//	$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where id=\"".$res['credit_text']."\" and language_id='$df[0]'");
//}
//else
//{
//if($fl==1)
//	{
//		$df[0]=$lanid;
//	}
//$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$res['credit_text']."\" and language_id='$df[0]'");//echo $res['credit_text'];
//}
// 
$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
if($clan=="")
{
	$clan='en';
	$lanid=$mysql->echo_one("select id from adserver_languages where code='en'");
}
else
{
	$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
}

if($res['adlang']==0)  //anylanguages
{
	if($clan=='en')
	{
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$res['padtext']."' ");
	}
	else
	{
	//echo "select credit from ppc_publisher_credits where parent_id=\"".$res['credit_text']."\" and language_id='$lanid'";
		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$res['padtext']."\" and language_id='$lanid'");
		if($credit_text=='')
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$res['padtext']."' ");
	}
}
else
{
		
		if($res['adlang']==$lanid )
		{
				if($clan=='en')
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$res['padtext']."' ");
				else
				{
					$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$res['padtext']."\" and language_id='".$res['adlang']."'");
				}
		}
		else
		{
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$res['padtext']."\" and language_id='".$res['adlang']."'");
			if($credit_text=='')
				$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$res['padtext']."\" and language_id='$lanid'");
		}
		if($credit_text=='')
			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where  id='".$res['padtext']."' ");
			
		
}
//if($res['adlang']==0)
//{
//$clan=$mysql->echo_one("select value from ppc_settings where name='client_language'");
//$lanid=$mysql->echo_one("select id from adserver_languages where code='$clan'");
//	if($clan=='en')
//	{
//		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id='".$res['adlang']."' and language_id='$lanid'");
//	}
//	else
//	{
//	
//		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$res['ctext']."\" and language_id='$lanid'");
//	}
//}
//else
//{
//		$parid=$mysql->echo_one("select parent_id from ppc_publisher_credits where language_id='".$res['adlang']."' ");
//		if($parid==0)
//		{
//		$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where id=\"".$res['ctext']."\" and language_id='".$res['adlang']."'");
//		}
//		else
//		{
//			$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where parent_id=\"".$res['ctext']."\" and language_id='".$res['adlang']."'");
//}
//}
//$credit_text=$mysql->echo_one("select credit from ppc_publisher_credits where id=\"".$res['credit_text']."\"");//echo $credit_text;
if($credit_text=="")
	$credit_text="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$Credit="";

if($res['allow_credit_color']==1)
{

$cr_type=0;
$cr_type=$mysql->echo_one("select credittype from ppc_publisher_credits where id='".$res['padtext']."'");		


		$res1=mysql_query("select * from ppc_credittext_bordercolor order by id DESC");
		while($row1=mysql_fetch_row($res1))
		{
				if($row1[0]==$res['credit_color'])
				{
					$template->setValue("{CREDIT_BORDER_COLOR}",$row1[0]);
					if($cr_type==0)	
					$template->setValue("{SELECTED_CREDIT}",'<span id= "selected_credit" style="padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">'.substr($credit_text,0,72).'</span>');
					else
					$template->setValue("{SELECTED_CREDIT}",'<span id= "selected_credit" style="padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">credit text</span>');
				}	
                
				if($cr_type==0)	
				$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">".substr($credit_text,0,72)."</a></li>";
				else
				$Credit=$Credit."<li ><a onMouseOver=\"javascript:updateCreditBorderColor($row1[0]  ,  '$row1[1]' ,  '$row1[2]' ) \" style=\"background-color:$row1[2];color:$row1[1];\">credit text</a></li>";
				$i++;
	   } 
}
else
{
		$res1=mysql_query("select * from ppc_credittext_bordercolor order by id DESC");
		while($row1=mysql_fetch_row($res1))
		{
				if($row1[0]==$res['credit_color'])
				{
					$Credit="";
					$template->setValue("{CREDIT_BORDER_COLOR}",$row1[0]);
					$template->setValue("{SELECTED_CREDIT}",'<span id= "selected_credit" style="cursor:default;padding:5px;background-color:'. $row1[2].';color:'. $row1[1].';">'.$credit_text.'</span>');
				}
			//	<label style="color:'. $row1[1].';background-color:'. $row1[2].';padding:2px;margin:2px">credit text<input name="cc" type="hidden"  value="'.$row1[0].'"> </label>';
		} 
}



$rs=",".$res['ppc_restricted_sites'].",";
$template->setValue("{REST}",$rs);
$template->setValue("{CREDIT}",$Credit);
$total=$mysql->echo_one("select count(*) from ppc_restricted_sites where uid='$uid' ");

$ctrstr="";
$res=mysql_query("select id,site  from  ppc_restricted_sites where  uid='$uid'");


//echo mysql_num_rows($res);
$ctrstr.="";

while($row=mysql_fetch_row($res))
{
		$ctrstr.="<option value=\"$row[0]\">$row[1]</option>";
} 
		
$template->setValue("{SITEFIELD}",$ctrstr);
$cnt=$mysql->echo_one("select ppc_restricted_sites from ppc_custom_ad_block  where id='$customid' and pid='$uid' and wapstatus='1'");
if($cnt!="")
{
	$result15=mysql_query("select id,site from ppc_restricted_sites where  uid='$uid' and id in ($cnt) ");
		while($row=mysql_fetch_row($result15))
		{
		$restrict.="<option value=\"$row[0]\">$row[1]</option>";						
		}
	}
		$template->setValue("{RESTRICT}",$restrict); 	 
		$template->setValue("{CNT}",$cnt.",");		 
		/*	 $template->openLoop("AD","select id,site from ppc_restricted_sites where uid='$uid'");			
			$template->setLoopField("{LOOP(AD)-ID}",'id');						
			$template->setLoopField("{LOOP(AD)-SITE}","site");
			$template->closeLoop();	*/
			
			
			
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001)); 

$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8939));
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
$template->setValue("{UID}",$uid);
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     //
//echo $template->getPage();
eval('?>'.$template->getPage().'<?php ');
?>

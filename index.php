<?php 

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
//includeClass("User");
//include_once("messages.".$client_language.".inc.php");
$template=new Template();
$template->loadTemplate("common-templates/index.tpl.html");
$template->includePage("{TABS}","common-header.php");
$template->includePage("{FOOTER}","common-footer.php");	

$ini_error_status=ini_get('error_reporting');



//...............................Adserver5.4..........................
		$template->setValue("{PUBLICIMAGE}",$public_background);
		$template->setValue("{ADVERTISERIMAGE}",$advertiser_image);
		$template->setValue("{PUBLISHERIMAGE}",$publisher_image);
		$template->setValue("{BANNERS}",$GLOBALS['banners_folder']);
		$template->setValue("{SLIDER}",$GLOBALS['ad_logos_folder']);
		
	//	$ab=mysql_query("select * from `adserver_public_images`");
		//$pimage=mysql_fetch_row($ab);
		/*while($pimage=mysql_fetch_row($ab))
		{
		 echo "....".$pimage[1]."<br>";
		}		
		*/
		$template->openLoop("IMAG","select * from `adserver_public_images` where status=1 ORDER BY `date` DESC");
		$template->setLoopField("{LOOP(IMAG)-NAME}","image_name");
		$template->closeLoop();
		$total=$mysql->echo_one("select count(*) from `ad_logos_details` where status=1");
		$template->openLoop("IMAGS","select * from `ad_logos_details` where status=1 ORDER BY `user_status` ASC");
		$template->setLoopField("{LOOP(IMAGS)-NAMES}","ad_logos_name");
		$template->closeLoop();
		
		
		//...............................Adserver5.4..........................





if(isset($_GET['r']) && $referral_system==1)
{
	$r=intval($_GET['r']);
	$host_name=trim($_GET['host']);
	phpSafe($host_name);
	if(substr($host_name,0,4)=="www.")
		$host_name=substr($host_name,4);

	$ref_url=trim($_GET['from']);	
	phpSafe($ref_url);
	if($ref_url=="")
	{
		$ref_url=trim($_SERVER['HTTP_REFERER']);
		//finding host name from ref url assuming host name is also not set.
		if(substr($tmp,0,7)=="http://")
			$tmp=substr($ref_url,7);// strip http://
		if(substr($tmp,0,8)=="https://")
			$tmp=substr($ref_url,8);// strip https://
		if(substr($tmp,0,4)=="www.")
			$tmp=substr($tmp,4);
		$host_name=substr($tmp,0,strpos($tmp,"/"));	
	}
	else if($host_name=="")
	{
		if(substr($tmp,0,7)=="http://")
			$tmp=substr($ref_url,7);// strip http://
		if(substr($tmp,0,8)=="https://")
			$tmp=substr($ref_url,8);// strip https://
			if(substr($tmp,0,4)=="www.")
				$tmp=substr($tmp,4);
			$host_name=substr($tmp,strpos($tmp,"/"));	
	
	}
	
	
	if(!get_magic_quotes_gpc())
		{
			$host_name=mysql_real_escape_string($host_name);
			$ref_url=mysql_real_escape_string($ref_url);
		}	
		
	$public_ip=getUserIP();
	$visit_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("Y",time()));
	if($mysql->total("ppc_publishers","uid='$r' and status=1")==1)
	{
		if(!isset($_COOKIE['io_ads_ref'])) //unique hit
		{
			setcookie('io_ads_ref',$r,time()+60*60*24*30,"/");
			$visit_query=mysql_query("select id from referral_daily_visits where rid='$r' and host_name='$host_name' and ref_url='$ref_url'");
			if($vid=mysql_fetch_row($visit_query))
				mysql_query("update referral_daily_visits set unique_hits=unique_hits+1 where  id='$vid[0]'");
			else
				mysql_query("insert into referral_daily_visits (ip,time,rid,host_name,ref_url,unique_hits,repeated_hits) values('$public_ip',$visit_time,$r,'$host_name','$ref_url',1,0);");
		}
		else if($_COOKIE['io_ads_ref']==$r) // repetitive hit
		{

			$visit_query=mysql_query("select id from referral_daily_visits where rid='$r' and host_name='$host_name' and ref_url='$ref_url'");
			if($vid=mysql_fetch_row($visit_query))
			{
			mysql_query("update referral_daily_visits set repeated_hits=repeated_hits+1 where  id='$vid[0]'");
			}
			else 
			{	
			mysql_query("insert into referral_daily_visits (ip,time,rid,host_name,ref_url,unique_hits,repeated_hits) values('$public_ip',$visit_time,$r,'$host_name','$ref_url',0,1);");
			}
		}
		else // already referred by someone else
		{
		
			// ignore;  do nothing.
		}
		if($ini_error_status!=0)
		{
			echo mysql_error();
		}
	}
	

	
}
if($single_account_mode==1)
{
$signup=$mysql->echo_one("select seoname from url_updation where name='signup'");

		if($signup=="")
		{
			$template->setValue("{COMREG}","registration.php");
		}
		else
		{
			$template->setValue("{COMREG}",$signup);
		}
		$signin=$mysql->echo_one("select seoname from url_updation where name='signin'");
		if($signin=="")
		{
			$template->setValue("{COMLOGIN}","login.php");
			$loginlink="login.php";
		}
		else
		{
			$template->setValue("{COMLOGIN}",$signin);
			$loginlink="$signin";
		}
}
else
{
$advreg=$mysql->echo_one("select seoname from url_updation where name='advertisersignup'");

		if($advreg=="")
		{
			$template->setValue("{ADVREG}","ppc-user-registration.php");
		}
		else
		{
			$template->setValue("{ADVREG}",$advreg);
		}
		
		$pubreg=$mysql->echo_one("select seoname from url_updation where name='publishersignup'");

		if($pubreg=="")
		{
			$template->setValue("{PUBREG}","ppc-publisher-registration.php");
		}
		else
		{
			$template->setValue("{PUBREG}",$pubreg);
		}
		
}
$advlogin=$mysql->echo_one("select seoname from url_updation where name='advertisers'");
		if($advlogin=="")
		{
			$template->setValue("{ADVLOGIN}","ppc-user-login.php");
		}
		else
		{
			$template->setValue("{ADVLOGIN}",$advlogin);
		}

		$publogin=$mysql->echo_one("select seoname from url_updation where name='publishers'");
		if($publogin=="")
		{
			$template->setValue("{PUBLOGIN}","ppc-publisher-login.php");
		}
		else
		{
			$template->setValue("{PUBLOGIN}",$publogin);
		}	
$template->setValue("{ENGINE_NAME}",$template->checkComMsg(0001));           
$template->setValue("{PUB_PROFIT}",$publisher_profit);    
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
$template->setValue("{COLORTHEMEB}",$selected_colorcode[2]);
$editmsg=$template->checkComMsg(1116);
$editmsg1=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg);
$template->setValue("{EDTMSG}",$editmsg1);
$editmsg2=$template->checkComMsg(1117);
$editmsg3=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg2);
$template->setValue("{EDTMSG1}",$editmsg3);
$editmsg5=$template->checkComMsg(1118);
$editmsg6=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg5);
$template->setValue("{EDTMSG2}",$editmsg6);
$editmsg8=$template->checkComMsg(1119);
$editmsg9=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg8);
$template->setValue("{EDTMSG3}",$editmsg9);
$editmsg11=$template->checkComMsg(1120);
$editmsg12=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg11);
$template->setValue("{EDTMSG4}",$editmsg12);
$editmsg15=$template->checkComMsg(1121);
$editmsg16=str_replace("{ENAME}",$template->checkComMsg(0001),$editmsg15);
$template->setValue("{EDTMSG5}",$editmsg16);


$edtmsg200=$template->checkComMsg(1124);
$edtmsg201=str_replace("{ENAME}",$template->checkComMsg(0001),$edtmsg200);
$template->setValue("{EDTMSG10}",$edtmsg201);
$edtmsg205=$template->checkComMsg(1125);
$edtmsg206=str_replace("{COMLOGIN}",$loginlink,$edtmsg205);
$edtmsg207=str_replace("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"",$edtmsg206);
$template->setValue("{EDTMSG11}",$edtmsg207);
$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');
?>

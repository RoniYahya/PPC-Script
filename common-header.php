<?php

//print_r( get_included_files());
global $color_code;
global $color_theme;
global $ppc_engine_name;
global $page_width;
global $logo_path;
global $logo_display_option;

$thead=new Template();
$selected_colorcode=array();
reset($color_code);
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}


$thead->loadTemplate("common-templates/common-header.tpl.html");

//		echo mysql_num_rows($res);


$header_ctrstr="";
$header_res=mysql_query("select * from adserver_languages where status='1' order by language asc");
//		echo mysql_num_rows($res);

		$header_ctrstr.="<select name=\"lang\" id=\"lang\" onChange=\"changelanguage();\" style=\"width:100px\" dir='ltr'>";
			while($header_row=mysql_fetch_row($header_res))
			{
				
				
				$header_ctrstr.="<option  value=\"$header_row[0]_$header_row[3]\"";
				if($GLOBALS['client_language'] == $header_row[3])
				$header_ctrstr.="selected";
				
				
				$header_ctrstr.=">$header_row[1] </option>";
				
				
				
			} 
		$header_ctrstr.="</select>";
		$link1=mysql_query("select * from url_updation where name='home'");
		$link=mysql_fetch_row($link1);
		if($link[3]=="")
		{
			$thead->setValue("{HOME}","index.php");
		}
		else
		{
			$thead->setValue("{HOME}",$link[3]);
		}
		$userlogin=$mysql->echo_one("select seoname from url_updation where name='advertisers'");
		if($userlogin=="")
		{
			$thead->setValue("{USERLOGIN}","ppc-user-login.php");
		}
		else
		{
			$thead->setValue("{USERLOGIN}",$userlogin);
		}
		$publisherlogin=$mysql->echo_one("select seoname from url_updation where name='publishers'");
		if($publisherlogin=="")
		{
			$thead->setValue("{PUBLISHERLOGIN}","ppc-publisher-login.php");
		}
		else
		{
			$thead->setValue("{PUBLISHERLOGIN}",$publisherlogin);
		}
		
		$signup=$mysql->echo_one("select seoname from url_updation where name='signup'");
		if($signup=="")
		{
			$thead->setValue("{COMREG}","registration.php");
		}
		else
		{
			$thead->setValue("{COMREG}",$signup);
		}
		$signin=$mysql->echo_one("select seoname from url_updation where name='signin'");
		if($signin=="")
		{
			$thead->setValue("{COMLOGIN}","login.php");
		}
		else
		{
			$thead->setValue("{COMLOGIN}",$signin);
		}
		$contact=$mysql->echo_one("select seoname from url_updation where name='contactus'");
		if($contact=="")
		{
			$thead->setValue("{CONTACT}","ppc-contact-us.php");
		}
		else
		{
			$thead->setValue("{CONTACT}",$contact);
		}
		$advfaq=$mysql->echo_one("select seoname from url_updation where name='advfaq'");
		if($advfaq=="")
		{
			$thead->setValue("{ADVFAQ}","ppc-advertiser-faq.php");
		}
		else
		{
			$thead->setValue("{ADVFAQ}",$advfaq);
		}
		$pubfaq=$mysql->echo_one("select seoname from url_updation where name='pubfaq'");
		if($pubfaq=="")
		{
			$thead->setValue("{PUBFAQ}","ppc-publisher-faq.php");
		}
		else
		{
			$thead->setValue("{PUBFAQ}",$pubfaq);
		}
		
		
		//...............................Adserver5.4..........................
			$thead->setValue("{CHATVISIBLE}",$chat_visible_status);
			
		$thead->setValue("{OFFLINE}",$offline_image);
		$thead->setValue("{ONLINE}",$online_image);
		$thead->setValue("{CHATSTATUS}",$chat_status);
		$thead->setValue("{PUBLICIMAGE}",$public_background);
		$thead->setValue("{BANNERS}",$GLOBALS['banners_folder']);
		$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
$thead->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$thead->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$thead->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$thead->setValue("{COLORTHEMEB}",$selected_colorcode[2]);

		//$bgimage_type;
		
		if($chat_visible_status==1)
		{
			if($chat_status==1)
			{
			 mysql_query("update nesote_chat_login_status set status=1 where user_id='1' ");
			}
		}
		//...............................Adserver5.4..........................
		
			$thead->setValue("{LOGODISPLAY}",$logo_display_option);
			$thead->setValue("{SINGLEMODE}",$single_account_mode);
$thead->setValue("{ENGINE_NAME}",$thead->checkComMsg(0001));   
$thead->setValue("{LOGO}",$logo_path);
$thead->setValue("{COLORTHEME1}",$selected_colorcode[0]);
$thead->setValue("{COLORTHEME2}",$selected_colorcode[1]);
$thead->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$thead->setValue("{PAGEWIDTH}",$page_width);  
$thead->setValue("{LANGUAGE}",$header_ctrstr);
$thead->setValue("{BANNERS}",$GLOBALS['banners_folder']);
if($GLOBALS['direction']=="ltr")
{
$thead->setValue("{LEFTCLASS}",'headleft');
$thead->setValue("{RIGHTCLASS}",'headright');
}
else
{
$thead->setValue("{LEFTCLASS}",'headright');
$thead->setValue("{RIGHTCLASS}",'headleft');
}

    	if($bgimage_type==1)
		{
			$thead->setValue("{BGTYPE}","fixed");
			
		}
		else
		{
			$thead->setValue("{BGTYPE}","scroll");
		}

  

eval('?>'.$thead->getPage().'<?php ');
?>
<?php
global $color_code;
global $color_theme;
global $ppc_engine_name;
global $page_width;
global $logo_path;
global $logo_display_option;

$thead=new Template();
$thead->loadTemplate("common-templates/common-loggedin-header.tpl.html");

$welcome= str_replace("{USERNAME}",$user->getUsername(),$thead->checkComMsg(1085));
$thead->setValue("{WELCOME1}",$welcome);
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
if(isset($_COOKIE['language'])&& ($_COOKIE['language']!=""))
{
	 $langid=$_COOKIE['language'];
	 	$result=mysql_query("select code from adserver_languages where id='$langid'");
	 	$row1=mysql_fetch_row($result);
	  $langcookie=$row1[0];
}
	  else
	  {
	  $langcookie=$GLOBALS['client_language'];
	  }
	
$header_ctrstr="";
$header_res=mysql_query("select * from adserver_languages where status='1' order by language asc");
//		echo mysql_num_rows($res);

		$header_ctrstr.="<select name=\"lang\" id=\"lang\"  onChange=\"changelanguage();\" style=\"width:100px\" dir='ltr' >";
			while($header_row=mysql_fetch_row($header_res))
			{
				
				
				$header_ctrstr.="<option  value=\"$header_row[0]_$header_row[3]\"";
				if($GLOBALS['client_language'] == $header_row[3])
				$header_ctrstr.="selected";
				
				
				$header_ctrstr.=">$header_row[1] </option>";
				
				
				
			} 
		$header_ctrstr.="</select>";
		//...............................Adserver5.4..........................
		$thead->setValue("{OFFLINE}",$offline_image);
		$thead->setValue("{CHATVISIBLE}",$chat_visible_status);
		
		$thead->setValue("{ONLINE}",$online_image);
		$thead->setValue("{CHATSTATUS}",$chat_status);
		$thead->setValue("{PUBLICIMAGE}",$public_background);
		$thead->setValue("{BANNERS}",$GLOBALS['banners_folder']);
		if($chat_visible_status==1)
		{
			if($chat_status==1)
			{
			 mysql_query("update nesote_chat_login_status set status=1 where user_id='1' ");
			}
		}
		//...............................Adserver5.4..........................
			$thead->setValue("{LOGODISPLAY}",$logo_display_option);
$thead->setValue("{COLORTHEME1}",$selected_colorcode[0]);
$thead->setValue("{COLORTHEME2}",$selected_colorcode[1]);
$thead->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$thead->setValue("{ENGINE_NAME}",$template->checkComMsg(0001));         
$thead->setValue("{LOGO}",$logo_path);
$thead->setValue("{PAGEWIDTH}",$page_width); 
$thead->setValue("{LANGUAGE}",$header_ctrstr);   
$thead->setValue("{COLORTHEME1}",$selected_colorcode[0]);
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
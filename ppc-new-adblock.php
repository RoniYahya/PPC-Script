<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_publishers");

 //************************5.4**************************************//

if($site_targeting==1)
{

$userid=$user->getUserID();


$no_site=mysql_query("select * from `ppc_publishing_urls` where pid='$userid'");
$no_s=mysql_fetch_row($no_site);


$active_site=mysql_query("select * from `ppc_publishing_urls` where pid='$userid' and status=1");
$active_s=mysql_fetch_row($active_site);



if(($no_s[0]<=0) || ($no_s[0]==''))
{
header("Location:publisher-show-message.php?id=10511");
exit(0);
}



if(($active_s[0]<=0) || ($active_s[0]==''))
{
header("Location:show-message.php?id=10512");
exit(0);
}

}
 //************************5.4**************************************//

$template=new Template();

$template->loadTemplate("publisher-templates/ppc-new-adblock.tpl.html");
//include_once("messages.".$client_language.".inc.php");
if(!$user->validateUser())
{
header("Location:publisher-show-message.php?id=1006");
exit(0);
}


//$wap=0;
//if(isset($_GET['wap']) && $_GET['wap']==1)
//	$wap=$_GET['wap'];
//else
//	$wap=0;
//$wap=trim($_POST['target_device']);
//if(isset($_POST['wap']) && $_GET['wap']==1)
//	$wap=$_GET['wap'];
//	else
//	$wap=0;
$template->includePage("{TABS}","publisher-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$i=0;


//$count=$mysql->echo_one("select country from ppc_users where uid=".$user->getUserID());
$ctrstr="";

		$form=new Form("NewAddblock","ppc-new-adblock-action.php");
		
		$des=mysql_query("select id,language,code from adserver_languages  where status='1'");
//		echo mysql_num_rows($res);
		$lan.="<select name=\"language\" id=\"language\"  dir='ltr'>";
		
	
				
				while($dow=mysql_fetch_row($des))
	{
		$lan.="<option value=\"$dow[0]\">$dow[1]</option>";
	}
			$lan.="<option value=\"0\" selected=\"selected\">Any Languages</option>";
$lan.="</select>";
				
	
		$res=mysql_query("select id,ad_block_name,width,height,ad_type from ppc_ad_block  where status='1' and allow_publishers='1' order by id");
//		echo mysql_num_rows($res);
		$ctrstr.="<select name=\"add\" id=\"add\" onChange=\"loadState();\" dir='ltr' >";
					while($row=mysql_fetch_row($res))
			{
				if($row[4]==1)
				$adblocktype="Text Only";
				if($row[4]==2)
				$adblocktype="Banner Only";
				if($row[4]==4)
				$adblocktype="Catalog Only";
				if($row[4]==6)
				$adblocktype="Inline Text";
				if($row[4]==7)
				$adblocktype="Inline Catalog";
				if($row[4]==3)
				$adblocktype="Text/Banner";
				if($i==0)
				$ctrstr.="<option selected=\"selected\" value=\"$row[0]\">$row[1] ($row[2] X $row[3]) - $adblocktype </option>";
				else
				$ctrstr.="<option value=\"$row[0]\">$row[1] ($row[2] X $row[3]) - $adblocktype </option>";
				$i++;
				
			} 
		$ctrstr.="</select>";
			
			$wapres=mysql_query("select id,ad_block_name,width,height,ad_type from wap_ad_block  where status='1' and allow_publishers='1' order by id");
		$wapctrstr.="<select name=\"wapadd\" id=\"wapadd\" onChange=\"loadState();\" dir='ltr'  >";
	
			while($waprow=mysql_fetch_row($wapres))
			{
				if($waprow[4]==1)
				$adblocktype="Text Only";
				if($waprow[4]==2)
				$adblocktype="Banner Only";
				if($waprow[4]==4)
				$adblocktype="Catalog Only";
				if($waprow[4]==6)
				$adblocktype="Inline Text";
				if($waprow[4]==7)
				$adblocktype="Inline Catalog";
				if($waprow[4]==3)
				$adblocktype="Text/Banner";
				if($i==0)
				$wapctrstr.="<option selected=\"selected\" value=\"$waprow[0]\">$waprow[1] ($waprow[2] X $waprow[3]) - $adblocktype </option>";
				else
				$wapctrstr.="<option value=\"$waprow[0]\">$waprow[1] ($waprow[2] X $waprow[3]) - $adblocktype </option>";
				$i++;
				
			} 
		$wapctrstr.="</select>";
		
		
		if($site_targeting==1) 
		{
		//drop down of sites
		
	$site_str="";
	$pid=$user->getUserID();
	//echo "select * from ppc_publishing_urls  where status='1' and pid='$pid' order by id"; exit;	
    $site_result=mysql_query("select * from ppc_publishing_urls  where status='1' and pid='$pid' order by id");
    $site_str.='<select name="site" id="site"  dir="ltr">';
    
    while($row_site=mysql_fetch_row($site_result))
    {
    	$site_str.='<option value="'.$row_site[0].'">'.$row_site[2].'</option>';
    }
   
    $site_str.='</select>';
    $template->setValue("{SITE}",$site_str);
		
		
	}	
		
//$form->isNotNull("language",$message[9999]);		
$form->isNotNull("add",$template->checkmsg(6066));
$template->setValue("{LANGUAGE}",$lan);	
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{ADDNAME}",$form->addTextBox("name","",25,30));
$template->setValue("{ADD}",$ctrstr);
$template->setValue("{WAPADD}",$wapctrstr);
//$template->setValue("{DOMAIN}",$form->addTextBox("domain",$mysql->echo_one("select domain from ppc_publishers where uid=".$user->getUserID()),40,255));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkPubMsg(7023)));

//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkPubMsg(0001)); 

$engine_title= str_replace("{ENGINE_NAME}",$template->checkPubMsg(0001),$template->checkPubMsg(8942));
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
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     //
eval('?>'.$template->getPage().'<?php ');

?>
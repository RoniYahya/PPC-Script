<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");


$template=new Template();
$template->loadTemplate("common-templates/change-details.tpl.html");
$template->includePage("{TABS}","common-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

if($single_account_mode==0)
{
	header("Location: index.php");
exit(0);
}
$user=new User("nesote_inoutscripts_users", "id");
$id=$user->getUserID();
if($_COOKIE['io_type']==md5("advertiser"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_users where username='".$_COOKIE['io_username']."'");
	
}
if($_COOKIE['io_type']==md5("publisher"))
{
	$commonid=$mysql->echo_one("select common_account_id from ppc_publishers where username='".$_COOKIE['io_username']."'");

}
if($commonid==0)
{
if($_COOKIE['io_type']==md5("advertiser"))
	{				
header("Location: ppc-change-details.php");
exit(0);	
	}
	elseif($_COOKIE['io_type']==md5("publisher"))
	{
	header("Location: ppc-change-publisher-details.php");
exit(0);	
	}
}
if(!$user->validateUser())
{
header("Location:error-message.php?id=1006");
exit(0);
}

$form=new Form("EditDetails","change-details-action.php");
$form->isNotNull("email",$template->checkmsg(6067));
			$form->isNotNull("firstname",$template->checkmsg(6077));
			$form->isNotNull("lastname",$template->checkmsg(6078));
			$form->isNotNull("address",$template->checkmsg(6079));
			$form->isNotNull("phone_no",$template->checkmsg(6081));
			$form->isPositiveInteger("phone_no",$template->checkmsg(1032));
$form->isNotNull("country",$template->checkmsg(6068));
$form->isNotNull("domain",$template->checkmsg(6069));
		$form->isWhitespacePresent("domain",$template->checkmsg(1031));
		$form->isDomain("domain",$template->checkmsg(1030));
$form->isEmail("email",$template->checkmsg(6022));
$count=$mysql->echo_one("select country from ppc_publishers where common_account_id=".$user->getUserID());

$ctrstr="";
		$res=mysql_query("select *  from location_country where code NOT IN('A1','A2','AP','EU') order by name");
		//echo mysql_num_rows($res);
//		$row=mysql_fetch_row($res);
//		print_r($res);
		$ctrstr.="<select name=\"country\" id=\"country\" onChange=\"loadState();\" dir='ltr' >
		<option value=\"\" selected=\"selected\">-Select Country-</option>";
			while($row=mysql_fetch_row($res))
			{
				if($row[0]==$count)
				$ctrstr.="<option selected=\"selected\" value=\"$row[0]\">$row[1]</option>";
				else
				$ctrstr.="<option value=\"$row[0]\">$row[1]</option>";
				
			} 
		$ctrstr.="</select>";
		
$edit=mysql_query("select * from ppc_users where common_account_id=".$user->getUserID());
$edit_res=mysql_fetch_row($edit);
$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{EMAILFIELD}",$form->addTextBox("email",$edit_res[3],40,255));
$template->setValue("{COUNTRY}",$ctrstr);
$template->setValue("{DOMAIN}",$form->addTextBox("domain",$edit_res[6],40,255));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkComMsg(1028)));
$template->setValue("{FNAMEFIELD}",$form->addTextBox("firstname",$edit_res[12],40,255));
$template->setValue("{LNAMEFIELD}",$form->addTextBox("lastname",$edit_res[13],40,255));
$template->setValue("{PHNOFIELD}",$form->addTextBox("phone_no",$edit_res[14],40,255));
$template->setValue("{ADDRESSFIELD}",$form->addTextArea("address",$edit_res[15]));
$template->setValue("{TAXID}",'<input type="text" name="taxidentification" id="taxidentification" size="40" value="'.$edit_res[19].'">');
//$template->setValue("{TABS}",$template->includePage($server_dir."advertiser-loggedin-header.php"));
$template->setValue("{ENGINE_NAME}",$template->checkComMsg(0001));         
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


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');
?>
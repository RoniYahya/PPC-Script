<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");
//include_once("messages.".$client_language.".inc.php");

$template=new Template();
$template->loadTemplate("ppc-templates/ppc-change-details.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

 
 
$user=new User("ppc_users");
$advid=$user->getUserID();
$commonid=$mysql->echo_one("select common_account_id from ppc_users where uid=$advid");
if(($commonid!=0))
{
	if($_COOKIE['io_type']==md5("Common"))
	{				
header("Location: change-details.php");
exit(0);	
	}

}

if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}


$form=new Form("EditDetails","ppc-change-details-action.php");
			$form->isNotNull("email",$template->checkmsg(6061));
			
			$form->isNotNull("firstname",$template->checkmsg(6077));
			$form->isNotNull("lastname",$template->checkmsg(6078));
			$form->isNotNull("address",$template->checkmsg(6079));
			$form->isNotNull("phone_no",$template->checkmsg(6081));
			$form->isPositiveInteger("phone_no",$template->checkmsg(1032));
$form->isNotNull("domain",$template->checkmsg(6010));
$form->isNotNull("domain",$template->checkmsg(6011));
$form->isWhitespacePresent("domain",$template->checkmsg(1031));
$form->isDomain("domain",$template->checkmsg(1030));
$form->isEmail("email",$template->checkmsg(6022));
$count=$mysql->echo_one("select country from ppc_users where uid=".$user->getUserID());

$ctrstr="";
		$res=mysql_query("select *  from location_country where code NOT IN('A1','A2','AP','EU') order by name");
		//echo mysql_num_rows($res);
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
	$atax=$mysql->echo_one("select taxidentification from ppc_users where uid=".$user->getUserID());	

$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());
$template->setValue("{EMAILFIELD}",$form->addTextBox("email",$mysql->echo_one("select email from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{COUNTRY}",$ctrstr);
$template->setValue("{DOMAIN}",$form->addTextBox("domain",$mysql->echo_one("select domain from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(7004)));
$template->setValue("{FNAMEFIELD}",$form->addTextBox("firstname",$mysql->echo_one("select firstname from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{LNAMEFIELD}",$form->addTextBox("lastname",$mysql->echo_one("select lastname from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{PHNOFIELD}",$form->addTextBox("phone_no",$mysql->echo_one("select phone_no from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{ADDRESSFIELD}",$form->addTextArea("address",$mysql->echo_one("select address from ppc_users where uid=".$user->getUserID())));
$template->setValue("{TAXID}",'<input type="text" name="taxidentification" id="taxidentification" size="40" value="'.$atax.'">');
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
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

eval('?>'.$template->getPage().'<?php ');

?>

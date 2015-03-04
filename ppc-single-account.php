<?php
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("User");
includeClass("Form");
includeClass("Template");


$template=new Template();

$template->loadTemplate("ppc-templates/ppc-single-account.tpl.html");
$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$user=new User("ppc_users");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}
$common_id=$mysql->echo_one("select common_account_id from ppc_users where uid=".$user->getUserID());
 if($common_id!=0)
{ 
header("Location:ppc-user-control-panel.php");
exit(0);
}
if(!isset($_POST['radiobutton']))
{
 
header("Location:show-message.php?id=8889");
exit(0);
}


if($_POST['radiobutton']==1) //yes
{
	$username=$_POST['username'];
	$password=$_POST['password'];
	
phpSafe($username);
phpSafe($password);
if($username==""||$password=="")
{
header("Location:show-message.php?id=1001");
exit(0);	
}

else
{ 
	$password=md5($password);
	$commonid=$mysql->echo_one("select count(*) from ppc_publishers where username='$username' and password='$password' and common_account_id!='0'");
	$publisher1=mysql_query("select * from ppc_publishers where username='$username' and password='$password'");
	$publisher=mysql_num_rows($publisher1);
	if($publisher==0)
	{
		header("Location:show-message.php?id=1005");
exit(0);	
	}
	elseif($commonid==1)
	{
		header("Location:show-message.php?id=8895");
exit(0);
	}
	else
	{
//$row1=mysql_query("select * from ppc_publishers where username='$username' and password='$password'");
$res=mysql_fetch_row($publisher1);

$form=new Form("details","ppc-single-account-action.php");
$form->isNotNull("email",$template->checkmsg(6009));
$form->isEmail("email",$template->checkmsg(6009));
$form->isNotNull("firstname",$template->checkmsg(6077));
$form->isNotNull("lastname",$template->checkmsg(6078));
$form->isNotNull("phone_no",$template->checkmsg(6081));
$form->isNotNull("address",$template->checkmsg(6079));
$form->isNotNull("country",$template->checkmsg(6010));
$template->setValue("{EMAILFIELD1}",$res[3]);
$template->setValue("{DOMAIN1}",$res[6]);
$template->setValue("{FNAMEFIELD1}",$res[15]);
$template->setValue("{LNAMEFIELD1}",$res[16]);
$template->setValue("{PHNOFIELD1}",$res[17]);
$template->setValue("{ADDRESSFIELD1}",$res[18]);
$template->setValue("{UNAME1}",$res[1]);
$template->setValue("{PUBTAX}",$res[24]);
$ctrstr1=$mysql->echo_one("select country from ppc_users where uid=".$user->getUserID());
//echo $ctrstr1;

$pub_coun=$mysql->echo_one("select name from location_country where code='$res[5]'");
$template->setValue("{COUNTRY1}",$pub_coun);
$template->setValue("{CO1}",$res[5]);
$template->setValue("{CO}",$ctrstr1);
$ctrstr=$mysql->echo_one("select name from location_country where code='$ctrstr1'");
$template->setValue("{EMAILFIELD}",$mysql->echo_one("select email from ppc_users where uid=".$user->getUserID()));
$template->setValue("{COUNTRY}",$ctrstr);
$template->setValue("{DOMAIN}",$mysql->echo_one("select domain from ppc_users where uid=".$user->getUserID()));
//$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(7004)));
$template->setValue("{FNAMEFIELD}",$mysql->echo_one("select firstname from ppc_users where uid=".$user->getUserID()));
$template->setValue("{LNAMEFIELD}",$mysql->echo_one("select lastname from ppc_users where uid=".$user->getUserID()));
$template->setValue("{PHNOFIELD}",$mysql->echo_one("select phone_no from ppc_users where uid=".$user->getUserID()));
$template->setValue("{ADDRESSFIELD}",$mysql->echo_one("select address from ppc_users where uid=".$user->getUserID()));

$template->setValue("{UNAME}",$mysql->echo_one("select username from ppc_users where uid=".$user->getUserID()));

$res2=mysql_query("select *  from location_country where code NOT IN('A1','A2','AP','EU') order by name");
		//echo mysql_num_rows($res);
		$ctr.="<select name=\"country\" id=\"country\" onChange=\"loadState();\" dir='ltr' >
		<option value=\"\" >-Select Country-</option>";
			while($row=mysql_fetch_row($res2))
			{
				if($row[0]==$ctrstr1)
				$ctr.="<option selected=\"selected\" value=\"$row[0]\">$row[1]</option>";
				else
				$ctr.="<option value=\"$row[0]\">$row[1]</option>";
				
			} 
		$ctr.="</select>";
		
$template->setValue("{EMAIL}",$form->addTextBox("email",$mysql->echo_one("select email from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{COTRY}",$ctr);
$template->setValue("{DOM}",$form->addTextBox("domain",$mysql->echo_one("select domain from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{SUBMIT}",$form->addSubmit($template->checkAdvMsg(8887)));
$template->setValue("{FNAME}",$form->addTextBox("firstname",$mysql->echo_one("select firstname from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{LNAME}",$form->addTextBox("lastname",$mysql->echo_one("select lastname from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{PHNO}",$form->addTextBox("phone_no",$mysql->echo_one("select phone_no from ppc_users where uid=".$user->getUserID()),40,255));
$template->setValue("{ADDRESS}",$form->addTextArea("address",$mysql->echo_one("select address from ppc_users where uid=".$user->getUserID())));
$template->setValue("{USERNAME}",$form->addTextBox("username",$mysql->echo_one("select username from ppc_users where uid=".$user->getUserID()),40,255));
$atax=$mysql->echo_one("select taxidentification from ppc_users where uid=".$user->getUserID());
$template->setValue("{TAXID}",'<input type="text" name="taxidentification" id="taxidentification" size="40" value="'.$atax.'">');
$template->setValue("{ADVTAX}",$atax);
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}

$confirm="<input id=\"account_radio_1\" name=\"radconf\" type=\"radio\" value=\"1\" onClick=\"show_detail(1)\" checked> ".$template->checkAdvMsg(8884)."&nbsp;";
	$confirm1="<input   name=\"radconf\" type=\"radio\" value=\"2\" onClick=\"show_detail(2)\"> ".$template->checkAdvMsg(8885);
$template->setValue("{CONFIRM}",$confirm);
$template->setValue("{CONFIRM1}",$confirm1);


$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));  

$template->setValue("{PAGEWIDTH}",$page_width);  
$template->setValue("{ENCODING}",$ad_display_char_encoding);  

$template->setValue("{PID}",$res[0]);  
	$template->setValue("{FORMSTART}",$form->formStart());
$template->setValue("{FORMCLOSE}",$form->formClose());	
}

}
}
eval('?>'.$template->getPage().'<?php ');
?>
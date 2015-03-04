<?php

include("config.inc.php");
if(!isset($_COOKIE['inout_admin']))
{
	header("Location:index.php");
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location:index.php");
	exit(0);
}
if($script_mode=="demo")
	{
 		include_once("admin.header.inc.php");
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}

$language="en";
$str="";
$estr="";
$type=$_REQUEST['type'];

if(!isset($_REQUEST['type']) || $_REQUEST['type']=="")
{
$type=1;
}

if(isset($_COOKIE['adlanguage']))
{
$language_cookie=$_COOKIE['adlanguage'];
}
else
{
$language_cookie="en";
}

if($type==1)
{
include_once("../locale/advertiser-template-".$language."-inc.php");
ksort($advertiser_message);
$advertiser_temp=$advertiser_message;
foreach($advertiser_message as $key => $value)
{
$str.= "<textarea name='msg_main_".$key."' id='msg_main_".$key."'  style='width: 320px;' readonly  >".htmlspecialchars($value, ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
//$str.= "<input type='text' name='msg_main_".$key."' id='msg_main_".$key."' value=\"".htmlspecialchars($value, ENT_QUOTES, $ad_display_char_encoding)."\" style='width: 320px;' readonly  /><br><br>";
}

}
if($type==2)
{
include_once("../locale/publisher-template-".$language."-inc.php");
ksort($publisher_message);
$publisher_temp=$publisher_message;
foreach($publisher_message as $key => $value)
{
$str.= "<textarea name='msg_main_".$key."' id='msg_main_".$key."'  style='width: 320px;' readonly  />".htmlspecialchars($value, ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
}
}
if($type==3)
{
include_once("../locale/messages.".$language.".inc.php");
ksort($message);
$message_temp=$message;
foreach($message as $key => $value)
{
$str.= "<textarea name='msg_main_".$key."' id='msg_main_".$key."'  style='width: 320px;' readonly  />".htmlspecialchars($value, ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
}
}
if($type==4)
{ 
include_once("../locale/common-template-".$language."-inc.php");
ksort($common_message);
$common_temp=$common_message;
foreach($common_message as $key => $value)
{
	
$str.= "<textarea name='msg_main_".$key."' id='msg_main_".$key."'  style='width: 320px;' readonly  />".htmlspecialchars($value, ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
}
}
if($type==""||$type==1)
{

	$language_cookie1=$language_cookie;
	 if(!file_exists("../locale/advertiser-template-".$language_cookie."-inc.php"))
	 {
	 $language_cookie1="en";
	 }
include_once("../locale/advertiser-template-".$language_cookie1."-inc.php");


foreach($advertiser_temp as $key => $value)
{
$estr.= "<textarea name='emsg_main_".$key."' id='msg_main_".$key."'   style='width: 320px;'   >".htmlspecialchars($advertiser_message[$key], ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
	
//$estr.= "<input type='text' name='emsg_main_".$key."' id='msg_main_".$key."' value=\"".htmlspecialchars($advertiser_message[$key], ENT_QUOTES, $ad_display_char_encoding)."\" style='width: 320px;'   /><br><br>";
}
}
if($type==2)
{$language_cookie1=$language_cookie;
	if(!file_exists("../locale/publisher-template-".$language_cookie."-inc.php"))
	 $language_cookie1="en";
include_once("../locale/publisher-template-".$language_cookie1."-inc.php");
//print_r($publisher_message)."<br><br>";



foreach($publisher_temp as $key => $value)
{
$estr.= "<textarea name='emsg_main_".$key."' id='msg_main_".$key."'  style='width: 320px;'   />".htmlspecialchars($publisher_message[$key], ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
}
}
if($type==3)
{$language_cookie1=$language_cookie;
	if(!file_exists("../locale/messages.".$language_cookie.".inc.php"))
	 $language_cookie1="en";
include_once("../locale/messages.".$language_cookie1.".inc.php");
//print_r($message)."<br><br>";



foreach($message_temp as $key => $value)
{
$estr.= "<textarea name='emsg_main_".$key."' id='emsg_main_".$key."'  style='width: 320px;'   />".htmlspecialchars($message[$key], ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
}
}


if($type==4)
{$language_cookie1=$language_cookie;
	if(!file_exists("../locale/common-template-".$language_cookie."-inc.php"))
	{
	 $language_cookie1="en";
	}
include_once("../locale/common-template-".$language_cookie1."-inc.php");
//print_r($publisher_message)."<br><br>";



foreach($common_temp as $key => $value)
{
$estr.= "<textarea name='emsg_main_".$key."' id='msg_main_".$key."'  style='width: 320px;'   />".htmlspecialchars($common_message[$key], ENT_QUOTES, $ad_display_char_encoding)."</textarea><br>";
}
}

$ctrstr="";
$res=mysql_query("select * from adserver_languages where status='1' order by language asc");
//		echo mysql_num_rows($res);
$i=0;
		$ctrstr.="<select name=\"lang\" id=\"lang\" onChange=\"changelanguage();\" style=\"width:100px\">";
			while($row=mysql_fetch_row($res))
			{
				
				
				$ctrstr.="<option  value=\"$row[3]\"";
				if($language_cookie == $row[3])
				$ctrstr.="selected";
				
				
				$ctrstr.=">$row[1] </option>";
				
				
				
			} 
		$ctrstr.="</select>";
include("admin.header.inc.php"); 

?>
<script type="text/javascript">

function changelanguage()
{
var lan;
lan=document.getElementById('lang').value;

Set_Cookie('adlanguage',lan,0, "/") ;
var langcook=Get_Cookie('language');

window.location.href = window.location.href;
}





function Set_Cookie( name, value, expires, path, domain, secure ) 
{	


	document.cookie = name + "=" +escape( value ) +

		( ( path ) ? ";path=" + path : "" ) + 

		( ( domain ) ? ";domain=" + domain : "" ) +

		( ( secure ) ? ";secure" : "" );

}


function Get_Cookie( name )
 {
	var start = document.cookie.indexOf( name + "=" );

	var len = start + name.length + 1;
		
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) )
	{
		return null;
	}

	if ( start == -1 ) return null;

	var end = document.cookie.indexOf( ";", len );

	if ( end == -1 ) end = document.cookie.length;

	return unescape( document.cookie.substring( len, end ) );
	

}



function Delete_Cookie( name, path, domain ) 
{
	if ( Get_Cookie( name ) ) document.cookie = name + "=" +

			( ( path ) ? ";path=" + path : "") +

			( ( domain ) ? ";domain=" + domain : "" ) +

			";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

</script>
<table width="100%"  border="0"   cellpadding="0" cellspacing="0">
<tr>
	
    <td colspan="3"   scope="row"><br><a href="ppc-edit-messages.php?type=1">Advertiser Template Content</a> | <a href="ppc-edit-messages.php?type=2">Publisher  Template Content</a> | <a href="ppc-edit-messages.php?type=4">SSO Template Content</a> | <a href="ppc-edit-messages.php?type=3">System Messages</a>  </td>
    </tr>
		<tr><td height="25" colspan="3" scope="row"></td>
		</tr><tr>
   <td   colspan="4" scope="row" class="heading">Edit <?php  if($type==1) echo "Advertiser Messages";elseif($type==2) echo "Publisher Messages";elseif($type==4) echo "Single Sign On Messages";else echo "System Messages"; ?></td>
  </tr>
 
		<tr><td height="25" colspan="3" scope="row"></td>
		</tr>
    <tr>
	<td width="203"  align="left"  ><strong>English language </strong> </td>
	<td width="378" align="center"></td>
	<td width="538" align="right"><strong> Choose  Language To Change</strong>&nbsp; <?php echo $ctrstr;  ?></td>
    </tr>
	<tr><td style="height:20px;" colspan="3">
	</td></tr>
 
	</table>  
	
  <form action="ppc-edit-message-action.php" method="post" enctype="multipart/form-data">
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="18%" align="left"><div style="float: left;"><?php echo $str; ?></div></td>
    <td width="31%">&nbsp;&nbsp;
      <input type="hidden" name="type" value="<?php echo $type; ?>" > </td>
    <td width="11%">&nbsp;&nbsp;
      <input type="hidden" name="language" value="<?php echo $language_cookie; ?>" > </td>
    <td width="40%" align="right"><div style="float: right;"><?php echo $estr; ?></div></td></tr>
    <tr><td colspan="4" align="center"><div ><input type="submit" name="submit" value="Update"></div></td></tr>
    </table>
    </form> 
	
	
<?php include("admin.footer.inc.php"); ?>
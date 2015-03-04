<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php

include("config.inc.php");
include("../extended-config.inc.php");  
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


?><?php include("admin.header.inc.php"); ?>
<?php

$id=$_GET['id'];
phpsafe($id);
$action=$_GET['action'];

$url=$_GET['url'];	 
$preview="";

$result=mysql_query("select b.title,b.link,b.summary,b.displayurl,b.adtype from ppc_users a,ppc_ads b where a.uid=b.uid and b.id='$id'");
$row=mysql_fetch_row($result);
 if($row[4]==0) 
 { 
	 $dispurl=$row[3];
	 if($dispurl=="")
	 {
	 $dispurl=$row[1];
	 }
	 $preview = "<a href=\"$row[1]\">$row[0]</a><br>$row[2]<br>$dispurl<br>"; 
 } 
 else 
 {
  	$preview = "<table><tr><td><a href=\"$row[1]\"><img src=\"{$server_dir}".$GLOBALS['banners_folder']."/$id/$row[2]\"  border=\"0\" ></a></td></tr></table>";
 }
 


$name=$mysql->echo_one("select b.username from ppc_ads a,ppc_users b where a.uid=b.uid and a.id='$id'");

if($action=="block")
	$type=10;
else
	$type=9;

$sub=$mysql->echo_one("select email_subject from email_templates where id='$type'");
$sub=str_replace("{USERNAME}",$name,$sub);
$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
$body=$mysql->echo_one("select email_body from email_templates where id='$type'");
$body=str_replace("{USERNAME}",$name,$body);
$body=str_replace("{TYPE}","PPC",$body);
$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
$preview="Ad id : $id";
$body=str_replace("{AD_ID}",$preview,$body);
?>

<form action="ppc-change-ad-status-action.php" method="post" enctype="multipart/form-data" name="form1">
<input type="hidden" name="url" value="<?php echo $url; ?>" />
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="34" colspan="5"><span class="inserted">
    To complete the action click the button below.
      </span></td>
    </tr>
  <tr>
    <td height="34" colspan="5"><strong>Mail Subject</strong><br><br>
      <input name="subject" type="text" id="subject" size="60" value="<?php echo $sub;?>">      <br></td>
  </tr>
  <tr>
    <td height="10" colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td height="34" colspan="4"><strong>Mail Body</strong><br><br>
  <textarea name="text" cols="50" rows="10"><?php echo html_entity_decode($body,ENT_QUOTES); ?></textarea></td>
    <td width="17%"><strong> </strong></td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td >&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td align="left">
    <input name="send_mail" type="checkbox" id="send_mail" value="1" checked >
  Send email notification  
  <?php
  if($script_mode=="demo")
 {
 echo "(Mail will not be send in demo.)";
 }
 ?> </td>
  <td colspan="4" >&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td colspan="4" >&nbsp;</td>
</tr>
<tr>
    <td width="0%"><input type="submit" name="Submit" value="Proceed !"></td>
    <td colspan="4" >&nbsp;</td>
  </tr>

<tr>
  <td><label>
   
  </label></td>
  <td colspan="4" >&nbsp;</td>
</tr>
</table>
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="id" value="<?php echo $id;?>">

</form>

<?php include("admin.footer.inc.php"); ?>

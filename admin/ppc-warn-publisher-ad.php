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
$pid=$_GET['id'];
phpsafe($pid);
$type=$_GET['type'];
$url=urldecode($_GET['url']);
	
	 
/* $preview="";

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
  	$preview = "<table><tr><td><a href=\"$row[1]\"><img src=\"{$server_dir}ppc-banners/$id/$row[2]\"  border=\"0\" ></a></td></tr></table>";
 }
  */


$name=$mysql->echo_one("select username from ppc_publishers where uid='$pid'");

	$type=13;

$sub=$mysql->echo_one("select email_subject from email_templates where id='$type'");
$sub=str_replace("{USERNAME}",$name,$sub);
$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
$body=$mysql->echo_one("select email_body from email_templates where id='$type'");
$body=str_replace("{USERNAME}",$name,$body);
$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
//$body=str_replace("{AD_PREVIEW}",$preview,$body);
?>

<form action="ppc-send-warning-mail.php" method="post" enctype="multipart/form-data" name="form1">
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
  <textarea name="text" cols="50" rows="10"><?php echo html_entity_decode($body,ENT_QUOTES); ?></textarea>
      
</td>
    <td width="17%"><strong> </strong></td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td >&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td >&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td>Revert last 24 hours  Clicks&nbsp;&nbsp;&nbsp;
    <input type="checkbox" name="repeet_clicks" id="repeet_clicks" value="1" checked="checked"></td>
  <td ></td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td >&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
    <td width="0%"><input type="submit" name="Submit" value="Proceed !"></td>
    <td colspan="4" >&nbsp;</td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td colspan="4" >&nbsp;</td>
</tr>
<tr>
  <td><?php if($script_mode=="demo") echo "<strong>Note:</strong> Mail will not be send in demo.";?>&nbsp;</td>
  <td colspan="4" >&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td colspan="4" >&nbsp;</td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td colspan="4" >&nbsp;</td>
</tr>
</table>
<input type="hidden" name="pid" value="<?php echo $pid;?>">
<input type="hidden" name="url" value="<?php echo $url; ?>">
<input type="hidden" name="category" value="<?php echo $_GET['category'];?>">
</form>

<?php include("admin.footer.inc.php"); ?>

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
	?>
<?php
$uid=$_GET['id'];
phpsafe($uid);
$url=$_GET['url'];
$name=$mysql->echo_one("select username from nesote_inoutscripts_users where id=$uid");

?>
<?php include("admin.header.inc.php");
$type=28;
$sub=$mysql->echo_one("select email_subject from email_templates where id='$type'");
$sub=str_replace("{USERNAME}",$name,$sub);
$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
$body=$mysql->echo_one("select email_body from email_templates where id='$type'");
$body=str_replace("{USERNAME}",$name,$body);
$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);
$body=str_replace("{PUB_LOGIN_PATH}",$server_dir."login.php",$body);
 ?>
 
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/users.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Reject User</td>
  </tr>
</table>

<form action="user-reject-action.php" method="post" enctype="multipart/form-data" name="form1">
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
</table>
<input type="hidden" name="uid" value="<?php echo $uid;?>">
</form>

<?php 
include("admin.footer.inc.php");
?>
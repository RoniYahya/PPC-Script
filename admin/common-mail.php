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


?><?php include("admin.header.inc.php"); ?>

<style type="text/css">
<!--
.style1 {color: #0000FF}
.style2 {color: #666666}
.style3 {color: #FF0000}
-->
</style>
<script language="javascript" type="text/javascript">
function editMail()
{
document.getElementById('sub_but').style.display="";
document.getElementById('edit_but').style.display="none";
document.getElementById('email_body').readOnly=false;
document.getElementById('email_sub').readOnly=false;
}
</script>
<?php
$msg="Welcome Email";
$type=trim($_GET['type']);
if($type=="")
	$type=24;

if($type==25)
{	
	$msg=" Email Verification mail";
}

if($type==26)
{	
	$msg=" Block mail";
}

if($type==27)
{	
	$msg=" Activation mail";
}

if($type==28)
{	
	$msg=" Reject mail";
}

if($type==29)
{	
	$msg=" Approval mail";
}

if($type==30)
{	
	$msg=" Status Change mail";
}
if($type==23)
{	
	$msg="Password Recovery Mail";
}
$email_found=false;	
if($mysql->total("email_templates","id='$type'")>0)	
	$email_found=true;	
?>
<body>
<form name="form1" method="post" action="email-update-action.php" enctype="multipart/form-data">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td   scope="row">&nbsp;</td>
    </tr>
    <tr>
	  
      <td  ><a href="common-mail.php?type=25">Email Verification Mail</a> | <a href="common-mail.php?type=24">Welcome Mail</a> | <a href="common-mail.php?type=29">Approve Mail</a> | <a href="common-mail.php?type=28">Reject Mail</a> | <a href="common-mail.php?type=26">Block Mail</a> <br><br>

 <a href="common-mail.php?type=27">Activate Mail</a> | <a href="common-mail.php?type=30">Change Status Mail</a> | <a href="common-mail.php?type=23">Password Recovery Mail</a></td>
    </tr>
  </table>
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4"  scope="row">&nbsp;
	  <input type="hidden" name="redir" value="<?php echo urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>">
	   <input type="hidden" name="type" value="<?php echo $type;?>">	  </td>
    </tr>
    <tr>
	<td height="32"  colspan="4" class="heading">   <?php echo $msg;?>     </td>
    </tr>
	  <?php 
	if($email_found)
	{
	?>

    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" scope="row">Subject&nbsp;<br>
        <input type="text" name="email_sub" id="email_sub" size="90" value="<?php echo $mysql->echo_one("select email_subject from email_templates where id='$type'");?>" readonly></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="17%" scope="row">&nbsp;</td>
      <td width="80%" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="0%" scope="row">&nbsp;</td>
      <td colspan="2" scope="row">Body&nbsp;&nbsp;&nbsp;&nbsp;<br>
	    <textarea  name="email_body" id="email_body" cols="80" rows="20" readonly><?php echo $mysql->echo_one("select email_body from email_templates where id='$type'");?> </textarea></td>
      <td width="3%">&nbsp;</td>
    </tr>

	<?php
	}
	else
	{
	?>
    <tr>
      <td colspan="4"  scope="row"></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" valign="middle" scope="row">Subject&nbsp;<br>  
        <input type="text" name="email_sub" id="email_sub" size="90"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td  valign="middle" scope="row">&nbsp;</td>
      <td  valign="middle" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2"  valign="middle" scope="row">Body
      <br>        <textarea name="email_body" id="email_body" cols="80" rows="20" ></textarea></td>
      <td width="3%">&nbsp;</td>
    </tr>

	<?php
	}
	?>
    <tr>
      <td scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2"  scope="row">
        <?php
	  if ($email_found)
	  {
	  ?>
		  <input type="button" value="Edit" id="edit_but" onClick="javscript:editMail()">
		  <input type="submit" value="Update" id="sub_but" style="display:none; ">
        <?php
	  }
	  else
	  {
	  ?>
  	    <input type="submit" value="Update" id="sub_but">
        <?php
	  }
	  ?>	  </td>
      <td>&nbsp;</td>
    </tr>
	    <tr>
      <td scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" align="left" scope="row">Note:Variables you can use are given below. </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" align="left" scope="row"> {USERNAME} - will be replaced by username. <br>
	    <?php if($type==23){ ?>
{PASSWORD}- will be replaced by the temporary password. <br>
<?php }?>
	 
 <?php if($type==25){ ?>
{CONFIRM_PATH}- will be replaced by the email confirmation link.<br>
<?php }?>
{ENGINE_NAME}- will be replaced by the adserver name. <br>
 <?php if($type==24){ ?>
{ADV_STATUS}- will be replaced by the advertiser default status.<br>

{PUB_STATUS}- will be replaced by the publisher default status.<br>
<?php }?>
</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td scope="row">&nbsp;</td>
      <td scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="3" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>

<?php include("admin.footer.inc.php");
?>
<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

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
$msg="Publisher Welcome Email";
$type=trim($_GET['type']);
if($type=="")
	$type=2;
if($type==3)
{	
	$msg="Publisher Approval Email";
}
if($type==4)
{	
	$msg="Publisher Rejection Email";
}
if($type==5)
{	
	$msg="Publisher Block Email";
}
if($type==6)
{	
	$msg="Publisher Activation Email";
}
if($type==14)
{	
	$msg="Fraud Publisher Block Email";
}
if($type==13)
{	
	$msg="Fraud Publisher Warning Email";
}
if($type==20)
{	
	$msg=" Publisher Email Verification Mail";
}
if($type==22)
{	
	$msg=" Publisher password recovery mail";
}
$email_found=false;	
if($mysql->total("email_templates","id='$type'")>0)	
	$email_found=true;	
?>
<form name="form1" method="post" action="email-update-action.php" enctype="multipart/form-data">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td  scope="row"><a href="publisher-mail.php?type=20">Email Id Verification Mail</a> | <a href="publisher-mail.php?type=2">Welcome Mail</a> | <a href="publisher-mail.php?type=3">Approval Mail</a> | <a href="publisher-mail.php?type=4">Rejection Mail</a>   | <a href="publisher-mail.php?type=5">Block Mail</a> <br />
<br />
 <a href="publisher-mail.php?type=6">Activation Mail</a> |  <a href="publisher-mail.php?type=13">Fraud  Warning Email</a> | <a href="publisher-mail.php?type=14">Fraud  Block Email</a> | <a href="publisher-mail.php?type=22">Password Recovery Mail</a>	  </td>
    </tr>
  </table>
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4"  scope="row">&nbsp;
	  <input type="hidden" name="redir" value="<?php echo urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>">
	   <input type="hidden" name="type" value="<?php echo $type;?>">	  </td>
    </tr>
    <tr>
   	  <td height="35" colspan="4" class="heading">        <?php echo $msg;?>     </td>
    </tr>
	  <?php 
	if($email_found)
	{
	?>

    <tr>
      <td height="33" scope="row">&nbsp;</td>
      <td colspan="2" scope="row">Subject&nbsp;<br />
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
      <td colspan="2" scope="row">Body&nbsp;&nbsp;&nbsp;&nbsp;<br />
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
      <td colspan="2" valign="middle" scope="row">Subject&nbsp;<br />  
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
      <td colspan="2"  valign="middle" scope="row">Body&nbsp;&nbsp;&nbsp;&nbsp;
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
      <td colspan="2" align="left" scope="row" class="info"><strong>Note : </strong>Variables you can use are given below</td>
      <td></td>
    </tr>
  	    <tr>
      <td scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
      <td align="left" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" align="left" scope="row">
	  {USERNAME} - will be replaced by publisher username.<br>
	  <?php if($type==22){?>
	{PASSWORD}- will be replaced by the temporay password.<br>
	<?php }?>
	<?php if($type==20){?>
	{PUB_CONFIRM_PATH}-will be replaced by confirmation link.<br>
	<?php }?>
	{ENGINE_NAME}- will be replaced by the adserver name.<br>
	<?php if($type==3 || $type==6) {?>
	{PUB_LOGIN_PATH}-will be replaced by publisher login path.<br>
	<?php }?> </td>
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

<?php include("admin.footer.inc.php"); ?>
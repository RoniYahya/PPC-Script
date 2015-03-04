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
function trim(stringValue){return stringValue.replace(/(^\s*|\s*$)/, "");}

function verifyform()
{
var a=document.getElementById("email_sub").value;
//alert(a.trim());
if(trim(a).length==0)
{
alert("Subject/body cannot be null.");
//document.ppc-search.s_keyword.focus();

}
}
</script>

<?php
$msg="Ad Activation Email";
$type=trim($_GET['type']);
if($type=="")
	$type=9;
if($type==10)
{	
	$msg="Ad Block Email";
}
if($type==11)
{	
	$msg="Ad Deletion Email";
}
if($type==16)
{	
	$msg="Monthly/Daily Budget crossed";
}

$email_found=false;	
if($mysql->total("email_templates","id='$type'")>0)	
	$email_found=true;	
?>
<form name="form1" method="post" action="email-update-action.php" enctype="multipart/form-data" >
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td  align="center" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td   scope="row"><a href="ad-mail.php?type=9">Activation Email</a> | <a href="ad-mail.php?type=10">Block Mail</a> | <a href="ad-mail.php?type=11">Deletion Mail</a> | <a href="ad-mail.php?type=16">Monthly/Daily Budget crossed</a></td>
    </tr>
  </table>
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4" align="center" scope="row">&nbsp;
	  <input type="hidden" name="redir" value="<?php echo urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>">
	   <input type="hidden" name="type" value="<?php echo $type;?>">	  </td>
    </tr>
    <tr>
     	  <td width="200" height="35" colspan="4" scope="row" class="heading">         <?php echo $msg;?>     </td>
    </tr>
	  <?php 
	if($email_found)
	{
	?>

    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" scope="row">Subject&nbsp;<br />
        <input type="text" name="email_sub" id="email_sub" size="90" onchange="return verifyform()" value="<?php echo $mysql->echo_one("select email_subject from email_templates where id='$type'");?>" readonly></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="17%" scope="row">&nbsp;</td>
      <td width="80%" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="200" scope="row">&nbsp;</td>
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
      <td colspan="4" align="center" scope="row"></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td colspan="2" valign="middle" scope="row">Subject&nbsp;<br />  
        <input type="text" name="email_sub" id="email_sub" size="90"></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td align="center" valign="middle" scope="row">&nbsp;</td>
      <td align="center" valign="middle" scope="row">&nbsp;</td>
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
      <td colspan="2" align="left" scope="row" class="info"><strong>Note:</strong>Variables you can use are given below.</td>
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
      <td colspan="2" align="left" scope="row"> {USERNAME} - will be replaced by advertiser username. <br>
{ENGINE_NAME}- will be replaced by the adserver name. <br>
{AD_ID}-will be replaced by ad id. </td>
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
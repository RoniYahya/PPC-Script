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

<style type="text/css">
<!--
.style1 {color: #0000FF}
.style2 {color: #666666}
.style3 {color: #FF0000}
-->
</style>
<script language="javascript" type="text/javascript">
function editTerms()
{
document.getElementById('sub_but').style.display="";
document.getElementById('edit_but').style.display="none";
document.getElementById('terms').readOnly=false;
}
</script>
<?php
$user="Advertiser";
$type=$_GET['type'];
if($type!=0)
{	
	$type=1;
	$user="Publisher";
}
$terms_found=false;	
if($mysql->total("terms_and_conditions","type=$type")>0)	
	$terms_found=true;	
?>
<form name="form1" method="post" action="terms-update-action.php" enctype="multipart/form-data">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td   scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td   scope="row"><a href="terms-conditions-settings.php?type=0">Advertiser Terms And Conditions</a> | <a href="terms-conditions-settings.php?type=1">Publisher Terms And Conditions</a></td>
    </tr>
  </table>
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3"  scope="row">&nbsp;
          <input type="hidden" name="redir" value="<?php echo urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>">
          <input type="hidden" name="type" value="<?php echo $type;?>">
      </td>
    </tr>
    <tr>
      <td height="41" colspan="3" class="heading"> <?php echo $user;?> Terms &amp; Conditions</td>
    </tr>
    <?php 
	if($terms_found)
	{
	?>
    <tr>
      <td height="41" colspan="3" class="inserted">Terms &amp; Conditions are displayed below. You may edit the same.</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td scope="row"><textarea  name="terms" id="terms" cols="80" rows="50" readonly>
	  <?php echo $mysql->echo_one("select terms from terms_and_conditions where type=$type");?>
	  </textarea></td>
      <td width="3%">&nbsp;</td>
    </tr>
    <?php
	}
	else
	{
	?>
    <tr>
      <td colspan="3"  class="inserted">Please input terms &amp; conditions</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td scope="row"><textarea name="terms" id="terms" cols="80" rows="50" >
	  
	  </textarea></td>
      <td width="3%">&nbsp;</td>
    </tr>
    <?php
	}
	?>
    <tr>
      <th scope="row">&nbsp;</th>
      <th align="left" scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td  scope="row"> <?php
	  if ($terms_found)
	  {
	  ?>
          <input name="button" type="button" id="edit_but" onClick="javscript:editTerms()" value="Edit">
          <input name="submit" type="submit" id="sub_but" style="display:none; " value="Update">
          <?php
	  }
	  else
	  {
	  ?>
          <input name="submit" type="submit" id="sub_but" value="Update">
          <?php
	  }
	  ?>
      </td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <th align="left" scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <th scope="row">&nbsp;</th>
      <th align="left" scope="row">&nbsp;</th>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" scope="row">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
</form>
<?php include("admin.footer.inc.php"); ?>
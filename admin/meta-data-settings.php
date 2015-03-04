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
function editMeta()
{
document.getElementById('sub_but').style.display="";
document.getElementById('edit_but').style.display="none";
document.getElementById('keywords').readOnly=false;
document.getElementById('desc').readOnly=false;
}
</script>
<?php
$user="Advertiser";
$type=$_GET['type'];
if($type!=0)
{	
	if($type==1)
	$user="Publisher";
	else
	$user="Common";
}
$meta_found=false;	
if($mysql->total("site_content","item_type=$type and (item_name='meta-keywords' or item_name='meta-description')")>0)	
	$meta_found=true;	
?>
<form name="form1" method="post" action="meta-data-update-action.php" enctype="multipart/form-data">
  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td   scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td   scope="row"><a href="meta-data-settings.php?type=0">Meta Data For Advertiser Pages</a> | <a href="meta-data-settings.php?type=1">Meta Data For Publisher Pages</a> | <a href="meta-data-settings.php?type=2">Meta Data For Common Pages</a></td>
    </tr>
  </table>
	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr>
      <th colspan="3"  scope="row">&nbsp;
	  <input type="hidden" name="redir" value="<?php echo urlencode("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>">
	   <input type="hidden" name="type" value="<?php echo $type;?>">	  </th>
    </tr>
	<?php 
	if($meta_found)
	{
	?>
	
	
	 <tr>
      <td colspan="3"  class="heading"><?php echo $user;?> meta data</td>
    </tr>
	
	    <tr>
      <th colspan="3" align="left" scope="row"><br />
<span class="inserted"><?php echo $user;?> meta data is displayed . You may edit the same. </span></th>
    </tr>
		<tr>
      <th scope="row"></th>
      <th scope="row" align="left"></th>
      <td width="3%">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="2" align="left" scope="row">Keywords (comma separated)<br />

  <input type="text" name="keywords" id ="keywords" value="<?php echo $mysql->echo_one("select item_value from site_content where item_type=$type and item_name='meta-keywords'");?>" size="75" readonly></td>
      <td width="3%">&nbsp;</td>
    </tr>
	
	<tr>
      <th scope="row"></th>
      <th scope="row" align="left"></th>
      <td width="3%">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="left" scope="row">Description<br />

      <textarea  name="desc" id="desc" cols="65" rows="10" readonly><?php echo $mysql->echo_one("select item_value from site_content where item_type=$type and item_name='meta-description'");?></textarea></td>
      <td width="3%">&nbsp;</td>
    </tr>

	<?php
	}
	else
	{
	?>
    <tr>
    
	  	  <td width="200" height="65" colspan="4" scope="row"><div ><span class="style7" style="font-size: 20px"><u>         Please input meta data for <?php echo strtolower($user);?> pages     </u></span></div></td>
	<tr>
      <th scope="row"></th>
      <th scope="row" align="left"></th>
      <td width="3%">&nbsp;</td>
    </tr>
	    <tr>
      <td colspan="2" align="left" scope="row">Keywords (comma separated)<br />

   <input type="text" name="keywords" id ="keywords" value="" size="75">		</td>
      <td width="3%">&nbsp;</td>
    </tr>
		    <tr>
      <th scope="row"></th>
      <th scope="row" align="left"></th>
      <td width="3%">&nbsp;</td>
    </tr>

    <tr>
      <td colspan="2" align="left" scope="row">Description<br />

      <textarea name="desc" id="desc" cols="65" rows="10" ></textarea></td>
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
      <td colspan="2" scope="row">
        <?php
	  if ($meta_found)
	  {
	  ?>
		  <input type="button" value="Edit" id="edit_but" onClick="javscript:editMeta()">
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
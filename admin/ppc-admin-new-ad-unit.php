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
<script type="text/javascript">
function show()
{
var dev=document.getElementById('device').value;
if(dev==0)
{
document.getElementById('wap').style.display="none";
document.getElementById('desktop').style.display="block";
}
else
{
document.getElementById('wap').style.display="block";
document.getElementById('desktop').style.display="none";
}
}
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/admin-adunits.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Ad Unit</td>
  </tr>

</table>

<form action="admin-new-ad-unit.php" method="post" enctype="multipart/form-data">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
  <td width="26%">&nbsp;</td>
  <td width="1%">&nbsp;</td>
  <td width="73%">&nbsp;</td>
</tr>
<tr>
    <td height="28">Target Device</td>
    <td >:</td>
    <td >
    <select name="device" id="device" onchange="show();">
    <option value="0">Desktop&Laptop</option>
     <option value="1">Wap</option>
    </select></td></tr>
<tr>
    <td height="27">Preferred Language</td>
    <td >:</td>
    <td >
   <select name="language" id="language" >

<?php 

$res=mysql_query("select id,language,code from adserver_languages  where status='1'");

while($row=mysql_fetch_row($res))
	{
		
		echo "<option value=\"$row[0]\">$row[1] </option>";	
	}
	?>
	<option value="0" selected="selected">Any Languages</option>
</select>    </td>
    </tr>
  <tr>
    <td height="28">Tracking Name</td>
    <td >:</td>
    <td ><label>
      <input name="tracking_name" type="text" id="tracking_name">
    </label></td>
  </tr>
  <tr>
  
    <td height="25">Select Ad Block</td>
    <td height="25" >:</td>
    <td ><div id="desktop" style="display: none;">
      <select name="select_ad_block" id="select_ad_block" onchange="show();">
      <?php
  $result=mysql_query("select id,width,height,ad_type,ad_block_name from ppc_ad_block where status=1 order by id ");
  $num=mysql_num_rows($result);
  ?>
	  <?php 
	  while($row=mysql_fetch_row($result))
	  	{
		 if($row[3]==1)
	{
	$ad_type="Text only";
	}
elseif($row[3]==2)
	{
	$ad_type="Banner only";
	}
elseif($row[3]==4)
	{
	$ad_type="Catalog only";

	}
	elseif($row[3]==7)
	{
	$ad_type="Inline Catalog only";
	}
	elseif($row[3]==6)
	{
	$ad_type="Inline Text only";
	}

else
	{
	$ad_type="Text/Banner";
	}
		?>
        <option value="<?php echo $row[0]; ?>"> <?php echo "$row[4]&nbsp;($row[1] X $row[2])"; ?>&nbsp;<?php echo $ad_type;?></option>
		<?php } ?>
      </select></div>
      <div id="wap" style="display: none;">
      <select name="select_ad_block1" id="select_ad_block1">
       <?php
  $result=mysql_query("select id,width,height,ad_type,ad_block_name from wap_ad_block where status=1 order by id ");
  $num=mysql_num_rows($result);
  ?>
	  <?php 
	  while($row=mysql_fetch_row($result))
	  	{
		 if($row[3]==1)
	{
	$ad_type="Text only";
	}
elseif($row[3]==2)
	{
	$ad_type="Banner only";
	}
elseif($row[3]==4)
	{
	$ad_type="Catalog only";

	}
	elseif($row[3]==7)
	{
	$ad_type="Inline Catalog only";
	}
	elseif($row[3]==6)
	{
	$ad_type="Inline Text only";
	}

else
	{
	$ad_type="Text/Banner";
	}
		?>
        <option value="<?php echo $row[0]; ?>"> <?php echo "$row[4]&nbsp;($row[1] X $row[2])"; ?>&nbsp;<?php echo $ad_type;?></option>
		<?php } ?>
      </select>
    </div>    </td>
  </tr>
    <tr>
    <td height="28">Include adult ad</td>
    <td >:</td>
    <td ><label>
     No  <input type="radio" name="adult_status" value="0" id="adult_status_no"  checked="checked">&nbsp;&nbsp;
                   Yes  <input type="radio" name="adult_status" value="1" id="adult_status_yes">
    </label></td>
  </tr>
  <tr>
  <tr>
    <td ><label></label>    </td>
    <td >&nbsp;</td>
    <td ><input type="submit" name="Submit" value="Submit" /></td>
  </tr>
</table>
</form>
<p>&nbsp;</p>
<?php include("//admin.footer.inc.php"); ?>
<script type="text/javascript">
show();
</script>
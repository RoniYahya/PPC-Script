<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







 

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

include("admin.header.inc.php"); 


if($chat_visible_status!=1)
{
	 mysql_query("update ppc_settings set value='1' where name='chat_visible_status'");
}

if(isset($_GET['wap']) && $_GET['wap']==1)
{
$wap_flag=$_GET['wap'];
$wap_string=' wap ';
$query_str="where wapstatus='1'";
$query_string="where wap_status='1'";
}
else
{
	$wap_flag=0;
	$wap_string='';
	$query_str="where wapstatus='0'";
	$query_string="where wap_status='0'";
}

?>

<style type="text/css">
dl, dt, dd, ul, ol, li{margin:0;padding:0;vertical-align:baseline;}
</style>
<script language="javascript">

</script>




<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/chatusers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Chat Settings</td>
  </tr>
</table>
<br />

<form enctype="multipart/form-data"  name="form1" action="chat_setting_action.php" method="post" >

	
	
<table width="100%"  border="0"  cellpadding="2" cellspacing="0" style="table-layout:fixed;border:1px solid #CCCCCC;">
<tr bgcolor="#b7b5b3" height="25px">
<td width="35%"   ><strong>Property</strong></td>
<td width="55%"   ><strong>Value</strong></td>

</tr>


<tr>

      <td colspan="2"	width="100%">
			
		<?php	 
  
   $res=mysql_query("SELECT * FROM nesote_chat_public_user WHERE status=2");
 $row=mysql_fetch_array($res);  
 ?>
			
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<td width="35%" height="30"  >Admin Chat User name </td>
					<td width="55%" height="30"><input name="chat_name" type="text" id="chat_name" value="<?php echo $row[username];?>"  maxlength="30" /></td>
								 
				
					</tr>
					<tr>
					<td width="35%" height="30"  >Chat Status</td>
					<td width="55%" height="30">
					<select id="chat_status" name="chat_status">
      	  
					<option <?php if($chat_status==1) {?>selected="selected"<?php }?> value="1">On Line</option>
					<option <?php if($chat_status==0) {?>selected="selected"<?php }?>value="0">Off Line</option>
     			 </select>
	  </td>
				
					</tr>
					<tr>
					<td width="35%" height="30"  >Chat Online Image </td>
					<td width="55%" height="30"><input type="file" size="33" id="online_logo" name="online_logo"> &nbsp;
      <br><span class="note">( Max size is 400 x 100 and 100 KB. Please use gif/jpg/png files) </span> 
	       <br>
			<img align="absmiddle" src="../ppc-banners/chat_status/<?php echo $online_image;?>">&nbsp;</td>
								 
				
					</tr>
					<tr>
					<td width="35%" height="30"  >Chat Off Line Image </td>
					<td width="55%" height="30"><input type="file" size="33" id="offline_logo" name="offline_logo"> &nbsp;
      <br><span class="note">( Max size is 400 x 100 and 100 KB. Please use gif/jpg/png files) </span> 
	       <br>
			<img align="absmiddle" src="../ppc-banners/chat_status/<?php echo $offline_image;?>">&nbsp;</td>
								 
				
					</tr>
					 	  
					</table>

</td>
</tr>
<tr>

        </tr>
 
 <tr>
 <td></td>
<td  colspan="2"><input name="Submit" type="submit" id="Submit" value="Submit"> 
</td>
</tr>
   </table>
      
   
</form>
<br>
<?php
 include("admin.footer.inc.php"); ?>

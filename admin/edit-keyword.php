<?php
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
	$keyid=trim($_GET['id']);
	phpSafe($keyid);

	$det=mysql_query("select * from system_keywords where id='$keyid'");
	$detail1=mysql_fetch_row($det);
	include("admin.header.inc.php"); 
	if($_POST)
{
	$keyword=trim($_POST['key']);
	$key_id=trim($_POST['key_id']);
	phpSafe($keyword);
	if($keyword=="")
	{
		?>
		
		<span class="already"><br><?php echo "Please go back and check whether you filled all mandatory fields !";?>         <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
		
		<?php include("admin.footer.inc.php");
		exit;
	}
	if(substr_count($ignore_list," ".$keyword." ")==0)
		{
 $systotal=$mysql->echo_one("select status from system_keywords where keyword='$keyword' and id!=$key_id");
			
							if($systotal=="")
							{
							
	mysql_query("update system_keywords set keyword='$keyword' where id='$key_id'");
	mysql_query("update ppc_keywords set keyword='$keyword' where sid='$key_id'");
				
?>
	<span class="already"><br><?php echo "Your keyword was successfully updated. ";?>         <a href="<?php echo $_REQUEST['url'] ?>">click here  to proceed</a></span><br><br>
<?php	
 include("admin.footer.inc.php");
		exit;	
	
}
else
{ ?>
	<span class="already"><br><?php echo "Already existing keyword, please change your keyword. ";?>         <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
<?php	
 include("admin.footer.inc.php");
		exit;	
}
		}
		else
		{ ?>
			<span class="already"><br><?php echo "This keyword is not allowed, please change your keyword. ";?>         <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
		<?php 
	 include("admin.footer.inc.php");
		exit;		
		}	
	
}
	
?>
<script type="text/javascript">
function check_value()
	{
	
	var key_val=document.getElementById('key').value;
				if(key_val=="")
					{
					alert("Keyword cannot be null");
					document.getElementById('key').value="";
					document.add_keyword.key.focus();
					return false;
					}
					</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/keywords.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Keywords</td>
  </tr>
</table>
<form name="form1" method="post" action="edit-keyword.php">

  <table width="100%"  border="0" cellpadding="0" cellspacing="0">
    <tr >
      <td height="19" colspan="5"  scope="row">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="5"  scope="row"><span class="style2">All Fields marked <span class="style3">*</span> are mandatory </span></td>
    </tr>
    <tr>
      <td scope="row">&nbsp;</td>
      <td width="15%">&nbsp;</td>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td width="15%" align="left" scope="row">Keyword <span class="style3">*</span></td>
      <td width="15%"><input type="text" name="key" id="key" value="<?php echo $detail1[1]; ?> ">
        
      <input type="hidden" name="key_id" value="<?php echo $keyid; ?>">
	   <input type="hidden" name="url" value="<?php echo $_REQUEST['url'] ?>">
        <td><input type="submit" name="Submit" value="Edit Keyword!" onblur="return check_value()" ></td>
    </tr>
<tr>
	  <td colspan="5" scope="row">&nbsp;</td>
    </tr>
     </table>
</form>

<?php include("admin.footer.inc.php"); ?>
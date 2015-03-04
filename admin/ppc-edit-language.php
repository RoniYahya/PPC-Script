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
if($script_mode=="demo")
	{
include("admin.header.inc.php"); 
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}
$id=$_REQUEST['id'];
$result=mysql_query("select * from adserver_languages where id='$id'");
$row=mysql_fetch_row($result);
$no=mysql_num_rows($result);

if($no==0)
{
	header("Location:ppc-manage-language.php");
	exit(0);
}

include("admin.header.inc.php"); 
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/languages.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Edit Language</td>
  </tr>
</table>

<form action="ppc-edit-language-action.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="language_id" value="<?php echo $id  ?>" >

<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td width="331"> Name</td>
    <td width="13">:</td>
    <td width="965"><label>
      <input name="language_name" type="text" value="<?php echo $row[1]; ?>" id="language_name">
    </label></td>
  </tr>
 <tr>
    <td width="331">Encoding</td>
    <td width="13">:</td>
    <td width="965"><label>
      <input name="encoding" type="text"value="<?php echo $row[2]; ?>" id="encoding">
    </label></td>
  </tr>
  <tr>
    <td width="331">Direction</td>
    <td width="13">:</td>
    <td width="965"><label>
      <select name="direction" id="select_direction">
     <?php if($row[4]=="ltr") { ?> <option value="ltr">Left to right</option> <option value="rtl">Right to left</option>
      <?php } else { ?> 
      <option value="rtl">Right to left</option><option value="ltr">Left to right</option><?php }?>
      </select>
    </label></td>
  </tr>
  <tr>
    <td width="331">Language code</td>
    <td width="13">:</td>
    <td width="965"><label>
      <input name="code" type="text" id="code"value="<?php echo $row[3]; ?>">
    </label></td>
  </tr>
  
  <tr>
    <td width="331"> </td>
    <td width="13"></td>
    <td ><label>
      <input type="submit" name="Submit" value="Update" onclick="return check_value()">
    </label></td>
    </tr>
    <tr height="30">
  <td colspan="3">
  </td>
  </tr>
    <tr>
  <td colspan="3" scope="row" class="note"><strong>Note: </strong> <a href="http://www.w3schools.com/tags/ref_language_codes.asp">Refer this link</a> to use correct language code for corrensponding language. </td></tr>
    
    
    
    
    
  </table>
</form>
<p>&nbsp;</p>
<script language="javascript">
function check_value()
				{
				
				if(document.getElementById('language_name').value=="")
					{
					alert("language name should not be blank");
					
					 return false;
					}
					 if (document.getElementById('encoding').value =="")
					 	{
							alert("encoding should not be blank");
							 return false;
						}
						
				
					
					 if (document.getElementById('code').value =="")
					 	{
							alert("language code should not be blank");
							 return false;
						}
						
					
				
				}


</script>
<?php include("admin.footer.inc.php"); ?>

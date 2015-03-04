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
include("admin.header.inc.php"); 

?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/languages.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Language</td>
  </tr>
</table>

<form action="ppc-create-language-action.php" method="post" enctype="multipart/form-data">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="378"> Name</td>
    <td width="13">:</td>
    <td width="920"><label>
      <input name="language_name" type="text" id="language_name">
    </label></td>
  </tr>
 <tr>
    <td width="378">Encoding</td>
    <td width="13">:</td>
    <td width="920"><label>
      <input name="encoding" type="text" id="encoding" value="<?php echo $ad_display_char_encoding; ?>">
    </label></td>
  </tr>
  <tr>
    <td width="378">Direction</td>
    <td width="13">:</td>
    <td width="920"><label>
      <select name="direction" id="select_direction">
      <option value="ltr">Left to right</option>
      <option value="rtl">Right to left</option>
      </select>
    </label></td>
  </tr>
  <tr>
    <td width="378">Language code</td>
    <td width="13">:</td>
    <td width="920"><label>
      <input name="code" type="text" id="code">(Eg:en for English,es for Spanish)
    </label></td>
  </tr>
  <tr>
    <td width="378">  </td>
    <td width="13"></td>
    <td  ><label>
      <input type="submit" name="Submit" value="Create" onclick="return check_value()">
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
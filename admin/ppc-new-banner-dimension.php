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
?><?php include("admin.header.inc.php");
if($script_mode=="demo")
	{ 
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
	}
 ?>
 <style type="text/css">
<!--
.style1 {color: #0000FF}
.style3 {color: #FF0000}
-->
</style>
 <script type="text/javascript">
function check_value()
				{
				//alert(document.getElementById('height').value);
				var val=document.getElementById('width').value;
				var valh=document.getElementById('height').value;
				var vald=document.getElementById('banner_file_max_size').value;
				
				if(!(isInteger(val)))
					{
					alert("Pelase enter a valid width value");
					document.getElementById('width').value="";
					document.form1.width.focus();
					return false;
					}
				else if(!(isInteger(valh)))
					{
					alert("Pelase enter a valid height value");
					document.getElementById('height').value="";
					document.form1.height.focus();
					return false;
					}
				
				else if(!(isInteger(vald)))
					{
					alert("Pelase enter a valid banner size value");
					document.getElementById('banner_file_max_size').value="";
					document.form1.banner_file_max_size.focus();
					return false;
					}
				}
				function isInteger(val)
				{
 				  // alert(val.value);
				  if(val==null)
				    {
			      //alert(val);
			        return false;
				    }
			    if (val.length==0)
				    {
			       // alert(val);
			        return false;
				    }
				 if (trim(val).length == 0) 
				 	{
					 return false;
				 	}
			    for (var i = 0; i < val.length; i++) 
				    {
			        var ch = val.charAt(i)
			        if (i == 0 && ch == "-")
				        {
			            continue
				        }
			        if (ch < "0" || ch > "9")
				        {
			            return false
				        }
				    }
			    return true
			}
			
			function trim(stringValue){
return stringValue.replace(/(^\s*|\s*$)/, "");
}
			
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/banner-dimension.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Create Banner Dimension </td>
  </tr>
</table>
 <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
 <br />

     <form name="form1" method="post" action="ppc-new-banner-dimension-action.php" onSubmit="return check_value()">
  <table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
      	 <td  height="30" align="left">Target Device</td>
		 <td align="left"><select name="target">
		 <option value="0" <?php if($row[4]=="0") echo "selected"; ?>>Desktops & Laptops</option>
		 <option value="1" <?php if($row[4]=="1") echo "selected"; ?>>Wap</option>
		 </select>
      	</td>
      	<td align="center"></td>
      </tr>
  <tr>
      	 <td  height="30" align="left">Width </td>
		 <td align="left"><input type="text" name="width" value="" id="width" >px
      	</td>
      	<td align="center"></td>
      </tr>
       <tr>
      	 <td  height="30" align="left">Height </td>
		 <td align="left"><input type="text" name="height" value="" id="height">px
      	</td>
      	<td align="center"></td>
      	      </tr>
      	       <tr>
      	 <td  height="30" align="left">Banner Size </td>
		 <td align="left"><input type="banner_file_max_size" name="banner_file_max_size" value="" id="banner_file_max_size" >KB
      	</td>
      	<td align="center"></td>
      	      </tr>
      	          <tr>     	 <td  height="30" align="left"> </td>
      	 <td  height="30" align="left" colspan="2"><input type="submit" name="submit" value="Submit"> </td>
		 
      	      </tr>
      	      
  </table></form>
<?php

 include("admin.footer.inc.php"); ?>
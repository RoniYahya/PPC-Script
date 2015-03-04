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
<style type="text/css">
<!--
.style1 {font-style: italic}
.style2 {font-style: italic}
.style3 {font-style: italic}
.style4 {font-style: italic}
.style5 {font-style: italic}
.style6 {font-style: italic}
.style7 {font-style: italic}
.style8 {font-style: italic}
.style9 {font-style: italic}
.style10 {font-style: italic}
.style11 {font-style: italic}
.style12 {font-style: italic}
-->
</style>

<form action="url-name-settings-action.php" method="post" enctype="multipart/form-data">
<table align="center" width="100%" height="162" >
<tr >
	<td colspan="3">&nbsp;</td>
	</tr>
<tr>
	<td colspan="3" class="heading">Search Engine Friendly URL Configuration</td>
	</tr>
<tr >
	<td colspan="3">&nbsp;</td>
	</tr>
		<tr>
	  <td colspan="3" align="left" class="note"><strong>Note :</strong> The seo names configured will be appended to the server directory url.</td>
    </tr>
	<td colspan="3">&nbsp;</td>
	</tr>

	<tr bgcolor="#CCCCCC" height="30px">
	  <td>Section</td>
<td>Page</td>
	<td width="44%">SEO name</td>
	</tr>
	
	
	<tr bgcolor="#EDEDED">
	  <td rowspan="6">Common Pages </td>
<td>Home</td>
	<td width="44%"><input type="text" name="index" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='home'");  ?>"></td>
	</tr>
	<tr>
	  <td bgcolor="#EDEDED">Advertiser Home </td>
	  <td bgcolor="#EDEDED"><input type="text" name="adv" value="<?php echo $mysql->echo_one("select seoname from url_updation  where  name='advertisers'");  ?>"></td>
	</tr><tr>
<td bgcolor="#EDEDED">Publisher Home </td>
<td bgcolor="#EDEDED"><input type="text" name="pub" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='publishers'");  ?>"></td>
	</tr>
<tr>
  <td bgcolor="#EDEDED">Advertiser FAQ</td><td bgcolor="#EDEDED"><input type="text" name="advfaq" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='advfaq'");  ?>"></td>
	</tr>
<tr>
  <td bgcolor="#EDEDED">Publisher FAQ</td><td bgcolor="#EDEDED"><input type="text" name="pubfaq" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='pubfaq'");  ?>"></td>
	</tr>	
	<tr>
	  <td bgcolor="#EDEDED">Contact Us</td><td bgcolor="#EDEDED"><input type="text" name="contact" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='contactus'");  ?>"></td>
	</tr>
	
	
<tr>
  <td rowspan="3">Single-Sign-On Pages </td>
<td>Sign Up</td><td><input type="text" name="reg" value="<?php echo $mysql->echo_one("select seoname from url_updation  where  name='signup'");  ?>"></td>
	</tr>
	<tr>
	  <td>Sign In</td><td><input type="text" name="login" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='signin'");  ?>"></td>
	</tr>
	<tr>
	  <td>Forgot password</td><td><input type="text" name="forgotpassword" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='forgotpassword'");  ?>"></td>
	</tr>
	
	
	
	<tr>
	  <td rowspan="4" bgcolor="#EDEDED">Dual-Sign-On Pages </td>
<td bgcolor="#EDEDED">Advertiser Sign Up</td><td bgcolor="#EDEDED"><input type="text" name="advreg" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='advertisersignup'");  ?>"></td>
	</tr>
	
<tr>
  <td bgcolor="#EDEDED">Publisher Sign Up</td><td bgcolor="#EDEDED"><input type="text" name="pubreg" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='publishersignup'");  ?>"></td>
	</tr>	

	
	<tr>
	  <td bgcolor="#EDEDED">Advertiser Forgot password</td><td bgcolor="#EDEDED"><input type="text" name="advforgotpassword" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='advertiserforgotpassword'");  ?>"></td>
	</tr>
	<tr>
	  <td bgcolor="#EDEDED">Publisher Forgot password</td><td bgcolor="#EDEDED"><input type="text" name="pubforgotpassword" value="<?php echo $mysql->echo_one("select seoname from url_updation  where name='publisherforgotpassword'");  ?>"></td>
	</tr>

	
	
	<tr>
	  <td align="center">&nbsp;</td>
<td align="center"><input type="submit" name="submit" value="Generate code" /></td>
	<td></td>
	</tr>
  </table>
</form>
<?php include("admin.footer.inc.php"); ?>
<?php include_once("config.inc.php"); 
if(isset($_COOKIE['inout_admin']) && isset($_COOKIE['inout_pass']) )
{
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if($inout_username==md5($username) && $inout_password==md5($password))
{
header("Location:main.php");
exit(0);
}
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>

<head>

<title>Inout Adserver - Pay Per Click Advertising Expert!</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<style type="text/css">

<!--

.style1 {

color: #FFFFFF;

font-weight: bold;

}

-->

</style>

<link href="style.css" rel="stylesheet" type="text/css">

</head>



<body>

<form name="form1" method="post" action="main.php">



<table   border="0" align="center" width="300px" class="loginTbl" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="3" align="center"><img src="images/logo.png" alt="Admin Area Home" width="315" border="0"></td>
</tr>
<tr>

<td colspan="3" height="20px"><span class="style1"><font>Inout Adserver Admin Login</font> <a href="http://www.inoutscripts.com/" class="right">InoutScripts Home</a></span></td>
</tr>


<tr>

<td width="3%">&nbsp;</td>

<td colspan="2" height="25px"><?php if(isset($_GET['logout'])) {?>You have successfully logged out.<?php  } ?></td>
</tr>

<tr>

<td>&nbsp;</td>

<td width="27%">Username</td>

<td width="70%"><input class="txt" name="username" type="text" <?php if($script_mode=="demo") echo "value=\"$username\"" ?> id="username"></td>
</tr>

<tr>

<td>&nbsp;</td>

<td>Password</td>

<td><input name="password"  class="txt" type="password" <?php if($script_mode=="demo") echo "value=\"$password\"" ?> id="password"></td>
</tr>

<tr>

<td>&nbsp;</td>

<td>&nbsp;</td>

<td><input type="submit" class="button" name="Submit" value="Login!"></td>
</tr>


</table>

</form>

</body>

</html>


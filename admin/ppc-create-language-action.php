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
$file="export_data";
include_once("admin.header.inc.php");
if($script_mode=="demo")
	{ 
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
	}

if(isset($_POST['Submit']))
{
 $name=trim($_POST['language_name']);
 phpsafe($name);
 $direction=trim($_POST['direction']);
  phpsafe($direction);
$encoding=trim($_POST['encoding']);
  phpsafe($encoding);
$code=trim($_POST['code']);
  phpsafe($code);
if($name==""||$encoding==""||$code=="")
{
	?><span class="already"><br><?php echo "Please Go Back and check whether you filled all mandatory fields !";?> <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
	<?php
	}else
	{
$ft=mysql_query("select * from adserver_languages where language='$name' or code='$code' ");
$vc=mysql_numrows($ft);
if($vc==0)
{
$bg=mysql_query("insert into adserver_languages values('','$name','$encoding','$code','$direction','1')");
?> <span class="inserted"><br><?php echo "New language  has been created successfully !"; ?></span><a href="ppc-manage-language.php">Continue</a><br><br>
<?php
}
else
{
?> <span class="already"><br><?php echo "The language or code already exists. "; ?><a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>	
<?php
}
	}
}
 include("admin.footer.inc.php");?>
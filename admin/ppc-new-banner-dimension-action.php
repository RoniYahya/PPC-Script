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
	$width=trim($_POST['width']);
	$msg=0;
if(!isPositiveInteger($width))
{
$msg=1;
}
$height=trim($_POST['height']);

if(!isPositiveInteger($height))
{
$msg=1;
}
$banner_size=trim($_POST['banner_file_max_size']);
if(!isPositiveInteger($banner_size))
{
$msg=1;
}
$wap=trim($_POST['target']);
?>
<?php 
if($width==""||$height==""||$banner_size=="")
{
	include("admin.header.inc.php"); ?>
<br> <span class="already">You have not filled all  mandatory fields. Please go back and fill the fields.<br>
		
			<a href="javascript:history.back(-1);" class="pagetable_activecell" {COLORTHEME5}>Click Here</a> to go back and modify.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
}

$banner_count=$mysql->echo_one("select count(*) from banner_dimension where width='$width' and height='$height' and wap_status='$wap'");
if($banner_count!=0)
{
	include("admin.header.inc.php"); ?>
<br> <span class="already">A banner dimension of specified height and width is already created.<br>
		 Please do not create duplicate banner sizes.
			<a href="javascript:history.back(-1);" class="pagetable_activecell" {COLORTHEME5}>Click Here</a> to go back and modify.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
		}
		if($msg==1)
		{
			include("admin.header.inc.php"); ?>
<br> <span class="already">All fields must be positive integers.<br>
				<a href="javascript:history.back(-1);" class="pagetable_activecell" {COLORTHEME5}>Click Here</a> to go back and modify.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
	
		}
		else
		{
			
			mysql_query("insert into banner_dimension (id,width,height,file_size,wap_status) values('0','$width','$height','$banner_size','$wap')");
			include("admin.header.inc.php"); ?>
<br> <span class="inserted">New banner dimension has been created succesfully.<br>
				<a href="ppc-manage-banner.php" class="pagetable_activecell" {COLORTHEME5}>Manage banner dimensions</a>.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
		}
?>
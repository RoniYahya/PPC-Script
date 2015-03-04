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
$bid=$_GET['id'];
phpSafe($bid);
$result=mysql_query("select * from banner_dimension where id=$bid");
$row=mysql_fetch_row($result);
if($row[4]==0)
{
	$wap_table="ppc_ad_block";
}
else
{
	$wap_table="wap_ad_block";
}
$width=trim($_POST['width']);
if(!isPositiveInteger($width))
$width=$row[1];
$height=trim($_POST['height']);
if(!isPositiveInteger($height))
$height=$row[2];
$banner_size=trim($_POST['banner_file_max_size']);
if(!isPositiveInteger($banner_size))
$banner_file_max_size=$row[3];
$wap=trim($_POST['target']);
$banner_count=$mysql->echo_one("select count(*) from banner_dimension where width='$width' and height='$height' and wap_status='$wap' and id <> '$bid'");
if($banner_count!=0)
{
	include("admin.header.inc.php"); ?>
<br> <span class="already">The specified height and width banner dimension is already created.<br>
		 Please do not create duplicate banner sizes.
			<a href="javascript:history.back(-1);" class="pagetable_activecell" >Click Here</a> to go back and modify.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
		}
		//echo "select ad_block_name from `$wap_table` where (ad_type='2' or ad_type='3')  and  height='$row[2]' and width='$row[1]' and max_size='$row[0]'";
		$ext_block=mysql_query("select ad_block_name from `$wap_table` where (ad_type='2' or ad_type='3')  and  height='$row[2]' and width='$row[1]' and max_size='$row[0]'");
	$num_ext_block=mysql_num_rows($ext_block);
		//$banner_count=$mysql->echo_one("select count(*) from banner_dimension where width='$row[1]' and height='$row[2]' and wap_status='$row[4]'");
if($num_ext_block!=0)
{
	include("admin.header.inc.php"); ?>
 <br><span class="already">The specified dimension is already used for adblock(s).<br>
		 You cannot edit this banner dimension.
			<a href="javascript:history.back(-1);" class="pagetable_activecell" >Click Here</a> to go back.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
		}
		//echo "select id from `ppc_ads` where ad_type='1'   and  wap_status='$row[4]' and bannersize='$row[0]'";
		$ext_block11=mysql_query("select id from `ppc_ads` where adtype='1' and wapstatus='$row[4]' and bannersize='$row[0]'");
		 $num_ext_block11=mysql_num_rows($ext_block11);
		//$banner_count=$mysql->echo_one("select count(*) from banner_dimension where width='$row[1]' and height='$row[2]' and wap_status='$row[4]'");
if($num_ext_block11!=0)
{
	include("admin.header.inc.php"); ?>
 <br><span class="already">The specified dimension is already used for ad(s).<br>
		 You cannot edit this banner dimension.
			<a href="javascript:history.back(-1);" class="pagetable_activecell" >Click Here</a> to go back.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
		}
			$public_service=mysql_query("select id from `ppc_public_service_ads` where adtype='1' and wapstatus='$row[4]' and bannersize='$row[0]'");
		 $public_service_ads=mysql_num_rows($public_service);
		//$banner_count=$mysql->echo_one("select count(*) from banner_dimension where width='$row[1]' and height='$row[2]' and wap_status='$row[4]'");
if($public_service_ads!=0)
{
	include("admin.header.inc.php"); ?>
 <br><span class="already">The specified dimension is already used for public service ad(s).<br>
		 You cannot edit this banner dimension.
			<a href="javascript:history.back(-1);" class="pagetable_activecell" >Click Here</a> to go back.
			</span>
		
	<strong><br><br /><br />
		 <?php
		  include("admin.footer.inc.php");
			exit(0);
		}
		else
		{

		mysql_query("update banner_dimension set width='$width',height='$height',file_size='$banner_size' where id=$row[0]");
		include("admin.header.inc.php"); ?>
 <br><br /><span class="inserted">Your banner dimension has been successfully updated.<br>
		 
			<a href="ppc-manage-banner.php" class="pagetable_activecell" >Manage banner dimensions</a>.
			</span>
		
	<strong><br><br /><br />
	<?php
		  include("admin.footer.inc.php");
			exit(0);
		}
		
?>
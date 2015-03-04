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


$id=$_GET['id'];
phpsafe($id);

$wap_status=$_GET['wap'];
phpsafe($wap_status);

if($wap_status==1)
{
	$wap_flag=1;
	$wap_string='and wapstatus=1';
	
	$wap_table='wap_ad_block';
	$wap_table1='ppc_custom_ad_block';
}

else
{
	$wap_flag=0;
	$wap_string='and wapstatus=0';
	
	$wap_table='ppc_ad_block';
	$wap_table1='ppc_custom_ad_block';
}

if(isset($_GET['status']))
{
	$status=$_GET['status'];
	if($status==0)
	{
		$status=1;
		$msg="activated";
	}
	else
	{
		$status=0;
		$msg="blocked";
		$num=$mysql->echo_one("select count(*) from ppc_custom_ad_block where bid=$id $wap_string" );
	//	$num1=$mysql->echo_one("select count(*) from ppc_ads where bannersize='$id' $wap_string");
	//	$num2=$mysql->echo_one("select count(*) from ppc_public_service_ads where bannersize='$id' $wap_string");

		if($num>0 )//|| $num1>0 || $num2>0)
		{
			include_once("admin.header.inc.php");
			?><br><?php
			$str="";
			if($num!=0) $str.=" $num ad unit(s) ";
			/*if($str!="") $str.=",";
			if($num1!=0) $str.=" $num1 banner ad(s) ";
			if($str!="") $str.=",";
			if($num2!=0) $str.=" $num2 public service banner ad(s)  ";
			$str=substr($str,0,-2);
			$str = str_replace(",,", ",", $str);*/

			?>
			<span class="already">You cannot deactivate this ad-block.<?php echo $str; ?> are using this ad block! <a href="javascript:history.back(-1)">Back</a>
			</span>
		<strong><br>
			<?php
			include("admin.footer.inc.php");exit(0);
		}

	}
}
else
{
	$status=1;
	$msg="activated";
}
//echo "status-$status";
//exit(0);
	
include_once("admin.header.inc.php");
mysql_query("update `$wap_table` set status=$status where id=$id;");
	?>
<br />
<span class="inserted"><br><?php echo "Ad block has been successfully $msg ! ";?></span><strong> <a href="ppc-manage-ad.php?wap=<?php echo $wap_flag; ?>">Manage Existing Ad blocks</a></strong>
<br>
<?php include("admin.footer.inc.php"); ?>
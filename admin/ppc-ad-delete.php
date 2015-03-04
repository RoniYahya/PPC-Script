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


$id=getSafePositiveInteger('id','g');
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


	
include_once("admin.header.inc.php");
?>

<?php	if($script_mode=="demo")
	{ 
		if(($id<=32  || ($id<=88 && $id>=85))&& $wap_status==0 )
		{
		echo "<br><span class=\"already\">You cannot delete this block in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
		}
		if($id<=7 && $wap_status==1 )
		{
		echo "<br><span class=\"already\">You cannot delete this block in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
		}
	}


//echo $id;
$num=$mysql->echo_one("select count(*) from $wap_table1 where bid=$id $wap_string");
//$num1=$mysql->echo_one("select count(*) from ppc_ads where bannersize='$id' $wap_string");
//$num2=$mysql->echo_one("select count(*) from ppc_public_service_ads where bannersize='$id' $wap_string");
	//echo "select count(*) from ppc_ads where bannersize='$id'";
if($num>0)// || $num1>0 || $num2>0)
{
	//	echo "record exist cannot delete";
	//exit(0);
	?>
	<span class="already"><br><?php
	$str="";
	if($num!=0) $str.=" $num ad unit(s) ";
	/*if($str!="") $str.=",";
	if($num1!=0) $str.=" $num1 banner ad(s) ";
	if($str!="") $str.=",";
	if($num2!=0) $str.=" $num2 public service banner ad(s)  ";
	$str=substr($str,0,-2);
	$str = str_replace(",,", ",", $str);*/
	
	?>
	Ad block cannot be deleted.<?php echo $str; ?> are using this ad block!
	</span>
<strong><br>
	<?php
	
}
else
{
	
	mysql_query("delete from `$wap_table` where id=$id;");
	?>
	<span class="inserted"><br><?php echo " Ad block has been successfully deleted ! ";?></span>
	<strong><br><?php
 } 
 if($wap_status==1)
{?>
<br><a href="ppc-manage-ad.php?wap=1">Manage Existing Ad blocks</a>
<br>

<?php }
else
{?>
<br><a href="ppc-manage-ad.php?wap=0">Manage Existing Ad blocks</a>
<br>

<?php }
include("admin.footer.inc.php"); ?>
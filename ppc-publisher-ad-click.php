<?php

$ini_error_status=ini_get('error_reporting');
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


if(isset($_GET['Flsids']))
{
$Flsids=$_GET['Flsids'];
$Flsids_array=explode('__',$Flsids);
$aid=intval($Flsids_array[0]);
}
else
{
$aid =intval($_GET['id']);
}



$par_val=substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"?")+1);
$set_str_v_path=md5($par_val.$ppc_engine_name);
setcookie("_io_vc","$set_str_v_path",time()+10);

/*if($server_dir!=substr($_SERVER['HTTP_REFERER'],0,strlen($server_dir)))
{

header("Location: $server_dir");

exit(0);
}*/

$wap_status=$mysql->echo_one("select wapstatus from ppc_ads where id='$aid'");


$trstr=$server_dir."pub-ad-click.php?".$par_val;

if($wap_status == 0)
{
?>
<script language="javascript">
window.location="<?php echo $trstr; ?>"	;
</script>
<?php
exit;
}
else if($wap_status ==1)
{
header("Location: $trstr");
exit;
}


?>
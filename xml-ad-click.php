<?php

$ini_error_status=ini_get('error_reporting');
include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");


$par_val=substr($_SERVER['REQUEST_URI'],strpos($_SERVER['REQUEST_URI'],"?")+1);

$set_str_v_path=md5($par_val.$ppc_engine_name);

setcookie("_io_vc","$set_str_v_path",time()+10);

$trstr=$server_dir."xml-click.php?".$par_val;

?>

<script language="javascript">

window.location="<?php echo $trstr; ?>"	;

</script>

<?php

exit;


?>

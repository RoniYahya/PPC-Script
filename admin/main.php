<?php
include( "../extended-config.inc.php" );
include( "config.inc.php" );
include_once( "../advertiser_statistics_utils.php" );
if(!isset($_COOKIE['inout_admin']) || !isset($_COOKIE['inout_pass'])){
	if(!isset($_POST['username'])){
		header("Location:index.php");
		exit();
	}

	if($_POST['username'] == $username && $_POST['password'] == $password ){
		setcookie("inout_admin", md5($username));
		setcookie("inout_pass", md5($password));
		$user_id = 1;
		setcookie("admin_in_user_id", $user_id, 0, "/" );
		if($default_chat_status == 1){
			mysql_query( "update nesote_chat_login_status set status=1 where user_id='{$user_id}' " );
			mysql_query( "update ppc_settings set value='1' where name='chat_status'" );
		}
	}
	else{
        header("Location:index.php");
        exit();
	}
}
else{
	$inout_username = $_COOKIE['inout_admin'];
	$inout_password = $_COOKIE['inout_pass'];
	if(!($inout_username == md5($username) && $inout_password == md5($password))){
		header("Location:index.php");
		exit();
	}
}
//die('??');
/*if(!mysql_query( "select * from ppc_settings;")){
	header("Location:install.php");
	exit();
}*/

include( "admin.header.inc.php" );
echo "
<style>{margin:0; padding:0;}</style>
<script language=\"JavaScript\">function openBrWindow(theURL,winName){window.open(theURL,winName,'width=550,height=550,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0');}</script>

<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td width=\"19\">&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n";
echo "  <tr>\n    <td height=\"33\" colspan=\"5\">";
echo "<span class=\"pagetable_activecell\">Hello Admin!!</span> Welcome back. Did you check the <a href=\"ppc-admin-to-do.php\" class=\"mainmenu\">";
echo "<strong>TO DO page</strong></a> today?</td>\n  </tr>\n  <tr>\n\n    <td colspan=\"2\" align=\"left\" valign=\"middle\">&nbsp;</td>\n\n    <td>&nbsp;</td>\n\n    <td colspan=\"2\">&nbsp;</td>\n  </tr>\n    <tr>\n\n    <td colspan=\"5\" align=\"left\" valign=\"middle\">\n\t<div style=\"float:left\"><iframe src=\"../statistics.php?key=";
echo md5( $username.$password );
echo "\" frameborder=\"0\" scrolling=\"no\" width=\"330px\" height=\"185px\"></iframe>\n\t<br /><br />\n\n\t<iframe src=\"../info.php?key=";
echo md5( $username.$password );
echo "\" frameborder=\"0\" scrolling=\"no\" width=\"330px\" height=\"125px\"></iframe></div>\n\t<div style=\"float:right\"><iframe src=\"../geographical-statistics.php\" frameborder=\"0\" scrolling=\"no\" width=\"350px\" height=\"325px\"></iframe></div>\t</td>\n  </tr>\n\n    <tr>\n      <td colspan=\"5\" align=\"left\" valign=\"middle\">\n\t  <div style=\"font-weight:bold;background-color:#dddddd;margin-top:8px;padding:4px 0px 2px 0px;color:#666666\"";
echo ">System Messages\n\t  <div style=\" background-color:#FFFFFF;margin: 6px 3px 2px 3px;font-weight:normal\">\n\t  \n\t  <div class=\"msg \">&diams; You are running version ";
echo $script_version;
echo " of the script. Please note that this version shows statistics of impressions and clicks excluding current hour.   </div>\n\t\t";
$danger = "";
if ( $timezone_change == "" )
{
    $danger = "danger";
}
echo "\t\t<div class=\"msg  ";
echo $danger;
echo "\">&diams; \n\t\t";
if ( $timezone_change == "" )
{
    echo "\t\tYour system timezone  is not set.\n\t\t";
}
else
{
    echo "\t\tYour system timezone  is set to ";
    echo $timezone_change;
    echo ".\n\t\t";
}
echo " Current server time is ";
echo date( timestamp( ) );
//echo __Func034__( timestamp( ) );
echo ".\n\t\t</div>\n\t\t\n\t\t";
//if ( !__Func036__( "date_default_timezone_set" ) )
if ( !ini_set( "date_default_timezone_set" ) )
{
    echo "\t\t<div class=\"msg  danger\">&diams; \n\t\tYou do not have support for date_default_timezone_set() function. Do get this addressed by your host.\n\t\t</div>\n\t\t";
}
echo "\t\t\n\t\t\n\t\t";
$ltime = $mysql->echo_one( "select e_time from statistics_updation where task='cron_run_time'" );
$danger = "";
if ( $ltime < timestamp( ) - 86400 )
{
    $danger = "danger";
}
echo "\t\t<div class=\"msg ";
echo $danger;
echo "\"> &diams; Your cron job was last executed sucessfully at ";
echo date( $ltime );
//echo __Func034__( $ltime );
echo ". \n\t\t</div>\n\t\n\t\t";
$idbrow = $mysql->select_one_row( "SHOW VARIABLES LIKE 'have_innodb'" );
$danger = "";
//if ( !strcmp __Func015__( $idbrow['Value'], "yes" ) )
if ( !strcmp( $idbrow['Value'], "yes" ) )
{
    echo "\t\t\t  <div class=\"msg danger\">&diams; Your database is not using INNODB storage engine.  </div>\n\t\t";
}
echo "\t  \n\t  \n\t\t<div class=\"msg\">&diams; \n\t\tYour database table collation is set to ";
echo $table_fields_collation;
echo ". If this is wrong, please contact our support desk. \n\t\t</div>\n\n\n";
$t_parentstatus_updation = 0;
$trig_res = mysql_query("show triggers");
while ( $trig_row = mysql_fetch_array( $trig_res ) )
{
    if ( $trig_row['Trigger'] == "adserver_parentstatus_updation" )
    {
        $t_parentstatus_updation = 1;
    }
}
if ( $t_parentstatus_updation == 0 )
{
    echo "\t\t\t  <div class=\"msg danger\">&diams; It seems 'adserver_parentstatus_updation' trigger is not created in your database. If your mysql user does not have privliege to create trigger, copy the trigger from admin/trigger.php and get it created by your host.  </div>\n";
}
echo "\n\n";

$time = mktime( 0, 0, 0, date("m", timestamp()), date("d", timestamp()), date( "y", timestamp()));

$daily_imp = 100001; // __Func037__( $time, $mysql, 0 - 1 );

echo "\n\t\t\n\t\t";
$danger = "";
if ( $GLOBALS['cache_timeout'] == 0 && 100000 < $daily_imp )
{
    $danger = "danger";
}
echo "\t\t<div class=\"msg ";
echo $danger;
echo "\">&diams; \n\t\tCaching of ad display units is   ";
$c_status = 0 < $GLOBALS['cache_timeout'] ? "turned on for ".$GLOBALS['cache_timeout']." minutes." : "turned off. ";
if ( $GLOBALS['cache_timeout'] == 0 && 100000 < $daily_imp )
{
    $c_status .= "Please enable the same in extended configurations.";
}

echo $c_status;
echo "\t\t</div>  \n\n\t\t";
$danger = "";
$scount = $mysql->total( "server_configurations" );
$lbs = "enabled.";
if ( $scount <= 1 )
{
    $lbs = "disabled.";
    if ( $mysql_server == "localhost" )
    {
        if ( 1500000 < $daily_imp )
        {
            $danger = "danger";
            $lbs = "disabled. Please setup load balancing stage I.";
        }
    }
    else
    {
        $lbs = "enabled. Stage I deployed.";
        if ( 3000000 < $daily_imp )
        {
            $danger = "danger";
            $lbs = "enabled. Stage I deployed. Please setup load balancing stage II by adding a load balancer.";
        }
    }
}
else if ( $mysql_server == "localhost" )
{
    $lbs = ( "enabled. Stage I has been bypassed. Stage II running. No. of load balancer(s) = ".( $scount - 1 ) ).".";
    if ( 1500000 * $scount < $daily_imp )
    {
        $danger = "danger";
        $lbs = ( "enabled. Stage I has been bypassed. Stage II running. No. of load balancer(s) = ".( $scount - 1 ) ).". Please consider adding a load balancer.";
    }
}
else
{
    $lbs = ( "enabled. Stage II running. No. of load balancer(s) = ".( $scount - 1 ) ).".";
    if ( 1500000 + 1500000 * $scount < $daily_imp )
    {
        $danger = "danger";
        $lbs = ( "enabled. Stage II running. No. of load balancer(s) = ".( $scount - 1 ) ).". Please consider adding a load balancer.";
    }
}

echo "\t\t\n\t\t<div class=\"msg ";
echo $danger;
echo "\">&diams; \n\t\tLoad balancing configuration is ";
echo $lbs;
echo "\t\t";
$not_running = $mysql->total( "server_configurations", "srv_type=2 and status<>'111'" );
echo "\t\t</div>  \n\t\t";
if ( 1 < $scount && 0 < $not_running )
{
    echo "\t\t<div class=\"msg danger\">&diams; \n\t\tLoad balancing configuration is enabled. But ";
    echo $not_running;
    echo " servers are not running properly. Please check from <a href=\"manage-loadbalance.php\">here</a>.\n\t\t</div>  \n\t\t";
}
echo "\t\t\n\t\t";
$danger = "";
if ( $smtpmailer == 1 && phpversion() < 5 )
{
    $danger = "danger";
}
echo "\t\t<div class=\"msg ";
echo $danger;
echo "\">&diams; \n\t\tSMTP mailing is   ";
if ( $smtpmailer == 1 )
{
    echo "configured by admin";
    if ( phpversion() < 5 )
    {
        echo " but disabled by the system as it requires PHP version > 5";
    }
}
else
{
    echo "not configured by admin; PHP mail() will be used";
}
echo ".\t  \n\t\t</div>  \n\t\t\n\t\t";
if ( strcmp ( $ad_display_char_encoding, $email_encoding ) != 0 )
{
    echo "\t\t<div class=\"msg danger\">&diams; Your system encoding is set to ";
    echo $ad_display_char_encoding;
    echo " but your email enconding in config file is set to ";
    echo $email_encoding;
    echo ".\n\t\t</div>  \n\t\t";
}
echo " \n\t\t\n\t\t\n\t\t";
if ( $maintenance_mode['enabled'] == 1 )
{
    echo "\t\t<div class=\"msg danger\">&diams; \n\t\tYour system is running in maintenance mode (extended configuration). IPs which have access are ";
    echo $maintenance_mode['allowed_ips'];
    echo "\t\t</div>  \n\t\t";
}

echo " \n\n  \n\t\t </div>\n\t  </div>\n  \n\t  </td>\n    </tr>\n  <tr>\n\n    <td colspan=\"2\" align=\"left\" valign=\"middle\">&nbsp;</td>\n\n    <td>&nbsp;</td>\n\n    <td colspan=\"2\">&nbsp;</td>\n  </tr>\n</table>\n\n";
include( "admin.footer.inc.php" );

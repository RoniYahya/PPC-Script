<?php
include( "../extended-config.inc.php" );
include( "config.inc.php" );
if ( !isset( $_COOKIE['inout_admin'] ) )
{
    header( "Location:index.php" );
    exit( 0 );
}
$inout_username = $_COOKIE['inout_admin'];
$inout_password = $_COOKIE['inout_pass'];
if ( !( $inout_username == md5( $username ) && $inout_password == md5( $password ) ) )
{
    header( "Location:index.php" );
    exit( 0 );
}

include( "admin.header.inc.php" );
echo "\n";
include( "../advertiser_statistics_utils.php" );
$flag_time = 0;
__Func023__( "ppc_new" );
$budget_period = $GLOBALS['budget_period'];
if ( $budget_period == 1 )
{
    $budget_period_unit = "Monthly";
    $PERIOD = "in ".__Func024__( "%B", __Func013__( ) );
}
else if ( $budget_period == 2 )
{
    $budget_period_unit = "Daily";
    $PERIOD = "today";
}
if ( isset( $_POST['statistics'] ) )
{
    $show = $_POST['statistics'];
}
else
{
    $show = $_GET['statistics'];
}
if ( $show == "" )
{
    $show = "day";
}
if($show=="day")
{
$flag_time=-1;	
$showmessage="Today";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));
$beg_time=$time;

//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-11,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("y",time()));
$beg_time=$time;
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;
	$end_time=mktime(0,0,0,1,1,date("y",time())+1);
$beg_time=$time;

}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
if ( $flag_time == 0 )
{
    $table_name = "advertiser_daily_statistics";
}
else if ( $flag_time == 2 )
{
    $table_name = "advertiser_yearly_statistics";
}
else
{
    $table_name = "advertiser_monthly_statistics";
}
if ( isset( $_REQUEST['adtype'] ) )
{
    $adtype = $_REQUEST['adtype'];
}
else if ( $_GET['adtype'] != "" )
{
    $adtype = $_GET['adtype'];
}
else
{
    $adtype = 3;
}
if ( isset( $_REQUEST['device'] ) )
{
    $device = $_REQUEST['device'];
}
else if ( $_GET['device'] != "" )
{
    $device = $_GET['device'];
}
else
{
    $device = 2;
}
if ( isset( $_REQUEST['status'] ) )
{
    $st = $_REQUEST['status'];
}
else if ( $_GET['status'] != "" )
{
    $st = $_GET['status'];
}
else
{
    $st = 4;
}
if ( $_REQUEST )
{
    if ( $adtype == "0" )
    {
        $adty = " and b.adtype='0' ";
    }
    else if ( $adtype == "1" )
    {
        $adty = " and b.adtype='1' ";
    }
    else if ( $adtype == "2" )
    {
        $adty = " and b.adtype='2' ";
    }
    else
    {
        $adty = "";
    }
    if ( $st == "1" )
    {
        $stat = " and b.status ='1' ";
    }
    else if ( $st == "-1" )
    {
        $stat = " and b.status ='-1' ";
    }
    else if ( $st == "0" )
    {
        $stat = " and b.status ='0' ";
    }
    else
    {
        $stat = "";
    }
    if ( $device == "0" )
    {
        $dev = " and b.wapstatus='0' ";
    }
    else if ( $device == "1" )
    {
        $dev = " and b.wapstatus='1' ";
        $wap_name = "Wap";
    }
    else
    {
        $dev = "";
    }
}
else
{
    $adty = "";
    $dev = "";
    $stat = "";
}
$pageno = 1;
if ( isset( $_REQUEST['page'] ) )
{
    $pageno = __Func025__( "page" );
}
$perpagesize = 20;
$total = $mysql->echo_one( "select count(*) from ppc_users a,ppc_ads b where a.uid=b.uid {$stat}  ".$dev.$adty );
$result = mysql_query( "select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused ,b.wapstatus,b.name,b.contenttype from ppc_users a,ppc_ads b where a.uid=b.uid  {$stat}  ".$dev.$adty." order by b.createtime desc LIMIT ".( $pageno - 1 ) * $perpagesize.", ".$perpagesize );
echo "\n";
echo "<s";
echo "tyle type=\"text/css\">\n<!--\n.style1 {\n\tcolor: #006600;\n\tfont-weight: bold;\n}\n.style2 {color: #333333}\n-->\n</style>\n\n";
echo "<s";
echo "cript type=\"text/javascript\">\nfunction showad(id)\n\t\t{\n\t\t\tdocument.getElementById('ad'+id).style.display='block';\n\t\t}\n\n\t\tfunction hidead(id)\n\t\t{\n\t\t\tdocument.getElementById('ad'+id).style.display='none';\n\t\t}\n</script>\n\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr>\n    <td height=\"53\" colspan=\"4\"  align=\"left\">";
include( "submenus/ads.php" );
echo " </td>\n  </tr>\n  <tr>\n   <td   colspan=\"4\" scope=\"row\" class=\"heading\">Ad Statistics</td>\n  </tr>\n</table>  <br />\n\n \n\n\n\n\n\n \n  \n\n \n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n\n  <tr>\n    <td colspan=\"8\"></td>\n  </tr>\n\n\n\n  <tr>\n    <td colspan=\"7\">&nbsp;</td>\n    </tr>\n  <tr>\n    <td  style=\"white-space: nowrap;\" colspan=\"7\">\n\t<form name=\"form1\" method=\"post\" action=\"ppc-admin-click-profit-statist";
echo "ics.php\">\n Period\n      ";
echo "<s";
echo "elect name=\"statistics\" id=\"statistics\">\n\t  <option value=\"day\"  ";
if ( $show == "day" )
{
    echo "selected";
}
echo ">Today</option>\n        <option value=\"week\"  ";
if ( $show == "week" )
{
    echo "selected";
}
echo ">Last 14 days</option>\n        <option value=\"month\"  ";
if ( $show == "month" )
{
    echo "selected";
}
echo ">Last 30 days</option>\n        <option value=\"year\"  ";
if ( $show == "year" )
{
    echo "selected";
}
echo ">Last 12 months</option>\n        <option value=\"all\"  ";
if ( $show == "all" )
{
    echo "selected";
}
echo ">All Time</option>\n      </select>\n     Status ";
echo "<s";
echo "elect name=\"status\" id=\"status\" >\n<option value=\"1\"  ";
if ( $st == "1" )
{
    echo "selected";
}
echo ">Active</option>\n<option value=\"-1\" ";
if ( $st == "-1" )
{
    echo "selected";
}
echo " >Pending</option>\n<option value=\"0\" ";
if ( $st == "0" )
{
    echo "selected";
}
echo ">Blocked</option>\n<option value=\"4\" ";
if ( $st != "1" && $st != "0" && $st != "-1" )
{
    echo "selected";
}
echo ">All</option>\n</select> Type ";
echo "<s";
echo "elect name=\"adtype\" id=\"adtype\" >\n<option value=\"0\" ";
if ( $adtype == "0" )
{
    echo "selected";
}
echo ">Text Ads</option>\n<option value=\"1\" ";
if ( $adtype == "1" )
{
    echo "selected";
}
echo ">Banner Ads</option>\n<option value=\"2\" ";
if ( $adtype == "2" )
{
    echo "selected";
}
echo ">Catalog Ads</option>\n<option value=\"3\" ";
if ( $adtype != "1" && $adtype != "0" && $adtype != "2" )
{
    echo "selected";
}
echo ">All Ads</option>\n</select>\t\t  Target ";
echo "<s";
echo "elect name=\"device\" id=\"device\" >\n<option value=\"0\" ";
if ( $device == "0" )
{
    echo "selected";
}
echo ">Desktop&Laptop</option>\n<option value=\"1\" ";
if ( $device == "1" )
{
    echo "selected";
}
echo ">Wap</option>\n<option value=\"2\" ";
if ( $device != "0" && $device != "1" )
{
    echo "selected";
}
echo ">All</option>\n</select>\t   \n      <input type=\"submit\" name=\"Submit\" value=\"Go\"> \n    </form>     </td>\n  </tr>\n   <tr>\n    <td colspan=\"7\" >&nbsp;</td>\n    </tr>\n  \n  \n  <tr>\n    <td colspan=\"7\">";
echo "<s";
echo "pan class=\"styleTitle\">Showing Ad Click Statistics of";
echo "<s";
echo "trong> ";
echo $showmessage;
echo " </strong></span></td>\n    </tr>\n  \n  <tr>\n    <td  colspan=\"6\">\n\t";
if ( 1 <= $total )
{
    echo "      Ads ";
    echo "<s";
    echo "pan class=\"inserted\">";
    echo ( $pageno - 1 ) * $perpagesize + 1;
    echo "</span> -\n        ";
    echo "<s";
    echo "pan class=\"inserted\">\n        ";
    if ( $total < $pageno * $perpagesize )
    {
        echo $total;
    }
    else
    {
        echo $pageno * $perpagesize;
    }
    echo "    </span>&nbsp;of ";
    echo "<s";
    echo "pan class=\"inserted\">";
    echo $total;
    echo "</span>&nbsp;\n    ";
}
echo "    &nbsp;&nbsp;  </td>\n    <td width=\"51%\" align=\"right\"  >";
echo $paging->page( $total, $perpagesize, "", "ppc-admin-click-profit-statistics.php?statistics={$show}&adtype={$adtype}&device={$device}&status={$st}" );
echo "</td>\n    </tr>\n  <tr>\n    <td colspan=\"7\">\n\t\n\t<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"datatable\">\n\n      <tr class=\"headrow\">\n      <td width=\"12%\">";
echo "<s";
echo "trong>Ad</strong></td>\n             <td width=\"14%\">";
echo "<s";
echo "trong>Owner</strong></td>\n             <td width=\"9%\">\n             ";
echo "<s";
echo "trong>Clicks</strong>             </td>\n             <td width=\"14%\">";
echo "<s";
echo "trong>Impressions</strong>             </td>\n             <td width=\"11%\">";
echo "<s";
echo "trong>CTR (%)</strong></td>\n             <td width=\"13%\">";
echo "<s";
echo "trong>Click Value(";
if ( $currency_format == "\$\$" )
{
    echo $system_currency;
}
else
{
    echo $currency_symbol;
}
echo ")</strong> </td>\n             <td width=\"10%\">";
echo "<s";
echo "trong>\tPublisher Share(";
if ( $currency_format == "\$\$" )
{
    echo $system_currency;
}
else
{
    echo $currency_symbol;
}
echo ")</strong></td>\n             <td width=\"10%\">";
echo "<s";
echo "trong>Referral share(";
if ( $currency_format == "\$\$" )
{
    echo $system_currency;
}
else
{
    echo $currency_symbol;
}
echo ")</strong></td>\n             <td width=\"7%\">";
echo "<s";
echo "trong>Your share(";
if ( $currency_format == "\$\$" )
{
    echo $system_currency;
}
else
{
    echo $currency_symbol;
}
echo ")</strong></td>\n            </tr>\n            \n\t  ";
$i = 0;
while ( $row = __Func011__( $result ) )
{
    $total_impressions = __Func026__( $row[7], $time, $mysql, $row[6], $flag_time );
    $total_clicks = __Func027__( $row[7], $time, $mysql, $row[6], $flag_time );
    if ( $total_impressions == 0 )
    {
        $ctr = 0;
    }
    else
    {
        $ctr = $total_clicks / $total_impressions;
        $ctr = __Func028__( $ctr, 2 );
    }
    echo "\t\n      <tr ";
    if ( $i % 2 == 1 )
    {
        echo "class=\"specialrow\" ";
    }
    echo ">\n        <td>";
    echo "<s";
    echo "pan onmouseover=\"showad(";
    echo $row[7];
    echo ")\" onmouseout=\"hidead(";
    echo $row[7];
    echo ")\"><a href=\"ppc-view-keywords.php?id=";
    echo $row[7];
    echo "&statistics=";
    echo $show;
    echo "&tab=5&wap=";
    echo $row[13];
    echo "\">";
    echo $row[14];
    echo "</a></span>\n      \t<div  id=\"ad";
    echo $row[7];
    echo "\" class=\"layerbox\" >\n      \t";
    if ( $row[8] == 0 )
    {
        echo "<a href=\"";
        echo $row[1];
        echo "\">";
        echo $row[0];
        echo "</a><br>";
        echo $row[2];
        echo "<br>";
        echo $row[9];
        echo "\t ";
    }
    else if ( $row[8] == 2 )
    {
        $catalog_width = $mysql->echo_one( "select width from catalog_dimension where id='{$row['11']}' " );
        $catalog_height = $mysql->echo_one( "select height from catalog_dimension where id='{$row['11']}' " );
        if ( $row[15] == "swf" )
        {
            echo "\t  <table   border=\"0\" cellpadding=\"5\" cellspacing=\"0\"  >\n\t\t<td width=\"";
            echo $catalog_width;
            echo "\" height=\"";
            echo $catalog_height;
            echo "\" align=\"center\" valign=\"top\"><a href=\"";
            echo $row[1];
            echo "\">\n\t\t\n\t\t";
            echo "<s";
            echo "cript type=\"text/javascript\">\n\n\t\t  var flashvars = {};\n\t\t  var params = {};\n\t\t  var attributes = {};\n\t\t  var i=1;\n\t\t  \n\t\t  flashvars.clickTag = \"\";\n\t\t  \n          flashvars.clickTAG = \"\";\n\t\t  flashvars.clickTARGET = \"_blank\";\n\t\t  \n\t\t  \n\t\t  \n\t\t   params.wmode=\"transparent\";\n\t\t \t\t\n\t      swfobject.embedSWF(\"";
            echo "../".$GLOBALS['banners_folder']."/{$row['7']}/{$row['0']}";
            echo "\", \"myFlashDiv_";
            echo $row[7];
            echo "\", \"";
            echo $catalog_width;
            echo "\", \"";
            echo $catalog_height;
            echo "\", \"9.0.0\", \"\",flashvars,params,attributes);\n</script>\n\t\t  <div id=\"myFlashDiv_";
            echo $row[7];
            echo "\"></div>\n\t\t\n\t\t\n\t\t\n\t\t\n\t\t\n\t\t\n\t\t</a></td>\n\t\t  <td align=\"left\" valign=\"top\"><a href=\"";
            echo $row[1];
            echo "\">";
            echo $row[9];
            echo "</a><br>";
            echo $row[2];
            echo "</td>\n\t\t  </table>\n\t\t  ";
        }
        else
        {
            echo "\t\t\n\t\t<table   border=\"0\" cellpadding=\"5\" cellspacing=\"0\"  >\n\t\t<td width=\"";
            echo $catalog_width;
            echo "\" height=\"";
            echo $catalog_height;
            echo "\" align=\"center\" valign=\"top\"><a href=\"";
            echo $row[1];
            echo "\"><img src=\"../";
            echo $GLOBALS['banners_folder'];
            echo "/";
            echo $row[7];
            echo "/";
            echo $row[0];
            echo "\" border=\"0\" ></a></td>\n\t\t  <td align=\"left\" valign=\"top\"><a href=\"";
            echo $row[1];
            echo "\">";
            echo $row[9];
            echo "</a><br>";
            echo $row[2];
            echo "</td>\n\t\t  </table>\n\t\t  \n\t\t  \n\t\t  \n\t\t  \n\t\t  ";
        }
    }
    else
    {
        $banner_width = $mysql->echo_one( "select width from banner_dimension where id='{$row['11']}' " );
        $banner_height = $mysql->echo_one( "select height from banner_dimension where id='{$row['11']}' " );
        if ( $row[15] == "swf" )
        {
            echo "\t\t   <table  cellpadding=\"0\" cellspacing=\"0\"  >\n\t   <tr><td>\n";
            echo "<s";
            echo "cript type=\"text/javascript\">\n\n\t\t  var flashvars = {};\n\t\t  var params = {};\n\t\t  var attributes = {};\n\t\t  var i=1;\n\t\t  \n\t\t  flashvars.clickTag = \"\";\n\t\t  \n          flashvars.clickTAG = \"\";\n\t\t  flashvars.clickTARGET = \"_blank\";\n\t\t  \n\t\t  \n\t\t  \n\t\t   params.wmode=\"transparent\";\n\t\t \t\t\n\t      swfobject.embedSWF(\"";
            echo "../".$GLOBALS['banners_folder']."/{$row['7']}/{$row['2']}";
            echo "\", \"myFlashDiv_";
            echo $row[7];
            echo "\", \"";
            echo $banner_width;
            echo "\", \"";
            echo $banner_height;
            echo "\", \"9.0.0\", \"\",flashvars,params,attributes);\n</script>\n\t\t  <div id=\"myFlashDiv_";
            echo $row[7];
            echo "\"></div>\n\n</td></tr>\n\t   </table>\n\n\t\t  ";
        }
        else
        {
            echo "<table  cellpadding=\"0\" cellspacing=\"0\"  >\n\t   <tr><td ><a href=\"";
            echo $row[1];
            echo "\"><img src=\"";
            echo "../".$GLOBALS['banners_folder']."/{$row['7']}/{$row['2']}";
            echo "\"  border=\"0\" ></a></td></tr>\n\t   </table>";
        }
    }
    echo "      \n\t\t   \n      \t</div></td>\t<td><a href=\"view_profile.php?id=";
    echo $row[6];
    echo "\">";
    echo $row[5];
    echo "</a></td>\n\t\t<td width=\"9%\" >\n\t\t";
    echo __Func029__( $total_clicks, 0 );
    echo "</td>\n\t\t\n\t\t<td >";
    echo __Func029__( $total_impressions, 0 );
    echo "</td>\n\t\t<td >";
    echo __Func029__( $ctr * 100 );
    echo "   </td>\n\t\t<td >";
    $ret = __Func028__( __Func030__( $row[7], $time, $mysql, $row[6], $flag_time ), 2 );
    if ( $ret == "" )
    {
        $ret = 0;
    }
    $ret = __Func028__( $ret, 2 );
    echo __Func029__( $ret );
    echo "&nbsp;</td>\n\t\t<td  >";
    $re = __Func028__( __Func031__( $row[7], $time, $mysql, $row[6], $flag_time ), 2 );
    if ( $re == "" )
    {
        $re = 0;
    }
    $re = __Func029__( $re );
    echo $re;
    echo "&nbsp;</td>\n\t\t\t<td >";
    $ref_sh = __Func028__( __Func032__( $row[7], $time, $mysql, $row[6], $flag_time ), 2 );
    if ( $ref_sh == "" )
    {
        $ref_sh = 0;
    }
    $ref_sh = __Func028__( $ref_sh, 2 );
    $adv_ref_sh = __Func028__( __Func033__( $row[7], $time, $mysql, $row[6], $flag_time ), 2 );
    if ( $adv_ref_sh == "" )
    {
        $adv_ref_sh = 0;
    }
    $adv_ref_sh = __Func028__( $adv_ref_sh, 2 );
    $tot_ref = $ref_sh + $adv_ref_sh;
    echo __Func029__( $tot_ref );
    echo " </td>\n\t\t\n\t\t<td >";
    echo __Func029__( $ret - ( $re + $tot_ref ) );
    echo "&nbsp; </td>\t\n      </tr>\n\n\t\t";
    ++$i;
}
echo "\t\n\t";
if ( __Func020__( $result ) == 0 )
{
    echo "  <tr align=\"left\"><td align=\"left\" colspan=\"9\">No Ads  </td></tr>\n\t  ";
}
echo "\t\t</table>\t\t</td>\n        </tr>\n\n\n  <tr>\n    <td  colspan=\"6\">";
if ( 1 <= $total )
{
    echo "      Ads ";
    echo "<s";
    echo "pan class=\"inserted\">";
    echo ( $pageno - 1 ) * $perpagesize + 1;
    echo "</span> -\n        ";
    echo "<s";
    echo "pan class=\"inserted\">\n        ";
    if ( $total < $pageno * $perpagesize )
    {
        echo $total;
    }
    else
    {
        echo $pageno * $perpagesize;
    }
    echo "    </span>&nbsp;of ";
    echo "<s";
    echo "pan class=\"inserted\">";
    echo $total;
    echo "</span>&nbsp;\n    ";
}
echo "    &nbsp;&nbsp; <br> </td>\n    <td  align=\"right\">";
echo $paging->page( $total, $perpagesize, "", "ppc-admin-click-profit-statistics.php?statistics={$show}&adtype={$adtype}&device={$device}&status={$st}" );
echo "</td>\n    </tr>\n  \n      <tr>\n    <td colspan=\"8\">&nbsp;</td>\n    </tr>\n</table>\n\n\n\n\n\n\n\n";
if ( $adserver_upgradation_date != 0 )
{
    echo "\t<p>";
    echo "<s";
    echo "trong>Note:</strong>";
    echo "<s";
    echo "pan class=\"info\"> Impressions and Click Through Rates are available from : ";
    echo __Func034__( $adserver_upgradation_date );
    echo ".</span> </p>\n\n";
}
echo "\t \n\n";
include( "admin.footer.inc.php" );
echo "\n\n\n\n\n";
?>

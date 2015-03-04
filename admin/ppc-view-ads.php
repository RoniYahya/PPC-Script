<?php

function validatelicense( $license_key, $scriptcode )
{
    return true;
}

include( "config.inc.php" );
include( "../extended-config.inc.php" );
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
validatelicense( $license_key, "adserveradv" );
$url = urlencode( $_SERVER['REQUEST_URI'] );
loadsettings( "ppc_new" );
$budget_period = $GLOBALS['budget_period'];
if ( $budget_period == 1 )
{
    $budget_period_unit = "Monthly";
    $PERIOD = "in ".strftime( "%B", time( ) );
}
else if ( $budget_period == 2 )
{
    $budget_period_unit = "Daily";
    $PERIOD = "today";
}
if ( $_REQUEST )
{
    $adtype = $_REQUEST['adtype'];
    $device = $_REQUEST['device'];
    $st = $_REQUEST['status'];
    if ( $adtype == "0" )
    {
        $adty = "and b.adtype='0'";
    }
    else if ( $adtype == "1" )
    {
        $adty = "and b.adtype='1'";
    }
    else if ( $adtype == "2" )
    {
        $adty = "and b.adtype='2'";
    }
    else
    {
        $adty = "";
    }
    if ( $st == "1" )
    {
        $stat = "and b.status ='1'";
        $urlstr = "?status=1";
    }
    else if ( $st == "-1" )
    {
        $stat = "and b.status ='-1'";
        $urlstr = "?status=-1";
    }
    else if ( $st == "0" )
    {
        $stat = "and b.status ='0'";
        $urlstr = "?status=0";
    }
    else
    {
        $stat = "";
    }
    if ( $device == "0" )
    {
        $dev = "and b.wapstatus='0'";
    }
    else if ( $device == "1" )
    {
        $dev = "and b.wapstatus='1'";
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
include( "admin.header.inc.php" );
$pageno = 1;
if ( isset( $_REQUEST['page'] ) )
{
    $pageno = getsafepositiveinteger( "page" );
}
$perpagesize = 20;
$result = mysql_query( "select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused,b.wapstatus,b.name,b.contenttype,b.adult_status from ppc_users a,ppc_ads b where a.uid=b.uid {$stat} {$dev} {$adty} order by b.updatedtime desc LIMIT ".( $pageno - 1 ) * $perpagesize.", ".$perpagesize );
if ( mysql_num_rows( $result ) == 0 && 1 < $pageno )
{
    --$pageno;
    $result = mysql_query( "select b.title,b.link,b.summary,b.maxamount,b.status,a.username,a.uid,b.id,b.adtype,b.displayurl,b.pausestatus,b.bannersize,b.amountused ,b.wapstatus,b.name,b.contenttype from ppc_users a,ppc_ads b where a.uid=b.uid {$stat} {$dev} {$adty} order by b.updatedtime desc LIMIT ".( $pageno - 1 ) * $perpagesize.", ".$perpagesize );
}
$total = $mysql->echo_one( "select count(*) from ppc_users a,ppc_ads b where a.uid=b.uid {$stat} {$dev}  {$adty} " );
echo "\n";
echo "<s";
echo "cript type=\"text/javascript\">\n\tfunction promptuser()\n\t\t{\n\t\tvar answer = confirm (\"You are about to delete the ad. It won't be available later.\")\n\t\tif (answer)\n\t\t\treturn true;\n\t\telse\n\t\t\treturn false;\n\t\t}\n\t\t\n\t\tfunction showad(id)\n\t\t{\n\t\t\tdocument.getElementById('ad'+id).style.display='block';\n\t\t}\n\n\t\tfunction hidead(id)\n\t\t{\n\t\t\tdocument.getElementById('ad'+id).style.display='none';\n\t\t}\n\t\t\n\t\t\n\n</script>\n";
echo "<s";
echo "cript  language=\"javascript\" type=\"text/javascript\" src=\"../swfobject.js\"></script>\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr>\n    <td height=\"53\" colspan=\"4\"  align=\"left\">";
include( "submenus/ads.php" );
echo " </td>\n  </tr>\n  <tr>\n   <td   colspan=\"4\" scope=\"row\" class=\"heading\">Manage Ads</td>\n  </tr>\n</table>\n\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n  <td colspan=\"4\">&nbsp;</td>\n</tr>\n<tr>\n<td colspan=\"4\">\n";
echo "<s";
echo "pan class=\"inserted\">All ads are listed below. You can view the statistics as well as block/activate/delete the ads.</span></td>\n  </tr>\n\n\n<tr>\n<td colspan=\"4\">\n<form name=\"ads\" action=\"ppc-view-ads.php\" method=\"get\">\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n <tr>\n    <td height=\"34\" colspan=\"\">Status</td><td>";
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
echo ">All</option>\n</select></td><td>Type</td><td>";
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
echo ">All Ads</option>\n</select></td><td>Target</td><td>";
echo "<s";
echo "elect name=\"device\" id=\"device\" >\n<option value=\"0\" ";
if ( $device == "0" )
{
    echo "selected";
}
echo ">Desktop&Laptop </option>\n<option value=\"1\" ";
if ( $device == "1" )
{
    echo "selected";
}
echo ">Wap</option>\n<option value=\"2\" ";
if ( $device != "0" && $device != "1" )
{
    echo "selected";
}
echo ">All</option>\n</select></td><td><input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>\n  </tr>\n  </table> </form>  </td>\n  </tr>\n";
if ( 0 < $total )
{
    echo "<tr>\n  <tr><td colspan=\"2\" >";
    if ( 1 <= $total )
    {
        echo "   Showing Ads ";
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
    echo "    &nbsp;&nbsp;  </td><td width=\"50%\" align=\"right\" colspan=\"2\" >\n   ";
    if ( $wap_flag != "" )
    {
        echo "   ";
        echo $paging->page( $total, $perpagesize, "", "ppc-view-ads.php?adtype={$adtype}&device={$device}&status={$st}" );
        echo "    ";
    }
    else
    {
        echo "    ";
        echo $paging->page( $total, $perpagesize, "", "ppc-view-ads.php?adtype={$adtype}&device={$device}&status={$st}" );
        echo "    ";
    }
    echo "</td>\n  </tr>\n  \n  <tr><td colspan=\"4\"></td>\n  </tr>\n  \n<tr><td colspan=\"4\">\n\n<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"  class=\"datatable\">\n        <tr class=\"headrow\">\n\t\t<td width=\"21%\" >";
    echo "<s";
    echo "trong>\nName</strong></td>\n\t\t<td width=\"16%\">";
    echo "<s";
    echo "trong>Owner</strong></td>\n\t\t<td width=\"15%\">\n";
    echo "<s";
    echo "trong>Type</strong></td>\n\t\t<td width=\"9%\">";
    echo "<s";
    echo "trong>Device</strong></td>\n\t\t<td width=\"7%\">";
    echo "<s";
    echo "trong>Status</strong></td>\n\t\t<td width=\"9%\">";
    echo "<s";
    echo "trong>";
    echo $budget_period_unit;
    echo " Budget(";
    if ( $currency_format == "\$\$" )
    {
        echo $system_currency;
    }
    else
    {
        echo $currency_symbol;
    }
    echo ")</strong></td>\n<td width=\"23%\">";
    echo "<s";
    echo "trong>Action</strong></td>\n<td width=\"23%\">";
    echo "<s";
    echo "trong>Adult Status</strong></td>\n</tr>\n";
    $i = 0;
    while ( $row = mysql_fetch_row( $result ) )
    {
        echo "<tr ";
        if ( $i % 2 == 1 )
        {
            echo "class=\"specialrow\" ";
        }
        echo ">\n<td>";
        echo "\n\n";
        echo "<s";
        echo "pan onmouseover=\"showad(";
        echo $row[7];
        echo ")\" onmouseout=\"hidead(";
        echo $row[7];
        echo ")\"><a href=\"ppc-view-keywords.php?id=";
        echo $row[7];
        echo "&wap=";
        echo $row[13];
        echo "&url=";
        echo $url;
        echo "\">";
        echo $row[14];
        echo "</a></span>\n<div  id=\"ad";
        echo $row[7];
        echo "\" class=\"layerbox\" >\n<div class=\"adbox\">\n\t";
        $catalog_width = 0;
        $catalog_height = 0;
        $banner_width = 0;
        $banner_height = 0;
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
                echo "\t\t<table   border=\"0\" cellpadding=\"5\" cellspacing=\"0\"  >\n\t\t\t<td width=\"";
                echo $catalog_width;
                echo "\" height=\"";
                echo $catalog_height;
                echo "\" align=\"center\" valign=\"top\"><a href=\"";
                echo $row[1];
                echo "\">\n";
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
                echo "\"></div>\n\n</a></td>\n\t\t\t  <td align=\"left\" valign=\"top\"><a href=\"";
                echo $row[1];
                echo "\">";
                echo $row[9];
                echo "</a><br>";
                echo $row[2];
                echo "</td>\n\t\t\t  </table>\n\n\t\t  ";
            }
            else
            {
                echo "\t\t\n\t\t\n\t\t<table   border=\"0\" cellpadding=\"5\" cellspacing=\"0\"  >\n\t\t<td width=\"";
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
            $banner_height = $mysql->echo_one( "select height from banner_dimension where id='{$row['11']}'" );
            $banner_width = $mysql->echo_one( "select width from banner_dimension where id='{$row['11']}'" );
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
        echo "      \n</div>\n</div></td>\n<td><a href=\"view_profile.php?id=";
        echo $row[6];
        echo "\">";
        echo $row[5];
        echo "</a></td>\n<td>";
        if ( $row[8] == "0" )
        {
            echo "Text";
        }
        else if ( $row[8] == "1" )
        {
            if ( $row[15] == "swf" )
            {
                echo "Flash Banner"." <br>({$banner_width} x {$banner_height})";
            }
            else
            {
                echo "Banner"." <br>({$banner_width} x {$banner_height})";
            }
        }
        else if ( $row[15] == "swf" )
        {
            echo "Flash Catalog"." <br>({$catalog_width} x {$catalog_height})";
        }
        else
        {
            echo "Catalog"." <br>({$catalog_width} x {$catalog_height})";
        }
        echo "</td>\n<td>\n";
        if ( $row[13] == 1 )
        {
            $image = "wap.png";
        }
        else
        {
            $image = "pc.png";
        }
        echo "<img src=\"images/";
        echo $image;
        echo "\" width=\"20\" height=\"20\" border=\"0\"></td><td>";
        if ( $row[4] == "0" )
        {
            echo "Blocked";
        }
        else if ( $row[4] == "1" )
        {
            echo "Active";
        }
        else
        {
            echo "Pending";
        }
        echo "</td>\n<td>";
        echo numberformat( $row[3] );
        echo "</td>\n<td><a href=\"ppc-delete-ad.php?category=all&id=";
        echo $row[7];
        echo "\$wap=";
        echo $row[13];
        echo "&url=";
        echo $url;
        echo "\" onclick=\"return promptuser()\">Delete</a> |\n    ";
        if ( $row[4] == 1 )
        {
            echo "<a href=\"ppc-change-ad-status.php?category=all&action=block&wap=".$row[13]."&id=".$row[7]."&url=".$url."\">Block</a>";
        }
        else if ( $row[4] == 0 - 1 )
        {
            echo "<a href=\"ppc-change-ad-status.php?category=all&action=activate&wap=".$row[13]."&id=".$row[7]."&url=".$url."\">Activate</a> | ";
            echo "<a href=\"ppc-change-ad-status.php?category=all&action=block&wap=".$row[13]."&id=".$row[7]."&url=".$url."\">Block</a>";
        }
        else
        {
            echo "<a href=\"ppc-change-ad-status.php?category=all&action=activate&wap=".$row[13]."&id=".$row[7]."&url=".$url."\">Activate</a>";
        }
        echo " </td>\n\t\t  <td>\n\t\t  \n\t\t  ";
        if ( $row[16] == 1 )
        {
            echo "<a href=\"ppc-change-adult-status.php?id=".$row[7]."&status=0\">TURN OFF </a>";
        }
        else
        {
            echo "<a href=\"ppc-change-adult-status.php?id=".$row[7]."&status=1\">TURN ON</a>";
        }
        echo "\t\t  \n\t\t \n\t\t  </td>\n</tr>\n";
        ++$i;
    }
    echo "</table>\n\n\n</td></tr>\n\n\n<tr> <td colspan=\"2\" >";
    if ( 1 <= $total )
    {
        echo "  Showing Ads ";
        echo "<s";
        echo "pan class=\"inserted\">";
        echo ( $pageno - 1 ) * $perpagesize + 1;
        echo "</span> - ";
        echo "<s";
        echo "pan class=\"inserted\">\n  ";
        if ( $total < $pageno * $perpagesize )
        {
            echo $total;
        }
        else
        {
            echo $pageno * $perpagesize;
        }
        echo "  </span>&nbsp;of ";
        echo "<s";
        echo "pan class=\"inserted\">";
        echo $total;
        echo "</span>&nbsp;\n  ";
    }
    echo "&nbsp;&nbsp; </td><td  width=\"50%\" align=\"right\" colspan=\"2\" >\n";
    if ( $wap_flag != "" )
    {
        echo "    ";
        echo $paging->page( $total, $perpagesize, "", "ppc-view-ads.php?adtype={$adtype}&device={$device}&status={$st}" );
        echo "    ";
    }
    else
    {
        echo "    ";
        echo $paging->page( $total, $perpagesize, "", "ppc-view-ads.php?adtype={$adtype}&device={$device}&status={$st}" );
        echo "    ";
    }
    echo "    </td>\n  </tr>\n</table>\n\n";
}
else
{
    echo "<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n<tr>\n  <td colspan=\"4\">&nbsp;</td>\n</tr>\n<tr>\n  <td colspan=\"4\">\n";
    echo "<br>-No Records Found-<br><br>";
    echo "</td>\n</tr>\n</table>\n";
}
include( "admin.footer.inc.php" );
?>

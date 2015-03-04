<?php
include( "config.inc.php" );
if(!isset( $_COOKIE['inout_admin'])){
	header("Location:index.php");
	exit();
}
$inout_username = $_COOKIE['inout_admin'];
$inout_password = $_COOKIE['inout_pass'];
if(!($inout_username == md5( $username ) && $inout_password == md5( $password))){
	header("Location:index.php");
	exit();
}
if(isset( $_GET['wap'] ) && $_GET['wap'] == 1)
{
    $wap_flag = $_GET['wap'];
    $wap_string = " wap ";
}
else
{
    $wap_flag = 0;
    $wap_string = "";
}

include( "admin.header.inc.php" );
echo "\n";
echo "<script type=\"text/javascript\">\n\tfunction promptuser()\n\t\t{\n\t\tvar answer = confirm (\"You are about to delete the ad block. It won't be available later.\")\n\t\tif (answer)\n\t\t\treturn true;\n\t\telse\n\t\t\treturn false;\n\t\t}\n</script>\n\n\n\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr>\n    <td height=\"53\" colspan=\"4\"  align=\"left\">";
include( "submenus/adblocks.php" );
echo " </td>\n  </tr>\n  <tr>\n   <td   colspan=\"4\" scope=\"row\" class=\"heading\"> Manage Ad Blocks</td>\n  </tr>\n</table><br />\n\n\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr>\n    <td height=\"53\" colspan=\"4\"  align=\"left\">\n\t<form action=\"ppc-manage-ad.php\" method=\"get\" name=\"adblocks\">\n\tAdblock Target Device \n\t";
echo "<select name=\"wap\">\n\t<option value=\"0\" ";
if ( $wap_flag == 0 )
{
    echo "selected";
}
echo " >Desktops & Laptops</option>\n\t\t<option value=\"1\"  ";
if ( $wap_flag == 1 )
{
    echo "selected";
}
echo " >Wap Device</option>\n\t</select>\n\t <input type=\"submit\" value=\"Go\" />\n\t</form>\n\t </td>\n  </tr>\n</table><br />\n\n\n   ";
if ( $wap_flag == 1 )
{
    $result = mysql_query( "select id,width,height,ad_block_name,ad_type,status from wap_ad_block order by id DESC" );
}
else
{
    $result = mysql_query( "select id,width,height,ad_block_name,ad_type,status from ppc_ad_block order by id DESC" );
}
$no = count( $result );
if ( $no != 0 )
{
    echo "\t  <table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"datatable\">\n\n   \n\t\t  \n\t<tr  class=\"headrow\">\n\t      <td width=\"5%\"   >&nbsp;</td>\n      <td width=\"26%\" align=\"left\"  >Ad block name </td>\n\t\t   <td width=\"23%\" align=\"left\"  >Dimension</td>\n\t\t  <td width=\"21%\" align=\"left\"  >Type</td>\n          <td width=\"25%\" align=\"left\"  >Options</td>\n    </tr>\n\t    ";
    $i = 0;
    while ( $row = mysql_fetch_row( $result ) )
    {
        if ( $row[4] == 1 )
        {
            $ad_type = "Text only";
        }
        else if ( $row[4] == 2 )
        {
            $ad_type = "Banner only";
        }
        else if ( $row[4] == 4 )
        {
            $ad_type = "Catalog only";
        }
        else if ( $row[4] == 6 )
        {
            $ad_type = "Inline Text Ad";
        }
        else if ( $row[4] == 7 )
        {
            $ad_type = "Inline Catalog Ad";
        }
        else
        {
            $ad_type = "Text/Banner";
        }
        echo "           <tr ";
        if ( $i % 2 == 1 )
        {
            echo "class=\"specialrow\" ";
        }
        echo ">\n\t\t    <td  style=\"border-bottom: 1px solid #b7b5b3;\">&nbsp;</td>\n            <td  align=\"left\" style=\"border-bottom: 1px solid #b7b5b3;\">";
        echo $row[3];
        echo "</td>\n         \n            \n\t\t\t\n\t\t  <td  style=\"border-bottom: 1px solid #b7b5b3;\">";
        echo $row[1];
        echo "&nbsp;x&nbsp;";
        echo $row[2];
        echo "</td>\n\t\t   <td  style=\"border-bottom: 1px solid #b7b5b3;\">";
        echo $ad_type;
        echo "</td>\n\t\t  <td  align=\"left\" style=\"border-bottom: 1px solid #b7b5b3;\">\n\t\t  ";
        if ( $row[4] != 6 && $row[4] != 7 )
        {
            echo "<a href=ppc-show-ad-block.php?id=".$row[0]."&wap=".$wap_flag.">Edit</a>";
            echo "&nbsp;&nbsp;<a href=\"ppc-ad-delete.php?id=";
            echo $row[0];
            echo "&wap=";
            echo $wap_flag;
            echo "\">Delete</a>&nbsp;&nbsp;<a href=\"ppc-activate-ad.php?id=";
            echo $row[0];
            echo "&status=";
            echo $row[5];
            echo "&wap=";
            echo $wap_flag;
            echo "\">";
            if ( $row[5] == 0 )
            {
                echo "Activate";
            }
            else
            {
                echo "Block";
            }
            echo "\t\t  \n\t\t  </a>";
            echo "<a href=ppc-show-inline-ad-blocks.php?id=".$row[0].">Edit</a>";
            echo "&nbsp;&nbsp;<a href=\"ppc-activate-ad.php?id=";
            echo $row[0];
            echo "&status=";
            echo $row[5];
            echo "&wap=";
            echo $wap_flag;
            echo "\">";
        }
        else if ( $row[5] == 0 )
        {
            echo "Activate";
        }
        else
        {
            echo "Block";
        }
        echo "</td>\n\t\t\t  </tr>\t\n\t\t\t ";
        $i = $i + 1;
    }
    echo "\t\t    </table>\n\t\t   ";
}
else
{
    echo "No Records Found<br><br>";
}
echo "\n<br />\n\n";
include( "admin.footer.inc.php" );
?>

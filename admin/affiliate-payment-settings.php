<?php

$file = "export_data";
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

include_once( "admin.header.inc.php" );
echo "<script language=\"javascript\">\nfunction check_value()\n\t\t\t\t{\n\t\t\t\tif(document.getElementById('publisher_referral').value==\"\")\n\t\t\t\t\t{\n\t\t\t\t\t\t//refresh();\n\t\t\t\t\t\talert(\"Please enter publisher referral profit percent\");\n\t\t\t\t\t\t//document.form1.ppc_engine_name.focus();\n\t\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('advertiser_referral').value==\"\")\n\t\t\t\t\t{\n\t\t\t\t\t\t//refresh();\n\t\t\t\t\t\talert(\"Please ent";
echo "er advertiser referral profit percent\");\n\t\t\t\t\t\t//document.form1.ppc_engine_name.focus();\n\t\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t}\n</script>\n\n";
$value1 = $mysql->echo_one( "select value from ppc_settings where name='advertiser_referral_profit'" );
$value2 = $mysql->echo_one( "select value from ppc_settings where name='publisher_referral_profit'" );
echo "<form name=\"form1\" method=\"post\" action=\"ppc-setting-action.php\" onSubmit=\"return check_value()\">\n  <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n    <tr  >\n      <td height=\"60\" colspan=\"4\" scope=\"row\" class=\"heading\">Referral  Settings </td>\n    </tr>\n  \t\n    <tr align=\"left\">\n\t<td height=\"25\" colspan=\"4\" class=\"inserted\">\n                 Specify the percentage of profit for advertis";
echo "er and publisher referrals. The referral payment is based on clicks recieved by the referred advertisers and publishers.</td>\n    </tr>\n    <tr>\n      <td width=\"20%\" height=\"24\">&nbsp;</td>\n      <td width=\"1%\">&nbsp;</td>\n      <td width=\"11%\">&nbsp;</td>\n      <td width=\"67%\">&nbsp;</td>\n    </tr>\n\t <tr>\n      <td height=\"24\">Referral system </td>\n      <td>";
echo "<span class=\"pagetable_activecell\">:</span></td>\n      <td>";
echo "<select name=\"referral\" id=\"referral\">\n        <option value=\"1\" ";
if ( $referral_system == 1 )
{
    echo " selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $referral_system == 0 )
{
    echo " selected";
}
echo ">No</option>\n      </select>      </td>\n      <td>&nbsp;</td>\n    </tr>\n     <tr>\n       <td height=\"24\">&nbsp;</td>\n       <td>&nbsp;</td>\n       <td>&nbsp;</td>\n       <td>&nbsp;</td>\n     </tr>\n    <tr>\n      <td height=\"24\">Profit percent for publisher referral</td>\n      <td>";
echo "<span class=\"pagetable_activecell\">:</span></td>\n      <td><input name=\"publisher_referral\" type=\"text\" id=\"publisher_referral\" size=\"3\" value=\"";
echo $value2;
echo "\">\n      % </td>\n      <td>      (if you specify 10% ,for every click recieved by the referred publisher, 10% of click value will be credited to referrer) </td>\n    </tr>\n    <tr>\n      <td height=\"24\">&nbsp;</td>\n      <td>&nbsp;</td>\n      <td>&nbsp;</td>\n      <td>&nbsp;</td>\n    </tr>\n    <tr>\n      <td height=\"24\">Profit percent for advertiser referral</td>\n      <td>";
echo "<span class=\"pagetable_activecell\">:</span></td>\n      <td><input name=\"advertiser_referral\" type=\"text\" id=\"advertiser_referral\" size=\"3\" value=\"";
echo $value1;
echo "\">\n      %</td>\n      <td>(if you specify 10% ,for every click recieved by the referred advertiser, 10% of click value will be credited to referrer) </td>\n    </tr>\n   \n    <tr align=\"center\" valign=\"middle\">\n      <td scope=\"row\">&nbsp;</td>\n      <td scope=\"row\">&nbsp;</td>\n      <td colspan=\"2\" align=\"left\" scope=\"row\">&nbsp;</td>\n    </tr>\n    <tr align=\"center\" valign=\"middle\">\n      <td scope=\"row\">&nbsp;";
echo "</td>\n      <td scope=\"row\">&nbsp;</td>\n      <td colspan=\"2\" align=\"left\" scope=\"row\"><input type=\"submit\" name=\"Submit\" value=\"Update !\"></td>\n    </tr>\n\t<tr>\n\t<td height=\"24\" scope=\"row\" colspan=\"4\"><p>";
echo "<span class=\"note\">\n";
echo "<strong>Admin's profit from adunits</strong><br><br>\t\n\t  Total click value = admin's profit + advertiser referral profit + publisher referral profit + publisher profit<br />\n\n\n\nEarning from admin ad unit = total click value -   advertiser referral profit <br>\n\nEarning from publisher ad unit = total click value - ( publisher profit + advertiser referral profit + publisher referral profit )<br>\n\nExample ";
echo "<strong>:</strong><br />\n Consider a click of 1";
echo $currency_symbol;
echo " value<br>\nPublisher profit = ";
echo $publisher_profit;
echo " %<br>\nAdvertiser referral profit = ";
echo $advertiser_referral_profit;
echo " %<br>\nPublisher referral profit = ";
echo $publisher_referral_profit;
echo " %<br>\n";
$pub_share = 1 / 100 * $publisher_profit;
$adv_ref_share = 1 / 100 * $advertiser_referral_profit;
$pub_ref_share = 1 / 100 * $publisher_referral_profit;
$adm_profit = 1 - ( $pub_share + $adv_ref_share + $pub_ref_share );
echo "Admin's profit from his/her adunit";
echo "<strong> :</strong>  1";
echo $currency_symbol;
echo " - ";
echo $adv_ref_share;
echo $currency_symbol;
echo " = ";
echo 1 - $adv_ref_share;
echo $currency_symbol;
echo "<br>\nAdmin's profit from publisher adunit ";
echo "<strong>:</strong> 1";
echo $currency_symbol;
echo " - ( ";
echo $pub_share;
echo $currency_symbol;
echo " + ";
echo $adv_ref_share;
echo $currency_symbol;
echo " + ";
echo $pub_ref_share;
echo $currency_symbol;
echo " ) = ";
echo $adm_profit;
echo $currency_symbol;
echo "<br>\n\t  </span>\n\t  &nbsp;</p></td>\n    </tr>\n  </table>\n</form>\n";
include( "admin.footer.inc.php" );
?>

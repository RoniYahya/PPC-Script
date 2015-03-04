<?php

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
loadsettings( "ppc_new" );
$budget_period = $GLOBALS['budget_period'];

include( "admin.header.inc.php" );
echo "\n<table   border=\"0\" width=\"100%\"><tr><td width=\"218\" height=\"65\" colspan=\"4\" scope=\"row\" class=\"heading\"> \n  Monetory Settings </td></tr></table>\n  \n    <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\"  style=\"border :1px solid #CCCCCC; \">\n    <tr>\n      <td  valign=\"top\" >\n<div style=\"clear: both\"></div>\n\n<div align=\"left\" width: 100%>\n\n<table width=\"100%\"  border=\"0\" cellpadding=\"0\" cellspaci";
echo "ng=\"0\"  class=\"indexmenus\" >  <tr height=\"30px\">\n    <td align=\"center\"  id=\"index1_li_1\" ><a href=\"javascript:index1_ShowTab('1');\"  >General Monetory</a></td>\n    <td  align=\"center\" id=\"index1_li_2\"><a href=\"javascript:index1_ShowTab('2');\" >Advanced Settings</a></td>\n    <td  align=\"center\" id=\"index1_li_4\"><a href=\"javascript:index1_ShowTab('3');\" >Publisher Payment</a></td>\n\t<td align=\"center\"  id=\"index1";
echo "_li_5\"><a href=\"javascript:index1_ShowTab('4');\"  >Advertiser Payment</a></td>\n \n \n   \n  </tr>\n</table>\n\n\n\n\n\n</div>\n<div style=\"clear: both\"></div></td>\n    </tr>\n  \n\n    <tr  >\n      <td width=\"100%\" valign=\"top\"  >\t\n  \n\n\n\t\t<div id=\"index1_div_1\" style=\"padding:5px;\" class=\"div_font_style\">\n<form name=\"form1\" method=\"post\" action=\"ppc-setting-action.php\" onSubmit=\"return check_monetorysettings()\">\n  <input type";
echo "=\"hidden\" name=\"redir_url\" value=\"monetory-settings-tab.php?tab=1\"  />\t\t \n\n  <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n    \n    <tr>\n      <td width=\"47%\">&nbsp;</td>\n      <td width=\"53%\">&nbsp;</td>\n    </tr>\n   \n   \n    <tr>\n      <td height=\"25\">Budget period for ads </td>\n      <td> ";
echo "<s";
echo "elect name=\"periodic_budget\" id=\"periodic_budget\"><option value=\"1\" ";
if ( $budget_period == 1 )
{
    echo "selected";
}
echo ">Monthly</option><option value=\"2\" ";
if ( $budget_period == 2 )
{
    echo "selected";
}
echo ">Daily</option></select></td>\n    </tr>\n\t <tr>\n      <td height=\"25\">Default budget for ad</td>\n      <td>       <input name=\"default_admaxamount\" type=\"text\" id=\"default_admaxamount\" size=\"7\" value=\"";
echo $default_admaxamount;
echo "\"> ";
if ( $GLOBALS['currency_format'] == "\$\$" )
{
    echo $GLOBALS['system_currency'];
}
else
{
    echo $currency_symbol;
}
echo "</td>\n    </tr>\n\n    <tr>\n      <td height=\"25\">Keyword min. click value </td>\n      <td><input name=\"min_click_value\" type=\"text\" id=\"min_click_value\" value=\"";
echo $min_click_value;
echo "\" size=\"7\">\n       ";
if ( $GLOBALS['currency_format'] == "\$\$" )
{
    echo $GLOBALS['system_currency'];
}
else
{
    echo $currency_symbol;
}
echo " </td>\n    </tr>\n\t\n    <tr>\n      <td height=\"25\">System currency </td><td>\n      ";
echo "<s";
echo "elect name=\"curren\" id=\"curren_cy\" onChange=\"currency_symb()\">\n      ";
$currency = mysql_query( "select * from ppc_currency where status=1 order by currency" );
while ( $currency1 = mysql_fetch_row( $currency ) )
{
    echo "     \n\t\t<option value=\"";
    echo $currency1[0];
    echo "\" ";
    if ( $system_currency == $currency1[1] )
    {
        echo "selected=\"selected\" ";
    }
    echo ">";
    echo $currency1[1];
    echo "</option>\n\t\t\n\t\t";
}
echo "</select>\n    \n    </td>\n    </tr>\n    \n    \n    \n    \n    \n    <tr>\n   \n      <td height=\"22\" align=\"center\" scope=\"row\"><div align=\"left\">Currency symbol </div></td>\n      <td align=\"left\" scope=\"row\" >\n       ";
mysql_data_seek( $currency, 0 );
while ( $currency1 = mysql_fetch_row( $currency ) )
{
    echo "      \n      \n    ";
    echo "<s";
    echo "pan id=\"curr_symb_";
    echo $currency1[0];
    echo "\" style=\"display:none \" name=\"symbol_currency\" >";
    echo $currency1[2];
    echo "</span>  \n  ";
}
echo "     <input type=\"hidden\" name=\"hid_temp\" id=\"hid_temp\" value=\"\" /></td>\n  </tr>\n\t    \n\t\t\n\t    \n\t\t\n\t\n\t \n   \n    <tr>\n      <td height=\"24\">Publisher profit percentage *</td>\n      <td><input name=\"publisher_profit\" type=\"text\" id=\"publisher_profit\" size=\"10\" value=\"";
echo $publisher_profit;
echo "\">\n      %</td>\n      </tr>\n<tr>\n      <td height=\"24\">Premium publisher profit percentage *</td>\n      <td><input name=\"premium_profit\" type=\"text\" id=\"premium_profit\" size=\"10\" value=\"";
echo $premium_profit;
echo "\">\n      %</td>\n      </tr>\n    <tr>\n      <td align=\"center\" scope=\"row\">&nbsp;</td>\n      <td align=\"left\" scope=\"row\"><input type=\"submit\" name=\"Submit\" value=\"Update !\"></td>\n    </tr>\n\t\t  <tr>\n\t\t  <td height=\"24\" scope=\"row\" colspan=\"2\">";
echo "<s";
echo "pan class=\"note\"><br />\n\n*If you specify 20% as the publisher profit, then for a click of value  1\$ you will get 0.8\$ and the publisher will get 0.2\$ \n\n<br /><br />\n\n\n";
echo "<s";
echo "trong>Admin's profit from adunits</strong><br><br>\t\n\n";
if ( $referral_system == 1 )
{
    echo "\nTotal click value = admin's profit + advertiser referral profit + publisher referral profit + publisher profit<br />\nPlease note that referral commission settings can be configured <a href=\"affiliate-payment-settings.php\">here</a>. <br>\nEarning from admin ad unit = total click value -   advertiser referral profit <br>\nEarning from publisher ad unit = total click value - ( publisher profit + advertiser";
    echo " referral profit + publisher referral profit )<br>\nExample ";
    echo "<s";
    echo "trong>:</strong><br />\nConsider a click of 1";
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
    echo "<s";
    echo "trong> :</strong>  1";
    echo $currency_symbol;
    echo " - ";
    echo $adv_ref_share;
    echo $currency_symbol;
    echo " = ";
    echo 1 - $adv_ref_share;
    echo $currency_symbol;
    echo "<br>\nAdmin's profit from publisher adunit ";
    echo "<s";
    echo "trong>:</strong> 1";
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
    echo "\n";
}
else
{
    echo "\nTotal click value = admin's profit + publisher profit<br />\nEarning from admin ad unit = total click value  <br>\nEarning from publisher ad unit = total click value -  publisher profit <br>\nExample ";
    echo "<s";
    echo "trong>:</strong><br />\nConsider a click of 1";
    echo $currency_symbol;
    echo " value<br>\nPublisher profit = ";
    echo $publisher_profit;
    echo " %<br>\n";
    $pub_share = 1 / 100 * $publisher_profit;
    $adm_profit = 1 - $pub_share;
    echo "Admin's profit from his/her adunit";
    echo "<s";
    echo "trong> :</strong>  1";
    echo $currency_symbol;
    echo " <br>\nAdmin's profit from publisher adunit ";
    echo "<s";
    echo "trong>:</strong> 1";
    echo $currency_symbol;
    echo " -  ";
    echo $pub_share;
    echo $currency_symbol;
    echo "  = ";
    echo $adm_profit;
    echo $currency_symbol;
    echo "\n";
}
echo "\n<br>\n      </span>&nbsp;</td>\n      </tr>\n\n\t<tr>\n      <td height=\"24\">&nbsp;</td>\n      <td>&nbsp;</td>\n      </tr>\n  </table>\n</form>\n\n\t\t</div>\n\n\t    <div id=\"index1_div_2\"  style=\"padding:5px;display:;\">\n<form name=\"form2\" method=\"post\" action=\"ppc-setting-action.php\" onSubmit=\"return check_advertiserbonus()\">\n  <input type=\"hidden\" name=\"redir_url\" value=\"monetory-settings-tab.php?tab=2\"  />\t\t \n\n  <table widt";
echo "h=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n    \n    <tr>\n      <td>&nbsp;</td>\n      <td width=\"55%\">&nbsp;</td>\n    </tr>\n    <tr align=\"left\">\n\t<td height=\"25\" colspan=\"2\"  scope=\"row\" class=\"inserted\"> Bonus settings \n        </td>\n    </tr>\n   \n   \n    <!--<tr>\n      <td height=\"24\">Account opening bonus</td>\n      <td cl>      <input name=\"opening_bonus\" type=\"text\" id=\"opening_bonus\" size=\"7\" val";
echo "ue=\"";
echo $opening_bonus;
echo "\"> ";
if ( $GLOBALS['currency_format'] == "\$\$" )
{
    echo $GLOBALS['system_currency'];
}
else
{
    echo $currency_symbol;
}
echo "      ";
echo "<s";
echo "pan class=\"note\">( if you dont want to provide bonus make this 0 )</span> </td>\n    </tr>\n\n --><tr>\n      <td height=\"30\">Mask bonus only advertisers from publisher network *</td>\n      <td>";
echo "<s";
echo "elect name=\"bonous_system_type\" id=\"bonous_system_type\">\n        <option value=\"1\" ";
if ( $bonous_system_type == 1 )
{
    echo "selected";
}
echo " >Yes</option>\n        <option value=\"0\" ";
if ( $bonous_system_type == 0 )
{
    echo "selected";
}
echo " >No</option>\n      </select></td>\n    </tr> \n\t\n\t\n\t    <tr>\n      <td width=\"45%\">&nbsp;</td>\n      <td width=\"55%\">&nbsp;</td>\n    </tr>\n    <tr>\n      <td height=\"26\" colspan=\"2\" scope=\"row\">";
echo "<s";
echo "pan class=\"inserted\">Revenue booster settings * </span></td>\n    </tr>\n    \n    <tr>\n      <td height=\"26\">Enable revenue booster </td>\n      <td>";
echo "<s";
echo "elect name=\"revenue_booster\" id=\"revenue_booster\">\n\t       <option value=\"1\"  ";
if ( $revenue_booster == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $revenue_booster == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n    <tr>\n      <td height=\"24\">Select revenue booster level </td>\n      <td>";
echo "<s";
echo "elect name=\"revenue_boost_level\" id=\"revenue_boost_level\">\n\t  <option value=\"";
echo $revenue_boost_level;
echo "\" selected>";
echo $revenue_boost_level;
echo "</option>\n        <option value=\"1\">1</option>\n        <option value=\"2\">2</option>\n        <option value=\"3\">3</option>\n      </select> \n        ";
echo "<s";
echo "pan class=\"note\"> ( 3 for maximum boosting ) </span> </td>\n    </tr>\n    <tr align=\"center\">\n      <td align=\"left\" scope=\"row\">&nbsp;</td>\n      <td align=\"left\" scope=\"row\">&nbsp;</td>\n    </tr>\n\t\n    <tr align=\"center\" valign=\"middle\">\n      <td scope=\"row\">&nbsp;</td>\n      <td align=\"left\" scope=\"row\"><input type=\"submit\" name=\"Submit\" value=\"Update !\"></td>\n    </tr>\n\t <tr   valign=\"middle\">\n      <td colspan";
echo "=\"2\" class=\"note\">\n\t<br />\n";
echo "<s";
echo "trong>* Mask bonus only advertiser   from publisher network  :</strong> If ads of advertisers who have only bonus balance are displayed in publisher network, you will have to pay for publishers from your pocket because bonus money in advertiser account is not real funds added by them. So it is better to mask  ads of advertisers who have only bonus balance from publisher adunits. But if bonus paid o";
echo "ut is not a significant amount compared to funds received, you can allow showing such advertiser ads in publihser adunits.<br />\n<br />\n\n";
echo "<s";
echo "trong>* Revenue booster :</strong>\tIf you enable the revenue booster feature and set the boosting level, advertisers will be suggested higher click values for their keywords. For the first few months you may turn it off to attract more advertisers.&nbsp;\n<br />\n<br />\n\n\t</td>\n\t</tr>\n\t\n  </table>\n</form>\n   </div>\t\n <div id=\"index1_div_4\" style=\"padding:5px;display:;\" >\n<form name=\"form4\" method=\"post\" actio";
echo "n=\"ppc-setting-action.php\" onSubmit=\"return check_publisherprofit()\">\n  <input type=\"hidden\" name=\"redir_url\" value=\"monetory-settings-tab.php?tab=3\"  />\t\t \n  <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n    \n    <tr>\n      <td width=\"43%\">&nbsp;</td>\n      <td width=\"50%\">&nbsp;</td>\n    </tr>\n    <tr>\n      <td height=\"34\">Minimum balance for publisher account </td>\n      <td><input name=";
echo "\"min_publisher_acc_balance\" type=\"text\" id=\"min_publisher_acc_balance\" value=\"";
echo $min_publisher_acc_balance;
echo "\" size=\"7\">\n";
if ( $GLOBALS['currency_format'] == "\$\$" )
{
    echo $GLOBALS['system_currency'];
}
else
{
    echo $currency_symbol;
}
echo " </td>\n    </tr>\n    \n    \n     <tr>\n      <td height=\"30\">Enable paypal payment mode  </td>\n      <td>";
echo "<s";
echo "elect name=\"publisher_paypalpayment\" id=\"publisher_paypalpayment\">\n\t   <option value=\"1\"  ";
if ( $publisher_paypalpayment == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $publisher_paypalpayment == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n    \n    \n    \n    \n    \n    \n    \n    \n    \n    \n    \n    \n\t     <tr>\n      <td height=\"30\">Enable check payment mode  </td>\n      <td>";
echo "<s";
echo "elect name=\"publisher_checkpayment\" id=\"publisher_checkpayment\">\n\t   <option value=\"1\"  ";
if ( $publisher_checkpayment == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $publisher_checkpayment == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n\n\t   <tr>\n      <td height=\"30\">Enable bank payment mode </td>\n      <td>";
echo "<s";
echo "elect name=\"publisher_bankpayment\" id=\"publisher_bankpayment\">\n\t   <option value=\"1\"  ";
if ( $publisher_bankpayment == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $publisher_bankpayment == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n\t\n    <tr align=\"center\" valign=\"middle\">\n      <td scope=\"row\">&nbsp;</td>\n      <td align=\"left\" scope=\"row\"><input type=\"submit\" name=\"Submit\" value=\"Update !\"></td>\n    </tr>\n  </table>\n</form>\n\n\t    </div>\n\t\t <div id=\"index1_div_5\" style=\"padding:5px;display:;\" >\n\t<form name=\"form5\" method=\"post\" action=\"ppc-setting-action.php\" onsubmit=\"return check_payment()\">\n  ";
echo "<input type=\"hidden\" name=\"redir_url\" value=\"monetory-settings-tab.php?tab=4\"  />\t\t \n  <table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n   \n <tr>\n   <td width=\"41%\" height=\"30\">&nbsp;</td>\n      <td width=\"53%\">&nbsp;</td>\n    </tr>\n\t    <tr>\n      <td height=\"29\">Minimum amount advertiser can deposit </td>\n      <td><input name=\"min_user_transaction_amount\" type=\"text\" id=\"min_user_transaction";
echo "_amount\" value=\"";
echo $min_user_transaction_amount;
echo "\" size=\"7\">\n";
if ( $GLOBALS['currency_format'] == "\$\$" )
{
    echo $GLOBALS['system_currency'];
}
else
{
    echo $currency_symbol;
}
echo "      </td>\n    </tr>\n        <tr>\n          <td height=\"26\" colspan=\"2\" scope=\"row\">&nbsp;</td>\n        </tr>\n        <tr>\n      <td height=\"26\" colspan=\"2\" scope=\"row\">";
echo "<s";
echo "pan class=\"inserted\">Paypal Payment settings are given below.</span></td>\n    </tr>\n    \n        <tr>\n      <td height=\"30\">Enable Paypal payment mode  </td>\n      <td>";
echo "<s";
echo "elect name=\"advertiser_paypalpayment\" id=\"advertiser_paypalpayment\" onchange=\"activate_paypal_filelds()\">\n\t  <option value=\"1\"  ";
if ( $advertiser_paypalpayment == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $advertiser_paypalpayment == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n    \n    \n    \n   <tr>\n      <td colspan=\"2\" scope=\"row\">\n    \n    \n    \n     <table id=\"paypal_details\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"display:\">\n     <tr>\n   <td width=\"44%\" height=\"25\">Paypal currency </td>\n      <td width=\"56%\"> ";
echo "<s";
echo "elect name=\"paypal_curr\" id=\"pay_curren_cy\" >\n      ";
$currency = mysql_query( "select * from ppc_currency where status=1 order by currency" );
while ( $currency1 = mysql_fetch_row( $currency ) )
{
    echo "     \n\t\t<option value=\"";
    echo $currency1[0];
    echo "\" ";
    if ( $paypal_currency == $currency1[1] )
    {
        echo "selected=\"selected\" ";
    }
    echo ">";
    echo $currency1[1];
    echo "</option>\n\t\t\n\t\t";
}
echo "</select> ";
echo "<s";
echo "pan class=\"note\">( Paypal currency must be same as system currency. )</span></td>\n    </tr>\n \n   <tr>\n   <td height=\"30\">Paypal email address </td>\n      <td><input name=\"paypal_email\" type=\"text\" id=\"paypal_email\" size=\"30\" value=\"";
echo $paypal_email;
echo "\" ></td>\n    </tr>\n\t<tr>\n      <td height=\"27\">Description for Paypal payment </td>\n      <td><input name=\"payapl_payment_item_escription\" type=\"text\" id=\"payapl_payment_item_escription\" size=\"30\" value=\"";
echo $payapl_payment_item_escription;
echo "\" ></td>\n    </tr>\n    </table>    </td>\n    </tr>\n    <tr>\n      <td height=\"21\" colspan=\"2\" scope=\"row\">&nbsp;</td>\n    </tr>\n    <tr>\n      <td height=\"26\" colspan=\"2\" scope=\"row\">";
echo "<s";
echo "pan class=\"inserted\">Authorize.net Payment settings are given below.</span></td>\n    </tr>\n    \n    <tr>\n      <td height=\"30\">Enable Autorize.net payment mode </td>\n      <td>";
echo "<s";
echo "elect name=\"advertiser_authpayment\" id=\"advertiser_authpayment\" onchange=\"activate_auth_filelds()\">\n\t  <option value=\"1\"  ";
if ( $advertiser_authpayment == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $advertiser_authpayment == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n\n    <tr>\n      <td colspan=\"2\" scope=\"row\">\n<table id=\"auth_details\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" >\n\t\n\t<tr>\n    <td width=\"44%\" height=\"30\">Authorize.net API Login ID</td>\n      <td width=\"56%\"><input type=\"text\" name=\"loginid\" id=\"loginid\" value=\"";
echo $authpaymentLoginid;
echo "\"  size=\"35\" /></td>\n    </tr>\n\t<tr>\n    <td width=\"44%\" height=\"30\">Authorize.net Transaction Key</td>\n      <td width=\"56%\"><input type=\"text\" name=\"transkey\" id=\"transkey\" value=\"";
echo $authpaymentTransactionKey;
echo "\"  size=\"35\" /></td>\n    </tr>\n\t<tr>\n\t  <td height=\"30\">Authorize.net \n\n\n MD5 Hash value</td>\n\t  <td><input type=\"text\" name=\"secretkey\" id=\"secretkey\" value=\"";
echo $authSecretCode;
echo "\"  size=\"35\" /></td>\n\t  </tr>\n</table></td>\n      </tr>\n    <tr>\n      <td height=\"26\" colspan=\"2\" scope=\"row\">&nbsp;</td>\n    </tr>\n    <tr>\n      <td height=\"26\" colspan=\"2\" scope=\"row\">";
echo "<s";
echo "pan class=\"inserted\">Check Payment settings are given below.</span></td>\n    </tr>\n    \n    <tr>\n      <td height=\"30\">Enable check payment mode </td>\n      <td>";
echo "<s";
echo "elect name=\"advertiser_checkpayment\" id=\"advertiser_checkpayment\" onchange=\"activate_check_filelds()\">\n\t  <option value=\"1\"  ";
if ( $advertiser_checkpayment == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $advertiser_checkpayment == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n\t\n\t\n\t<tr><td colspan=\"2\">\n\t<table id=\"check_details\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" style=\"display:\">\n\t\n\t<tr>\n    <td width=\"44%\" height=\"30\">Payee name for advertiser check deposit</td>\n      <td width=\"56%\"><input type=\"text\" name=\"payeename\" id=\"payeename\" value=\"";
echo $checkpayment_payeename;
echo "\"  size=\"35\" /></td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Payee address</td>\n      <td><textarea  name=\"address\"  id=\"address\" rows=\"8\" id=\"address\" cols=\"35\">";
echo $checkpayment_payeeaddress;
echo "</textarea></td>\n    </tr>\n</table></td></tr>\n\t    <tr>\n      <td height=\"24\">&nbsp;</td>\n      <td>&nbsp;</td>\n    </tr>\n\t    <tr>\n      <td height=\"26\" colspan=\"2\" scope=\"row\">";
echo "<s";
echo "pan class=\"inserted\">Bank Payment settings are given below.</span></td>\n    </tr>\n    \n\t <tr>\n      <td height=\"30\">Enable bank payment  </td>\n      <td>";
echo "<s";
echo "elect name=\"advertiser_bankpayment\" id=\"advertiser_bankpayment\" onchange=\"activate_fields()\">\n\t  <option value=\"1\"  ";
if ( $advertiser_bankpayment == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $advertiser_bankpayment == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n    </tr>\n    \n\n\t<tr>\n\t\t<td colspan=\"2\">\n\t<table id=\"bank_details\" style=\"display:\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">\n\t\n\t\n  \n   <tr>\n    <td height=\"30\">Beneficiary name for fund transfer</td>\n      <td><input type=\"text\" name=\"bank_beneficiaryname\" id=\"bank_beneficiaryname\" value=\"";
echo $mysql->echo_one( "select value  from `admin_payment_details` where name='bank_beneficiaryname'" );
echo "\"  size=\"35\" /> </td>\n    </tr>\n\t \n\t <tr>\n    <td height=\"30\">Banking Account Number</td>\n      <td><input type=\"text\" name=\"bank_account_number\" id=\"bank_account_number\" value=\"";
echo $mysql->echo_one( "select value  from `admin_payment_details` where name= 'bank_account_number'" );
echo "\"  size=\"35\" /> </td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Banking Swift/Routing Number</td>\n      <td><input type=\"text\" name=\"routing_number\" id=\"routing_number\" value=\"";
echo $mysql->echo_one( "select value  from `admin_payment_details` where name='routing_number'" );
echo "\"  size=\"35\" /> </td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Bank Name</td>\n      <td><input type=\"text\" name=\"bank_name\" id=\"bank_name\" value=\"";
echo $mysql->echo_one( "select value  from `admin_payment_details` where name='bank_name'" );
echo "\"  size=\"35\" /></td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Bank Address</td>\n      <td><textarea name=\"bank_address\"  id=\"bank_address\" rows=\"8\"  cols=\"35\">";
echo $mysql->echo_one( "select value  from `admin_payment_details` where name='bank_address'" );
echo "</textarea></td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Bank City</td>\n      <td><input type=\"text\" name=\"bank_city\" id=\"bank_city\" value=\"";
echo $mysql->echo_one( "select value  from `admin_payment_details` where name='bank_city'" );
echo "\"  size=\"35\" /></td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Bank Province</td>\n      <td><input type=\"text\" name=\"bank_province\" id=\"bank_province\" value=\"";
echo $mysql->echo_one( "select value  from `admin_payment_details` where name='bank_province'" );
echo "\"  size=\"35\" /></td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Bank Country</td>\n      <td><input type=\"text\" name=\"bank_country\" id=\"bank_country\" value=\"";
echo $mysql->echo_one( "select value from `admin_payment_details`  where name='bank_country'" );
echo "\"  size=\"35\" /></td>\n    </tr>\n \t <tr>\n    <td height=\"30\">Account Type</td>\n      <td><input type=\"text\" name=\"account_type\" id=\"account_type\" value=\"";
echo $mysql->echo_one( "select value   from `admin_payment_details` where name= 'account_type'" );
echo "\"  size=\"35\" /></td>\n    </tr>\n\n\t\n\t    <tr align=\"center\">\n      <td align=\"left\" scope=\"row\">&nbsp;</td>\n      <td align=\"left\" scope=\"row\">&nbsp;</td>\n    </tr>\n\t</table>\t</td></tr>\n\n\t\n\t\n\t<tr>\n    <td colspan=\"2\">&nbsp;</td>\n    </tr>\n   \n\t <!--<tr>\n   <td height=\"30\" scope=\"row\">&nbsp;</td>\n      <td>&nbsp;</td>\n      <td>&nbsp;</td>\n    </tr>-->\n\t \n\t\t    <tr>\n      <td height=\"26\" colspan=\"2\" scope=\"row\">";
echo "<s";
echo "pan class=\"inserted\">Local currency payment settings are given below.  </span>(Applicable for bank and check payments only)</td>\n    </tr>\n    \n  \t<tr>\n\t<td height=\"25\">Enable local currency payment </td>\n      <td>";
echo "<s";
echo "elect name=\"local_currency_pay\" id=\"local_currency_pay\">\n\t   <option value=\"1\"  ";
if ( $local_currency_pay == 1 )
{
    echo "selected";
}
echo ">Yes</option>\n        <option value=\"0\" ";
if ( $local_currency_pay == 0 )
{
    echo "selected";
}
echo ">No</option>\n      </select></td>\n\t</tr>\n\n\t<tr>\n\t<td height=\"26\">Enter minimum amount for local currency transaction </td>\n      <td><input type=\"text\" name=\"min_local_currency_pay_amt\"  id=\"min_local_currency_pay_amt\" value=\"";
echo $min_local_currency_pay_amt;
echo "\"  size=\"7\" maxlength=\"7\t\"/></td>\n\t</tr>\n\n\n    <tr align=\"center\">\n      <td align=\"left\" scope=\"row\">&nbsp;</td>\n      <td align=\"left\" scope=\"row\">&nbsp;</td>\n    </tr>\n    <tr align=\"center\">\n      <td align=\"left\" scope=\"row\">&nbsp;</td>\n      <td align=\"left\" scope=\"row\"><input type=\"submit\" name=\"Submit\" value=\"Update !\"></td>\n    </tr>\n  </table>\n</form>\n\n\n\t    </div>\n\t\t  \n      </td>\n    \n    </tr>\n    \n</tabl";
echo "e>\n\n\n\n\n\n\n";
echo "<s";
echo "cript language=\"javascript\" type=\"text/javascript\">\nfunction index1_ShowTab(id)\n{\n\n\tif(id=='1')\n\t{\n\t\tdocument.getElementById('index1_div_1').style.display=\"\";\n\t\t//document.getElementById('index1_div_3').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_4').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_2').style.display=\"none\";\n\t\t\tdocument.getElementById('index1_div_5').styl";
echo "e.display=\"none\";\n\t\t\n\t\tdocument.getElementById(\"index1_li_1\").style.background=\"url(images/li_bgselect.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_2\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\t//document.getElementById(\"index1_li_3\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_4\").style.background=\"url(images/li_bgnormal.j";
echo "pg) repeat-x\";\n\t\t\tdocument.getElementById(\"index1_li_5\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t}\n\tif(id=='2')\n\t{\n\t\tdocument.getElementById('index1_div_1').style.display=\"none\";\n\t\t//document.getElementById('index1_div_3').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_4').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_2').style.display=\"\";\n\t\t\tdocument.g";
echo "etElementById('index1_div_5').style.display=\"none\";\n\t\t\n\t\tdocument.getElementById(\"index1_li_2\").style.background=\"url(images/li_bgselect.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_1\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\t//document.getElementById(\"index1_li_3\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_4\").style.ba";
echo "ckground=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\t\tdocument.getElementById(\"index1_li_5\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t}\n\t/* if(id=='3')\n\t{\n\t  \tdocument.getElementById('index1_div_1').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_2').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_4').style.display=\"none\";\n\t\t//document.getElementById('index1_";
echo "div_3').style.display=\"\";\n\t\t\tdocument.getElementById('index1_div_5').style.display=\"none\";\n\t\t\n\t\tdocument.getElementById(\"index1_li_3\").style.background=\"url(images/li_bgselect.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_1\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_2\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\tdocument.g";
echo "etElementById(\"index1_li_4\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\t\tdocument.getElementById(\"index1_li_5\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t}*/\n  if(id=='3')\n\t{\n\t  \tdocument.getElementById('index1_div_1').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_2').style.display=\"none\";\n\t\t//document.getElementById('index1_div_3').style.display=\"none";
echo "\";\n\t\tdocument.getElementById('index1_div_4').style.display=\"\";\n\t\t\tdocument.getElementById('index1_div_5').style.display=\"none\";\n\t\t\n\t\tdocument.getElementById(\"index1_li_4\").style.background=\"url(images/li_bgselect.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_1\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_2\").style.background=\"url(images/li_";
echo "bgnormal.jpg) repeat-x\";\n\t\t//document.getElementById(\"index1_li_3\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\t\tdocument.getElementById(\"index1_li_5\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t}\n\t if(id=='4')\n\t{\n\t  \tdocument.getElementById('index1_div_1').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_2').style.display=\"none\";\n\t\t//document.getElementByI";
echo "d('index1_div_3').style.display=\"none\";\n\t\tdocument.getElementById('index1_div_4').style.display=\"none\";\n\t\t\tdocument.getElementById('index1_div_5').style.display=\"\";\n\t\t\n\t\tdocument.getElementById(\"index1_li_5\").style.background=\"url(images/li_bgselect.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li_1\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\tdocument.getElementById(\"index1_li";
echo "_2\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\t//document.getElementById(\"index1_li_3\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t\t\tdocument.getElementById(\"index1_li_4\").style.background=\"url(images/li_bgnormal.jpg) repeat-x\";\n\t}\n   \n\n\t\n\t\n}\n\n\n";
if ( isset( $_REQUEST['tab'] ) && is_numeric( $_REQUEST['tab'] ) && 0 < $_REQUEST['tab'] && $_REQUEST['tab'] <= 4 )
{
    echo "index1_ShowTab(";
    echo $_REQUEST['tab'];
    echo " );\n";
}
else
{
    echo "index1_ShowTab(1);\n";
}
echo "\n\n</script>\n";
echo "<s";
echo "cript language=\"javascript\">\nfunction currency_symb()\n\t\t\t{\nvar id=document.getElementById('curren_cy').value;\n\n\t\t\tif(document.getElementById('curren_cy').value==\"\")\n\t\t\t{\n\t\t\n\t\t\n\t\t\t}\n\t\t\telse\n\t\t\t{\n\t\t\tdocument.getElementById('curr_symb_'+id).style.display=\"\";\n\t\tvar pre;\n\t\tpre=document.getElementById('hid_temp').value;\n\t\t\t\n\t\t\tdocument.getElementById('hid_temp').value='curr_symb_'+id;\n\t\t\tif(pre!=\"\")\n\t\t\t";
echo "\n\t\t\tdocument.getElementById(pre).style.display=\"none\";\n\t\t\t}\n\t\t\t\t\n\t\t\t}\t\nfunction check_monetorysettings()\n\t\t\t\t{\n\n\t\t\t\t\t \tif (trim(document.getElementById('default_admaxamount').value).length==0)\n\t\t\t\t\t \t{\n\t\t\t\t\t\talert(\"Please enter default budget for ad. \");\n\t\t\t\t\t\t return false;\n\t\t\t\t\t\t}\n\t\t\t\t\t\t\n\t\t\t\t\t\tif (trim(document.getElementById('min_click_value').value).length==0)\n\t\t\t\t\t \t{\n\t\t\t\t\t\talert(\"Please ente";
echo "r min. click value \");\n\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\n\t\t\t\t\t if (trim(document.getElementById('paypal_currency').value).length== 0)\n\t\t\t\t\t \t{\n\t\t\t\t\talert(\"Please enter paypal currency type  \");\n\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\n\t\t\t\t\t if (trim(document.getElementById('currency_symbol').value).length == 0)\n\t\t\t\t\t \t{\n\t\t\t\t\t\talert(\"Please enter currency symbol   \");\n\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\t\t\t\t\t\t \n\t\t\t\t\t";
echo " if (trim(document.getElementById('publisher_profit').value).length == 0)\n\t\t\t\t\t \t{\n\t\t\t\t\t\talert(\"Please enter publisher profit percentage   \");\n\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\t\t\t\t\t\t if (trim(document.getElementById('premium_profit').value).length == 0)\n\t\t\t\t\t \t{\n\t\t\t\t\t\talert(\"Please enter publisher premium percentage   \");\n\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\t\t\t\t}\n//function check_advertiserbonus()\n//\t\t\t\t{\n";
echo "//\n//\t\t\t\t\t if (trim(document.getElementById('opening_bonus').value).length == 0)\n//\t\t\t\t\t \t{\n//\t\t\t\t\t\talert(\"Please enter account opening bonus  \");\n//\t\t\t\t\t\t return false;\n//\t\t\t\t\t\t }\n//\t\t\t\t}\n\t\t\t\t\nfunction check_publisherprofit()\n\t\t\t\t{\n\t\t\t\tif(document.getElementById('min_publisher_acc_balance').value==\"\")\n\t\t\t\t\t{\n\t\t\t\t\t\t//refresh();\n\t\t\t\t\t\talert(\"Please enter minimum balance for publisher account   \");\n";
echo "\t\t\t\t\t\t//document.form1.ppc_engine_name.focus();\n\t\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t\t\n\t\t\t\t}\n\t\t\tfunction trim(stringValue){\n\t\t\t\t\treturn stringValue.replace(/(^\\s*|\\s*\$)/, \"\");\n\t\t\t\t\t}\n\t\t\t\t\n\t\t\t\tfunction check_payment()\n\t\t\t\t{\n\n\t\t\t\t\t if (trim(document.getElementById('min_user_transaction_amount').value).length == 0)\n\t\t\t\t\t \t{\n\t\t\t\t\talert(\"Please enter minimum amount advertiser can deposit  \");\n\t\t\t\t\t\t return f";
echo "alse;\n\t\t\t\t\t\t }\n\t\t\t\t\t if (trim(document.getElementById('paypal_email').value).length == 0)\n\t\t\t\t\t \t{\n\t\t\t\t\t\t\talert(\"Please enter paypal email \");\n\t\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\t\t\t\t if (trim(document.getElementById('payapl_payment_item_escription').value).length == 0)\n\t\t\t\t\t \t{\n\t\t\t\t\t\t\talert(\"Please enter paypal payment description\");\n\t\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\t\t\t\t\t\t\t\t\t\n\t\t\t\t\n if(document.getElemen";
echo "tById('advertiser_checkpayment').options[document.getElementById('advertiser_checkpayment').selectedIndex].value==1)\t\t\t\n {\n \t \t\t\t\t\tif(document.getElementById('payeename').value==\"\")\n\t\t\t\t\t\t{\n\t\t\t\t\t\t\talert(\"Please enter payee name for check transfer  \");\t\t\t\t\t\t\n\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('address').value==\"\")\n\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\talert(\"Please enter payee address  \");\t";
echo "\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t}\n\n }\n if(document.getElementById('advertiser_bankpayment').options[document.getElementById('advertiser_bankpayment').selectedIndex].value==1)\t\t\t\n {\t\t\t\t\t\t\n\t\t\t\t\tif(document.getElementById('bank_beneficiaryname').value==\"\")\n\t\t\t\t\t\t{\n\t\t\t\t\t\t\talert(\"Please enter beneficiary name for fund transfer  \");\t\t\t\t\t\t\n\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementByI";
echo "d('bank_account_number').value==\"\")\n\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\talert(\"Please enter Bank Account Number  \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('routing_number').value==\"\")\n\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\t\talert(\"Please enter Bank Swift/Routing Number   \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('bank_name').value==\"\")\n\t\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\t\tal";
echo "ert(\"Please enter Bank Name  \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('bank_address').value==\"\")\n\t\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\t\talert(\"Please enter Bank Address   \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('bank_city').value==\"\")\n\t\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\t\talert(\"Please enter Bank City \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t\t}\n\t\t\t\tif";
echo "(document.getElementById('bank_province').value==\"\")\n\t\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\t\talert(\"Please enter Bank Province   \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('bank_country').value==\"\")\n\t\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t\t\t\talert(\"Please enter Bank Country   \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t\t\t\t}\n\t\t\t\tif(document.getElementById('account_type').value==\"\")\n\t\t\t\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\t";
echo "\t\t\talert(\"Please enter Account Type   \");\t\t\t\t\t\t\n\t\t\t\t\t\t\t\t\t\t\treturn false;\n\n\t\t\t\t\t\t\t\t\t\t\t}\n}\t\t\t\t\t\t\t\t\t\t\t\n if(document.getElementById('local_currency_pay').options[document.getElementById('local_currency_pay').selectedIndex].value==1)\n {\n\t\t\t\t\t if (trim(document.getElementById('min_local_currency_pay_amt').value).length == 0)\n\t\t\t\t\t \t{\n\t\t\t\t\talert(\"Please enter minimum  amount for local currency transactio";
echo "n  \");\n\t\t\t\t\t\t return false;\n\t\t\t\t\t\t }\n\t\t\t\t\t\t\n\t\t\t\t\n\t\t\t\t\tif(document.getElementById('min_local_currency_pay_amt').value<=0)\n\t\t\t\t\t{\n\t\t\t\t\t\t//refresh();\n\t\t\t\t\t\talert(\"Please enter valid amount for local currency transaction  \");\n\t\t\t\t\t\t//document.form1.ppc_engine_name.focus();\n\t\t\t\t\t\treturn false;\n\t\t\t\t\t}\n}\t\t\t\t\n\t\t\t}\n\t\tfunction activate_fields()\n\t\t\t{\n\t\t\tif(document.getElementById('advertiser_bankpayment').val";
echo "ue==1)\n\t\t\t\tdocument.getElementById('bank_details').style.display=\"\";\n\t\t\telse\n\t\t\t\tdocument.getElementById('bank_details').style.display=\"none\";\n\t\t\t}\t\t\n\t\tfunction activate_check_filelds()\n\t\t\t{\n\t\t\t\n\t\t\tif(document.getElementById('advertiser_checkpayment').value==1)\n\t\t\t\tdocument.getElementById('check_details').style.display=\"\";\n\t\t\telse\n\t\t\t\tdocument.getElementById('check_details').style.display=\"none\";\n";
echo "\t\t\t}\t\t\n\t\tfunction activate_auth_filelds()\n\t\t\t{\n\t\t\tif(document.getElementById('advertiser_authpayment').value==1)\n\t\t\t\tdocument.getElementById('auth_details').style.display=\"\";\n\t\t\telse\n\t\t\t\tdocument.getElementById('auth_details').style.display=\"none\";\n\t\t\t}\n\t\t\tfunction activate_paypal_filelds()\n\t\t\t{\n\t\t\tif(document.getElementById('advertiser_paypalpayment').value==1)\n\t\t\t\tdocument.getElementById('paypal";
echo "_details').style.display=\"\";\n\t\t\telse\n\t\t\t\tdocument.getElementById('paypal_details').style.display=\"none\";\n\t\t\t}\ncurrency_symb();\t\nactivate_fields();\nactivate_check_filelds();\nactivate_auth_filelds();\t\nactivate_paypal_filelds();\t\t\n</script>\n <br />\n\n";
include( "admin.footer.inc.php" );
?>

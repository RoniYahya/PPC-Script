<?php
include( "config.inc.php" );
if(!isset( $_COOKIE['inout_admin'])){
	header("Location:index.php");
	exit();
}
$inout_username = $_COOKIE['inout_admin'];
$inout_password = $_COOKIE['inout_pass'];
if ( !( $inout_username == md5( $username ) && $inout_password == md5( $password ) ) ){
	header("Location:index.php");
	exit();
}

include( "admin.header.inc.php" );
echo "<style type=\"text/css\">\n<!--\n.style3 {\n\tcolor: #009933;\n\tfont-size: 14px;\n\tfont-weight: bold;\n}\n\n-->\n</style>\n";
$ret1 = 0;
$ret2 = 0;
$share_paid = $mysql->echo_one( "select SUM(amount) from ppc_publisher_payment_hist where status=3" );
if ( $share_paid == "" )
{
    $share_paid = 0;
}
$share_due = $mysql->echo_one( "select SUM(accountbalance) from ppc_publishers " );
if ( $share_due == "" )
{
    $share_due = 0;
}
$share_due_temp = $mysql->echo_one( "select SUM(amount) from ppc_publisher_payment_hist where status=0 or status=1" );
if ( $share_due_temp == "" )
{
    $share_due_temp = 0;
}
$share_due = $share_due + $share_due_temp;
$amt_under_settlement = $mysql->echo_one( "select COALESCE(sum(publisher_profit),0) from `ppc_daily_clicks`" );
if ( $amt_under_settlement == "" )
{
    $amt_under_settlement = 0;
}
$adv_referral_amt = $mysql->echo_one( "select COALESCE(sum(pub_ref_profit),0) from `ppc_daily_clicks`" );
if ( $adv_referral_amt == "" )
{
    $adv_referral_amt = 0;
}
$pub_referral_amt = $mysql->echo_one( "select COALESCE(sum(adv_ref_profit),0) from `ppc_daily_clicks`" );
if ( $pub_referral_amt == "" )
{
    $pub_referral_amt = 0;
}
$amt_under_settlement = $amt_under_settlement + $adv_referral_amt + $pub_referral_amt;
$tot = $share_paid + $share_due;
echo "\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr>\n    <td height=\"53\" colspan=\"4\"  align=\"left\">";
include( "submenus/status.php" );
echo " </td>\n  </tr>\n  <tr>\n   <td   colspan=\"4\" scope=\"row\" class=\"heading\">Revenue Details</td>\n  </tr>\n</table>\n<br />\n\n \n \n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n\n\n\n  <tr>\n    <td width=\"59%\">&nbsp;</td>\n    <td width=\"1%\">&nbsp;</td>\n    <td width=\"37%\">&nbsp;</td>\n    <td width=\"3%\">&nbsp;</td>\n  </tr>\n  \n\n\n\n  <tr >\n    <td >";
echo "<s";
echo "pan class=\"inserted\">Fund   Details </span></td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n   <tr>\n    <td>Total Fund Received - Paypal </td>\n    <td>: </td>\n    <td align=\"right\">";
$fund = $mysql->echo_one( "select SUM(amount) from inout_ppc_ipns where result='1'" );
if ( $fund == "" )
{
    $fund = 0;
}
echo "<strong>".htmlspecialchars( $fund )."</strong>";
echo "</td>\n    <td>&nbsp;</td>\n  </tr>\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n  <tr>\n    <td>Total Fund Received - Authorize.net </td>\n    <td>: </td>\n    <td align=\"right\">";
$fund_auth = $mysql->echo_one( "select SUM(x_amount) from authorize_ipn where x_response_code='1'" );
if ( $fund_auth == "" )
{
    $fund_auth = 0;
}
echo "<strong>".htmlspecialchars( $fund_auth )."</strong>";
echo "</td>\n    <td align=\"center\">+</td>\n  </tr>\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n <tr>\n    <td>Total Fund Received - Check/Bank/Internal Transfer </td>\n    <td>:</td>\n    <td align=\"right\">";
$check = $mysql->echo_one( "select SUM(amount) from advertiser_fund_deposit_history where status='3'" );
if ( $check == "" )
{
    $check = 0;
}
echo "<strong>".htmlspecialchars( $check )."</strong>";
echo "</td>\n    <td align=\"center\">+</td>\n </tr>\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n  <tr>\n    <td>Total Fund Received - Others (Excluding bonus deposits) </td>\n    <td>:</td>\n    <td align=\"right\">";
$others = $mysql->echo_one( "select SUM(amount) from advertiser_bonus_deposit_history where type=1" );
if ( $others == "" )
{
    $others = 0;
}
echo "<strong>".htmlspecialchars( $others )."</strong>";
echo "</td>\n    <td align=\"center\">+</td>\n  </tr>\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n  <tr style=\"background-color:#E0E0E0\" height=\"25px\">\n    <td>Total funds recieved (A) </td>\n    <td>:</td>\n    <td align=\"right\">";
$total = $others + $check + $fund + $fund_auth;
echo "<strong>";
echo " &nbsp;";
echo htmlspecialchars( $total )."</strong>";
echo "</td>\n    <td>&nbsp;</td>\n  </tr>\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n \n      <tr>\n        <td height=\"19\" >";
echo "<span class=\"inserted\">Publisher Share Details </span></td>\n        <td width=\"1%\">&nbsp;</td>\n        <td width=\"37%\">&nbsp;</td>\n        <td width=\"3%\">&nbsp;</td>\n      </tr>\n      <tr>\n        <td >&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td width=\"59%\" >Publisher share paid&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nb";
echo "sp;&nbsp;</td>\n        <td width=\"1%\">:</td>\n        <td width=\"37%\" align=\"right\"> ";
echo "<strong></strong>";
echo "<strong>".htmlspecialchars( $share_paid )."</strong>";
echo "</td>\n        <td width=\"3%\">&nbsp;</td>\n      </tr>\n      <tr>\n        <td >&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td >Publisher share due </td>\n        <td width=\"1%\">:</td>\n        <td width=\"37%\" align=\"right\">";
echo "<strong></strong>";
echo "<strong>".htmlspecialchars( $share_due )."</strong>";
echo "</td>\n        <td width=\"3%\" align=\"center\">+</td>\n      </tr>\n\t  <tr>\n        <td >&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n\t  </tr>\n   \n   \n      <tr style=\"background-color:#E0E0E0\" height=\"25px\">\n        <td >Total publisher share (B) </td>\n        <td>:</td>\n        <td align=\"right\">";
echo "<strong></strong>";
echo "<strong>".htmlspecialchars( $tot )."</strong>";
echo "</td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td >&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n\n      <tr>\n        <td >";
echo "<span class=\"inserted\"> Generated Revenue Details </span></td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td >&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td >Total click value generated </td>\n        <td>:</td>\n        <td align=\"right\">";
echo "<strong>";
$ret = $mysql->echo_one( "select COALESCE(sum(money_spent),0) from advertiser_yearly_statistics " ) + $mysql->echo_one( "select COALESCE(sum(money_spent),0) from advertiser_monthly_statistics " ) + $mysql->echo_one( "select COALESCE(sum(money_spent),0) from advertiser_daily_statistics " );
if ( $ret == "" )
{
    $ret = 0;
}
$set = $mysql->echo_one( "select COALESCE(SUM(clickvalue),0) from `ppc_daily_clicks` " );
if ( $set == "" )
{
    $set = 0;
}
echo htmlspecialchars( $ret + $set );
echo "        </strong></td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td >&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n \n  \n      <tr>\n        <td >Bonus spent in these clicks </td>\n        <td>:</td>\n        <td align=\"right\">";
$b = $mysql->echo_one( "select SUM(amount) from advertiser_bonus_deposit_history where type=0" );
if ( $b == "" )
{
    $b = 0;
}
$bon_bal = $mysql->echo_one( "select SUM(bonusbalance) from ppc_users " );
if ( $bon_bal == "" )
{
    $bon_bal = 0;
}
$bon_used = $b - $bon_bal;
echo "<strong>";
echo "".htmlspecialchars( $bon_used )."</strong>";
echo "</td>\n        <td align=\"center\">-</td>\n      </tr>\n\t  \n      <tr>\n        <td ></td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n\t  \n\t   <tr  style=\"background-color:#E0E0E0\" height=\"25px\">\n        <td >Net click value generated (C) </td>\n        <td>:</td>\n        <td align=\"right\">";
echo "<s";
echo "trong>\n          ";
$cval_gen = $ret + $set - $bon_used;
echo "         ";
echo htmlspecialchars( $cval_gen );
echo "        </strong></td>\n        <td>&nbsp;</td>\n      </tr>\n      \n\t\n\t  \n      <tr>\n        <td ></td>\n        <td>&nbsp;</td>\n        <td></td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td >";
echo "<s";
echo "pan class=\"inserted\">Summary</span></td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td ></td>\n        <td>&nbsp;</td>\n        <td></td>\n        <td>&nbsp;</td>\n      </tr>\n   <tr bgcolor=\"#CCCCCC\" height=\"25px\">\n    <td class=\"inserted\"> Fund Balance = A - B</td>\n    <td> :</td>\n    <td align=\"right\">";
$account = $total - $tot;
echo "<strong>";
echo htmlspecialchars( $account )."</strong>";
echo "</td>\n    <td>&nbsp;</td>\n  </tr>\n  <tr>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n    <td>&nbsp;</td>\n  </tr>\n  \n  \n  \n     <tr bgcolor=\"#CCCCCC\" height=\"25px\">\n        <td class=\"inserted\">Net revenue  = C - B</td>\n        <td>:</td>\n        <td align=\"right\">";
echo "<s";
echo "trong>";
$rev_ppc = $cval_gen - $tot;
echo htmlspecialchars( $rev_ppc );
echo "</strong></td>\n        <td>&nbsp;</td>\n      </tr>\n      <tr>\n        <td width=\"59%\" ></td>\n        <td width=\"1%\">&nbsp;</td>\n        <td width=\"37%\">&nbsp;</td>\n        <td width=\"3%\">&nbsp;</td>\n      </tr>\n  <tr>\n    <td colspan=\"4\">&nbsp;</td>\n  </tr>\n  <tr>\n    <td colspan=\"4\">&nbsp;</td>\n  </tr>\n</table>\n\n\n\n";
include( "admin.footer.inc.php" );
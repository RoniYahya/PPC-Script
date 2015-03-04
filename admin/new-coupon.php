<?php
include( "config.inc.php" );
if(!isset($_COOKIE['inout_admin'])){
	header( "Location:index.php" );
	exit();
}
$inout_username = $_COOKIE['inout_admin'];
$inout_password = $_COOKIE['inout_pass'];
if ( !( $inout_username == md5( $username ) && $inout_password == md5( $password ) ) ){
    header( "Location:index.php" );
    exit();
}

include( "admin.header.inc.php" );
echo "\n\n\n\n<link rel=\"stylesheet\" type=\"text/css\" href=\"epoch_styles.css\" />\n\n";
echo "<script type=\"text/javascript\" src=\"epoch_classes.js\"></script>\n\n";
echo "<script type=\"text/javascript\">\n\n/*You can also place this code in a separate file and link to it like epoch_classes.js*/\n\nvar bas_cal,dp_cal,ms_cal,dp_cal2;      \n\n\n\nwindow.onload = function () {\n\n\t\tdp_cal  = new Epoch('epoch_popup','popup',document.getElementById('e_date'));\t\n\n\t//\tdp_cal2  = new Epoch('epoch_popup','popup',document.getElementById('popup_container2'));\t\n\n};\n\n\n\n\n\n</script>\n\n";
echo "<style type=\"text/css\">\n\n\n\n.style1 {color: #FF0000}\n\n\n\n</style>\n";
echo "<script language=\"javascript\">\nfunction check_value()\n\t\t\t\t{\n\t\t\t\tif((document.getElementById('c_type').value==\"\")|| (document.getElementById('c_name').value==\"\")||(document.getElementById('c_times').value==\"\")||(document.getElementById('gift_code').value==\"\")||(document.getElementById('g_amount').value==\"\")||(document.getElementById('e_date').value==\"\"))\n\t\t\t\t\t{\n\t\t\t\t\t//refresh();\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t";
echo "\t\talert(\"Please fill all compulsory fields\");\n\t\t\t\t\t//document.form1.ad_title.focus();\n\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t\telse\n\t\t\t\t\t\t\t{\n\n\t\t\t\t\t\t\tvar gamount=document.getElementById('g_amount').value;\n\t\t\t\t\t\t\tgamount=gamount.trim();\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\tvar ctimes=document.getElementById('c_times').value;\n\t\t\t\t\t\t\tctimes=ctimes.trim();\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t\t\tif(gamount<=0)\n\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\tale";
echo "rt(\"Please enter positive value\");\n\t\t\t\t\t\t\tdocument.getElementById('g_amount').focus();\n\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\tif(ctimes<0)\n\t\t\t\t\t\t\t{\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\talert(\"Please enter non-negative value\");\n\t\t\t\t\t\t\tdocument.getElementById('c_times').focus();\n\t\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t\t\n\t\t\t\t\t/*\t\t\n\t\t\t\t\t\t\t\n\t\t\t\tfor (i = 0; i < gamount.length; i++) \n\t\t\t\t\t{\t\t\t\n\t\t\t\t\t ch = gamount[i];  ";
echo " \n\t\t\t\t\t if (ch < \"0\" || ch > \"9\")\n\t\t\t\t\t\t{\n\t\t\t\t\t\talert(\"Please enter positive integers\");\n\t\t\t\t\t\tdocument.getElementById('g_amount').focus();\n\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t}\n\t\t\t\t   }\n\t\t\t\t   \n\t\t\t\t   \n\t\t\t\t   \n\t\t\t\t   for (i = 0; i < ctimes.length; i++) \n\t\t\t\t\t{\t\t\t\n\t\t\t\t\t ch = ctimes[i];   \n\t\t\t\t\t if (ch < \"0\" || ch > \"9\")\n\t\t\t\t\t\t{\n\t\t\t\t\t\talert(\"Please enter positive integers\");\n\t\t\t\t\t\t\n\t\t\t\t\t\tdocument.getElementById";
echo "('c_times').focus();\n\t\t\t\t\t\treturn false;\n\t\t\t\t\t\t}\n\t\t\t\t   }\n\t\t\t\t   \n\t\t\t\t   */\n\t\t\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t\n\t\t\t\t\t\t\t}\n\t\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\n\t\t\t\t}\n\n</script>\n\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr>\n    <td height=\"50\" colspan=\"4\"  align=\"left\">";
include( "submenus/coupons.php" );
echo " </td>\n  </tr>\n  <tr>\n   <td   colspan=\"4\" scope=\"row\" class=\"heading\">Create Coupon Code</td>\n  </tr>\n</table>\n\n<br />\n<form action=\"new-coupon-action.php\" method=\"post\" enctype=\"multipart/form-data\" onsubmit=\"return check_value()\">\n\n<table width=\"100%\"   >\n\n <tr>\n  \n    <td height=\"36\">Coupon Name </td>\n    <td>:</td>\n    <td><label>\n       <input name=\"c_name\" type=\"text\" id=\"c_name\">\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  <tr>\n  \n    <td height=\"36\">Coupon Code </td>\n    <td>:</td>\n    <td><label>\n       <input name=\"gift_code\" type=\"text\" id=\"gift_code\">\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  \n   <tr>\n    <td width=\"355\" height=\"36\">Coupon Type</td>\n    <td width=\"15\">:</td>\n    <td width=\"925\"><label>\n    ";
echo "<select name=\"c_type\" id=\"c_type\" >\n\t<option value=\"1\">Percentage (%)</option>\n\t<option value=\"2\">Flat Rate</option>\n\t</select>\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  <tr>\n    <td width=\"355\" height=\"36\">Coupon Percentage/Amount</td>\n    <td width=\"15\">:</td>\n    <td width=\"925\"><label>\n      <input name=\"g_amount\" type=\"text\" id=\"g_amount\">\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  \n  \n   <tr>\n    <td width=\"355\" height=\"36\">Max Usage (no. of times) * </td>\n    <td width=\"15\">:</td>\n    <td width=\"925\"><label>\n      <input name=\"c_times\" type=\"text\" id=\"c_times\"  value=\"0\">\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong><br /> </td>\n  </tr>\n  \n\n  <tr>\n\n    <td height=\"39\">Expiry Date</td>\n    <td height=\"39\">:</td>\n    <td><label>\n       <input name=\"e_date\" type=\"text\" id=\"e_date\"  readonly class=\"input\">\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  \n  \n  \n  <tr>\n    <td colspan=\"3\" align=\"center\"><label>\n      <input type=\"submit\" name=\"Submit\" value=\"Submit\">\n    </label></td>\n    </tr>\n</table>\n</form>\n\n<br />\n";
echo "<span class=\"note\">\n";
echo "<strong>* Max Usage :</strong> Specify a postive integer if you would like to limit the maximum number of times a coupon can be used within expiry. Specify 0 if you do not need  such a restriction. In any case a particular coupon can be used by one user only once.\n</span>\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n";
include( "admin.footer.inc.php" );
?>

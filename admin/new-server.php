<?php
include( "config.inc.php" );
if ( !isset( $_COOKIE['inout_admin'] ) ){
    header( "Location:index.php" );
    exit( 0 );
}
$inout_username = $_COOKIE['inout_admin'];
$inout_password = $_COOKIE['inout_pass'];
if ( !( $inout_username == md5( $username ) && $inout_password == md5( $password ) ) ){
    header( "Location:index.php" );
    exit( 0 );
}

include( "admin.header.inc.php" );
echo "\n";
echo "<style type=\"text/css\">\n<!--\n.style3 {font-weight: bold}\n\n-->\n</style>\n\n\n\n\n";
echo "<style type=\"text/css\">\n\n\n\n.style1 {color: #FF0000}\n\n\n\n</style>\n";
echo "<script language=\"javascript\">\nfunction isPositive(strString)\n   {\n\n   var strValidChars = \"0123456789.\";\n   var strChar;\n   var retResult = true;\n\n   if (strString.length == 0) return false;\n\n   //  test strString consists of valid characters listed above\n\n   for (i = 0; i < strString.length && retResult == true; i++)\n      {\n      strChar = strString.charAt(i);\n      if (strValidChars.indexOf(strCh";
echo "ar) == -1)\n         {\n         retResult = false;\n         }\n      }\n   return retResult;\n   }\n   \n\n\nfunction check_value()\n\t\t\t\t{\n\t\t\t\t    if(document.getElementById('servername').value==\"\")\n\t\t\t\t\t{\n\t\t\t\t\talert(\"Please fill server name\");\n\t\t\t\t\tdocument.getElementById('servername').focus();\n\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t\telse if(document.getElementById('serverurl').value==\"\")\n\t\t\t\t\t{\n\t\t\t\t\talert(\"Please";
echo " fill access url\");\n\t\t\t\t\tdocument.getElementById('serverurl').focus();\n\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t\telse if(isPositive(document.getElementById('minrange').value)==false) \n\t\t\t\t\t{\t\n                    alert(\"Please enter positive range start value.\");\n\t\t\t\t\tdocument.getElementById('minrange').focus();\n\t\t\t\t\treturn false;\n   \t                }\n\t\t\t\t\t\n\t\t\t\t\telse if(document.getElementById('maxrange').va";
echo "lue==0)\n\t\t\t\t\t{\n\t\t\t\t\t alert(\"Please enter positive range end value.\");\n\t\t\t\t\tdocument.getElementById('maxrange').focus();\n\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t\t\n\t                else if(isPositive(document.getElementById('maxrange').value)==false) \n\t\t\t\t\t{\t\n                    alert(\"Please enter positive range end value.\");\n\t\t\t\t\tdocument.getElementById('maxrange').focus();\n\t\t\t\t\treturn false;\n   \t          ";
echo "      }\n\t\t\t\t\t\t\t\t\t\t\n\t\t\t\t\telse if(document.getElementById('minrange').value > 0 )\n\t\t\t\t\t{\n\t\t\t\t\tif(parseInt(document.getElementById('maxrange').value) <= parseInt(document.getElementById('minrange').value))\n\t\t\t\t\t{\n\t\t\t\t\talert(\"Range end value must be greater than range start value \");\n\t\t\t\t\tdocument.getElementById('minrange').focus();\n\t\t\t\t\treturn false;\n\t\t\t\t\t}\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t}\n\t\t\t\t\t\n\t";
echo "\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\t\n\t\t\t\t\n\t\t\t\t}\n\n</script>\n\n<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n  <tr>\n    <td height=\"53\" colspan=\"4\"  align=\"left\">";
include( "submenus/loadbalance.php" );
echo " </td>\n  </tr>\n  <tr>\n   <td   colspan=\"4\" scope=\"row\" class=\"heading\">Configure New Server</td>\n  </tr>\n</table>\n<br />\n";
echo "<span class=\"info\">";
echo "<strong>Note:</strong> Please don't add servers simply for testing purpose as it can interfere with statistics updation.</span>\n<form action=\"new-server-action.php\" method=\"post\" enctype=\"multipart/form-data\" onsubmit=\"return check_value()\">\n\n<table width=\"100%\"    border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n\n\n  \n  \n   <tr>\n  \n    <td height=\"36\">Server Type </td>\n    <td>:</td>\n    <td><label>\n       ";
echo "<select id=\"srvtype\" name=\"srvtype\">\n\t   <option value=\"1\">Application Server</option>\n\t   <option value=\"2\">Load Balance Server</option>\n\t   <!--<option value=\"3\">Statistics Server</option>-->\n\t   </select>\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  \n <tr>\n  \n    <td height=\"36\">Server Name </td>\n    <td>:</td>\n    <td><label>\n       <input name=\"servername\" type=\"text\" id=\"servername\" size=\"30\">\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  <tr>\n  \n    <td height=\"36\">Access Url </td>\n    <td>:</td>\n    <td><label>\n       <input name=\"serverurl\" type=\"text\" id=\"serverurl\" size=\"30\">\n    </label>";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong></td>\n  </tr>\n  \n  \n   <tr>\n  \n    <td height=\"36\">Range Start Value </td>\n    <td>:</td>\n    <td><label>\n       <input name=\"minrange\" type=\"text\" id=\"minrange\" size=\"10\" value=\"1\">\n    </label>\n    ";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong>";
echo "<span class=\"note\">(Starting value of the publisher id range for this server)</span></td>\n  </tr>\n  \n  \n   <tr>\n  \n    <td height=\"36\">Range End Value </td>\n    <td>:</td>\n    <td><label>\n       <input name=\"maxrange\" type=\"text\" id=\"maxrange\" size=\"10\" value=\"0\">\n    </label>\n    ";
echo "<strong>";
echo "<span class=\"style1\">*</span></strong>";
echo "<span class=\"note\">(Range End Value must be greater than Range Start Value.)</span></td>\n  </tr>\n  \n  <tr>\n    <td height=\"36\">  </td>\n    <td> </td>\n    <td>        <input type=\"submit\" name=\"Submit\" value=\"Submit\"></td>\n  </tr>\n  \n  \n</table>\n</form>\n\n\n\n";
include( "admin.footer.inc.php" );
?>

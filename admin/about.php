<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php

include("config.inc.php");
if(!isset($_COOKIE['inout_admin']))
{
	header("Location:index.php");
	exit(0);
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
{
	header("Location:index.php");
	exit(0);
}

include("admin.header.inc.php");

?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td width="1%">&nbsp;</td>

    <td width="98%">&nbsp;</td>

    <td width="1%">&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td>&nbsp;</td>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td align="left"><strong><font  size="+1"><u>Inout Adserver Ultimate</u></font></strong></td>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td> <p>Inout Adserver is developed by <a href="http://www.inoutscripts.com/">InoutScripts</a>. 
        All rights reserved solely to InoutScripts. <BR>
        <BR>
        <a href="http://www.inoutscripts.com/terms.php">Terms of use</a> are available 
        at inoutscripts.com.<br>
        <BR>
        Some icons used in the script are published by <a href="http://icon-king.com/">David Vignoni</a> under LGPL License.Thank you David.<br>
        <br>
This product includes GeoLite data created by MaxMind, available from <a href="http://www.maxmind.com/">http://www.maxmind.com/</a>.Thanks to Maxmind.<br>        
<br>
        The animated graphical
        statistics in this product are accomplished using <a href="http://www.fusioncharts.com/">FusionCharts FREE</a>. Thanks  to InfoSoft Global (P) Limited. <br>
        <br>
The geographical statistics in this script are acheived using ammmap from <a href="http://www.ammap.com/">www.ammap.com</a>. Thanks to ammap.com<br>

        <br>
        This product uses Cross Browser marquee script from <a href="http://www.dynamicdrive.com">Dynamic Drive</a>. Thanks to Dynamic Drive.        <br>
        <br>
        Special thanks to Giorgi Matakheria for the javascript utilities. <br>
        <br>

        Special thanks to Kenneth &amp; Nicholas Varazashvili for their support throughout the development of this software. We would also like to thank all our clients who provided valuable suggestions throughout the evolvement of this software.<br>
        <br>
        <strong><u>Key People Behind this Software</u></strong><br>
        <br>
        <strong>Robin Paulose </strong> (Project Manager, Technical Lead)        <br>
        <br>
		  <strong>Jerin Mathew </strong> (Developer) <br><br>
		  <strong>Binish Xavier </strong> (Developer) <br><br>
		 Jins George  (Developer) <br>
		  
		  Lakshmi Nair  (Developer) <br>
		  Mathew G </strong> (Developer) <br>
		  Leo Joseph (Developer) <br>
		 Rajath Kamal  (Developer) <br>
		 Renjith M.N.(Developer)<br>
 		 Gopakumar G  (Developer) <br>

        
<br>


             
      <strong>Sijith Chandran </strong>(Template Design) <br>
        <br>
       Jubin Kurian(Support and Customer Relationship Manager) <br>
        <br>
        <BR>
        Please contact our <a href="http://www.inoutscripts.com/support/">Support 
            Desk</a> for your questions and help requests.<BR>
    </p></td>

    <td>&nbsp;</td>

  </tr>

  <tr>

    <td>&nbsp;</td>

    <td></td>

    <td>&nbsp;</td>

  </tr>

</table><BR>

<?php include("admin.footer.inc.php"); ?>
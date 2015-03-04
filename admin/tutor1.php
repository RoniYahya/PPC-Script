<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

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

    <td width="93%">&nbsp;</td>
  </tr>

  <tr> 

    <td>&nbsp;</td>
  </tr>

  <tr> 

    <td><strong class="inserted"> First time user Quick Start : : A Quick Guide to Test the Basic Features</font></strong></td>
  </tr>

  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Follow the steps below to test the basic features, if you are new to PPC advertising system.</td>
  </tr>
  <tr> 

    <td>&nbsp;</td>
  </tr>

  <tr> 

    <td><p><strong>&raquo;</strong> Configure the system related settings like engine name, default encoding, deafult language, single-sign-on, operation mode (whether keyword based on keyword independant), email settings, format settings, advertiser payment options, publisher withdrawal options etc under &quot;Essential Settings&quot;.  </p>
      <p><strong>&raquo;</strong> Create an ad display unit based on any of the available ad blocks from &quot;Admin's Ad Display&quot; section. <br>
      <br>
        <strong>&raquo;</strong> Paste the  HTML code of the new adunit in your desired page where you want to show the ads. </p>
      <p><em>&#8226; If you are using keyword specific code, please ensure to write your keywords in place of &lt;YOUR KEYWORDS HERE&gt; (in the case of multiple keywords use comma as separator Eg:keyword1,keyword2)manually or echo the keyword variable using PHP. <br>
&#8226; If you are using content related code, please ensure that the page where you are going to paste the code contains proper meta keywords, meta description and title .</em><br>
        <br>
        <strong>&raquo;</strong>        Create an advertiser account and add some fund to that account.<br>
        <br>
        <strong>&raquo;</strong> Login to the newly created account and create a few ads (Text/Banner/Catalog).<br>
        <br>
        <strong>&raquo;</strong> Add a few keywords to the newly created ads. <br>
        <br>
        <strong>&raquo;</strong> From the admin area activate the newly created ads and keywords.<br>
        <br>
        <strong>&raquo;</strong> Now from the browser, access the page where you have copied the html code.<br>
  <br>
        <strong>&raquo;</strong> The ads may  show up now if the keywords added for the ad matches with keywords in the test page. If ads are not shown, please invoke admin/adserver_cron.php and try again. <br>
        <br>
        <strong>&raquo;</strong> If ads are still not showing up, login back to the advertiser account and  set click values according to  the suggested click&nbsp;        rate and try again. <br>
        <br>
        <strong>&raquo;</strong> Click on any of the ads. Now  you will be redirected to the corresponding URL you had set for the ad.<br>
        <br>
        <strong>&raquo;</strong> Go back to the admin area/the advertiser login area to see the statistics. Please note that statistics are updated hourly when cron job runs. </p>
      <p><strong>Testing publisher account : </strong></p>
      <p><strong>&raquo;</strong> Create a publisher account and approve the same from admin area.</p>
      <p><strong>&raquo;</strong> Login to the publisher account and  generate ad display code as in case of step 2 .</p>
      <p><strong>&raquo;</strong> Copy the code and paste in a web page as you did in step 3.</p>
      <p><strong>&raquo;</strong>  Access the ad display page from browser and check whether ads are shown. <br>
        <strong><br>
      </strong></p></td>
  </tr>

  <tr> 

  <td>    </tr>
</table>

<?php include("admin.footer.inc.php"); ?>
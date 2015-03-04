<?php
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
$link1=mysql_query("select * from url_updation where name='home'");
		$link=mysql_fetch_row($link1);
		if($link[3]=="")
		{
			$home="index.php";
		}
		else
		{
			$home=$link[3];
		}
		$link2=mysql_query("select * from url_updation where name='advertisers'");
		$link3=mysql_fetch_row($link2);
		if($link3[3]=="")
		{
			$adver_login="ppc-user-login.php";
		}
		else
		{
			$adver_login=$link3[3];
		}
		$publink=mysql_query("select * from url_updation where name='publishers'");
		$publink1=mysql_fetch_row($publink);
		if($publink1[3]=="")
		{
			$pub_login="ppc-publisher-login.php";
		}
		else
		{
			$pub_login=$publink1[3];
		}
			$advfaq=mysql_query("select * from url_updation where name='advfaq'");
		$advfaq1=mysql_fetch_row($advfaq);
		if($advfaq1[3]=="")
		{
			$adv_faq="ppc-advertiser-faq.php";
		}
		else
		{
			$adv_faq=$advfaq1[3];
		}
			$pubfaq=mysql_query("select * from url_updation where name='pubfaq'");
	$pubfaq1=mysql_fetch_row($pubfaq);
		if($pubfaq1[3]=="")
		{
			$pub_faq="ppc-publisher-faq.php";
		}
		else
		{
			$pub_faq=$pubfaq1[3];
		}

				$contactus=mysql_query("select * from url_updation where name='contactus'");
	$contactus1=mysql_fetch_row($contactus);
		if($contactus1[3]=="")
		{
			$contactus2="ppc-contact-us.php";
		}
		else
		{
			$contactus2=$contactus1[3];
		}
if($single_account_mode==1)
{
if($portal_system==0)
{
$comregister=mysql_query("select * from url_updation where name='signup'");
		$comregister1=mysql_fetch_row($comregister);
		if($comregister1[3]=="")
		{
			$com_reg="registration.php";
		}
else
		{
			$com_reg=$comregister1[3];
		}	
		$comlogin=mysql_query("select * from url_updation where name='signin'");
		$comlogin1=mysql_fetch_row($comlogin);
		if($comlogin1[3]=="")
		{
			$com_login="login.php";
		}
else
		{
			$com_login=$comlogin1[3];
		}	
	$forgotpass=mysql_query("select * from url_updation where name='forgotpassword'");
		$forgotpass1=mysql_fetch_row($forgotpass);
		if($forgotpass1[3]=="")
		{
			$forgot_pass="forgot-password.php";
		}
else
		{
			$forgot_pass=$forgotpass1[3];
		}
$site_string="<url>
  <loc>".$server_dir."$home</loc>
</url>
<url>
  <loc>".$server_dir."$adver_login</loc>
</url>
<url>
  <loc>".$server_dir."$pub_login</loc>
</url>
<url>
  <loc>".$server_dir."$com_reg</loc>
</url>
<url>
  <loc>".$server_dir."$com_login</loc>
</url>
<url>
  <loc>".$server_dir."$adv_faq</loc>
</url>
<url>
  <loc>".$server_dir."$pub_faq</loc>
</url>
<url>
  <loc>".$server_dir."$contactus2</loc>
</url>
<url>
  <loc>".$server_dir."$forgot_pass</loc>
</url>";

}
else
{
	
$site_string="<url>
  <loc>".$server_dir."$home</loc>
</url>
<url>
  <loc>".$server_dir."$adv_faq</loc>
</url>
<url>
  <loc>".$server_dir."$pub_faq</loc>
</url>
<url>
  <loc>".$server_dir."$contactus2</loc>
</url>
";

}
}
else
{
$advregister=mysql_query("select * from url_updation where name='advertisersignup'");
		$advregister1=mysql_fetch_row($advregister);
		if($advregister1[3]=="")
		{
			$adv_reg="ppc-user-registration.php";
		}
		else
		{
			$adv_reg=$advregister1[3];
		}	
			$pubregister=mysql_query("select * from url_updation where name='publishersignup'");
		$pubregister1=mysql_fetch_row($pubregister);
		if($pubregister1[3]=="")
		{
			$pub_reg="ppc-publisher-registration.php";
		}
		else
		{
			$pub_reg=$pubregister1[3];
		}
$advforgot=mysql_query("select * from url_updation where name='advertiserforgotpassword'");
		$advforgot1=mysql_fetch_row($advforgot);
		if($advforgot1[3]=="")
		{
			$adv_forgot="ppc-forgot-password.php";
		}
		else
		{
			$adv_forgot=$advregister1[3];
		}	
			$pubforgot=mysql_query("select * from url_updation where name='publisherforgotpassword'");
		$pubforgot1=mysql_fetch_row($pubforgot);
		if($pubforgot1[3]=="")
		{
			$pub_forgot="ppc-publisher-forgot-password.php";
		}
		else
		{
			$pub_forgot=$pubforgot1[3];
		}
$site_string="<url>
  <loc>".$server_dir."$home</loc>
</url>
<url>
  <loc>".$server_dir."$adver_login</loc>
</url>
<url>
  <loc>".$server_dir."$pub_login</loc>
</url>
<url>
  <loc>".$server_dir."$adv_reg</loc>
</url>
<url>
  <loc>".$server_dir."$pub_reg</loc>
</url>
<url>
  <loc>".$server_dir."$adv_faq</loc>
</url>
<url>
  <loc>".$server_dir."$pub_faq</loc>
</url>
<url>
  <loc>".$server_dir."$contactus2</loc>
</url>
<url>
  <loc>".$server_dir."$pub_forgot</loc>
</url>
<url>
  <loc>".$server_dir."$adv_forgot</loc>
</url>";

}
?>
<?php include("admin.header.inc.php"); 

?>
<br />

<table  border="0" cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td  colspan="4" scope="row" class="heading"> XML Sitemap content </td></tr>
</table>
<br />

  <table order="0" cellpadding="0" cellspacing="0" border="0" width="100%">
  <tr>
  <td>
  <textarea cols="75" rows="15"><?php echo'<?xml version="1.0" encoding="'.$ad_display_char_encoding.'"?>';?>
   
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php echo $site_string; ?>

</urlset>
  </textarea>
  </td>
  </tr>
  <tr>
  <td> Create a new sitemap.xml file in the root directory of your site.<br />
Copy the above xml sitemap content and paste it in the  file that you are created.
  </table>

<?php include("admin.footer.inc.php"); ?>
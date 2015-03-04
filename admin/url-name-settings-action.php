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
$flag=0;
include_once("admin.header.inc.php");
if($script_mode=="demo")
	{ 
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
	}



$home=trim($_POST['index']);
$advert=trim($_POST['adv']); //advertisers
$publ=trim($_POST['pub']);   //publishers
$advreg=trim($_POST['advreg']);  //adv registration
$pubreg=trim($_POST['pubreg']); //pub registration
$advfaq=trim($_POST['advfaq']); 
$pubfaq=trim($_POST['pubfaq']);
$advforgot=trim($_POST['advforgotpassword']);
$pubforgot=trim($_POST['pubforgotpassword']);
$contact=trim($_POST['contact']);
$reg=trim($_POST['reg']);  //common registration
$login=trim($_POST['login']);
$forgot=trim($_POST['forgotpassword']); //common forgot password
$home=str_replace(" ","-",$home);
$advert=str_replace(" ","-",$advert);
$publ=str_replace(" ","-",$publ);
$advreg=str_replace(" ","-",$advreg);
$pubreg=str_replace(" ","-",$pubreg);
$advfaq=str_replace(" ","-",$advfaq);
$pubfaq=str_replace(" ","-",$pubfaq);
$advforgot=str_replace(" ","-",$advforgot);
$pubforgot=str_replace(" ","-",$pubforgot);
$contact=str_replace(" ","-",$contact);
$reg=str_replace(" ","-",$reg);	
$login=str_replace(" ","-",$login);
$forgot=str_replace(" ","-",$forgot);
  mysql_query("BEGIN"); 
if($home==""&&$advert==""&&$publ==""&&$advreg==""&&$pubreg==""&&$advfaq==""&&$pubfaq==""&&$advforgot==""&&$pubforgot==""&&$contact==""&&$login==""&&$reg==""&&$forgot=="")
{

	$flag=2;
}
else
{
	
if(!mysql_query("update url_updation set seoname='$home' where name='home'"))
{
	$flag=1;
}


if(!mysql_query("update url_updation set seoname='$advert' where name='advertisers'"))
{
	$flag=1;
}

if(!mysql_query("update url_updation set seoname='$publ' where name='publishers'"))
{
	$flag=1;

}
if(!mysql_query("update url_updation set seoname='$advreg' where name='advertisersignup'"))
{
	$flag=1;
}

if(!mysql_query("update url_updation set seoname='$pubreg' where name='publishersignup'"))
{
	$flag=1;
}

if(!mysql_query("update url_updation set seoname='$advforgot' where name='advertiserforgotpassword'"))
{
	$flag=1;
}

if(!mysql_query("update url_updation set seoname='$pubforgot' where name='publisherforgotpassword'"))
{
	$flag=1;
}

if(!mysql_query("update url_updation set seoname='$advfaq' where name='advfaq'"))
{
	$flag=1;
}

if(!mysql_query("update url_updation set seoname='$pubfaq' where name='pubfaq'"))
{
	$flag=1;
}

if(!mysql_query("update url_updation set seoname='$contact' where name='contactus'"))
{
	$flag=1;
}
if(!mysql_query("update url_updation set seoname='$login' where name='signin'"))
{
$flag=1;
}
  
   
if(!mysql_query("update url_updation set seoname='$reg' where name='signup'"))
{
	$flag=1;
}
  
if(!mysql_query("update url_updation set seoname='$forgot' where name='forgotpassword'"))
{
	$flag=1;
}

}




if($flag==1)
{
		mysql_query("ROLLBACK");
		echo "<br><span class=\"inserted\">An error occured while updating, please try again later.</span> <a href=\"url-name-settings.php\">Back</a><br><br>";
}
elseif($flag==2)
{

	mysql_query("update url_updation set seoname='' ");
	mysql_query("COMMIT");
		echo "<br><span class=\"inserted\" >All urls have been reset. You may delete the .htaccess file which you had uploaded for supporting SEO urls.</span> <a href=\"javascript:history.back(-1);\">Back</a><br><br>";
}
else
{
	mysql_query("COMMIT");
	
//	echo "<span class=\"inserted\"><br>Your updation is successfully completed!</span>
//<a href=\"main.php\">Continue</a><br><br>";
$dir=explode("://", $server_dir);

	$dir1=explode("/", $dir[1]);
	if($dir1[1]!="")
	{
		
		$count=count($dir1);
		
for($i=1;$i<$count;$i++)
{
	if($dir1[$i]!="")
		$string.=$dir1[$i]."/";
}
$str=$string;
	}
	else
	{
		$str="/";
	}
	
?>
<br>
<span class="heading" >Search Engine Friendly URL Configuration</span>
<br><br />

<table cellpadding="0" cellspacing="0" border="0">
<tr><td>
<textarea style="background-color:#CCCCCC" cols="80" rows="10" readonly="readonly">
<?php

$sd='<IfModule mod_rewrite.c>   
RewriteEngine on
Options +FollowSymlinks 
RewriteBase /'.$str.'
RewriteRule \.htaccess - [F]
';
if($home!="")
$sd.='RewriteRule ^'.$home.'$ index.php  [L]
';

if($advert!="")
$sd.='RewriteRule ^'.$advert.'$ ppc-user-login.php  [L]
';

if($publ!="")
$sd.='RewriteRule ^'.$publ.'$ ppc-publisher-login.php  [L]
';

if($contact!="")
$sd.='RewriteRule ^'.$contact.'$ ppc-contact-us.php  [L]
';
if($advfaq!="")
$sd.='RewriteRule ^'.$advfaq.'$ ppc-advertiser-faq.php  [L]
';
if($pubfaq!="")
$sd.='RewriteRule ^'.$pubfaq.'$ ppc-publisher-faq.php  [L]
';
if($reg!="")
$sd.='RewriteRule ^'.$reg.'$ registration.php  [L]
';
if($login!="")
$sd.='RewriteRule ^'.$login.'$ login.php  [L]
';
if($forgot!="")
$sd.='RewriteRule ^'.$forgot.'$ forgot-password.php  [L]
';
if($advreg!="")

$sd.='RewriteRule ^'.$advreg.'$ ppc-user-registration.php  [L]
';
if($pubreg!="")

$sd.='RewriteRule ^'.$pubreg.'$ ppc-publisher-registration.php  [L]
';
if($advforgot!="")

$sd.='RewriteRule ^'.$advforgot.'$ ppc-forgot-password.php  [L]
';
if($pubforgot!="")

$sd.='RewriteRule ^'.$pubforgot.'$ ppc-publisher-forgot-password.php  [L]
';
	

 echo $as.$sd.'</IfModule>';
 ?>
 </textarea></td></tr>
 <tr height="50">
 <td>
 Create a new .htaccess file in the folder where you have uploaded adserver files in your site.<br />
 Copy the above code and paste it in the file that you are created.
<br /><br />

<span class="bold">Note : </span><span class="info"> Please ensure that your site is running on  a <strong>mod_rewrite</strong> enabled Apache web server</span>
</td>
</tr></table>

<?php

	}

 include("admin.footer.inc.php");
?>
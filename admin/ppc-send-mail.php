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
	?>
<?php
$uid=$_GET['id'];
phpsafe($uid);
$type=$_GET['type'];
if($type==1)
{
$name=$mysql->echo_one("select username from ppc_publishers where uid=$uid");
$email=$mysql->echo_one("select email from ppc_publishers where uid=$uid");
}
else if($type==0)
{
$name=$mysql->echo_one("select username from ppc_users where uid=$uid");
$email=$mysql->echo_one("select email from ppc_users where uid=$uid");
}
else 
{
$name=$mysql->echo_one("select username from `nesote_inoutscripts_users` where id=$uid");
$email=$mysql->echo_one("select email from `nesote_inoutscripts_users` where id=$uid");
}
$url=urldecode($_GET['url']);
$url=urlencode($url);
 include("admin.header.inc.php");
 if($script_mode=="demo")
 {
 echo "<br>You cannot send mail in demo.<br><br>";
 include("admin.footer.inc.php");
 exit(0);
 }
 ?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php if($type==0) {  include "submenus/advertisers.php";} else { include "submenus/publishers.php"; }   ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Send Email</td>
  </tr>
</table>

<form action="send-mail-action.php" method="post" enctype="multipart/form-data" name="form1">
<input type="hidden" name="url" value="<?php echo $url; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td height="34" colspan="5"><span class="inserted">
    Please fill in the email subject and message.
      </span></td>
    </tr>
  <tr>
    <td height="34" colspan="5"><strong>Mail Subject</strong><br><br>
      <input name="subject" type="text" id="subject" size="60" value="<?php echo $ppc_engine_name;?>">      
      <br></td>
  </tr>
  <tr>
    <td height="10" colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td height="34" colspan="4"><strong>Mail Body</strong><br><br>
  <textarea name="text" cols="50" rows="10">Hi <?php echo $name;?>
  
  
  
  
  
Regards
<?php echo $ppc_engine_name;?>
  </textarea></td>
    <td width="17%"><strong> </strong></td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td >&nbsp;</td>
  <td colspan="3">&nbsp;</td>
</tr>
<tr>
    <td width="0%"><input type="submit" name="Submit" value="Proceed !"></td>
    <td colspan="4" >&nbsp;</td>
  </tr>
<tr>
  <td>&nbsp;</td>
  <td colspan="4" >&nbsp;</td>
</tr>
</table>
<input type="hidden" name="email" value="<?php echo $email;?>">
<input type="hidden" name="ref" value="<?php echo $_SERVER['HTTP_REFERER'];?>">
</form>

<?php 
include("admin.footer.inc.php");
 ?>
<?php

include("config.inc.php");
include_once("../classes/class.Template.php");
$template=new Template();

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
if($script_mode=="demo")
	{
 include_once("admin.header.inc.php");
		echo "<br>This feature is disabled in demo version<br><br>";
		include("admin.footer.inc.php");
		exit(0);
	}

$language_array=$_POST;
$type=$_POST['type'];
$language_code=$_POST['language'];


foreach($language_array as $key => $value)
{
 $k=substr($key,0,10);

if($k=="emsg_main_")
{
$k=substr($key,10);
if($type==1)
{
	if($value=="")
	{
	
	include_once("../locale/advertiser-template-en-inc.php");
	$value=$template->checkAdvMsg($k);
	}
	else
	{
	 	     if (get_magic_quotes_gpc())
            $value = stripslashes($value); 
		$value=str_replace('"','\"',$value);
			}
$str.='$advertiser_message['.$k.']="'.$value.'";'."\r\n";
}
elseif($type==2)
{
if($value=="")
	{
	include_once("../locale/publisher-template-en-inc.php");
	$value=$template->checkPubMsg($k);
	}
	else
	{
	// if(!get_magic_quotes_gpc())
	 	     if (get_magic_quotes_gpc())
            $value = stripslashes($value); 
		$value=str_replace('"','\"',$value);
	}
	$str.='$publisher_message['.$k.']="'.$value.'";'."\r\n";
}
elseif($type==3)
{
if($value=="")
	{
	include_once("../locale/messages.en.inc.php");
	$value=$template->checkmsg($k);
	}
		else
	{
	 	     if (get_magic_quotes_gpc())
            $value = stripslashes($value); 
		$value=str_replace('"','\"',$value);
			}
	$str.='$message['.$k.']="'.$value.'";'."\r\n";
}
else
{
if($value=="")
	{
	include_once("../locale/common-template-en-inc.php");
	$value=$template->checkComMsg($k);
	}
	else
	{
	 	     if (get_magic_quotes_gpc())
            $value = stripslashes($value); 
		$value=str_replace('"','\"',$value);
			}
	$str.='$common_message['.$k.']="'.$value.'";'."\r\n";	
}
}
}
//print_r($str);
//exit;
$results=$str;

$results="<?php\r\n".$results."\r\n?>";

if($type==1)
{
$myFile="../locale/advertiser-template-".$language_code."-inc.php";
}
elseif($type==2)
{
	$myFile="../locale/publisher-template-".$language_code."-inc.php";
}
elseif($type==3)
{
	$myFile="../locale/messages.".$language_code.".inc.php";
}
else
{
	 $myFile="../locale/common-template-".$language_code."-inc.php";
}
    $fp = fopen($myFile,"w");
    
     fwrite($fp,$results);

fclose($fp);
 include_once("admin.header.inc.php"); ?> <span class="inserted"><br><?php echo "Message  edited successfully !"; ?></span>
 <a href="ppc-edit-messages.php?type=<?php echo $type; ?>">Continue</a><br><br>
<?php
include("admin.footer.inc.php");
?>

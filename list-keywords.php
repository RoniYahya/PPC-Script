<?php
ob_start();
//sleep(1);
include("extended-config.inc.php");
include($GLOBALS['admin_folder']."/config.inc.php");
//include("functions.inc.php");
//include_once("messages.".$client_language.".inc.php");
includeClass("User");
includeClass("Template");


$user=new User("ppc_users");
if(!$user->validateUser())
{
	header("Location:show-message.php?id=1006");
	exit(0);
}

//includeClass("Template");

$template=new Template();
$template->loadTemplate("ppc-templates/list-keywords.tpl.html");

$kid=0;
$id=0;
$type=1;//listing

if(isset($_GET['id']))
{
	$id=getSafePositiveInteger("id","g");
	if(isset($_GET["key"]) && isset($_GET["click_val"]))
	$type=2;//insertion

}
if(isset($_GET['kid']))
{
	$type=3;//updation
	$kid=getSafePositiveInteger("kid","g");
	$id=$mysql->echo_one("select aid from ppc_keywords where id='$kid'");
}

$uid=$user->getUserID();
if(!myAd($id,$uid,$mysql))
{
	header("Location:show-message.php?id=5010");
	exit(0);
}


$selected_colorcode=array();
foreach ($color_code as $key=>$value)
{
	if($key==$color_theme)
	{
		$selected_colorcode=$value;
		break;
	}
}
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");
/*if($type!=3)
 {
 $output_str="<META HTTP-EQUIV=\"Pragma\" CONTENT=\"no-cache\">  ";
 }
 else
 {
 $output_str="";
 }*/


if($auto_keyword_approve==0)
{
	$keystatus_setting=-1;
}
else
{
	$keystatus_setting=$auto_keyword_approve;
}


if($type!=1)// other than listing
{

	$key=trim($_GET["key"]);
	phpsafe($key);
	$maxcv1=$_GET["click_val"];
	phpsafe($maxcv1);
	$msg="";
	$ignore_list=$ignoreList;

	if($key!="" && $maxcv1!="" && is_numeric($maxcv1) && $maxcv1>=$min_click_value)
	{
		$keyword=$key;
		$maxcv=$maxcv1;

		if($type==2) //insertion
		{

			$c=str_replace("\r\n",",", $keyword);
			$c=str_replace("\n",",", $c);
			$c=str_replace("\r",",", $c);
			//$d=str_replace(", "," ", $c);
			//$e=str_replace(","."\n"," ", $d);
			$bb=explode(",",$c);
			$num=count($bb);
			//	print_r($bb);
			for($i=0;$i<$num;$i++)
			{
				$keystatus=$keystatus_setting;
				$bb[$i]=trim($bb[$i]);

				if(substr_count($ignore_list," ".$bb[$i]." ")==0)
				{
					if($bb[$i]!="")
					{
							
						phpsafe($bb[$i]);
						$lastid=0;
						$systotal=$mysql->select_one_row("select id,status	 from system_keywords where keyword='$bb[$i]' limit 0,1");
							
						if($systotal[0]=="")
						{
							mysql_query("insert into system_keywords values('0','$bb[$i]','".time()."',0,'$keystatus');");
							$lastid=mysql_insert_id();
						}
						else
						{
							$lastid=$systotal[0];
							$keystatus=$systotal[1];
						}
						if($keystatus!=0)
						{
							if($mysql->total("ppc_keywords","aid='$id' AND keyword='$bb[$i]'")==0)
							{
								mysql_query("insert into ppc_keywords values('0','$id','$uid','$bb[$i]','$maxcv','$keystatus','".time()."','$maxcv',$lastid);");

							}
						}
					}
				}
			}
		}
		else  //updation
		{
			if(substr_count($ignore_list," ".$keyword." ")==0)
			{
				phpsafe($keyword);
				$systotal=$mysql->select_one_row("select id,status	 from system_keywords where keyword='$keyword' limit 0,1");
				if(isset($systotal[1]) && $systotal[1]==0)
				{
					echo $template->checkAdvMsg(5029);
					die;
				}

				$total=$mysql->echo_one("select id from ppc_keywords where aid='$id' AND id <>'$kid' AND keyword='$keyword' limit 0,1");
				if($total=='')
				{
					if($systotal[0]=='')
					{
						mysql_query("insert into system_keywords values('0','$keyword','".time()."',0,'$keystatus_setting');");
						$systemid=mysql_insert_id();
					}
					else
					{
						$systemid=$systotal[0];
						$keystatus_setting=$systotal[1];
					}
					//mysql_query("update system_keywords set keyword='$keyword' where id='$systemid'");
					mysql_query("update `ppc_keywords` set keyword='$keyword',maxcv='$maxcv' ,weightage='$maxcv',sid='$systemid',status='$keystatus_setting' where id='$kid'");
					ob_clean();
					echo $keystatus_setting;
					exit(0);
				}
				else
				{
					echo $template->checkAdvMsg(5025);
					die;
				}
			}
			else
			{
				echo $template->checkAdvMsg(5029);
				die;
			}
		}
	}
	else 
	{
		if($type==3)
		{
			echo $template->checkAdvMsg(5026);
			die;
		}
	}
}

if($type!=3) // listing/insertion
{

	$result = mysql_query("select* from ppc_keywords where aid=$id  order by id  desc");
	$num=mysql_num_rows($result);
	$template->setValue("{NUM}",$num);
	//$output_str.="<table width='100%'  border='0' align='left' cellpadding='0' cellspacing='0'>";
	$template->setValue("{MESSAGESS}","");
	if($num>0)
	{
		$url="ppc-manage-keywords.php?id=$id";
		$template->setValue("{URL}",$url);
		//$output_str.= "<tr>
		//    <td colspan='4'>&nbsp;</td>
		//  </tr>";
		$template->openLoop("KEY","select* from ppc_keywords where aid=$id  order by id  desc");
		$template->setLoopField("{LOOP(KEY)-ID}","id");
		$template->setLoopField("{LOOP(KEY)-UID}","uid");
		$template->setLoopField("{LOOP(KEY)-KEYWORD}","keyword");
		$template->setLoopField("{LOOP(KEY)-MAXCV}","maxcv");
		$template->setLoopField("{LOOP(KEY)-STATUS}","status");
		//$template->setLoopField("{LOOP(KEY)-TIME}","time");
		//$template->setLoopField("{LOOP(KEY)-WTAGE}","weightage");
		$template->closeLoop();

		$template->setValue("{MODE}",$ad_keyword_mode);
		$template->setValue("{DEFAULTKEY}",$keywords_default);
		$template->setValue("{MIN_CLK}",$min_click_value);
		$addata=$mysql->echo_one("select adtype from ppc_ads where id='$id'");
		$template->setValue("{ADDATA}",$addata);
		$template->setValue("{REVENUE_BOOSTER}",$revenue_booster);
		$template->setValue("{REVENUE_BOOST_LEVEL}",$revenue_boost_level);
		$template->setValue("{MESSAGELAST}","");
	}
	else
	{
		if($keywords_default=="")
		{
			$msg_keyword=$template->checkAdvMsg(5030);
		}
		else
		$msg_keyword=$template->checkAdvMsg(5024);
		$template->setValue("{MESSAGELAST}",$msg_keyword);

	}

}

$template->setValue("{TYPE}",$type);

// Date in the past
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
// always modified
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
// HTTP/1.1
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
// HTTP/1.0
header("Pragma: no-cache");
//echo $output_str;
//echo $template->getPage();die;
eval('?>'.$template->getPage().'<?php ');

?>
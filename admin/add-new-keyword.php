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

?>
<?php

//print_r($_POST);
if($ad_keyword_mode==2)
	{
include_once("admin.header.inc.php");		
echo "<br><br>";
echo "<span class=already>";
echo "You are using keyword independent mode.";		
echo "<br><br>";	
echo "<a href=javascript:history.back(-1)>Click here to go back</a></span>";			
echo "<br><br>";	
include_once("admin.footer.inc.php");
		}
$keyword=trim($_POST['ad_keyword']);
$maxcv=trim($_POST['clk_val']);
$id=trim($_POST['aid']);
$uid=trim($_POST['uid']);
$url=urldecode(trim($_POST['url']));
$wap_flag=$_GET['wap'];

phpsafe($keyword);
phpsafe($maxcv);
phpsafe($id);
phpsafe($uid);
//phpsafe($url);
$flag=0;
if($maxcv=="" || $keyword=="")
	$flag=2;
if($maxcv<$min_click_value)
	$flag=5;
if(!isPositiveInteger($maxcv))
	$maxcv=$min_click_value;
if($auto_keyword_approve==0)
{
$keystatus=-1;
}
else
{
	$keystatus=$auto_keyword_approve;
}
				$check_val=array_search($keyword, $ignoreList);
				if(substr_count($ignoreList,$keyword)==0)
						{
						if($mysql->total("system_keywords","keyword='$keyword'")==0)
							{
							mysql_query("insert into system_keywords values('0','$keyword','".time()."','0','$keystatus')");
							 $lastid=mysql_insert_id();
							}
							else
							{
								$systotal=$mysql->select_one_row("select id,status	 from system_keywords where keyword='$keyword' limit 0,1");
								$lastid=$systotal[0]	;
								$keystatus=$systotal[1]	;
								
							}
							
							if($keystatus==0)
							{
								$flag=4;
							}
						elseif($mysql->total("ppc_keywords","aid='$id' AND keyword='$keyword'")==0)
							{
							mysql_query("insert into ppc_keywords values('0','$id','$uid','$keyword','$maxcv','$keystatus','".time()."','$maxcv','$lastid');");
							}
						else
							{
							$flag=1;
							}
						}
					else
						{
						$flag=4;
						}
if($flag==0)
	{
	header("Location:$url");
	exit(0);
	}
	if($flag==1)
		{
		$msg="The specifed keyword is already added. Please specify another one.";
		}
	if($flag==4)
		{
		$msg="You are not allowed to use this keyword .";
		}
		if($flag==5)
		{
		$msg="Click value should be greater than minimum click value";
		}
		if($flag==2)
		{
		$msg="Please enter all the required values";
		}
include_once("admin.header.inc.php");		
echo "<br><br>";
echo "<span class=already>";
echo "$msg";		
echo "<br><br>";	
echo "<a href=javascript:history.back(-1)>Click here to go back</a></span>";			
echo "<br><br>";	
include_once("admin.footer.inc.php");
?>

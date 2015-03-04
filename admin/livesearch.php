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



//get the q parameter from URL
$q=$_GET["q"];
phpsafe($q);
//$type=$_GET['typ'];
/*
$q=str_replace("\r\n",",", $q);
$q=str_replace("\n",",", $q);
$q=str_replace("\r",",", $q);
$d=str_replace(", "," ", $q);
//$e=str_replace(","."\n"," ", $d);
	$bb=explode(",",$d);
	$num=count($bb);	*/
	//for($i=0;$i<$num;$i++)
	//{			
	
$sql="SELECT keyword,count(keyword) as c FROM `ppc_keywords`  WHERE keyword like '$q%'  group by keyword order by keyword ASC";
	
$result = mysql_query($sql);
$hint="";
  while($row = mysql_fetch_array($result))
  {
	/*if($type=="1")
  	{
	$hint.="<label onClick=clearadvSearch(this.title,NewKeywords)  title=\"".$row['keyword']."\" onmouseover=\"setSelection(this)\" onmouseout=\"resetSelection(this)\" style=\"white-space:nowrap;\">".$row['keyword']."(".$row[c].")"."</label>". "<br>";
  	}
  	else
  	{*/
  		
  		$hint.="<label onClick=clearSearch(this.title)  title=\"".$row['keyword']."\" onmouseover=\"setSelection(this)\" onmouseout=\"resetSelection(this)\" style=\"white-space:nowrap;\">".$row['keyword']."(".$row[c].")"."</label>". "<br>";
  /*	}
  }
	}*/
	}	

if ($hint == "")
  {
  $response="no suggestion";
  }
else
  {
  $response=$hint;
  }

//output the response
echo $response;
?> 
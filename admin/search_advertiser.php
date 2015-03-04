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
//$q=$_GET["q"];

$search_key=$_GET['s_type'];
$search_keywords=$_GET["q"];
$temp="$search_keywords%";

$result =mysql_query("select $search_key from ppc_users where $search_key like '".$temp."'");
$hint="";
while($row = mysql_fetch_array($result))
  {
	$hint.="<label onClick=clearSearch(this.title)  title=\"".$row[0]."\" onmouseover=\"setSelection(this)\" onmouseout=\"resetSelection(this)\" style=\"white-space:nowrap;\">".$row[0]."</label>". "<br>";
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
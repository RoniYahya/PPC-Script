<?php
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
//get the q parameter from URL
$q=trim($_GET["q"]);
//$type=$_GET['typ'];
//
//$a=str_replace("\n",",", $type);
//$b=str_replace("\r",",", $a);
//$c=str_replace("\n\r",",", $b);
//$d=str_replace(", "," ", $c);
//$e=str_replace(","."\n"," ", $d);
//	$bb=explode(",",$d);
	
//	 $num=count($bb);
	
//	for($i=0;$i<$num;$i++)
//	{		
//	if($bb[$i]=="")
//	{
//		//$hint = "";
//		
//	}else
//	{

//		$len=strlen($bb[$i]);
//		if($len>=3)
//		{
phpsafe($q);
if($q!="")
{
	
	
$sql="SELECT keyword FROM `system_keywords`  WHERE keyword like '$q%' and status=1 group by keyword order by rating DESC";
	
$result = mysql_query($sql);

 $hint="";	
while($row = mysql_fetch_array($result))
  {
  	
	$hint.="<label onClick=clearadvSearch(this.title,NewKeywords)  title=\"".$row['keyword']."\" onmouseover=\"setSelection(this)\" onmouseout=\"resetSelection(this)\" style=\"white-space:nowrap;\">".$row['keyword']."</label>". "<br>";
  	
  	
  }
}

if ($hint == "")
  {
  $response="no suggestion";
  }
else
  {
  $response=$hint;
  }

echo $response;
		
	
?> 
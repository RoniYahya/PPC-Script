<?php 

/*--------------------------------------------------+

|													 |

| Copyright ? 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/


?><?php
include("../extended-config.inc.php");
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
include_once("admin.header.inc.php");	

$flag_time=0;

if(isset($_GET['caches']))
{
 $show=$_GET['caches']; 
}


if(isset($_GET['cacheslave']))
{
 $showslave=$_GET['cacheslave']; 
}

$slavecount=$mysql->echo_one("select count(id) from server_configurations where srv_type='2' and status='111'");


if($show==1 || $showslave ==1)
{
$showmessage="7 Days";
$time=mktime(0,0,0,date("m",time()),date("d",time())-7,date("Y",time()));
}
else if($show==2  || $showslave ==2)
{
$showmessage="14 Days";
$time=mktime(0,0,0,date("m",time()),date("d",time())-14,date("Y",time()));
}
else if($show==3  || $showslave ==3)
{
$showmessage="30 Days";
$time=mktime(0,0,0,date("m",time()),date("d",time())-30,date("Y",time())); 
}


$directory = "../".$GLOBALS['cache_folder']."/";

if(isset($_GET['caches']))
{
$dir_name = $directory;
$dir = opendir($dir_name);

$i=0;
$flag=0;
while ($file_name = readdir ($dir))
{

//$ls_name=substr($file_name,-4);
//if ($ls_name == ".php") 
//{

if (file_exists($dir_name.$file_name)) 
{
     if(filemtime($dir_name.$file_name)<$time)
	 {
	 
	 unlink($dir_name.$file_name);
	 $i=$i+1;
	 $flag=1;
	 
	 }
}


//}
}
closedir($dir);




}


$master_data="";
if(isset($_GET['cacheslave']))
{



$master_data="";	
$result=mysql_query("select id,server_url,name from server_configurations where srv_type='2' and status='111'");
	
if(mysql_num_rows($result) >0)
{
while($row=mysql_fetch_row($result))
{


if($fp_master=fopen($row[1]."slave-clear-cache.php?time=".$time,"r")) 
{
	while(!feof($fp_master))
	$master_data.=fgetc($fp_master);
    fclose($fp_master);
}
elseif (function_exists('curl_init')) {      
      // initialize a new curl resource
     $ch = curl_init();     
     curl_setopt($ch, CURLOPT_URL, $row[1]."slave-clear-cache.php?time=".$time);
     curl_setopt($ch, CURLOPT_HEADER, 0);       
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
     $content = curl_exec($ch);       
     curl_close($ch);
     $master_data=$content;
     
   
     
 } 




$master_data=$master_data." of slave server ".$row[2]."<br>";






}
}





}




$filecount = count(glob("$directory*.*"));

?>

<table width="100%" border="0" >
  <tr><td  height="65" colspan="4" scope="row" class="heading">Clear files from cache folder</td></tr></table>
  <table width="100%"   border="0" cellspacing="0" cellpadding="0"  >
    
	<tr>
    <td>&nbsp;</td>
    </tr>  
	    <tr>
    <td>Total Files In Master Server Cache Folder :&nbsp; <?php echo $filecount; ?></td>
    </tr>  
	    <tr>
    <td >&nbsp;</td>
    </tr>  
	  
	 <tr>
    <td><form name="form1" method="get" action="clear-cache.php">
  Delete Cache Files From Master Server Older Than 
      <select name="caches" id="caches">
	 
	    <option value="1" <?php if($show==1) echo "selected"; ?>>7 Days</option>
		
        <option value="2" <?php if($show==2) echo "selected"; ?>>14 Days</option>
		
        <option value="3" <?php if($show==3) echo "selected"; ?>>30 Days</option>
      </select>
      <input type="submit" name="Submit" value="Delete">
    </form></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>  
   <tr>
    <td height="15">&nbsp;</td>
    </tr>  
   <tr>
    <td><strong><?php if($flag==1){echo $i." files deleted from cache folder";}?></strong>&nbsp;</td>
    </tr>  
 
	
	
   <tr>
    <td height="15">&nbsp;</td>
    </tr>  
	
	
	<?php
	if($slavecount >0)
	{
	?>
	 <tr>
    <td><form name="form2" method="get" action="clear-cache.php">
  Delete Cache Files From Slave Server Older Than 
      <select name="cacheslave" id="cacheslave">
	 
	    <option value="1" <?php if($showslave==1) echo "selected"; ?>>7 Days</option>
		
        <option value="2" <?php if($showslave==2) echo "selected"; ?>>14 Days</option>
		
        <option value="3" <?php if($showslave==3) echo "selected"; ?>>30 Days</option>
      </select>
      <input type="submit" name="Submit" value="Delete">
    </form></td>
    </tr>
  <tr>
    <td>&nbsp;</td>
    </tr>  
	
	 <tr>
    <td height="10">&nbsp;</td>
    </tr>  
   <tr>
    <td><strong><?php echo $master_data; ?></strong>&nbsp;</td>
    </tr>  
	
	<?php
	}
	?>
	
   <tr>
    <td height="30">&nbsp;</td>
    </tr>  
	
	
	
	
	
	  </table>




<?php include_once("admin.footer.inc.php"); ?>
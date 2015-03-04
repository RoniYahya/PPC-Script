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
include("../extended-config.inc.php");  
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



if(isset($_POST['uid_strs']))
{

$uid_strs=$_POST['uid_strs'];
$uid_strs_array=explode('_',$uid_strs);
$count=count($uid_strs_array);

$srv_id=0;
$uid=0;
//print_r($uid_strs_array);

for($i=0;$i<$count-1;$i++)
{
if(isset($_POST['srv_'.$uid_strs_array[$i]]) && isset($_POST['uid_'.$uid_strs_array[$i]]))
{
$srv_id=$_POST['srv_'.$uid_strs_array[$i]];
$uid=$_POST['uid_'.$uid_strs_array[$i]];


mysql_query("update ppc_publishers set server_id='$srv_id' where uid='$uid'");

}



}

}












include("admin.header.inc.php");
?>
  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
       <tr>
      <td scope="row"> 
	  <span class="inserted"><br><?php echo "Server configurations for selected publishers have been successfully updated!"; ?> <a href="manage-server-configuration.php">Server Allotment</a></span><br><br></td>
    </tr>
  </table>
 


<?php include("admin.footer.inc.php"); ?>
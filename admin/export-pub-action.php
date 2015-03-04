<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







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


set_time_limit(0);

$type=$_REQUEST['type'];
phpSafe($type);

$str="";
if($type!=3)
{
	$str=" where status=$type ";
}
include("admin.header.inc.php"); 

//	echo "select  uid,username,email,status,country,domain,regtime,,accountbalance,xmlstatus,warning_status,traffic_analysis,firstname,lastname,phone_no,address from ppc_publishers a $str order by a.username   ";die;
?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/publishers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Export Publishers</td>
  </tr>
</table>

<?php

$result=mysql_query("select  uid,username,email,status,country,domain,regtime,accountbalance,xmlstatus,warning_status,traffic_analysis,firstname,lastname,phone_no,address from ppc_publishers a $str order by a.username ");

    if(mysql_num_rows($result)==0)
	{
     echo "<br><br>No results  found <br>";
	}
	else
	{

	 $query_str="Publisher id,username,email,status,country,domain,registered date,accountbalance,xmlstatus,warning status,traffic analysis,firstname,lastname,phone no,address \n";
	 while($row=mysql_fetch_array($result))
	 {
 
			$query_str.='"'.$row['uid'].'"'.",";
			$query_str.='"'.$row['username'].'"'.",";
			$query_str.='"'.$row['email'].'"'.",";
			
			if($row['status']==1)
			$status="Active";

			
			if($row['status']==0)
			$status="Blocked";
			
			if($row['status']==-1)
			$status="Pending and email confirmed";	

			if($row['status']==-2)
			$status="Pending and email not confirmed";
						
						
//			$field=$row[$i];
//			$field=str_replace('"','\"',$field);
			
			$query_str.='"'.$status.'"'.",";
			
			$country=$mysql->echo_one("select name from location_country where code='$row[country]'");
			$query_str.='"'.$country.'"'.",";
			
			$query_str.='"'.$row['domain'].'"'.",";
			
			$date=date("d-m-y",$row['regtime']);
			$query_str.='"'.$date.'"'.",";
			
			$query_str.='"'.$row['accountbalance'].'"'.",";
			
			if($row['xmlstatus']==1)
			$query_str.='"'."On".'"'.",";
			else
			$query_str.='"'."Off".'"'.",";
			
			if($row['warning_status']==1)
			$query_str.='"'."Warned".'"'.",";
			else
			$query_str.='"'."Not Warned".'"'.",";
			
			if($row['traffic_analysis']==1)
			$query_str.='"'."On".'"'.",";
			else
			$query_str.='"'."Off".'"'.",";
			
			$query_str.='"'.$row['firstname'].'"'.",";
			$query_str.='"'.$row['lastname'].'"'.",";
			$query_str.='"'.$row['phone_no'].'"'.",";
//			$query_str.=$row['address']."\n";

			
		    $address=html_entity_decode( $row['address'] );
		    $address=str_replace('"','""',$address);
			$address='"'.$address.'"';
			$query_str.=$address."\n";
			

	 }
	 
	    

	    $myfile = "../".$GLOBALS['cache_folder']."/pub_details_".time().".csv";

	    $file = fopen($myfile, 'w');
		flock($file, LOCK_EX);		
		fwrite($file,$query_str);
		flock($file, LOCK_UN);
		fclose($file);
		chmod($myfile, 0777);
		
		echo "<br><br>Export complete, <font color='red'> <b><a href='$myfile'>clik here </a></font> </b>for download<br>";
	}
	
	

 include("admin.footer.inc.php"); ?>
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
include("admin.header.inc.php"); ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/advertisers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Export Advertisers</td>
  </tr>
</table>

<br> 
<?php 
$result=mysql_query("select  uid,username,email,status,country,domain,regtime,accountbalance,firstname,lastname,phone_no,address  from ppc_users a $str order by a.username  ");



    if(mysql_num_rows($result)==0)
	{
     echo "<br><br>No results  found <br>";

	}
	else
	{
	
	 $query_str="Advertiser id,username,email,status,country,domain,registered date,accountbalance,firstname,lastname,phone no,address \n";
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

			
			$query_str.='"'.$status.'"'.",";
			
			$country=$mysql->echo_one("select name from location_country where code='$row[country]'");

			$query_str.='"'.$country.'"'.",";
			$query_str.='"'.$row['domain'].'"'.",";
			
			$date=date("d-m-y",$row['regtime']);			
			$query_str.='"'.$date.'"'.",";
			
			$query_str.='"'.$row['accountbalance'].'"'.",";

			
			$query_str.='"'.$row['firstname'].'"'.",";
			$query_str.='"'.$row['lastname'].'"'.",";
			$query_str.='"'.$row['phone_no'].'"'.",";
			
		    $address=html_entity_decode( $row['address'] );
		    $address=str_replace('"','""',$address);
			$address='"'.$address.'"';
			$query_str.=$address."\n";

	 	
	 }
	   
	    $myfile = "adv_down/adv_details_".time().".csv";	    
	    $myfile = "../".$GLOBALS['cache_folder']."/adv_details_".time().".csv";

	    $file = fopen($myfile, 'w');
		flock($file, LOCK_EX);		
		fwrite($file,$query_str);
		flock($file, LOCK_UN);
		fclose($file);
		chmod($myfile, 0777);
		
		echo "<br><br>Export complete, <font color='red'> <b><a href='$myfile'>clik here </a></font></b> for download<br>";
	}
	
	

 include("admin.footer.inc.php"); ?>
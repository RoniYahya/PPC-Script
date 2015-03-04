<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

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
	$url=$_SERVER['REQUEST_URI'];
	//echo $url;
	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 10;

//filtering status 

$status_value=5;

if(isset($_REQUEST['status']))
{

if($_REQUEST['status']==1)
$status_value=1;
if($_REQUEST['status']==-1)
$status_value=-1;
if($_REQUEST['status']==-2)
$status_value=-2;
if($_REQUEST['status']==0)
$status_value=0;

}

$str_status="";
if($status_value==1)
{
 $str_status="and a.status=1";
}
else if($status_value==-2)
{
 $str_status="and a.status=-2";
}
else if($status_value==-1)
{
 $str_status="and a.status=-1";
}
else if($status_value==0)
{
 $str_status="and a.status=0";
}

$com_account="";
//filtering status

//filtering single signon
$commonaccount=trim($_REQUEST['commonaccount']);
if(isset($_REQUEST['commonaccount']))
{
if($_REQUEST['commonaccount']==1)
{
	$com_account="and a.common_account_id!=0";
}
elseif($commonaccount=="0")
{
	$com_account="and a.common_account_id=0";
}
else
{
	$com_account="";
}
}
else
{
	$commonaccount="both";
}


/////////////////////////////country section/////////////////////////////
//filtering single signon
//filtering country

function chatstatus($userid)
{

 $res4=mysql_query("SELECT status FROM nesote_chat_login_status WHERE user_id='$userid'");
 $row4=mysql_fetch_array($res4);  

 if($row4[status]==1)
 {

$str="Online";
 return $str;
 }
 else
 {
 $str="Off Line";
 return $str;
 }
}
function chatstatusimage($userid)
{

 $res4=mysql_query("SELECT status FROM nesote_chat_login_status WHERE user_id='$userid'");
 $row4=mysql_fetch_array($res4);  

 if($row4[status]==1)
 {
 $str.="<img src='chats/images/available.png'>";

 return $str;
 }
 else
 {
 $str="";
 return $str;
 }
}
function countryflag($myip)
{

require_once ("../geo/geoip.inc");
		$gi=geoip_open("../geo/GeoIP.dat",GEOIP_STANDARD);
		//$myip=$_SERVER['REMOTE_ADDR'];
		//$myip="59.98.136.252";

		$geoip_code=geoip_country_code_by_addr($gi, $myip);
	
 $res=mysql_query("SELECT id FROM nesote_chat_country WHERE country_iso2='$geoip_code'");
 $row=mysql_fetch_array($res);  
 $total=mysql_num_rows($res);
if($total!=0)
{
 $res=mysql_query("SELECT * FROM nesote_chat_country WHERE country_status=1 and country_iso2='$geoip_code'");
 $row=mysql_fetch_array($res);
 $str="";
 $str.="chats/flags/$row[id]/$row[country_flag]";
 return $str; 
 
}
}
function countryname($myip)
{
require_once ("../geo/geoip.inc");
		$gi=geoip_open("../geo/GeoIP.dat",GEOIP_STANDARD);
		//$myip=$_SERVER['REMOTE_ADDR'];
		//$myip="59.98.136.252";

		$geoip_code=geoip_country_code_by_addr($gi, $myip);
		
 $res=mysql_query("SELECT id FROM nesote_chat_country WHERE country_iso2='$geoip_code'");
 $row=mysql_fetch_array($res);  
 $total=mysql_num_rows($res);
if($total!=0)
{
 $res=mysql_query("SELECT * FROM nesote_chat_country WHERE country_status=1 and country_iso2='$geoip_code'");
 $row=mysql_fetch_array($res);

 return $row[country_name]; 
 
}
}





/////////////////////////////country section/////////////////////////////


//echo "select  a.uid,a.username,b.name,a.domain from ppc_users a,location_country b  where a.country=b.code $str_status  $str_country $com_account order by a.uid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize;die;
$result=mysql_query("select  * from nesote_chat_public_user where status!=2 $str_status  $str_country $com_account order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
if(mysql_num_rows($result)==0 && $pageno>1)
	{
		$pageno--;
		$result=mysql_query("select * from nesote_chat_public_user where status!=2 $str_status $str_country  $com_account order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);

	}
$total=$mysql->echo_one("select count(*) from nesote_chat_public_user  where status!=2  $str_status $str_country $com_account");
?><?php include("admin.header.inc.php"); ?>
<style type="text/css">
<!--
.style6 {font-size: 20px}

.style1 {color: white;
	font-weight: bold;}
-->
</style>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("Do you really want to delete this user.")
		if (answer)
			return true;
		else
			return false;
		}
		</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/chatusers.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Chat Users</td>
  </tr>
</table>

<br>

<?php
if($total>0)
{
?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  <tr>
    <td colspan="2" ><?php if($total>=1) {?>   Showing Users <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br /><br/>    </td>
    <td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-view-users.php?status=$status_value&country=$country_code"); ?></td>
  </tr>
    </table>
  <table width="100%" border="0" cellpadding="0" cellspacing="0"  class="datatable">
  
  <tr class="headrow">
    <td width="13%" height="34"><strong>User Name </strong></td>
    <td width="15%"><strong>Email</strong></td>
    <td width="23%"><strong>Chat Status</strong></td>
	<td width="23%"><strong>Country</strong></td>
	
    <td width="23%"><strong>Browser</strong></td> 
   
	<td width="23%"><strong>Action</strong></td> 
	
  </tr>
<?php
	
$i=1;

while($row=mysql_fetch_row($result))
{
?>
  <tr><td><?php echo chatstatusimage($row[0]);?>&nbsp;&nbsp;<?php echo $row[2];?></td>
	
  <td><?php echo $row[3];?></td>
	 <td><?php echo chatstatus($row[0]);?></td> 
	<td><?php $country_flag=countryflag($row[6]) ?>
	<?php $country_name=countryname($row[6]) ?>
	
	<img src="<?php echo $country_flag; ?>"   title="Country:<?php echo $country_name; ?>"/>
	</td>
	
	<td><?php echo $row[7];?></td> 
	<td><a href="delete_chat_user.php?id=<?php echo $row[0];?>" onclick="return promptuser();">Delete</a></td> 
	

  </tr>


<?php
$i++;
}

?>
</table>
	<?php if($total>=1) {?>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" >  Showing Users <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
			<span class="inserted">
			<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
		</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span> </td>
		<td width="51%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-view-users.php?status=$status_value&country=$country_code"); ?></td>
	  </tr>
	</table>
	<?php } ?>  
<?php
}
else
{
	echo"<br>No Records Found<br><br>";
}
 include("admin.footer.inc.php"); ?>
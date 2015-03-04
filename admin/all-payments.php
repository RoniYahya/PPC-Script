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
 include("admin.header.inc.php"); 
 ?>
 <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/status.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Cash Flow Reports</td>
  </tr>
</table>
<br />

<?php
$st_date=mktime(0, 0, 0, 1,1,date("Y")-1);
$st_date1=date("m/d/Y",$st_date);
$end_date=mktime(0, 0, 0, 12,31,date("Y")-1);
$end_date1=date("m/d/Y",$end_date);
if($_POST)
{
$report=trim($_POST['payment_report']);
phpSafe($report);
$fromdp=trim($_POST['exdate']);
phpSafe($fromdp);
$todp=trim($_POST['todate']);
phpSafe($todp);

$expiry=explode("/",$fromdp);
$toexpiry=explode("/",$todp);
$time=time();
 $fromtime=mktime(0,0,0,$expiry[0],$expiry[1]+1,$expiry[2]);
 $totime=mktime(0,0,0,$toexpiry[0],$toexpiry[1]+1,$toexpiry[2]);

if($report==1)	
{
	$adv=trim($_POST['advertisers']);
	$table="ppc_users";
	$ty="inflow";
	
}
if($report==2)
{
	$adv=trim($_POST['publishers']);
	$table="ppc_publishers";
	$ty="outflow";
}
if($report==3)
{
	$adv=trim($_POST['common']);
	
	$table="nesote_inoutscripts_users";
	$ty="netflow";
	
}

phpSafe($adv);
if($adv==0)
	{
		$advtiserid="";
		$commonid="";
		$condition="";	
	$condition1="";
	$condition2="";
	$pubcondition="";
		
	}
	else
	{
		$advtiserid=" where uid='$adv'";
		$commonid=" where common_account_id='$adv'";
		$condition="and uid='$adverser_deta1[7]'";
$condition1="userid='$adverser_deta1[7]' AND ";
$condition2="x_cust_id='$adverser_deta1[7]' AND";
$publisherid=$mysql->echo_one("select uid from  ppc_publishers where common_account_id=$adv");
$pubcondition="and uid='$publisherid'";
	}
if($report==""||$fromdp==""||$todp=="")
{
	 
?>
<span class="already"><br><?php echo "Please go back and check whether you filled all mandatory fields !";?>           <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
<?php  include("admin.footer.inc.php"); ?>
<?php 
exit;
}
elseif(($time<$fromtime) || ($totime<$fromtime))
{  ?>
<span class="already"><br><?php echo "Please check the start date !";?>           <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
<?php  include("admin.footer.inc.php"); ?>
<?php 
exit;
}
elseif($totime==$fromtime)
{ ?>
<span class="already"><br><?php echo "Please enter a valid end date !";?>           <a href="javascript:history.back(-1);">Go Back And Modify</a></span><br><br>
<?php  include("admin.footer.inc.php"); ?>
<?php 
exit;
}
else
{
$check=0;
$bank=0;
$paypal=0;
$authorize=0;
$total=0;
if($report==1 || $report==2)
{
	
$adverser_deta=mysql_query("select firstname,lastname,address,email,phone_no,username,taxidentification,uid from $table  $advtiserid");
$adverser_deta1=mysql_fetch_row($adverser_deta);
if($report==1)
{
//echo "select sum(amount) from advertiser_fund_deposit_history  where pay_type =1 $condition and status=1  and date between '$fromtime' and '$totime'";
//echo "select sum(amount) from advertiser_fund_deposit_history where pay_type =2 $condition and status=1 and date between '$fromtime' and '$totime'";
$check=$mysql->echo_one("select sum(amount) from advertiser_fund_deposit_history  where pay_type =1 $condition and status=3  and date between '$fromtime' and '$totime'");
$bank=$mysql->echo_one("select sum(amount) from advertiser_fund_deposit_history where pay_type =2 $condition and status=3 and date between '$fromtime' and '$totime'");
$paypal=$mysql->echo_one("select sum(amount) from inout_ppc_ipns  where $condition1 result='1' and UNIX_TIMESTAMP(receivedat) between '$fromtime' and '$totime'");
$authorize=$mysql->echo_one("select sum(x_amount) from authorize_ipn where $condition2  UNIX_TIMESTAMP(timestamp) between '$fromtime' and '$totime'");
if($check=="")
$check=0;
if($bank=="")
$bank=0;
if($paypal=="")
$paypal=0;
if($authorize=="")
$authorize=0;

$total=$check+$bank+$paypal+$authorize;


}
if($report==2)
{
	$check=$mysql->echo_one("select sum(amount) from ppc_publisher_payment_hist where paymode=0 $pubcondition and status=3  and processdate between '$fromtime' and '$totime'");
$bank=$mysql->echo_one("select sum(amount) from ppc_publisher_payment_hist where paymode=2 $pubcondition and status=3 and processdate between '$fromtime' and '$totime'");
$paypal=$mysql->echo_one("select sum(amount) from ppc_publisher_payment_hist  where paymode=1 $pubcondition and status=3 and processdate between '$fromtime' and '$totime'");
if($check=="")
$check=0;
if($bank=="")
$bank=0;
if($paypal=="")
$paypal=0;
$total=$check+$bank+$paypal;
}
if($adv!=0)
{
$report_data='"Firstname","'.$adverser_deta1[0].'"'. "\n";
$report_data.='"Lastname","'.$adverser_deta1[1].'"'."\n";
$report_data.='"Address","'.$adverser_deta1[2].'"'."\n";
$report_data.='"Email","'.$adverser_deta1[3].'"'."\n";
$report_data.='"Phone no","'.$adverser_deta1[4].'"'."\n";
$report_data.='"Tax identification number","'.$adverser_deta1[6].'"'."\n";
$fname="../_cache/".$ty."_".$adverser_deta1[5].".csv";
}
else
$fname="../_cache/".$ty."_all.csv";

//echo $fname;
if($report==1)
{
$report_data.='"Total amount received from Paypal","'.moneyFormat($paypal).'"'."\n";

$report_data.='"Total amount received from Authorize.net","'.moneyFormat($authorize).'"'."\n";

$report_data.='"Total amount received from Check","'.moneyFormat($check).'"'."\n";
$report_data.='"Total amount received from Bank","'.moneyFormat($bank).'"'."\n";
}
else
{
	$report_data.='"Total amount withdrawal from Paypal","'.moneyFormat($paypal).'"'."\n";
	$report_data.='"Total amount withdrawal from Check","'.moneyFormat($check).'"'."\n";
$report_data.='"Total amount withdrawal from Bank","'.moneyFormat($bank).'"'."\n";
}
$report_data.='"Grand Total","'.moneyFormat($total).'"'."\n";



$fp = fopen($fname,'w');
fwrite($fp, $report_data);
fclose($fp);
?>
<span class="inserted"><br><?php echo "Cash flow report file is created .";?>           <a href="<?php if($adv!=0) { echo $server_dir."_cache/".$ty."_".$adverser_deta1[5]; }else { echo $server_dir."_cache/".$ty."_all"; }?>.csv">Click here </a> to view this report.</span><br><br>
<?php 
exit;

}
elseif($report==3)  //single account users
{
	
	if($adv!=0)
{
	//echo "select firstname,lastname,address,email,phone_no,username,taxidentification,uid from ppc_users  $commonid";
	$adverser_deta=mysql_query("select firstname,lastname,address,email,phone_no,username,taxidentification,uid from ppc_users  $commonid");
$adverser_deta1=mysql_fetch_row($adverser_deta);

$report_data='"Firstname","'.$adverser_deta1[0].'"'. "\n";
$report_data.='"Lastname","'.$adverser_deta1[1].'"'."\n";
$report_data.='"Address","'.$adverser_deta1[2].'"'."\n";
$report_data.='"Email","'.$adverser_deta1[3].'"'."\n";
$report_data.='"Phone no","'.$adverser_deta1[4].'"'."\n";
$report_data.='"Tax identification number","'.$adverser_deta1[6].'"'."\n";
$fname="../_cache/".$ty."_".$adverser_deta1[5].".csv";
}
else
{
	$fname="../_cache/".$ty."_all.csv";
}

	
	//echo "select sum(amount) from advertiser_fund_deposit_history  where pay_type=1 $condition and status=1  and date between '$fromtime' and '$totime'";
$check=$mysql->echo_one("select sum(amount) from advertiser_fund_deposit_history  where pay_type =1 $condition and status=3  and date between '$fromtime' and '$totime'");
$bank=$mysql->echo_one("select sum(amount) from advertiser_fund_deposit_history where pay_type =2  $condition and status=3 and date between '$fromtime' and '$totime'");
$paypal=$mysql->echo_one("select sum(amount) from inout_ppc_ipns  where $condition1  result='1' and UNIX_TIMESTAMP(receivedat) between '$fromtime' and '$totime'");
$authorize=$mysql->echo_one("select sum(x_amount) from authorize_ipn where $condition2  UNIX_TIMESTAMP(timestamp) between '$fromtime' and '$totime'");
$pubcheck=$mysql->echo_one("select sum(amount) from ppc_publisher_payment_hist where paymode=0 $pubcondition and status=3  and processdate between '$fromtime' and '$totime'");
$pubbank=$mysql->echo_one("select sum(amount) from ppc_publisher_payment_hist where paymode=2 $pubcondition and status=3 and processdate between '$fromtime' and '$totime'");
$pubpaypal=$mysql->echo_one("select sum(amount) from ppc_publisher_payment_hist  where paymode=1 $pubcondition and status=3 and processdate between '$fromtime' and '$totime'");
///echo "select sum(amount) from inout_ppc_ipns  where $condition1 result='1' and UNIX_TIMESTAMP(receivedat) between '$fromtime' and '$totime'";
if($check=="")
$check=0;
if($bank=="")
$bank=0;
if($paypal=="")
$paypal=0;
if($authorize=="")
$authorize=0;
if($pubcheck=="")
$pubcheck=0;
if($pubbank=="")
$pubbank=0;
if($pubpaypal=="")
$pubpaypal=0;

$total=($check+$bank+$paypal+$authorize)-($pubcheck+$pubbank+$pubpaypal);
$report_data.='"Inflow"'. "\n";
$report_data.='"Total amount received from Paypal","'.moneyFormat($paypal).'"'."\n";
$report_data.='"Total amount received from Authorize.net","'.moneyFormat($authorize).'"'."\n";
$report_data.='"Total amount received from Check","'.moneyFormat($check).'"'."\n";
$report_data.='"Total amount received from Bank","'.moneyFormat($bank).'"'."\n"."\n";
$report_data.='"Outflow"'. "\n";
$report_data.='"Total amount withdrawal from Paypal","'.moneyFormat($pubpaypal).'"'."\n";
$report_data.='"Total amount withdrawal from Check","'.moneyFormat($pubcheck).'"'."\n";
$report_data.='"Total amount withdrawal from Bank","'.moneyFormat($pubbank).'"'."\n"."\n";
$report_data.='"Net cash flow","'.moneyFormat($total).'"'."\n";

$fp = fopen($fname,'w');
fwrite($fp, $report_data);
fclose($fp);
?>
<span class="inserted"><br><?php echo "Cash flow report file is created. ";?>  <a href="<?php if($adv!=0) { echo $server_dir."_cache/".$ty."_".$adverser_deta1[5]; }else { echo $server_dir."_cache/".$ty."_all"; }?>.csv">Click here</a> to view this report.</span><br><br>
<?php 
exit;
}
}
}
if(($single_account_mode==1) )
$aa="User";
else
$aa="";
?>
<link rel="stylesheet" type="text/css" href="epoch_styles.css" />



<script type="text/javascript" src="epoch_classes.js"></script>



<script type="text/javascript">



/*You can also place this code in a separate file and link to it like epoch_classes.js*/



var dp_cal;      
var to_cal;






window.onload = function () {



		dp_cal  = new Epoch('epoch_popup','popup',document.getElementById('exdate'));	

	to_cal  = new Epoch('epoch_popup','popup',document.getElementById('todate'));

		



};











</script>
<script type="text/javascript">
function show()
{

var type=document.getElementById('payment_report').value;

if(type==1)
{
document.getElementById('adv_flow').style.display='block';
document.getElementById('pub_flow').style.display="none";
document.getElementById('com_flow').style.display="none";
document.getElementById('name').innerHTML="Advertiser";
}
if(type==2)
{
document.getElementById('adv_flow').style.display="none";
document.getElementById('pub_flow').style.display="block";
document.getElementById('com_flow').style.display="none";
document.getElementById('name').innerHTML="Publisher";
}
if(type==3)
{
document.getElementById('adv_flow').style.display="none";
document.getElementById('pub_flow').style.display="none";
document.getElementById('com_flow').style.display="block";
document.getElementById('name').innerHTML="<?php echo $aa;?>";

}

}


</script>
<form name="payment_report" method="post" action="all-payments.php">
<table width="100%"  border="0" cellspacing="0" cellpadding="0"> 
   <tr>
    <td width="23%" ></td>
    <td width="77%" align="right" ></td>
   </tr>
  <tr>
    <td colspan="2">
    </td>
    </tr>
    <tr>
    <td>Report type</td><td>
    <select name="payment_report" onchange="show()" id="payment_report">
    <option value="1">inflow</option>
    <option value="2">outflow</option> 
    
     <option value="3">netflow</option>
    
  
    </select>
	</td>
	</tr>
	<tr height="20">
    <td colspan="2">
    </td>
    </tr>
	<tr>
	<td ><span id="name" > </span>
	</td>
	<td><div id="adv_flow" style="display:none;">
	<select name="advertisers" >
	<?php
	$result=mysql_query("select uid,username from ppc_users where status=1 order by username asc");
	while($row=mysql_fetch_row($result))
	{
	?>
	<option value="<?php echo $row[0]; ?>"><?php echo $row[1]; ?></option>
	
	<?php } ?><option value="0" selected="selected">All</option>
	</select>
	</div>
	<div id="pub_flow" style="display:none;">
	<select name="publishers">
	<?php
	$result1=mysql_query("select uid,username from ppc_publishers where status=1 order by username asc");
	while($row1=mysql_fetch_row($result1))
	{
	?>
	<option value="<?php echo $row1[0]; ?>"><?php echo $row1[1]; ?></option>
	<?php } ?>
	<option value="0" selected="selected">All</option>
	</select>
	
	</div>
	

	
	
		<div id="com_flow" style="display:none;">
		<?php if(($single_account_mode==1)) {?>
	<select name="common">
	<?php
	$result3=mysql_query("select id,username from nesote_inoutscripts_users where status=1 order by username asc");
	while($row3=mysql_fetch_row($result3))
	{
	?>
	<option value="<?php echo $row3[0]; ?>"><?php echo $row3[1]; ?></option>
	<?php } ?><option value="0" selected="selected">All</option>
	</select>
		<?php } ?>
	</div>

	</td>
	</tr>
	<tr height="20">
    <td colspan="2">
    </td>
    </tr>
	<tr>
	<td>From </td>
	<td><input type="text" name="exdate"  value="<?php echo $st_date1; ?>" id="exdate" readonly="readonly"/></td>
	</tr>
	<tr height="20">
    <td colspan="2">
    </td></tr>
    <tr>
	<td>To </td>
	<td><input type="text" name="todate"  value="<?php echo $end_date1; ?>" id="todate" readonly="readonly"/></td>
	</tr>
    <tr height="20">
    <td colspan="2">
    </td></tr>
	<tr><td></td>
	<td ><input type="submit" name="submit" value="Submit">
	</table>
	</form>
	<script type="text/javascript">
	show();
	</script>
	
<?php
 include("admin.footer.inc.php"); 
?>
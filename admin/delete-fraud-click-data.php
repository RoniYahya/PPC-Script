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



?><?php include("admin.header.inc.php"); ?>
<?php 

$show="";
$showmessage="";
$time=0;


if(isset($_GET['del-statistics']))
{
$delete_show=$_GET['del-statistics'];
if($delete_show=="day")
{
$showmessage="Today";
$delete_time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
}
else if($delete_show=="week")
{
$showmessage="Last 7 Days";
$delete_time=mktime(0,0,0,date("m",time()),date("d",time())-6,date("y",time()));
}
else if($delete_show=="month")
{
$showmessage="This Month";
$delete_time=mktime(0,0,0,date("m",time()),1,date("y",time()));
}
else if($delete_show=="year")
{

$showmessage="This Year";
$delete_time=mktime(0,0,0,1,1,date("y",time()));
}


if($script_mode=="demo" && $delete_show!="year")
	{ 
		echo "<br><span class=\"already\">You cannot do this in demo.</span><br><br>";
		include_once("admin.footer.inc.php");
		exit(0);
	}




mysql_query("DELETE from ppc_fraud_clicks where clicktime<'$delete_time' ");


}



?>
<style type="text/css">
<!--
.style1 {color: white;
	font-weight: bold;}
-->
</style>

<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2" align="center" scope="row">&nbsp;</td>
    </tr>
      <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/fraud.php"; ?> </td>
  </tr>
</table>



<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
    <td colspan="2">&nbsp;</td>
  </tr>
<tr>
  <td  colspan="2" scope="row" class="heading">         Delete Fraud Click Statistics     </td>
</tr>
<tr>
  <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">
    <form name="form2" method="get" action="delete-fraud-click-data.php">
			 Delete data older than 
            <select name="del-statistics" id="del-statistics">
			
		
              <option value="day" >Today</option>
              <option value="week" >Last 7 days</option>
              <option value="month" >This Month</option>
              <option value="year" >This Year</option>
            </select>
			
            <input type="submit" name="del-Submit" value="Delete Statistics">
    </form>    </td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
 
  <tr>
    <td colspan="2"   scope="row">Total entries in fraud click table : <?php echo $mysql->echo_one("select count(id) from ppc_fraud_clicks"); ?></td>
  </tr>
  <tr>
    <td colspan="2"   scope="row">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="note" scope="row"><strong>Note: </strong> You may delete older fraud click related entries  if  there is too much data.</td>
  </tr>

  <tr>
    <td colspan="2">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="32%">&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td width="21%">&nbsp;</td>
    <td width="28%">&nbsp;</td>
  </tr>
</table>


<?php include("admin.footer.inc.php"); ?>

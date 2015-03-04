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
$delete_time=time()-(86400*7);
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



mysql_query("DELETE from ppc_fraud_clicks where publisherfraudstatus=0 and clicktime<'$delete_time' ");


}







if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }


if($show=="day")
{
$showmessage="Today";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
}
else if($show=="week")
{
$showmessage="Last 7 Days";
$time=time()-(86400*7);
}
else if($show=="month")
{
$showmessage="This Month";
$time=mktime(0,0,0,date("m",time()),1,date("y",time()));
}
else if($show=="year")
{

$showmessage="This Year";
$time=mktime(0,0,0,1,1,date("y",time()));
}
else if($show=="all")
{
$showmessage="All Time";
$time=0;

}


?>
<style type="text/css">
<!--
.style1 {color: white;
	font-weight: bold;}
-->
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/fraud.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading"> Repetitive click statistics  </td>
  </tr>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">


<tr>
  <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><form name="form1" method="get" action="ppc-fraud-public-click.php">
        Show Status
            <select name="statistics" id="statistics">
              <option value="day" <?php 
			  				  if($show=="day") echo "selected";			  
			  ?>>Today</option>
              <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>This Week</option>
              <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>This Month</option>
              <option value="year"  <?php 
			  				  if($show=="year")echo "selected";			  
			  ?>>This Year</option>
              <option value="all"  <?php 
			  				  if($show=="all")echo "selected";			  
			  ?>>All Time</option>
            </select>
			
            <input type="submit" name="Submit" value="Show Statistics">
			</form></td>
		
			<td><form name="form2" method="get" action="ppc-fraud-public-click.php">
			 Delete data older than 
            <select name="del-statistics" id="del-statistics">
			
		
              <option value="day" >Today</option>
              <option value="week" >This Week</option>
              <option value="month" >This Month</option>
              <option value="year" >This Year</option>
            </select>
			
            <input type="submit" name="del-Submit" value="Delete Statistics">
    </form></td>
  </tr>
 </table>
 <br />
 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <?php
  
	       $perpagesize=20;
          $pageno=1;
         if(isset($_GET['page']))
	     $pageno=getSafePositiveInteger('page');
		 
		 $res=mysql_query("select ip from ppc_fraud_clicks where publisherfraudstatus=0 and clicktime>$time group by ip");
		 $total=mysql_num_rows($res);
		  $result=mysql_query("select ip,count(id) a from ppc_fraud_clicks where publisherfraudstatus=0 and clicktime>$time group by ip order by a desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		  $no=mysql_num_rows($result);
		 if($no>0)
  
        {

      ?>
  <tr>
    <td colspan="3">
        For the <span class="inserted">repetitive</span> clicks, money is not deducted from advertiser account. Only one click from an ip is considered valid for an ad on a particular day. Others are treated as repetitive clicks </span></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" scope="row"><?php if($total>=1) {?>
Showing clicks <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?></td>
    <td width="47%" align="right" scope="row"><?php echo $paging->page($total,$perpagesize,"","ppc-fraud-public-click.php?statistics=$show"); ?></td>
  </tr>
  <tr>
    <td colspan="3">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
        <tr class="headrow">
          <td>Public IP</td>
          <td colspan="3">No of Clicks </td>
        </tr>
        <?php
		$i=1;
        while($row=mysql_fetch_row($result))
		 { ?>
        
        <tr <?php if($i%2==1){ ?> class="specialrow" <?php } ?>>
          <td style="border-bottom: 1px solid #b7b5b3;"><?php echo $row[0];?></td>
          <td width="51%" style="border-bottom: 1px solid #b7b5b3;" ><?php echo numberFormat($row[1],0);?></td>
        </tr>
        <?php  $i++; } ?>
    </table></td>
  </tr>
  <tr>
    <td width="21%">&nbsp;</td>
    <td width="28%">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><?php if($total>=1) {?>
Showing clicks <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?></td>
    <td align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-fraud-public-click.php?statistics=$show"); ?></td>
  </tr>
  <?php
}
?>
</table>
<?php
if($no==0)
	echo"<br>No Records Found<br><br>";

?>

<?php include("admin.footer.inc.php"); ?>

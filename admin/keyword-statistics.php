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
$flag_time=0;
if(!isset($_GET['statistics']))
{	$show="week";	}
else
{ $show=$_GET['statistics']; }
if(!isset($_GET['keybase']))
{	$showbase="imp";	}
else
{ $showbase=$_GET['keybase']; }

//if($show=="day")
//{
//$flag_time=-1;	
//$showmessage="Today";
//$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
//$end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));
//$beg_time=$time;
//
////echo date("d M h a",$end_time);
//}
if($show=="week")
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="month")
{
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	//$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
	
$beg_time=$time;
}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
if(!isset($_GET['order']))
{	$order="des";	}
else
{ $order=$_GET['order']; }
if(!isset($_GET['keybase']))
{	$showbase="imp";	}
else
{ $showbase=$_GET['keybase']; }
$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
//
//		
//
//
////$result=mysql_query("select * from system_keywords  order by keyword ASC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
//if(mysql_num_rows($result)==0 && $pageno>1)
//	{
//		$pageno--;
//		$result=mysql_query("select  * from system_keywords  order by keyword ASC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
//
//	}
$tab= getkeywordtable($beg_time,$end_time,$flag_time,$showbase,$order);

  $total=count($tab);
 

 include("admin.header.inc.php"); 
?>
<style type="text/css">
<!--
.style6 {font-size: 20px}

.style1 {color: white;
	font-weight: bold;}
-->
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/keywords.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Keyword Statistics</td>
  </tr>
</table>
<br />


<form name="form1" method="get" action="keyword-statistics.php">
    
    Show  statistics  of 
      <select name="statistics" id="statistics">
	          <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
  </select>
  Sort by
      <select name="keybase" id="keybase">
	  <option value="imp"  <?php 
			  				  if($showbase=="imp")echo "selected";			  
			  ?>>Impressions</option>
        <option value="clk"  <?php 
			  				  if($showbase=="clk")echo "selected";			  
			  ?>>Clicks</option>
        <option value="money"  <?php 
			  				  if($showbase=="money")echo "selected";			  
			  ?>>Money spent</option>
      </select>
      <select name="order" id="order">
	  <option value="asc"  <?php 
			  				  if($order=="asc")echo "selected";			  
			  ?>>Ascending</option>
        <option value="des"  <?php 
			  				  if($order=="des")echo "selected";			  
			  ?>>Descending</option>
      </select>
      <input type="submit" name="Submit" value="Show Statistics">
</form><br />

<?php
//if($total>0)
//{
//	
 if(count($tab) > 0)
{
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  

    <tr>
    <td colspan="2" ><?php if($total>=1) {?> Showing Users <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br /><br/>    </td>
    <td width="50%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","keyword-statistics.php"); ?></td>
  </tr>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">
  
  <tr class="headrow">
    <td width="23%"  >Keyword</td>
    <td width="26%" >Impressions</td>
    <td width="25%" >Clicks</td>
    <td width="24%" >Money Spent</td>
  </tr>
  <?php //$tablestructure= getkeywordtable($beg_time,$end_time,$flag_time,$showbase,$order);
  $i=0;
foreach($tab as $key => $value)
{?>
  <tr <?php if($i%2==1) { ?>class="specialrow" <?php } ?>>
    <td ><?php $name=$mysql->echo_one("select keyword from system_keywords where id='$value[0]'"); echo $name;?></td>
    <td ><?php echo numberformat($value[1],0); ?></td>
    <td ><?php echo numberformat($value[2],0); ?></td>
    <td ><?php echo numberformat($value[3]); ?></td>
  </tr>
  <?php	
   $i++;
}
      ?>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">

      <tr>
    <td colspan="2" ><?php if($total>=1) {?> Showing Users <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br /><br/>    </td>
    <td width="50%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","keyword-statistics.php"); ?></td>
  </tr>
  <tr height="30">
  <td colspan="4" class="note">
  
 <strong> Note: </strong> Last 24 hours data are not included in this statistics.
  
  </td>
  </tr>
</table>
<?php
}
else
{
	?>
	<br />No Record Found<br />
<?php
}


include("admin.footer.inc.php");	
?>
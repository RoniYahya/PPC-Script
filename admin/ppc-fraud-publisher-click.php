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
$url=urlencode($_SERVER['REQUEST_URI']);
$show="";
$showmessage="";
$time=0;

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
.style1 {
	color: #006600;
	font-weight: bold;
}
.style1 {color: white;
	font-weight: bold;}
-->
</style>

<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="2" align="center" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="center" scope="row"><a href="ppc-fraud-public-click.php">Repetitive Click Statistics</a> | <a href="ppc-fraud-publisher-click.php">Fraudulent Publisher Click Statistics</a> | <a href="ppc-ctr-fraud-detection.php">CTR Based Fraud Detection</a> </td>
    </tr>
</table>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
  <td>&nbsp;</td>
  <td colspan="2" class="style1">&nbsp;</td>
  <td width="1%">&nbsp;</td>
</tr>
<tr>
    <td width="1%" height="37">&nbsp;</td>
    
	<td  colspan="3"><div align="center"><span class="style7" style="font-size: 20px"><u>         Publisher fraud click statistics     </u></span></div></td>
    
  </tr>
<tr>
  <td>&nbsp;</td>
  <td colspan="2">&nbsp;</td>
  <td>&nbsp;</td>
</tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><form name="form1" method="get" action="ppc-fraud-publisher-click.php">
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
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <?php
  
	       $perpagesize=20;
          $pageno=1;
         if(isset($_GET['page']))
	     $pageno=getSafePositiveInteger('page');
		 $res=mysql_query("select pid from ppc_fraud_clicks where (publisherfraudstatus=1 or publisherfraudstatus=2 ) and clicktime>$time and pid!=0 group by pid");
		 $total=mysql_num_rows($res);
		  $result=mysql_query("select pid,count(id) a from ppc_fraud_clicks where (publisherfraudstatus=1 or publisherfraudstatus=2)  and clicktime>$time and pid!=0 group by pid order by a desc LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		  $no=mysql_num_rows($result);
		 if($total>0)
  
        {

      ?>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><span class="styleTitle">Showing <span class="inserted">fraud clicks</span> statistics of<strong> <?php echo $showmessage; ?></strong> . <br>
        These clicks are most likely fraud attempts done by publisher. For the fraudulent clicks, money is not deducted from advertiser account. </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="52%" scope="row"><?php if($total>=1) {?>
Showing clicks <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?></td>
    <td width="46%" align="right" scope="row"><?php echo $paging->page($total,$perpagesize,"","ppc-fraud-publisher-click.php?statistics=$show"); ?></td>
    <td scope="row">&nbsp;</td>
    
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" rowspan="4">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="1%">&nbsp;</td>
          <td width="18%">&nbsp;</td>
          <td colspan="4">&nbsp;</td>
		   <td width="1%" >&nbsp;</td>
        </tr>
        <tr bgcolor="#b7b5b3" class="style11" height="28">
          <td>&nbsp;</td>
          <td><span class="style1">Publisher Name </span></td>
          <td><span class="style1">No of Possible Fraud Clicks </span></td>
		    <td><span class="style1">No of Invalid Clicks </span></td>
		    <td><span class="style1">Warned user</span></td>
		  
		    <td colspan="2"><span class="style1">Action</span></td>
        </tr>
        <?php 
        $i=1;
        while($row=mysql_fetch_row($result))
		 { ?>
       
        <tr <?php if($i%2==1) echo 'bgcolor="#ededed"';?> height="28">
          <td style="border-bottom: 1px solid #b7b5b3;">&nbsp;</td>
          <td style="border-bottom: 1px solid #b7b5b3;"><?php
		  $name=$mysql->echo_one("select username from ppc_publishers where uid='$row[0]'");
		  $w_status=$mysql->echo_one("select warning_status from ppc_publishers where uid='$row[0]'");
		   echo $name;
		   $invalid_clicks=$mysql->echo_one("select count(publisherfraudstatus) from ppc_fraud_clicks where pid='$row[0]' and publisherfraudstatus=2 and clicktime>$time");
		   ?></td>
          <td width="21%" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($mysql->echo_one("select count(publisherfraudstatus) from ppc_fraud_clicks where pid='$row[0]' and publisherfraudstatus=1 and clicktime>$time"),0);?></td>
		   <td width="17%" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($invalid_clicks,0);?></td>
		   <td width="14%" style="border-bottom: 1px solid #b7b5b3;">
		   <?php
		   //	echo $row[0];
		   if($w_status==0)
		   		echo "No";
			else
				echo "Yes";
		   ?>		   </td>
		  <td width="14%" style="border-bottom: 1px solid #b7b5b3;"><a href="ppc-warn-publisher-ad.php?type=7&id=<?php echo $row[0]; ?>&url=<?php echo $url; ?>	">Warn publisher</a></td>
		  <td width="14%" style="border-bottom: 1px solid #b7b5b3;"><a href="ppc-block-publisher-ad.php?type=8&id=<?php echo $row[0]; ?>&url=<?php echo $url; ?>	">Block publisher</a></td>
        </tr>
        <?php $i++; } ?>
    </table></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><?php if($total>=1) {?>
Showing clicks <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> - <span class="inserted">
<?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
</span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
<?php } ?></td>
    <td align="right"><?php echo $paging->page($total,$perpagesize,"","ppc-fraud-publisher-click.php?statistics=$show"); ?></td>
    <td>&nbsp;</td>
  </tr>
  <?php
}
?>
</table>
<?php
if($no==0)
	echo"<br>&nbsp;&nbsp;&nbsp;-No Records Found-<br><br>";

?>

<?php include("admin.footer.inc.php"); ?>

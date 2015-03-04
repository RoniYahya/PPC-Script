<?php 







/*--------------------------------------------------+



|													 |



| Copyright � 2006 http://www.inoutscripts.com/      |



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





?><?php include("admin.header.inc.php"); ?>
<?php 
include("../publisher_statistics_utils.php");
$show="";
$showmessage="";
$time=0;
$flag_time=0;
$url=urlencode($_SERVER['REQUEST_URI']);
if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }



if($show=="day")
{
$flag_time=-1;	
$showmessage="Today";
$time=mktime(0,0,0,date("m",time()),date("d",time()),date("y",time()));
$end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));
$beg_time=$time;

//echo date("d M h a",$end_time);
}
else if($show=="week")
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
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}
else if($show=="year")
{

	$flag_time=1;
	$showmessage="Last 12 months";
	$time=mktime(0,0,0,date("m",time())+1-11,1,date("Y",time()));//$end_time-(365*24*60*60); //mktime(0,0,0,1,1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("y",time()));
$beg_time=$time;
}
else if($show=="all")
{
	$flag_time=2;
	$showmessage="All Time";
	$time=0;
	$end_time=mktime(0,0,0,1,1,date("y",time())+1);
$beg_time=$time;

}
else
{
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
}


//adding ststistics table 18/08/2009
if($flag_time==0)
	  {
		$table_name="publisher_daily_statistics";
	  }
	 else if($flag_time==2)
	 {
	 	$table_name="publisher_yearly_statistics";
	 }
	 else
	 {
		$table_name="publisher_monthly_statistics";
	 }

$wapstr="";
	$wap=-1;
	if(isset($_GET['wap']))
	$wap=trim($_GET['wap']);

if($wap!=-1)
	$wapstr="and wapstatus='".$wap."'";
//echo $wapstr;
?>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php include "submenus/admin-adunits.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">Admin  adunit statistics</td>
  </tr>

</table>

<br />


  <form name="form1" method="get" action="ppc-adunit-click-statistics.php">
          Show statistics
             <select name="statistics" id="statistics">
	  <option value="day"  <?php 
			  				  if($show=="day")echo "selected";			  
			  ?>>Today</option>
        <option value="week"  <?php 
			  				  if($show=="week")echo "selected";			  
			  ?>>Last 14 days</option>
        <option value="month"  <?php 
			  				  if($show=="month")echo "selected";			  
			  ?>>Last 30 days</option>
        <option value="year"  <?php 
			  				  if($show=="year")echo "selected";			  
			  ?>>Last 12 months</option>
        <option value="all"  <?php 
			  				  if($show=="all")echo "selected";			  
			  ?>>All Time</option>
      </select>
			
			Select Target Device 
<select name="wap">
<option value="-1" <?php if($wap==-1) echo "selected"; ?>>All</option>
<option value="0" <?php if($wap==0) echo "selected"; ?>>Desktops & Laptops</option>
<option value="1" <?php if($wap==1) echo "selected"; ?>>WAP Devices</option>
</select>
 
  
            <input type="submit" name="Submit" value="Show Statistics">
</form>
	
	<br />

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr><td >

	
	</td><td ></td></tr>
	  
		<tr>
		  <td height="25" colspan="7">  <strong class="inserted">XML API Statistics </strong>

      
        
	  </tr>

		  
</table>
	


<br />

<table width="100%" cellpadding="2" cellspacing="0" class="datatable">


		  <tr class="headrow">
		<td width="226"> <strong>Total Clicks  </strong> </td>
			<td width="284"><strong>Total impressions </strong></span></td>
	<td width="248"><strong>CTR(%)</strong></span></td>
		<td width="241"><strong>Click profit(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </strong></span></td>
		<td width="290"><strong>ECPM(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></span></td>
		</tr>
		<?php
	
			$tot_clck=round(getPubBlockClicks($time,$mysql,$id,-1,$flag_time),2); // $mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid='$id' and bid='-1' and time>'$time'");
			$tot_imp=round(getPubBlockImpressions($time,$mysql,$id,-1,$flag_time),2); // $mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid='$id' and bid='-1' and time>'$time'");
			if($tot_imp==0)
				$crt_tot=0;
			else
				{
				$crt_tot=($tot_clck/$tot_imp) * 100;
				$crt_tot=round($crt_tot,2);

				}
			$pub_profit=round(getPubBlockPublisherprofit($time,$mysql,$id,-1,$flag_time),2); // round($mysql->echo_one("select COALESCE(sum(publisher_profit),0) from $table_name where uid='$id' and bid='-1' and time>'$time'"),2);
			//$ar_sh=round($mysql->echo_one("select COALESCE(sum(adv_ref_profit),0) from $table_name where uid='$id' and bid='-1' and time>'$time'"),2);
			//$pr_sh=round($mysql->echo_one("select COALESCE(sum(pub_ref_profit),0) from $table_name where uid='$id' and bid='-1' and time>'$time'"),2);
			
			?>
			 <tr  >
			<td  height="25" style="border-bottom: 1px solid #b7b5b3;" ><?php echo numberFormat($tot_clck,0);  ?>&nbsp;</td>
			<td  height="25" style="border-bottom: 1px solid #b7b5b3;" ><?php echo numberFormat($tot_imp,0);  ?>&nbsp;</td>
			<td  height="25" style="border-bottom: 1px solid #b7b5b3;" ><?php echo numberFormat($crt_tot);  ?>&nbsp;</td>
			<td  height="25" style="border-bottom: 1px solid #b7b5b3;" ><?php echo numberFormat($pub_profit);  ?>&nbsp;</td>
			
			<td  height="25" style="border-bottom: 1px solid #b7b5b3;" ><?php echo numberFormat(getECPM($pub_profit,$tot_imp));  ?>&nbsp;</td>
			</tr>
</table>
<br />
	
  <?php
  

	$pageno=1;
	if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
	$perpagesize = 20;
	$total=$mysql->echo_one("select count(*) from  ppc_custom_ad_block   where   pid=0 $wapstr");
	$result=mysql_query("select name, id,wapstatus from ppc_custom_ad_block where pid='0' and status='1'  $wapstr order by id desc  LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
   
    $lastpage=floor($total/$perpagesize);
	 if($total%$perpagesize!=0)
		 $lastpage+=1;
      ?>
	<?php if($total>=1) 
	{?> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
	    <td width="50%" colspan="1" align="left" >
     
	Ad units <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total)
		{
		if($adserver_upgradation_date!=0)
			 echo $total+1; 
		else	
			echo $total;  
		 }
		 else 
		 echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php 
			if($adserver_upgradation_date!=0)
			 echo $total+1; 
		else	
			echo $total;  
 ?></span>&nbsp;
      &nbsp;&nbsp;  </td>
    <td width="50%"  align="right" scope="row" ><?php echo $paging->page($total,$perpagesize,"","ppc-adunit-click-statistics.php?statistics=$show&wap={$wap}"); ?></td>
    </tr>
</table> 
<br /> 
<?php } ?>


  <?php  if(mysql_num_rows($result)>0 || $adserver_upgradation_date!=0)
  {?>



<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="datatable">

        <tr class="headrow">

          <td width="30%"><span class="style1">Adunit Name </span></td>
         <td width="12%" ><span class="style1">Type</span></td>
          <td><span class="style1"> Clicks </span></td>
		    <td ><span class="style1">Impressions</span></td>
	        <td ><span class="style1">CTR (%)</span> </td>

          <td ><span class="style1">Click Value(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>) </span></td>
		  <td width="9%"> ECPM (<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)  </td>
        </tr>
        <?php 
        $i=1;
        
		
        while($row=mysql_fetch_row($result))
		 { 
		        if($row[2]==1)
		        {
		        	$image='wap.png';
		        }
		        else
		        {
		        	$image='pc.png';
		        }
		 	?>
       

        <tr <?php if($i%2==1)  { ?> class="specialrow" <?php } ?>>
          <td style="border-bottom: 1px solid #b7b5b3;"><a href="admin-adunit-click-statistics.php?id=<?php echo $row[1]; ?>&statistics=<?php echo $show; ?>&url=<?php echo $url; ?>"><?php
		 echo $row[0];
		   ?></a></td>
		   <td style="border-bottom: 1px solid #b7b5b3;"><img src="images/<?php echo $image;?>"></td>
          <td width="12%" style="border-bottom: 1px solid #b7b5b3;"><?php 
		
		  $total_clicks=getPubBlockClicks($time,$mysql,0,$row[1],$flag_time); //$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid=0 and  bid='$row[1]' and time>$time");
		    echo numberFormat($total_clicks,0);
		  ?></td>
		   <td width="13%" style="border-bottom: 1px solid #b7b5b3;"><?php 
		   	$total_impressions=getPubBlockImpressions($time,$mysql,0,$row[1],$flag_time);  //$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid=0 and  bid='$row[1]' and time>$time");
		if($total_impressions==0)
		{
		$ctr=0;
		}
	else
		{
		$ctr=($total_clicks/$total_impressions) * 100;
		$ctr=round($ctr,2);
		}
		   echo numberFormat($total_impressions,0); ?></td>
           <td width="10%" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($ctr); ?></td>

           <td width="14%" style="border-bottom: 1px solid #b7b5b3;"><?php $gg=getPubBlockMoneySpent($time,$mysql,0,$row[1],$flag_time); echo numberFormat($gg);  //round($mysql->echo_one("select COALESCE(sum(money_spent),0) from $table_name where uid=0 and bid='$row[1]' and time>$time"),2); ?></td>
		  <td> <?php echo numberFormat(getECPM($gg,$total_impressions));  ?></td>
	    </tr>
        <?php $i++; } 

		//echo $total." ".$lastpage." ".$pageno;
		if($adserver_upgradation_date!=0 && ($lastpage == $pageno || $total==0))
		{
				$total_clicks=getPubBlockClicks($time,$mysql,0,0,$flag_time);  //$mysql->echo_one("select COALESCE(sum(clk_count),0) from $table_name where uid=0 and bid=0 and time>$time");	
				$total_impressions=getPubBlockImpressions($time,$mysql,0,0,$flag_time);  //$mysql->echo_one("select COALESCE(sum(impression),0) from $table_name where uid=0 and bid=0 and time>$time");
				if($total_impressions==0)
				{
				$ctr=0;
				}
				else
				{
				$ctr=($total_clicks/$total_impressions) * 100;
				$ctr=round($ctr,2);
				}
		$result=getPubBlockMoneySpent($time,$mysql,0,0,$flag_time);  //mysql_query("select COALESCE(sum(money_spent),0) from $table_name where uid='0' and bid=0 and time>$time");
		$row=mysql_fetch_row($result);
		$ro=mysql_num_rows($result);

		?>
				<tr <?php if($i%2==1)  { ?> class="specialrow" <?php } ?>>
				  <td style="border-bottom: 1px solid #b7b5b3;"><?php
				 echo "Uncategorized";
				   ?></td>
				   <td><img src="images/pc.png"></td>
				  <td width="12%" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($total_clicks,0);?></td>
				   <td width="13%" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($total_impressions,0); ?></td>
				   <td width="10%" style="border-bottom: 1px solid #b7b5b3;"><?php echo numberFormat($ctr); ?></td>
		
			<td width="14%" style="border-bottom: 1px solid #b7b5b3;"> <?php $gg=getPubBlockMoneySpent($time,$mysql,0,0,$flag_time);
			echo   numberFormat($gg); //round($row[0],2);?></td>
			<td> <?php echo numberFormat(getECPM($gg,$total_impressions));  ?></td>
	      </tr>

		<?php } ?>
    </table>




  
  <?php
  }
  else
  {
  ?>
   <br />
No records found 
  <?php
  }
  ?>
 
 <?php if($total>=1) 
	{?> 
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

  		    <tr>
	    <td width="50%" colspan="1" align="left" >
	     
	Ad units <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total)
		{
		if($adserver_upgradation_date!=0)
			 echo $total+1; 
		else	
			echo $total;  
		 }
		 else 
		 echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php 
			if($adserver_upgradation_date!=0)
			 echo $total+1; 
		else	
			echo $total;  
 ?></span>&nbsp;
       &nbsp;&nbsp;  </td>
    <td width="50%"  align="right" scope="row" ><?php echo $paging->page($total,$perpagesize,"","ppc-adunit-click-statistics.php?statistics=$show&wap={$wap}"); ?></td>
    </tr>
  
  
</table>
<?php } ?> 
<br />

<?php include("admin.footer.inc.php"); ?>
<?php
include("config.inc.php");
include("../extended-config.inc.php"); 
include("../graphutils.inc.php");
 include("../publisher_statistics_utils.php");
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
$bid=$_GET['id'];
$url=urlencode($_SERVER['REQUEST_URI']);

$deleteurl=$_REQUEST['url'];
if(!isset($_GET['statistics']))
{	$show="day";	}
else
{ $show=$_GET['statistics']; }
if(isset($_GET['wap']))
{
 $wap_flag=$_GET['wap'];
phpSafe($wap_flag);
}


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
	$end_time=mktime(0,0,0,date("m",time())+1,1,date("y",time()));
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
//exit(0);

//echo $show;

?><?php include("admin.header.inc.php"); 

$unitname1=mysql_query("select name,pid from `ppc_custom_ad_block` where id='$bid'");
$unitname=mysql_fetch_row($unitname1);
if($unitname[1]==0)
{
	$pub_name="Admin";
}
else
{
$pub_name=$mysql->echo_one("select username from ppc_publishers where uid='$unitname[1]'");

}

$curfile=substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],"/")+1); 

?>
<script language='javascript' src='../FusionCharts/FusionCharts.js'></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php if($curfile=="admin-adunit-click-statistics.php") include "submenus/admin-adunits.php"; else include "submenus/publishers.php"; ?> </td>
  </tr>
  
  <tr>
   <td   colspan="4" scope="row" class="heading">Adunit Detailed Statistics</td>
  </tr>

</table>
<br />

  
  <table width="100%">
  <tr>
  <td width="29%">Adunit name</td>
  <td width="59%">: <?php echo  $unitname[0];?></td>
  </tr>
   <tr>
  <td width="29%">Created by</td>
  <td width="59%">: <?php echo  $pub_name;?></td>
  </tr>
  </table>
  
<br />


	<form name="form1" method="get" action="<?php echo $curfile; ?>?id=<?php echo $bid; ?>">
  Show click statistics as of 
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
      <input type="submit" name="Submit" value="Show Statistics">
      <input name="id" type="hidden" id="id" value="<?php echo $_REQUEST['id']; ?>">
	 </form>
<br />


<?php
	 $uid=$mysql->echo_one("select pid from `ppc_custom_ad_block` where id='$bid'");
//echo "select pid from `ppc_custom_ad_block` where id='$bid'";
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
}
//echo "plotPublisherGraphs(".$show.",".$flag_time.",".$beg_time.",".$end_time.",".$selected_colorcode.",".$uid.",".$referral_system.",".$bid.")";
$returnVal=plotPublisherGraphs($show,$flag_time,$beg_time,$end_time,$selected_colorcode,$uid,$referral_system,$bid);




$FC=$returnVal[0];


$FD=$returnVal[1];

			$FC->renderChart();
			echo "<br>";
		
			$FD->renderChart();
?>



<br />
<table width="100%" cellpadding="0" cellspacing="0" class="datatable">
<tr class="headrow">
<td width="12%" ><span class="style2"><strong>Period</strong></td>
<td width="18%" ><span class="style2"><strong>Impressions</strong></span></td>
<td width="12%" ><span class="style2"><strong>Clicks</strong></span></td>
<td width="18%" ><span class="style2"><strong>CTR(%)</strong></span></td>
<td width="23%" >
<span class="style2"><strong>Money Gained(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></span></td>
<td width="17%" >
<span class="style2"><strong>ECPM(<?php if($GLOBALS['currency_format']=="$$")echo $GLOBALS['system_currency'];else echo $GLOBALS['currency_symbol'] ?>)</strong></span></td>
</tr>
  <?php
 
$tablestructure= getTimeBasedPublisherStatistics($show,$flag_time,$beg_time,$end_time,$uid,$bid);
 if(count($tablestructure) > 0)
{$i=0;
foreach($tablestructure as $key => $value)
{
	
if($flag_time==0)
            {
               // $str=date("d/M/Y",$key-1);
$str=dateFormat($key-1,"%b %d"); 
                $show_duration="$str";
                           
            }
            else if($flag_time==1)
            {
               // $str=date("M/Y",$key-86400);
$str=dateFormat($key-86400,"%b");
                $show_duration="$str";
            }
             else if($flag_time==2)
            {
       
               // $str=date("Y",$key-86400);
$str=dateFormat($key-86400,"%Y");
                $show_duration="$str";
            }   
           
             if($flag_time==-1)
            {
           // $str=date("d/M h a",$key);
$str=dateTimeFormat($key,"%d %b %l %p");
            $show_duration="$str";
            }
             
  
  
  ?>
  <tr <?php if($i%2==1) echo 'bgcolor="#ededed"';?> height="25">
  <td ><?php echo $show_duration; ?></td>
   <td ><?php echo numberFormat($value[0],0); ?></td>
 <td ><?php echo numberFormat($value[1],0); ?></td>

 <td ><?php   echo numberFormat(getCTR($value[1],$value[0])); ?> </td>
<td ><?php echo numberFormat($value[2]); ?></td>
<td ><?php $ecpm=getECPM($value[2],$value[0]) ;	 echo numberFormat($ecpm); ?></td>
  </tr> 
  <?php 
  
  $i++;
}
}
  ?>

</table>


<br />








<?php include("admin.footer.inc.php");
?>
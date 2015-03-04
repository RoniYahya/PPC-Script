<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

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
$tablename="ppc_daily_clicks";
$tablename1="ppc_daily_ impressions";

//echo date("d M h a",$end_time);
}
else if($show=="week")
{
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}
else if($show=="month")
{
$flag_time=0;	
	$showmessage="Last 30 days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-28,date("Y",time())); //mktime(0,0,0,date("m",time()),1,date("y",time()));
	$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}

else
{
$flag_time=0;	
$showmessage="Last 14 Days";
	$time=mktime(0,0,0,date("m",time()),date("d",time())-12,date("Y",time()));//$end_time-(14*24*60*60);
$end_time=mktime(0,0,0,date("m",time()),date("d",time())+1,date("y",time()));
$beg_time=$time;
$tablename="ppc_clicks";

}
$spec_time_limits=mktime(date("H",time())-24,0,0,date("m",time()),date("d",time()),date("y",time()));
$last_hour_end_time=mktime(date("H",time()),0,0,date("m",time()),date("d",time()),date("y",time()));		
if($flag_time ==0)
{
   $country=mysql_query("SELECT sum(ids),cds FROM (SELECT count(id) as ids,country as cds  FROM $tablename WHERE  time>='$beg_time' and time <='$last_hour_end_time'  and country <>'' group by country UNION SELECT count(id) as ids,country as cds FROM ppc_daily_clicks WHERE time>='$spec_time_limits' and time <='$last_hour_end_time'  and country <>'' group by country)x group by x.cds");
}
else
{


$country=mysql_query("select count(id) ,country from  $tablename where  country <>'' and time>='$beg_time' and time <='$last_hour_end_time'  group by country ");
//echo "select count(id) no,country from  $tablename where country <>'' and time>='$beg_time' group by country ORDER BY no DESC";
}
$map='';
while($row=mysql_fetch_row($country))
{
$cname=$mysql->echo_one("select name from location_country  where code='$row[1]'");  
 $map.='<area title="'.$cname.'"  mc_name="'.$row[1].'" value="'.$row[0].'"></area>';
}




 $map='<map map_file="maps/world.swf"   zoom="100" zoom_x="0" zoom_y="40" ><areas>'. $map
 .'</areas><labels><label x="0" y="50" width="100%" align="center" text_size="16" color="#999999"><text><![CDATA[<b>Geographical Clicks Distribution]]></text><description><![CDATA[]]></description></label></labels></map>';
 
 $width=340;
 $height=290;
 if(isset($_GET['width']))
 $width=intval($_GET['width']);
 
 if(isset($_GET['height']))
 $height=intval($_GET['height']);

if($width==0)$width=340;
if($height==0)$height=290;


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style type="text/css">
body { margin:0; padding:0; font-family:Arial, Helvetica, sans-serif;}
</style>
</head>

<body bgcolor="#dddddd" style="padding-top:2px;">




 <form name="form1" method="get" action="geographical-statistics.php">
		  
           
  
                &nbsp;	<strong>Showing clicks of </strong>
				
				<select name="statistics" id="statistics">
  
              <option value="day" <?php if($show=="day") { ?>  selected  <?php } ?> >Today &nbsp;</option>
  
              <option value="week"  <?php if($show=="week") { ?>  selected  <?php } ?> >Last 14 days&nbsp;</option>
  
              <option value="month"  <?php if($show=="month") { ?>  selected <?php } ?> >Last 30 days&nbsp;</option>
  
            
              </select>
			  

    <input type="submit" name="Submit" value="Update &raquo;">
 
 
   
        

</form>


<div style="padding:4px;border:1px solid #CCCCCC;width:<?php echo $width; ?>px;height:<?php echo $height; ?>px">
 <!-- ammap script-->

  <script type="text/javascript" src="ammap/swfobject.js"></script>

	<div id="flashcontent" style="float:left ">

		<strong>Your flash player needs to be upgraded!</strong>

	</div>



	<script type="text/javascript">

		// <![CDATA[		

		var so = new SWFObject("ammap/ammap.swf", "ammap", "<?php echo $width; ?>", "<?php echo $height; ?>", "8", "#FFFFFF");

		so.addVariable("path", "ammap/");

		so.addVariable("settings_file", escape("ammap/ammap_settings.xml"));                  // you can set two or more different settings files here (separated by commas)

		//so.addVariable("data_file", escape("ammap/ammap_data.xml"));		

  	   so.addVariable("map_data", '<?php echo htmlspecialchars($map) ?>');                                   // you can pass map data as a string directly from this file



		so.write("flashcontent");

		// ]]>

	</script>

<!-- end of ammap script -->


</div>




</body>
</html>
<?php 
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
include("admin.header.inc.php"); 
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php /*?><?php include "submenus/chatusers.php"; ?><?php */?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Export DataBase </td>
  </tr>
</table>


<script>
$(document).ready(function(){	

	$(".select_table").click(function()
	{
	$('#show').prop('checked', false);
		 var fld = document.getElementById('table_db');
		 var values = [];
		for (var i = 0; i < fld.options.length; i++)
		 {
			   if (fld.options[i].selected)
				{
				 values.push(fld.options[i].value);
				}
		 }
//alert(values);
	
	})
;});


function show()
{
var show=($('input[name=show]:checked').val());

if(show==1)
{
		var fld = document.getElementById('table_db');
		for (var i = 0; i < fld.options.length; i++)
		 {
			  fld.options[i].selected="selected";
				
		 }	
 //$('#selected_db').css("display","none");

}
else
{

// $('#show').prop('checked', false);
		var fld = document.getElementById('table_db');
		for (var i = 0; i < fld.options.length; i++)
		 {
			  fld.options[i].selected="";
				
		 }	
 $('#selected_db').css("display","block");
}


}

function exports()
{
var show=($('input[name=show]:checked').val());

if(show==1)
{

		$.ajax({
		  cache:false,
		  type:"post",
		  url:"export_all.php",
			  success : function(data)
			  {	
			  
			 document.getElementById("download_db").innerHTML=data;
			  //alert(data);
			  }
		  });
}
else
{


 
		 var fld = document.getElementById('table_db');
		 var values = [];
		for (var i = 0; i < fld.options.length; i++)
		 {
			   if (fld.options[i].selected)
				{
				 values.push(fld.options[i].value);
				}
		 }

$.ajax({
		  cache:false,
		  type:"post",
		  url:"export_selected.php",
		 data:"values="+values,
			  success : function(data)
			  {	
			 document.getElementById("download_db").innerHTML=data;
			  }
		  });
}

}
 </script>
 <style>
 #table_db{ height:333px; width:333px;
 }
 .outer_db{padding:33px}
 
 </style>
 
<?php

$myBackups="../".$GLOBALS['cache_folder']."/";
define('BACKUP_DIR',$myBackups); 
define('HOST',$mysql_server ); 
define('USER',$mysql_username ); 
define('PASSWORD',$mysql_password ); 
define('DB_NAME',$mysql_dbname); 

// Define  Database Credentials
/*
Define the filename for the sql file
If you plan to upload the  file to Amazon's S3 service , use only lower-case letters 
*/
$fileName = 'mysqlbackup--' . date('d-m-Y') . '@'.date('h.i.s').'.sql' ; 
// Set execution time limit
if(function_exists('max_execution_time')) {
if( ini_get('max_execution_time') > 0 ) 	set_time_limit(0) ;
}
if (!file_exists(BACKUP_DIR)) mkdir(BACKUP_DIR , 0700) ;
if (!is_writable(BACKUP_DIR)) chmod(BACKUP_DIR , 0700) ; 
// Create an ".htaccess" file , it will restrict direct accss to the backup-directory . 
/*$content = 'deny from all' ; 
$file = new SplFileObject(BACKUP_DIR . '/.htaccess', "w") ;
$file->fwrite($content) ;
*/$mysqli = new mysqli(HOST , USER , PASSWORD , DB_NAME) ;
if (mysqli_connect_errno())
{
   printf("Connect failed: %s", mysqli_connect_error());
  }
// Introduction information
 $return .= "--\n";
$return .= "-- A Mysql Backup System \n";
$return .= "--\n";
$return .= '-- Export created: ' . date("Y/m/d") . ' on ' . date("h:i") . "\n\n\n";
$return = "--\n";
$return .= "-- Database : " . DB_NAME . "\n";
$return .= "--\n";
$return .= "-- --------------------------------------------------\n";
$return .= "-- ---------------------------------------------------\n";
$return .= 'SET AUTOCOMMIT = 0 ;' ."\n" ;
$return .= 'SET FOREIGN_KEY_CHECKS=0 ;' ."\n" ;
$tables = array() ; 
?>
<?php
// Exploring what tables this database has
$result = $mysqli->query('SHOW TABLES' ) ; 
// Cycle through "$result" and put content into an array
?>
<div class="outer_db">
Select All<input type="checkbox" id="show" name="show"  value="1" onclick="show()"/>
<div id="selected_db">
<select name="table_db" id="table_db" multiple="multiple" >
<?php
$i=0;
while ($row = $result->fetch_row()) 
{
//$tables[] = $row[0] ;
?>
<?php if($row[0]!="view_keywords"){?> 
	 <option class="select_table"  id="select_table_<?php echo $i; ?>"  value="<?php echo $row[0]; ?>" ><?php echo $row[0]; ?> </option>
	 <?php }?>
 <?php
$i++;
}
?>

 </select>
 </div>
 <input type="button" name="Export"  value="Export" onclick="exports()"/> 
</div>
<div id="download_db"></div>  
<?php
 include("admin.footer.inc.php"); ?>

<?php

include("config.inc.php");

if(!isset($_COOKIE['inout_admin']))
{
header("Location:index.php");
}
$inout_username=$_COOKIE['inout_admin'];
$inout_password=$_COOKIE['inout_pass'];
if(!(($inout_username==md5($username)) && ($inout_password==md5($password))))
	{
	header("Location:index.php");
	}


?><?php include("admin.header.inc.php");

  ?>
     <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="53" colspan="4"  align="left"><?php //include "submenus/publishing-urls.php"; ?> </td>
  </tr>
  <tr>
   <td   colspan="4" scope="row" class="heading">Manage Publishing Urls</td>
  </tr>
</table>
   		  <?php
   		  
   		
		  
				  	$pageno=1;
if(isset($_REQUEST['page']))
$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
//echo "select id,url,catagory,status,adlang,pid from publishing_urls ".$condition .$owner .$condition1 .$category_con ." order by id DESC LIMIT ";
		  $result=mysql_query("select id,url,status,pid from ppc_publishing_urls order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
		  $no=mysql_num_rows($result);
		  $total=$mysql->echo_one("select count(*) from ppc_publishing_urls");
		
		  ?>
		  
	
<form name="ads" action="manage-ppc-publishing-urls.php" method="get">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 <tr>
    <td  colspan="">&nbsp;</td>
  </tr>
  </table> </form> 
		  
		 <?php

   if($no!=0)
		  { 
  ?>  
		  
		  
		  
		    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="4" align="center" scope="row">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="4" align="left" scope="row"><span class="inserted"> Url's are listed below. </span></td>
    </tr>
     <tr>
      <td colspan="2" scope="row"><?php if($total>=1) {?>   Showing publishing urls <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;<br /><br/>    </td>
    <td width="30%" colspan="2" align="right"><?php echo $paging->page($total,$perpagesize,"","manage-ppc-publishing-urls.php"); ?>&nbsp;</td>
    </tr>
  </table>
  
  
  
  
  
 
  
    <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="datatable">

		  <tr valign="middle" bgcolor="#CCCCCC">
		    <td height="25"  align="left" ><strong>Url Name</strong></td>
		    <td height="25"  align="left" ><strong>Owner</strong></td>
		    <td width="22%"  align="left" ><strong>Status</strong></td>
		    <td width="50%" colspan="2" align="center"><strong>Actions</strong></td>
</tr>

 <?php
		$i=0;
		  while($row=mysql_fetch_row($result))
          {
         
		  
	 if($row[2]==1)
	$st="Active";
	else if($row[2]==-1)
	$st="Pending";  
		  else if($row[2]==2)
	$st="Blocked";  
		  
		  
		  ?>
           <tr <?php if($i%2==1) echo 'bgcolor="#ededed"';?>>  
		    <td  style="border-bottom: 1px solid #b7b5b3;padding-left: 3px"  width="14%" height="30" align="left">
           <a href="<?php echo "http://".$row[1]; ?>" target="_blank"><?php 	echo (wordwrap($row[1], 50, "<br>", 1));?></a>			 </td>
            <td  style="border-bottom: 1px solid #b7b5b3;padding-left: 3px"  width="14%" align="left"><?php echo $pub_name=$mysql->echo_one("select username from ppc_publishers  where uid='$row[3]'"); ?>&nbsp;</td>
			 <td height="30" align="left"  style="border-bottom: 1px solid #b7b5b3;padding-left: 3px"><?php echo $st; ?>&nbsp;       		             			 </td>
			 <td height="30" colspan="2" align="left"  style="border-bottom: 1px solid #b7b5b3;padding-left: 3px">&nbsp;&nbsp;
		

		   &nbsp;&nbsp;<a href="delete-ppc-publishing-url-action.php?urid=<?php echo $row[0] ;?>" onclick="return promptuser()">Delete</a>	
		   <?php   if($row[2]!=2) { ?>
		    | <a href="ppc-publishing-url-status.php?urid=<?php echo $row[0] ;?>&type=2">Block</a>
			<?php } ?>
			<?php if($row[2]!=1) { ?>
		    | <a href="ppc-publishing-url-status.php?urid=<?php echo $row[0] ;?>&type=1"> Activate</a>
		   <?php } ?>
		   
		   </td>
          </tr>	
 	<?php
	$i++;
	 }
 	?>
	  </table>
	 
	  <table  width="100%">
	  <tr><td colspan="4"  height="10"></td></tr>
	<tr>
	  <td colspan="4"><?php if($total>=1) {?>   Showing publishing urls <span class="inserted"><?php echo ($pageno-1)*$perpagesize+1; ?></span> -
        <span class="inserted">
        <?php if((($pageno)*$perpagesize)>$total) echo $total; else echo ($pageno)*$perpagesize; ?>
    </span>&nbsp;of <span class="inserted"><?php echo $total; ?></span>&nbsp;
    <?php } ?>    &nbsp;&nbsp;    </td>
    <td width="32%" colspan="3" align="right"><?php echo $paging->page($total,$perpagesize,"","manage-ppc-publishing-urls.php"); ?>&nbsp;</td>
    </tr>
	<tr>
	<td colspan="6"><span class="note"><strong>Note :</strong>
<strong></strong>Urls will be created in pending state initally.The url will be activated automatically when you activate any ad position of the url.</span></td></tr>

  </table>
  		 <?php 
		   }
		  else
		  {
		  echo "<br>- There is no record to display -<br><br>";
		  }
		 


include("admin.footer.inc.php"); 
?>
<script type="text/javascript">
	function promptuser()
		{
		var answer = confirm ("You are about to delete the url. It won't be available later.")
		if (answer)
			return true;
		else
			return false;
		}
		</script>
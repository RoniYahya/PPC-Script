<?php 







/*--------------------------------------------------+



|													 |



| Copyright  2006 http://www.inoutscripts.com/      |



| All Rights Reserved.								 |



| Email: scripts@inoutscripts.com                    |



|                                                    |



+---------------------------------------------------*/















?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<title>Inout Adserver - Pay Per Click Advertising Expert!</title>



<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ad_display_char_encoding; ?>">



<link href="style.css" rel="stylesheet" type="text/css">

<style type="text/css">
select { border:1px solid #ccc; padding:2px 5px;}
input[type="submit"], 
input[type="button"], 
a.homelink {
    border: 0 none;
	border:1px solid #4B738E;
    border-radius: 5px 5px 5px 5px;
	-moz-border-radius: 5px 5px 5px 5px;
	-webkit-border-radius: 5px 5px 5px 5px;
	-o-border-radius: 5px 5px 5px 5px;
    color:#20435E;
    float: none;
    font-size: 16px;
    margin: 5px 5px 0 5px;
    padding: 1px 5px;
    text-decoration: none;
    text-shadow: 1px 0 1px #fff;
	background: #ebf1f6; /* Old browsers */
background: -moz-linear-gradient(top, #ebf1f6 0%, #abd3ee 50%, #89c3eb 51%, #d5ebfb 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#ebf1f6), color-stop(50%,#abd3ee), color-stop(51%,#89c3eb), color-stop(100%,#d5ebfb)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #ebf1f6 0%,#abd3ee 50%,#89c3eb 51%,#d5ebfb 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #ebf1f6 0%,#abd3ee 50%,#89c3eb 51%,#d5ebfb 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #ebf1f6 0%,#abd3ee 50%,#89c3eb 51%,#d5ebfb 100%); /* IE10+ */
background: linear-gradient(to bottom, #ebf1f6 0%,#abd3ee 50%,#89c3eb 51%,#d5ebfb 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ebf1f6', endColorstr='#d5ebfb',GradientType=0 ); /* IE6-9 */
}
</style>

</head>







<body class="adminSide">


<br>
<?php if($chat_visible_status!=0)
{
?>
<!--//////////////////////////////////adserver5.4 chat//////////////////////-->


<script type="text/javascript" src="chats/js/jquery-1.7.2.min.js"></script>



<script type="text/javascript" src="chats/js/chatnew.js"></script>
<script language="javascript" type="text/javascript">
		var ints=self.setInterval(function(){check_active_users()},2000);
			var ints=self.setInterval(function(){online_user_count()},2000);
		//check_active_users();
		
		function online_user_count()
		{
					$.ajax({
									  cache:false,
									  type:"post",
										url: "chats/online_user_count.php",
									//	data: "&pro_id="+pro_id+"&varition_status="+varition_status,
										success: function(data)
										 {
										document.getElementById("online_count").innerHTML=data;
										}
									
										});

		}
		function check_active_users(){
		//alert("jj");
			$.ajax({
									  cache:false,
									  type:"post",
										url: "chats/chatlist.php",
									//	data: "&pro_id="+pro_id+"&varition_status="+varition_status,
										success: function(data)
										 {
									//	alert(data);	
										document.getElementById("chat_main_container").innerHTML=data;
										}
									
										});

		}
		</script>
<script type="text/javascript">
$(document).ready(function(){

 $(".chatWrap").hover(function(){
 	$(this).stop(true,true).animate({"opacity":"1","right":"0"});
	},
	function(){
	$(this).stop(true,true).animate({"opacity":"1","right":"-136px"});
	
	});
	
	
	
	$(".rightPannel").hover(function(){
		$(".rightPannel").css("zIndex","50");
		$(".chatDispBoxWrap").fadeIn();
	},
	function(){
		$(".rightPannel").css("zIndex","5");
		$(".chatDispBoxWrap").fadeOut();
	});


	
});

 function chatWith(reciever,sender,cname) {

	originalTitle = document.title;

	startChatSession(reciever,sender,cname);
	$([window, document]).blur(function(){
		windowFocus = false;
	}).focus(function(){
		windowFocus = true;
		document.title = originalTitle;
	});
}

	

</script>
<link type="text/css" rel="stylesheet" media="all" href="chats/css/chat.css" />

<div class="rightPannel">
<div class="chatTopBar">
                        <span id="online_count"></span>
                    </div>
            	<div class="chatDispBoxWrap" id="chat_main_container">
                    
                    <div class="chatDispBox">
                        <iframe id="chatframe" allowtransparency="true" src="chats/chatlist.php" marginwidth="0" marginheight="0" frameborder="0" vspace="0" hspace="0" style="overflow:visible; width:100%" ></iframe>
                     </div>
                </div>
				
				
            </div>
<?php
}?>
<!--//////////////////////////////////adserver5.4 chat//////////////////////-->




<table width="100%"  border="0"  cellpadding="0" cellspacing="0" class="nesoteparenttable">



<tr>



<td>

<table width="100%"  border="0" cellpadding="0" cellspacing="0"  style="padding:5px; padding-bottom:0px;">

<tr >

<td width="223" align="left"><a href="main.php"><img src="images/logo.png" alt="Admin Area Home" width="315" border="0"></a></td>

<td align="right" valign="bottom" class="note"><a href="tutor1.php" >First Time Guide</a><br>
More help? Check our <a href="http://www.inoutscripts.com/forum/view/subforums/9/Inout-Adserver">forum</a><br>
Even more help? Visit our <a href="http://www.inoutscripts.com/support/">support desk</a><br>
<br>

</td>

</tr>

</table>

<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="boxhead">
<tr>
<td width="99%" align="left"><div align="center">
<img src="images/home.png"  align="absmiddle"><a href="main.php" class="header">Home</a> | 
<?php if($single_account_mode==1) { ?>
<img src="images/user.png"  align="absmiddle"><a href="manage-users.php" class="header">Users</a>  | 
<?php } ?>
<img src="images/advertiser.png"  align="absmiddle"><a href="ppc-view-users.php" class="header">Advertisers</a>  | 
<img src="images/publisher.png"  align="absmiddle"><a href="ppc-view-publishers.php" class="header">Publishers</a> | 
<img src="images/text.png"  align="absmiddle"> <a href="ppc-view-ads.php" class="header">Ads</a> | 
<img src="images/adcode.png"  align="absmiddle"><a href="ppc-admin-new-ad-unit.php" class="header">Ad Code</a> |  
<img src="images/Dollar.png" width="22" align="absmiddle"><a href="ppc-overall-profit-statistics.php" class="header">Profit Statistics</a> | 
<img src="images/logout.png"  align="absmiddle"><a href="logout.php" class="header">Logout</a> 
</div></td>

</tr>
</table>

<br>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="160px" style="vertical-align: top;">

<?php include "admin.sidebar.php"; ?>
</td>

<td style="vertical-align:top;padding:0px 5px;" align="left">
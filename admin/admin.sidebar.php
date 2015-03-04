
<style type="text/css">
#leftMenu {
    float: left;
    padding-left: 0;
    padding-right: 6px;
    padding-top: 0;
    vertical-align: top;
    width: 165px;
}
#dhtmlgoodies_slidedown_menu .dhtmlgoodies_activeItem {
    color: green;
    font-weight: bold;
}
#dhtmlgoodies_slidedown_menu li {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #DDDDDD;
    border-radius: 5px 5px 5px 5px;
-webkit-border-radius: 5px 5px 5px 5px;
-moz-border-radius: 5px 5px 5px 5px;
-o-border-radius: 5px 5px 5px 5px;
    list-style-type: none;
    margin-bottom: 2px;
    padding: 3px;
    position: relative;
}
#dhtmlgoodies_slidedown_menu ul {
    margin: 0;
    padding: 0;
    position: relative;
}
#dhtmlgoodies_slidedown_menu div {
    margin: 0;
    padding: 0;
}
#dhtmlgoodies_slidedown_menu {
    border: 0 none;
    display: none;
    width: 170px;
}
#dhtmlgoodies_slidedown_menu a {
    clear: both;
    color: #fff;
    display: block;
    padding-left: 2px;
    text-decoration: none;
}
#dhtmlgoodies_slidedown_menu .dhtmlgoodies_activeItem {
    color: #fff;
    font-weight: bold;
}
#dhtmlgoodies_slidedown_menu .slMenuItem_depth1 {
    font-weight: bold;
}
#dhtmlgoodies_slidedown_menu .slMenuItem_depth2 {
    margin-top: 1px;
}
#dhtmlgoodies_slidedown_menu .slMenuItem_depth3 {
    color: blue;
    font-style: italic;
    margin-top: 1px;
}
#dhtmlgoodies_slidedown_menu .slMenuItem_depth4 {
    color: red;
    margin-top: 1px;
}
#dhtmlgoodies_slidedown_menu .slMenuItem_depth5 {
    margin-top: 1px;
}
#dhtmlgoodies_slidedown_menu .slideMenuDiv1 ul {
    padding: 1px;
}
#dhtmlgoodies_slidedown_menu .slideMenuDiv2 ul {
    padding: 0;
}
#dhtmlgoodies_slidedown_menu .slideMenuDiv2 ul li a {
    background: none repeat scroll 0 0 #F6F6F6;
    padding: 10px;
	border-radius: 3px 3px 3px 3px;
-moz-border-radius: 3px 3px 3px 3px;
-webkit-border-radius: 3px 3px 3px 3px;
-o-border-radius: 3px 3px 3px 3px;
color:#003366;
}
#dhtmlgoodies_slidedown_menu .slideMenuDiv2 ul li a:hover {
    background: none repeat scroll 0 0 #EEEEEE;
}
#dhtmlgoodies_slidedown_menu .slideMenuDiv2 ul li a img {
    margin-right: 5px;
}
#dhtmlgoodies_slidedown_menu .slideMenuDiv3 ul {
    margin-left: 10px;
    padding: 1px;
}
#dhtmlgoodies_slidedown_menu .slMenuItem_depth4 ul {
    margin-left: 15px;
    padding: 1px;
}
#dhtmlgoodies_slidedown_menu img {
    border: 0 none;
    margin-right: 3px;
}
a.slMenuItem_depth1 {
    background: #2989d8; /* Old browsers */
background: -moz-linear-gradient(top, #2989d8 50%, #207cca 51%, #1e5799 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(50%,#2989d8), color-stop(51%,#207cca), color-stop(100%,#1e5799)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top, #2989d8 50%,#207cca 51%,#1e5799 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top, #2989d8 50%,#207cca 51%,#1e5799 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(top, #2989d8 50%,#207cca 51%,#1e5799 100%); /* IE10+ */
background: linear-gradient(to bottom, #2989d8 50%,#207cca 51%,#1e5799 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#2989d8', endColorstr='#1e5799',GradientType=0 ); /* IE6-9 */
    border-bottom: 0 none;
    border-radius: 3px 3px 3px 3px;
-moz-border-radius: 3px 3px 3px 3px;
-webkit-border-radius: 3px 3px 3px 3px;
-o-border-radius: 3px 3px 3px 3px;
    display: block;
    height: 24px;
    line-height: 24px;
    padding: 4px 0 4px 10px;
    text-decoration: none;
}
a.slMenuItem_depth1:link, a.slMenuItem_depth1:visited {
    color: #5E7830;
}
a.slMenuItem_depth1:hover {
    background-position: 100% -32px;
    color: #26370A;
}
a.dhtmlgoodies_activeItem {
    background-position: 100% -64px;
    color: #26370A;
}
a.slMenuItem_depth2 {
    padding: 5px 0;
}
.slideMenuDiv2 li {
   
    border-width: 0 !important;
    margin: 2px 0 !important;
    padding: 0 !important;
}

.slideMenuDiv2 li a:hover {
    background: none repeat scroll 0 0 #EAEAEA;
}

</style>	


<script type="text/javascript" src="js/menu.js"></script> 



<div id="leftMenu"> 
		<!-- START OF MENU --> 
		<div id="dhtmlgoodies_slidedown_menu"> 
			<ul> 
			
				<li id='menu0'><a href="#" ><img src="images/home.png"   align="left">Home</a>
					<ul> 
						<li><a href="main.php"><img src="images/1rightarrow.png" align="left">Dashboard</a></li> 
						<li><a href="ppc-admin-to-do.php"><img src="images/1rightarrow.png" align="left">TO DO List</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=0'"><img   border="0" src="images/help.png">Need some help ?</a></li>
						<li style="display:none"><a href="clear-cache.php">Clear Cache</a></li> 
						 
					</ul> 
				</li> 
				
				<li id='menu1' <?php if($single_account_mode==0) { ?> style="display:none" <?php } ?>><a href="#" ><img src="images/user.png"   align="left">User Accounts</a> 
					<ul> 
						<li><a href="manage-users.php"><img src="images/1rightarrow.png" align="left">Manage Users</a></li> 
						<li><a href="search-users.php"><img src="images/1rightarrow.png" align="left">Search Users</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=1'"><img   border="0" src="images/help.png">Need some help ?</a></li>
						 
						<li style="display:none"><a href="view-user-profile.php">Profile</a></li> 
						<li style="display:none"><a href="approve-user.php">Approve</a></li> 
						<li style="display:none"><a href="reject-user.php">Reject</a></li> 
						<li style="display:none"><a href="confirm-user-status.php">Change Status</a></li> 
						<li style="display:none"><a href="change-user-status.php">Block/Activate Both</a></li> 
						<li style="display:none"><a href="change-common-user-status.php">Approve/Reject only one account</a></li> 
						
						
					</ul> 
				</li> 
				
				<li id='menu2'><a href="#" ><img src="images/advertiser.png"   align="left"  >Advertiser Accounts</a> 
					<ul> 
						<li><a href="ppc-view-users.php"><img src="images/1rightarrow.png" align="left">Manage Advertisers</a></li> 
						<li><a href="ppc-search.php"><img src="images/1rightarrow.png" align="left">Search Advertisers</a></li> 
						<li><a href="ppc-admin-user-profit-statistics.php"><img src="images/1rightarrow.png" align="left">Advertiser Statistics</a></li> 
						<li><a href="ppc-admin-payment-deposit-history.php"><img src="images/1rightarrow.png" align="left">Advertiser Payments</a></li> 
						<li><a href="export-adv.php"><img src="images/1rightarrow.png" align="left">Export Advertisers</a></li> 
						<li><a  href="javascript:document.location.href='help_admin.php?id=2'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="view_profile.php">Profile</a></li> 
						<li style="display:none"><a href="ppc-change-user-status.php">Change Status</a></li> 
						<li style="display:none"><a href="add-advertiser-fund.php">Add Fund</a></li> 
						<li style="display:none"><a href="add-advertiser-fund-action.php">Add Fund Action</a></li> 

						<li style="display:none"><a href="ppc-advertiser-request-view-details.php">Payment Details</a></li> 
						<li style="display:none"><a href="ppc-advertiser-request-change-status.php">Payment Change Status</a></li> 
						<li style="display:none"><a href="ppc-advertiser-request-approve.php">Payment Approve</a></li> 
						<li style="display:none"><a href="ppc-advertiser-request-completed-status.php">Payment Completion</a></li> 
						<li style="display:none"><a href="export-adv-action.php">Advertiser Exporting</a></li> 

						
					</ul> 
				</li> 
				
				<li id='menu3'><a href="#" ><img src="images/publisher.png"   align="left">Publisher Accounts</a> 
					<ul> 
						<li><a href="ppc-view-publishers.php"><img src="images/1rightarrow.png" align="left">Manage Publishers</a></li> 
						<li><a href="search-publisher.php"><img src="images/1rightarrow.png" align="left">Search Publishers</a></li> 
						<li><a href="ppc-admin-publisher-profit-statistics.php"><img src="images/1rightarrow.png" align="left">Publisher Statistics</a></li> 
						<li><a href="ppc-publisher-withdrawal-history.php"><img src="images/1rightarrow.png" align="left">Publisher Withdrawals</a></li> 
						<li><a href="export-pub.php"><img src="images/1rightarrow.png" align="left">Export Publishers</a></li>
						 <li><a  href="javascript:document.location.href='help_admin.php?id=3'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="ppc-approve-publisher.php">Approve</a></li> 
						<li style="display:none"><a href="ppc-disapprove-publisher.php">Reject</a></li> 
						<li style="display:none"><a href="ppc-change-publisher-status.php">Change Status</a></li> 
						<li style="display:none"><a href="view_profile_publishers.php">Profile</a></li> 

						<li style="display:none"><a href="ppc-warn-publisher-ad.php">Fraud Warning</a></li> 
						<li style="display:none"><a href="ppc-block-publisher-ad.php">Fraud Blocking</a></li> 

						<li style="display:none"><a href="ppc-publisher-request-view-details.php">Withdrawal Details</a></li> 
						<li style="display:none"><a href="ppc-publisher-request-change-status.php">Withdrawal Change Status</a></li> 
						<li style="display:none"><a href="ppc-publisher-request-approve-action.php">Withdrawal Approve Action</a></li> 

						<li style="display:none"><a href="adunit-click-statistics.php">Adunit Statistics</a></li> 
						<li style="display:none"><a href="export-pub-action.php">Advertiser Exporting</a></li> 
						
					</ul> 
				</li>  
				
				<li id='menu4' ><a href="#"><img src="images/text.png"   align="left">Ads By Users</a>  
					<ul> 
						<li><a href="ppc-view-ads.php"><img src="images/1rightarrow.png" align="left">Manage Ads</a></li> 
						<li><a href="ppc-search-ad.php"><img src="images/1rightarrow.png" align="left">Search By ID</a></li> 
						<li><a href="ppc-keyword-search.php"><img src="images/1rightarrow.png" align="left">Search By Keyword</a></li> 
						<li><a href="ppc-admin-click-profit-statistics.php"><img src="images/1rightarrow.png" align="left">Ad Statistics</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=4'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						
						<li style="display:none"><a href="ppc-delete-ad.php">Delete</a></li> 
						<li style="display:none"><a href="ppc-change-ad-status.php">Change Status</a></li> 
						<li style="display:none"><a href="ppc-view-keywords.php">Ad detail</a></li> 
						
						
						
						
						
					</ul> 
				</li> 
				<li id='menu5' ><a href="#"><img src="images/keywords.png"   align="left">System Keywords</a>  
					<ul> 
						<li><a href="system-keywords.php"><img src="images/1rightarrow.png" align="left">Manage Keywords</a></li> 
												<li><a href="add-keywords.php"><img src="images/1rightarrow.png" align="left">Add Keywords</a></li> 
						<li><a href="keyword-statistics.php"><img src="images/1rightarrow.png" align="left">Keyword Statistics</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=5'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						
						<li style="display:none"><a href="delete-keywords.php">Delete keyword</a></li> 
						<li style="display:none"><a href="edit-keyword.php">Edit keyword</a></li> 
						
						
						
						
						
					</ul> 
				</li> 
				
				<li id='menu6' ><a href="#"><img src="images/publicservice.png"   align="left">Public Service Ads</a>  
					<ul> 
						<li><a href="ppc-new-public-text-ad.php"><img src="images/1rightarrow.png" align="left">Create Text Ad</a></li> 
						<li><a href="ppc-new-public-image-ad.php"><img src="images/1rightarrow.png" align="left">Create Banner Ad</a></li> 
						<li><a href="ppc-new-public-catalog-ad.php"><img src="images/1rightarrow.png" align="left">Create Catalog Ad</a></li> 
						<li><a href="ppc-view-public-ads.php"><img src="images/1rightarrow.png" align="left">Manage Ads</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=6'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="ppc-new-public-text-add-action.php">Create Text Ad Action</a></li> 
						<li style="display:none"><a href="ppc-new-public-image-ad-action.php">Create Banner Ad Action</a></li> 
						<li style="display:none"><a href="ppc-new-public-catalog-ad-action.php">Create Catalog Ad Action</a></li> 
						<li style="display:none"><a href="ppc-edit-service-ad.php">Edit</a></li> 
						<li style="display:none"><a href="ppc-edit-public-service-ad-action.php">Edit Action</a></li> 
						<li style="display:none"><a href="ppc-change-service-ad-status.php">Block/Activate</a></li> 
						<li style="display:none"><a href="ppc-delete-service-ad.php">Delete</a></li> 
						
						
						
						
					</ul> 
				</li> 
				
				<li id='menu7' ><a href="#"><img src="images/adblock.png"   align="left">Ad Block Settings</a>  
					<ul> 
						<li><a href="ppc-manage-ad.php"><img src="images/1rightarrow.png" align="left">Manage Ad Blocks</a></li> 
						<li><a href="ppc-manage-banner.php"><img src="images/1rightarrow.png" align="left">Banner Dimensions</a></li> 
						<li><a href="ppc-manage-catalog.php"><img src="images/1rightarrow.png" align="left">Catalog Dimensions</a></li> 
						<li><a href="ppc-manage-publisher-credit.php"><img src="images/1rightarrow.png" align="left">Credit Texts</a></li> 
						<li><a href="ppc-edit-credittext-bordercolor.php"><img src="images/1rightarrow.png" align="left">Credit Text/Border  <br />Color Combinations</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=7'"><img   border="0" src="images/help.png">Need some help ?</a></li>
						
						<li style="display:none"><a href="ppc-new-ad-block.php">Create Ad Block</a></li> 
						<li style="display:none"><a href="ppc-show-ad-block.php">Edit Ad Block</a></li> 
						<li style="display:none"><a href="ppc-show-inline-ad-blocks.php">Edit Inline Ad Block</a></li> 
						<li style="display:none"><a href="ppc-activate-ad.php">Block/Activate Ad Block</a></li> 
						<li style="display:none"><a href="ppc-ad-delete.php">Delete Ad Block</a></li> 
						

						<li style="display:none"><a href="ppc-new-banner-dimension.php">New Banner</a></li> 
						<li style="display:none"><a href="ppc-new-banner-dimension-action.php">New Banner Action</a></li> 
						<li style="display:none"><a href="ppc-banner-edit.php">Edit Banner</a></li> 
						<li style="display:none"><a href="ppc-banner-edit-action.php">Edit Banner Action</a></li> 
						<li style="display:none"><a href="ppc-banner-dimension-delete.php">Delete Banner</a></li> 

						<li style="display:none"><a href="ppc-catalog-edit.php">Edit Catalog</a></li> 
						<li style="display:none"><a href="edit-catalog-action.php">Edit Catalog Action</a></li> 
						
						<li style="display:none"><a href="add-new-catalog.php">Add Catalog</a></li> 
						<li style="display:none"><a href="add-catalog-action.php">Add Catalog Action</a></li>
						<li style="display:none"><a href="delete-catalog-confirm.php">Add Catalog Action</a></li>
						

						<li style="display:none"><a href="ppc-edit-publisher-credit.php">Edit Credit</a></li> 
						<li style="display:none"><a href="ppc-edit-publisher-credit-action.php">Edit Credit Action</a></li> 
						<li style="display:none"><a href="ppc-delete-publisher-credit.php">Delete Credit</a></li> 
						<li style="display:none"><a href="ppc-add-new-publisher-credit-text.php">Add Credit</a></li> 
						<li style="display:none"><a href="ppc-add-new-publisher-credit-action.php">Add Credit Action</a></li> 
						
						
						<li style="display:none"><a href="ppc-credittext-bordercolor.php">Add Credit/Border Color</a></li> 
						<li style="display:none"><a href="ppc_credittext_bordercolor.php">Add Credit/Border Color Action</a></li> 
						<li style="display:none"><a href="ppc-delete-credittext-bordercolor.php">Delete Credit/Border</a></li> 
						<li style="display:none"><a href="ppc-change-credittext-bordercolor.php">Edit Credit/Border Color</a></li> 
						<li style="display:none"><a href="ppc_update_credittext_bordercolor.php">Edit Credit/Border Color Action</a></li> 
						
						
						
					</ul> 
				</li> 
				
				<li id='menu8' ><a href="#"><img src="images/adcode.png"   align="left">Admin's Ad Display</a>  
					<ul> 
						<li><a href="ppc-admin-new-ad-unit.php"><img src="images/1rightarrow.png" align="left">Create Ad Unit</a></li> 
						<li><a href="ppc-manage-ad-units.php"><img src="images/1rightarrow.png" align="left">Manage Ad Units</a></li> 
						<li><a href="xml-api-admin.php"><img src="images/1rightarrow.png" align="left">XML API For Ads</a></li> 
						<li><a href="ppc-adunit-click-statistics.php"><img src="images/1rightarrow.png" align="left">Adunit Statistics</a></li> 
						<li><a href="ppc-admin-page-visit.php"><img src="images/1rightarrow.png" align="left">Traffic Analysis</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=8'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="ppc-ad-unit-delete.php">Delete Ad unit</a></li> 
						<li style="display:none"><a href="ppc-admin-modify-ad-unit.php">Edit Ad unit</a></li> 
						<li style="display:none"><a href="ppc-admin-modify-wap-ad-unit.php">Edit Wap Ad unit</a></li> 
						<li style="display:none"><a href="ppc-admin-modify-inline-ad-unit.php">Edit Inline Ad unit</a></li> 
						<li style="display:none"><a href="admin-adunit-click-statistics.php">Ad unit statistics</a></li> 


					</ul> 
				</li> 

				<li id='menu9' ><a href="#"><img src="images/settings.png"   align="left">Essential Settings</a>  
					<ul> 
						<li><a href="basic-settings-tab.php"><img src="images/1rightarrow.png" align="left">Basic Settings</a></li> 
						<li><a href="monetory-settings-tab.php"><img src="images/1rightarrow.png" align="left">Monetory Settings</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=9'"><img   border="0" src="images/help.png">Need some help ?</a></li>
					</ul> 
				</li> 
				
				<li id='menu10' ><a href="#"><img src="images/coupon.png"   align="left">Coupons</a>  
					<ul> 
						<li><a href="new-coupon.php"><img src="images/1rightarrow.png" align="left">Create Coupon</a></li> 
						<li><a href="manage-coupons.php"><img src="images/1rightarrow.png" align="left">Manage Coupons</a></li>
						 <li><a  href="javascript:document.location.href='help_admin.php?id=10'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="new-coupon-action.php">New Coupon Action</a></li> 
						<li style="display:none"><a href="coupon-details.php">Coupon Details</a></li> 
						<li style="display:none"><a href="edit-coupon.php">Edit Coupon</a></li> 
						<li style="display:none"><a href="edit-coupon-action.php">Edit Coupon Action</a></li> 
						
						
					</ul> 
				</li> 

				<li id='menu11' ><a href="#"><img src="images/sitecontent.png"   align="left" >Site Content</a>  
					<ul> 
						<li><a href="meta-data-settings.php?type=0"><img src="images/1rightarrow.png" align="left">Meta Data</a></li> 
						<li><a href="terms-conditions-settings.php?type=0"><img src="images/1rightarrow.png" align="left">Terms & Conditions</a></li> 
						<li><a href="ppc-page-settings.php"><img src="images/1rightarrow.png" align="left">Theme & Logo Settings</a></li> 
						<li><a href="url-name-settings.php"><img src="images/1rightarrow.png" align="left">SEO Urls Configuration</a></li> 
						<li><a href="create-sitemap.php"><img src="images/1rightarrow.png" align="left">Sitemap Generator</a></li> 
						<li><a href="affiliate-settings.php"><img src="images/1rightarrow.png" align="left">Inoutscripts Affiliate Id</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=11'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="url-name-settings-action.php">SEO Urls Action</a></li> 
						
					</ul> 
				</li> 
				
				<li id='menu12'><a href="#" ><img src="images/announce.png"   align="left" >Announcements</a> 
					<ul> 
						<li><a href="create-messages.php"><img src="images/1rightarrow.png" align="left">Create Message</a></li> 
						<li><a href="manage-message.php"><img src="images/1rightarrow.png" align="left">Manage Messages</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=12'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="create-message-action.php">Create Action</a></li> 						
						<li style="display:none"><a href="edit-message.php">Edit</a></li> 
						<li style="display:none"><a href="edit-message-action.php">Edit Action</a></li> 
						<li style="display:none"><a href="change-message-status.php">Change Status</a></li> 
						
						
					</ul> 
				</li>

				<li id='menu13' ><a href="#"><img src="images/mail.png"   align="left" >Email Templates</a>  
					<ul> 
						<li><a href="ppc-mail.php?type=19"><img src="images/1rightarrow.png" align="left">Advertiser Templates</a></li> 
						<li><a href="publisher-mail.php?type=20"><img src="images/1rightarrow.png" align="left">Publisher Templates</a></li> 
						<li><a href="common-mail.php?type=25"><img src="images/1rightarrow.png" align="left">User Templates <br />(Single-Sign-On Mode)</a></li> 
						<li><a href="ad-mail.php?type=9"><img src="images/1rightarrow.png" align="left">Ad Templates</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=13'"><img   border="0" src="images/help.png">Need some help ?</a></li>

					</ul> 
				</li> 
				

				<li id='menu14' ><a href="#"><img src="images/language.png"   align="left" >Languages</a>  
					<ul> 
						<li><a href="ppc-create-language.php"><img src="images/1rightarrow.png" align="left">Add Language</a></li> 
						<li><a href="ppc-manage-language.php"><img src="images/1rightarrow.png" align="left">Manage Languages</a></li> 
						<li><a href="ppc-edit-messages.php"><img src="images/1rightarrow.png" align="left">Edit Messages</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=14'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="ppc-create-language-action.php">Create Language Action</a></li>
						<li style="display:none"><a href="ppc-delete-language.php">Delete Language</a></li>
						<li style="display:none"><a href="ppc-block-language.php">Block Language</a></li>
						<li style="display:none"><a href="ppc-activate-language.php">Block Language</a></li>
						<li style="display:none"><a href="ppc-edit-language.php">Edit Language</a></li>
						<li style="display:none"><a href="ppc-edit-language-action.php">Edit Language Action</a></li>
						<li style="display:none"><a href="ppc-edit-message-action.php">Edit Message Action</a></li>
					</ul> 
				</li> 
				
				<li id='menu15' ><a href="#"><img src="images/referral.png"   align="left" >Referral System</a>  
					<ul> 
						<li><a href="affiliate-payment-settings.php"><img src="images/1rightarrow.png" align="left">Referral Settings</a></li> 
						<li><a href="affiliate-banners.php"><img src="images/1rightarrow.png" align="left">Referral Banners</a></li> 
						<li><a href="ppc-referral-statistics.php"><img src="images/1rightarrow.png" align="left">Referral Statistics</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=15'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="add-new-affiliate-banner.php">Add Banner</a></li>
						<li style="display:none"><a href="add-new-affiliate-banner-action.php">Add Banner Action</a></li>
						
						<li style="display:none"><a href="edit-affiliate-banner.php">Edit Banner</a></li>
						<li style="display:none"><a href="edit-affiliate-banner-action.php">Edit Banner Action</a></li>
						<li style="display:none"><a href="delete-affiliate-banner.php">Delete Banner</a></li>
						
						<li style="display:none"><a href="referral-traffic-sources.php">Traffic Statistics</a></li>
						
						
						
						
					</ul> 
				</li> 
				
				<li id='menu16' ><a href="#"><img src="images/load.png"   align="left" >Load Balancing</a>  
					<ul> 
						<li><a href="new-server.php"><img src="images/1rightarrow.png" align="left">New Server</a></li>
						<li><a href="manage-loadbalance.php"><img src="images/1rightarrow.png" align="left">Manage Servers</a></li> 
						<li><a href="manage-server-configuration.php"><img src="images/1rightarrow.png" align="left">Server Allotment</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=16'"><img   border="0" src="images/help.png">Need some help ?</a></li>

						<li style="display:none"><a href="new-server-action.php">New Server Action</a></li>
						<li style="display:none"><a href="edit-server.php">Edit Server</a></li>
						<li style="display:none"><a href="edit-server-action.php">Edit Server Action</a></li>
						<li style="display:none"><a href="new-server-configuration-action.php">Server Allotment Action</a></li>
						<li style="display:none"><a href="check-server.php">Check Server Status</a></li>
						
						
						
						
						
					</ul> 
				</li> 
					
				<li id='menu17' ><a href="#"><img src="images/fraud.png"   align="left" >Fraud Control</a>  
					<ul> 
					
						<li><a href="click-analysis.php"><img src="images/1rightarrow.png" align="left">Click Analysis</a></li> 
						<li><a href="fraud-analysis.php"><img src="images/1rightarrow.png" align="left">Fraud analysis</a></li> 
						<li><a href="view-traffic-enabled-publishers.php"><img src="images/1rightarrow.png" align="left">Suspicious  Publishers</a></li> 
						<li><a href="ppc-ctr-fraud-detection.php"><img src="images/1rightarrow.png" align="left">Check Publisher CTR</a></li> 
						<li><a href="delete-fraud-click-data.php"><img src="images/1rightarrow.png" align="left">Delete Fraud Click Data</a></li>
						 <li><a  href="javascript:document.location.href='help_admin.php?id=17'"><img   border="0" src="images/help.png">Need some help ?</a></li>

					</ul> 
				</li> 
				
				<li id='menu18' ><a href="#"><img src="images/system.png"   align="left" >System Status</a>  
					<ul> 
						<li><a href="ppc-time-profit-statistics.php"><img src="images/1rightarrow.png" align="left">Overall Statistics</a></li> 
						<li><a href="show-statistics.php"><img src="images/1rightarrow.png" align="left">Verify Statistics</a></li> 
						<li><a href="ppc-overall-profit-statistics.php"><img src="images/1rightarrow.png" align="left">Revenue Details</a></li> 
						<li><a href="all-payments.php"><img src="images/1rightarrow.png" align="left">Cash Flow Reports</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=18'"><img   border="0" src="images/help.png">Need some help ?</a></li>
					</ul> 
				</li> 
				
				
							 
			 <?php
				if($site_targeting==1)
				{
				?>
				
				<li id='menu19' ><a href="#"><img src="images/site_dir.png"    align="left" height="25" width="25" >Publishing Urls</a>  
					<ul> 
						<li><a href="manage-ppc-publishing-urls.php"><img src="images/1rightarrow.png" align="left">Manage urls</a></li> 
						 <li><a  href="javascript:document.location.href='help_admin.php?id=19'"><img   border="0" src="images/help.png">Need some help ?</a></li>
					</ul> 
				</li> 

				  
			 <?php	} ?>	
				
				
				
				
				
				
				<li id='menu20' ><a href="#"><img src="images/logo-icon.png" width="25"   align="left" >Partner's Logos</a>  
					<ul> 
						<li><a href="create_logos.php"><img src="images/1rightarrow.png" align="left">Upload Logo</a></li> 
						<li><a href="manage_logos.php"><img src="images/1rightarrow.png" align="left">Manage Logos</a></li> 
						<li><a  href="javascript:document.location.href='help_admin.php?id=20'"><img   border="0" src="images/help.png">Need some help ?</a></li>
					</ul> 
				</li> 
			
			 
			 <li id='menu21' ><a href="#"><img src="images/template.png"   align="left" height="25" width="25" >Text Ad Templates</a>  
					<ul> 
						<li><a href="ppc-ad-template.php"><img src="images/1rightarrow.png" align="left">Create Ad Template</a></li> 
						
						<li><a href="view-templates.php"><img src="images/1rightarrow.png" align="left">Manage Ad Templates</a></li> 
						<li><a  href="javascript:document.location.href='help_admin.php?id=21'"><img   border="0" src="images/help.png">Need some help ?</a></li>
						 <li style="display:none"><a href="ppc-new-ad-template-action.php">New Template</a></li>
						 <li style="display:none"><a href="ppc-change-ad-template-status.php">Block/Activate</a></li>
						 <li style="display:none"><a href="ppc-delete-ad-template.php">Delete</a></li>
						 
					</ul> 
				</li> 
				<li id='menu22'><a href="#"><img src="images/db_backup.png"   align="left" >Backup Section</a>  
					<ul> 
						<li><a href="export_database.php"><img src="images/1rightarrow.png" align="left">Export DataBase</a></li> 
						<li><a  href="javascript:document.location.href='help_admin.php?id=22'"><img   border="0" src="images/help.png">Need some help ?</a></li>
		
					</ul> 
				</li> 
					<?php if($chat_visible_status!=0)
					{
					?>
 			  <li id='menu23'><a href="#"><img src="images/chat-icon.png"   align="left" >Chat Section</a>  
					<ul> 
						<li><a href="manage_chat_users.php"><img src="images/1rightarrow.png" align="left">Manage Users</a></li> 
						<li><a href="chat_setting.php"><img src="images/1rightarrow.png" align="left">Chat Setting</a></li> 
						<li><a  href="javascript:document.location.href='help_admin.php?id=23'"><img   border="0" src="images/help.png">Need some help ?</a></li>
					
					</ul> 
				</li> 
				<?php
				}
				?>
				  <li id='menu24'><a href="#"><img src="images/slideIcon.png"   align="left" >Slide Images</a>  
					<ul> 
						<li><a href="create_slide_image.php"><img src="images/1rightarrow.png" align="left">Add Slide Image</a></li> 
						<li><a href="manage_slide_image.php"><img src="images/1rightarrow.png" align="left">Manage Slide Image</a></li> 
						<li><a  href="javascript:document.location.href='help_admin.php?id=24'"><img   border="0" src="images/help.png">Need some help ?</a></li>
					
					</ul> 
				</li> 
				<!--<li ><a href="#">Level 0</a>  
					<ul> 
						<li><a href="#">Level 1</a> 
							<ul> 
								<li><a href="#">Level 2</a> 
									<ul> 
										<li><a href="http://www.nesote.com">Level 3</a></li> 
										<li><a href="http://www.nesote.com">Level 3</a></li> 
										<li><a href="http://www.nesote.com">Level 3</a></li> 
									</ul> 
								</li> 
								<li><a href="http://www.nesote.com">Level 2</a></li> 
								<li><a href="#">Level 2</a> 
									<ul> 
										<li><a href="#">Level 3</a> 
											<ul> 
												<li><a href="http://www.nesote.com">Level 4</a></li> 
												<li><a href="http://www.nesote.com">Level 4</a></li> 
											</ul> 
										</li> 
										<li><a href="http://www.nesote.com">Level 3</a></li> 
										<li><a href="http://www.nesote.com">Level 3</a></li> 
									</ul> 
								</li> 
							</ul> 
						</li> 
						<li><a href="http://www.nesote.com">Level 1</a></li> 
						<li><a href="http://www.nesote.com">Level 1</a></li> 
						<li><a href="http://www.nesote.com">Level 1</a></li> 
						<li><a href="http://www.nesote.com">Level 1</a></li> 
 
					</ul> 
				</li> -->
			</ul> 
		</div> 
		<!-- END OF MENU --> 

<?php  $browser_url= substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],"/")+1);
 ?>

<script type="text/javascript"> 
<?php if($browser_url=='help_admin.php')
{?>
		initSlideDownMenu(<?php echo $_GET['id']; ?>);
<?php
}
else
{?>
		initSlideDownMenu(-1);
<?php
}
?>		
		</script> 
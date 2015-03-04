<?php
include("config.inc.php");
include("admin.header.inc.php");
?>
<div  class="help" >
  
    <?php 

$id=1;
if(isset($_GET['id']))
	$id=getSafeInteger('id','g');
if($id==0)
{?>
<p><strong class="heading">Admin Home</strong></p>
<p>
There are mainly two areas to check in the home section of your adserver.</p>
<ul>
<li><strong>Dashboard :</strong></li>

<p>The dashboard gives an idea about how your system is doing. In the dashboard you can find the statistics of clicks, impressions, CTR and revenue. You can also see the geographical distribution of clicks from here. Additionally it gives information about active advertiser and publisher accounts in the system. </p>
<p>To ensure a smooth performance,  the system also displays certain messages based on values of various parameters . Some of these messages are just notifications while some are warnings (in red color). The warnings require immediate attention from your side. </p>
<li><strong>TO DO List :</strong></li>


<p>As the name suggests, this list contains a list of things you must do periodically or as and when a request arises. The various activities to be done are approving pending account requests, approving pending payment/withdrawal requests, updating geo-ip data etc. </p>
</ul>


  <?php
}
if($id==1)
{
?>

<p>  <strong class="heading">User accounts of adserver in a glance</strong></p>
  <p> There are basically two types of user accounts in adserver, viz. advertisers and publishers. Advertisers are your sources of funds while publishers contribute to the strength,  depth and breadth of your network. Adserver allows you to set  dual as well as single sign-on options for these accounts. Even in single-sign-on the system internally keeps two separate advertiser and publisher accounts to provide the flexibility of switching b/w the sign-on modes anytime. But it is always recommended that you decide on your desired sign-on mode and set it before you go live.</p>
  <p>A user on registration will be placed into 'Email not verified' status. On confirming his email his advertiser account and publisher account will be set to default status as configured under ' Basic Settings'. For an 'Email not verified' user, admin can approve or reject advertiser and publisher account severally or together . Similary for a user with both accounts active, admin can block both accounts severally or together and vice versa in case of a user with both accounts blocked. If a user has both accounts in different status, then admin can modify advertiser and publisher status severally only. In any case a user can access his control panel only if he has either of the accounts active. </p>
  <p><u>Conversion from dual-sign-on to single-single-on</u>: <br />
  SSO (Single-Sign-On) is available from version 5.3 onwards. If you  have upgraded from an older version with dual-sign-on, still you can enable SSO as mentioned earlier. In such cases the system will go to a migration mode where old users will continue to run in dual-sign-on mode unless they opt to convert to SSO. New registrations and those who have migrated can use the benefits of SSO. Under migration the single-sign-on users users will login to the common control panel and others to their respective control panels. Conversion of a user from dual-sign-on to SSO can happen in following ways.</p>
  <p>&rsaquo; Users who already have two separate accounts for advertiser and publisher can merge those and choose a single login from their control panel. <br />
  &rsaquo; A user with only advertiser account or only publisher account  can request for a new publisher account or advertiser account respectively.</p>
  <p>There might be advertiser/publisher accounts which were blocked while system was running in dual-sign-on mode. You may delete such accounts. 
    Once all the accounts accounts are migrated , the system will disable login options of individual accounts from the public pages. </p>
	
  <p><u>Conversion from single-sign-on to dual-single-on</u>: <br />
     There is nothing to take care here. You just need to disable single sign on from under Basic Settings. The users will have to login separately to their accounts after this. Common login/registration/control and related pages will get disabled.
  </p>
  
  <ul >
<li><strong>Manage Users: </strong></li>
<p>This section will list down SSO accounts in system . If your system is migrating from dual-sign-on to single-sign-on there can differences in number of SSO user accounts and individual advertiser and publisher accounts. From here you can control the advertiser status and publisher status of a user account. You can also delete accounts from the system. If required you can also login to his account from here.
</p>
<li><strong>Search Users:</strong></li>
		<p>You can search for users using username or email and view the complete profile of the user. Also you can do the operations mentioned above from the profile page.
        </p>
  </ul>

  
    <?php 
}
if($id==2)
{

?>
   <p> <strong class="heading">Advertisers</strong>  </p>
  <p>  This section shows the complete details  of advertiser accounts in the system including their ads, payments and statistics.   An advertiser upon registration will be in 'Email not verified' state until they click on verification link in the email. On clicking confirmation link, his status becomes becomes active/pending based on the value of  default status of advertiser accounts as configured under Basic Settings. </p>
  <ul>
<li><strong>Manage Advertisers:</strong> </li>
		<p>Here you can view the advertisers in the system. You have the option to filter them by status and country. If you are migrating to SSO you can also filter them based on SSO status.  The various operations you can do on advertiser accounts are given below.		  </p>
		<p> <u>View Profile</u>: Here you can  view the complete details of the advertiser, including ads posted by the advertiser, click statistics of the advertiser and fund history. From here you may  change the status of his account, ads or payments. </p>
		<p><u>Login</u>: Admin can login to an active advertiser's control panel by clicking the login link.</p>
		<p><u>Send Mail</u>: You can send a personal mail to the advertiser from here.</p>
		<p><u>Add fund</u>: From here you can add fund to the advertiser. You can add funds either to his account balance or his bonus balance. You may provide proper comments while adding funds manually.</p>
		<p><u>Delete</u>: If SSO is disabled, you can delete individual advertiser accounts . When the system is under migration from dual-sign-on to single-sign-on then also you can delete accounts which do not have  single-single-on .</p>
		
			<p> <u>Change Status</u>: If an advertiser has single-sign-on then all status modification operations are done from this single page where you can view the  current advertiser status and publisher status of his account and configure new status.  </p>
			
            <p><strong>Note :</strong> The following operations are available 	for accounts without single-sign-on.
		    </p>
            <p> <u>Block</u>: This option is available for an active advertiser. If you try to block the advertiser you will redirected to a confirmation page where you have the option of editing the default "block email template". You may edit the email and proceed. The advertiser will be blocked only after sending the mail. </p>

		<p>		  <u> Activate</u>:  This option is available for a blocked advertiser. If you try to activate the advertiser you will redirected to a confirmation page where you have the option of editing the default "activate email template". You may edit the email and proceed. The advertiser will be activated only after sending the mail. </p>
		
		
		<p><u>Approve</u>:  This option is available for a pending advertiser. If you try to approve the advertiser you will redirected to a confirmation page where you have the option of editing the default "approve email template". You may edit the email and proceed. The advertiser will be approved only after sending the mail.</p>
		
		<p> <u> Reject</u>:  This option is available for a pending advertiser. If you try to reject the application of the advertiser you will redirected to a confirmation page where you have the option of editing the default "rejecting email template". You may edit the email and proceed. The advertiser's application  will be rejected only after sending the mail. </p>
		
		
		
		<li><strong>Search Advertisers :</strong></li>
		<p>You can search for an advertiser using his username or email and check his profile to get details about his account, status, ads and statistics.

		</p>
		<li><strong> Advertiser  statistics :</strong></li>
		<p>	It shows the click statistics of each advertiser including details like clicks received, total click value, publisher share, referral share and your share.</p>

  <li> <strong>Advertiser payment history :</strong></li> 

		<p>This section shows the  complete payment history of all the advertisers. You may filter payments by type or status. The various types of payments are given below.
    </p>
		<p><u>Paypal payments</u>: </p>
			<p>This section shows all paypal payments which got completed successfully.</p>
			<p><u>Authorize.net payments</u>: </p>
			<p>This section shows all successful  payments done through authorize.net gateway.</p>
	        <p><u>Check/Bank payments</u>:    </p>
        <p>Advertisers will be able to make check/bank payments only if the check/bank payment is enabled by you. If the advertiser has applied a check/bank payment, the status will be "pending" initially. You can view the details of the payment and approve the same if the details are correct; the status of the request becomes "approved" once you approve. But the payment is not yet completed. You can mark it as "completed" after the payment cleared, or mark as "rolled back" if the check is not cleared or bank transaction failed. The money is credited in advertiser account only after completing the request. If any pending request is not valid you may reject the same.          </p>
        <p>Also note that admin can convert the local currency check/bank payments made by advertiser to system currency while marking the payment as completed. </p>
        <p><u>Transfer payments</u>: </p>
			<p>These are transfers done from publisher accounts to advertiser accounts by users who have single-sign-on.</p>
		<p><u>Other payments</u>:		</p>
        <p>It shows the history of bonus deposits as well as the history of funds added from admin area. Please note that if you have upgraded from a version prior to Version 2 it will not show the account opening bonus history of existing advertisers. </p>
  </ul>
		
 
    <?php
}		
if($id==3)
{
?>
    
  <p><strong  class="heading">Publishers</strong>
  </p>   This section shows the complete details  of publisher accounts in the system including their adunits, withdrawals and statistics.   A publisher upon registration will be in 'Email not verified' state until they click on verification link in the email. On clicking confirmation link, his status becomes becomes active/pending based on the value of  default status of publisher accounts as configured under Basic Settings. 
    
  </p>
  <ul>
  <li> <strong>Manage Publishers :</strong></li> 

	<p>Here you can view the publishers in the system. You have the option to filter them by status and country. If you are migrating to SSO you can also filter them based on SSO status.  The various operations you can do on publisher accounts are given below. </p>
	
	
	<p> <u>View Profile</u>: Here you can  view the complete details of the publisher, including adunits, click statistics (valid as well as fraud)  and withdrawal history. From here you may  change the status of his account  or withdrawals. </p>
		<p><u>Login</u>: Admin can login to an active publisher's control panel by clicking the login link.</p>
		<p><u>Send Mail</u>: You can send a personal mail to the publisher from here.</p>
	
		<p> <u>  XML API</u>: From here you can enable/disable xml api for the publishers. </p>
		<p> <u>  Traffic Analysis</u>: If you want to monitor traffic quality of a publisher you may enable it here. The system will collect the urls where his ads are displayed, the traffic details to those pages along with impression and click statistics. Please note that this operation consumes more cpu and memory; so use this only for suspicious publishers. </p>
		<p> <u>  Warn</u>: If you have noticed that a publisher is frequently doing fraud activity, you may issue a warning to him. </p>
		<p> <u>  Captcha Status</u>: If bot click tracking is enabled and system has detected bot clicks from a publisher, then captcha verification will be automatically turned on for his clicks. You may manually  disable/enable the same from here.</p>
		<p> <u>  Premium Status </u>: From here you can enable/disable premium status for a publisher. If a publisher has premium status he will get a higher click share than other publishers. The premium click share percentage can be configured from 'Monetary Settings'. </p>
		<p> <u> Server Allotment</u>: If load-balancing is turned on you may change the default ad query server of a publisher from here. </p>

		<p><u>Delete</u>: If SSO is disabled, you can delete individual publisher accounts . When the system is under migration from dual-sign-on to single-sign-on then also you can delete accounts which do not have  single-single-on .</p>
		
			<p> <u>Change Status</u>: If a publisher has single-sign-on then all status modification operations are done from this single page where you can view the  current publisher status and publisher status of his account and configure new status.  </p>
			
            <p><strong>Note :</strong> The following operations are available 	for accounts without single-sign-on.</p>
			
		<p> <u>  Block</u>: You can block an active publisher from this section.  You will be redirected to a confirmation page where you have the option of editing the default "block email template". You may edit the email and proceed. The publisher will be blocked only after sending the mail. </p>

		<p><u>Activate</u>: You can activate a blocked publisher from here. You will be redirected to a confirmation page where you have the option of editing the default "activate email template". You may edit the email and proceed. The publisher will be activated only after sending the mail.</p>

		<p><u>Approve</u>: You can approve a pending publisher from here. You will be redirected to a confirmation page where you have the option of editing the default "approve email template". You may edit the email and proceed. The publisher will be approved only after sending the mail. </p>
		<p><u>Reject</u>: You can reject a pending application of a publisher from here. You will be redirected to a confirmation page where you have the option of editing the default "rejection email template". You may edit the email and proceed. The publisher will be rejected only after sending the mail.</p>
		
				<li><strong>Search Publishers :</strong></li>
		<p>You can search for a publishers using his username or email and check his profile to get details about his account, status, adunits and statistics.

		</p>
		<li><strong> Publisher  statistics :</strong></li>
		<p>	It shows the click statistics of each publisher including details like clicks received, total click value, publisher share and referral share .</p>

  <li> <strong>Publisher Withdrawal History :</strong></li> 

		<p>This section shows the  entire publisher withdrawal history of all the publishers. You may filter withdrawals by type or status. 	The various types of withdrawals are paypal withdrawals, bank withdrawals, check withdrawals and transfers(from publisher accounts to advertiser accounts by users who have single-sign-on).
		</p>
		<p>If the publisher has requested a  payment, the status will be "pending" initially. You can view the details of the payment. You may approve or reject requests from here. Additionally you may deny a request. When you deny a request , unlike a reject operation,  the requested amount is not credited back to publisher account. Except for transfer request, the approved withdrawals can be completed or rolled back. In case of a transfer, the request is automatically completed on approval.  </p>
		<p> 
		
		In case of paypal payment fill in the paypal transaction id and payer email. Once you have approved you may mark the request as completed/failed depending on the paypal transaction status.</p>
		<p> In the case of check payment you have to fill in the check number and payer information  upon approving the request. You can mark the request as "completed"  after the check is cleared, or mark as "rolled back" if the check is not cleared.		</p>
		<p>In the case of bank payment you have to fill in the transaction id and any additional payee information   upon approving the request. You can mark the request as "completed"  after the transaction is completed, or mark as "rolled back" if the transaction fails.		</p>
  </ul>
  
    <?php
}		
if($id==4)
{
?>		
     <p> <strong  class="heading">Manage Ads</strong>  </p>
  <p>In this section you can manage the ads created by your advertisers.  </p>
  <ul>
  <li> <strong>Manage Ads :</strong></li> 

		<p>This section shows all the  ads in the system. You can filter the ads based on status,type and target device. You can block/activate/delete the ads as well as keywords from here. You can also view the statistics of the ads from here.</p>
		
		<li><strong> Find ad by id :</strong></li> 

		<p>Here you can find an ad by it's id value in the database. You may use id to refer to an ad while communicating with an advertiser because an advertiser can also search for ads from his control panel by id.
		
	    </p>
		<li> <strong>Find ad by keyword :</strong></li>	

		<p>Here you can find an ad by its keyword .You will get assistance when you type each letter.

        </p>
		<li> <strong>Ad  Statistics :</strong></li>

			<p>This shows all the  statistics related to ads like total clicks, total impressions, click through rate, total click value, publisher share, referral share and your share. You can also view keyword based statistics for each ad.		
	        </p>
  </ul>	
  
    <?php
}		
if($id==5)
{
?>		
     <p> <strong  class="heading">Manage Keywords</strong>  </p>
  <p>In this section you can manage the  keywords in the system.  </p>
  <ul>
 		
		<li> <strong>System Keywords :</strong></li> 
		<p>All keywords in the system are listed in this section.  If you block or delete any keyword from here, it will affect all ads using the keyword. You will have to manually activate each new keyword from here, unless the automatic keyword approval is  enabled under 'Basic Settings'.</p>
		<li> <strong>Add Keywords :</strong></li> 
		<p>From here you can  add keywords so that your advertisers will get good suggestions while inputting keywords.</p>

			<li> <strong>Keyword  Statistics :</strong></li>

			<p>This page shows  statistics of keywords like total clicks, total impressions, and total click value. This page helps you to identify the top keywords in  your adserver.

            </p>
  </ul>	
  
    <?php
}		
if($id==6)
{
?>		
    
  <p>  <strong  class="heading">Public Service ads</strong> </p>
  <p>The public service ads are displayed when no paid ads are available. There are no keywords for the public service ads and only admin can create the public service ads.  If there are public service ads, we can ensure that the site will always show some ads. Also please note that statistics are not collected for public service ads. </p>
 <p>This system allows you to create public service text ads, banner ads and catalog ads from here. Like the paid ads, public service ads can also be targeted to wap/desktop  devices and a specific language. Also from the management section you may block/activate/edit/delete the public service ads. 
  </ul>
    <?php
}		
if($id==7)
{
?>
    
  <p> <strong  class="heading">Ad Display Blocks</strong>  </p>
  <p>You have a set of predefined ad blocks here which serve as the base for creating ad display units; you may create additional blocks as well. There are separate ad blocks for WAP devices and Desktops. Please note that there are 4 types of adblocks for WAP devices,viz, text only,banner only, catalog only and text/banner. In case of desktop targeted ad blocks you have 6 different types,viz, text only, banner only, catalog only, text/banner, inline text and inline catalog.
    
  </p>
  <ul>
  <li><strong>Create Ad Block :</strong></li>
	<p>Here you can create a new ad block. You can create an ad block by configuring different parameters given below.</p>
	<p>

			&#8226;Target Device: This indicates whether the ad block is WAP targeted or Desktop targeted.<br />
			
			&#8226;Ad block name: This is the name of the adblock. This name will be shown to publishers along with the block dimensions while creating adunits.<br />
			
			&#8226;Ad type: There are 4 options. Text only (show only text ads), Banner only(show only banner ads), Catalog only(show only catalog ads) and Text/Banner(show either text or banner)<br />
			
			&#8226;Allow for publishers: If you choose "No", the publishers cannot use this adblock for creating adunits.<br />
			
			&#8226;Width: This is the width of the adblock. Its value should be in pixels. This can be be specified only for text only and  catalog only ad blocks.<br />
			
			&#8226;Height: This is the height of the adblock. Its value should be in pixels. This can be be specified only for text only and  catalog only ad blocks.<br />

			&#8226;Banner size : This is applicable for banner only ad blocks and text/banner adblocks. You can choose an available banner dimension from the list. The height and width of banner dimension will be set for such  ad blocks. <br />
 
			&#8226;Catalog size: Here you can choose the catalog dimension from available list.  This can be be specified  for catalog only ad blocks.<br />

			&#8226;No. of Catalog ads: You can specify the number of catalog ads here. This can be be specified   for  catalog only ad blocks.<br />

			&#8226;Catalog alignment: You can specify the position of the image here. This can be be specified for catalog only ad blocks.<br />

			&#8226;Text Ad type : Here you can specify whether text ads should display title alone, title+description or title+description+display url. This is applicable for text only and text/banner ad blocks.<br />

			&#8226;No. of text ads: This is applicable for text only and text/banner ad blocks. Admin can select the maximum number of text ads in the adblock. (Minimum-1, Maximum-10)<br />
			&#8226;Ad orientation: Admin can select the ad orientation as vertical or horizontal.  This is not applicable for  banner only ad blocks.<br />

			&#8226;Text Line Height : This will control the actual height used by per line of text.  This is not applicable for banner only ad blocks.<br />

			&#8226;Ad title font settings: Admin can select the title font color,size and decoration . This is not applicable for  banner only ad blocks.<br />

			&#8226;Ad description font settings: Admin can select the description font color,size and decoration .  This is not applicable for  banner only ad blocks.<br />

			&#8226;Ad display url font settings: Admin can select the display url font color,size and decoration . This is not applicable for catalog only and banner only ad blocks.<br />

			&#8226;Background color: Admin can select the background color.  This is not applicable for banner only ad blocks.<br />
			
			&#8226;Credit text : This is the credit text shown in the ad display units. You may choose one from the credit texts which you have created. If you don't want credit text , you can leave it blank.<br />
			
			&#8226;Credit text font settings : Here you can choose the font, font weight and text decoration for credit text.<br />
			
			&#8226;Credit text/border color: Admin can select the color combination of credit text and border color.<br />

			&#8226;Credit text positioning/alignment : Here you can specify the credit text position (top or bottom) and credit text alignment (left or right). For text only adblocks which is configured to display title alone,  credit text can also be positioned to left or right.<br />
			
			
			&#8226;Border type: It can be set to regular/rounded. For banner only ad block, rounded border is not available.    <br />
	</p>
		<p>
			
		  Admin can also enable/disable the publisher override option for some of these parameters. Admin can modify values for these settings check the preview. You can activate the adblock once you are satisfied with the preview. Also there is dummy ad setting which is shown in the preview. Admin can set the dummy ad title, dummy description and dummy display url  settings.( These dummy settings are shown in the  preview for publishers also. ) </p>
		<li><strong> Manage Ad Blocks :</strong></li> 
		<p>In this section you can edit/block/activate the ad blocks in the system. Only active ad blocks can be used for generating ad display units. Also you cannot block an 'ad block' if there are ad units based on the same . Also note that inline ad blocks cannot be deleted. <br>
		  <br>
		  <blink><B>Note :</B></blink> If you have upgraded from any version prior to Version 2 , the custom banner sizes which you had created in the older version would be converted to banner  only blocks.

	    </p>
		<li><strong>Banner Dimensions :</strong></li> 
		<p>In this section you can create,delete,edit different banner dimensions in your system. You can edit/delete an existing dimension only if it is not used in any ad block/ads. Please ensure that every banner dimension is linked to atleast one ad block, otherwise ads which use the banner dimensions which are not linked to any ad blocks may never be served in the network.
        </p>
		<li><strong>Catalog Dimensions :</strong></li> 
		<p>From here you can manage the catalog dimensions in your system. Two dimensions are available, one each for WAP targeted and Desktop targeted ads. You can modify the existing dimensions only if they are not used in any ads. So you need to set the preferred dimension before your system goes live.
        </p>
		<li><strong>Manage Credit Text :</strong></li> 
		<p>In this section you can create/edit/delete different credit texts to be used in ad blocks. You can use image credits also. You can also specify multi-langauge versions of a credit text while creating or editing the same. You may delete an existing credit text  only if it is not used in any ad block .
        </p>
		<li> <strong>Credit Text/Border Color Combinations:</strong></li> 
		<p>From here you can create/edit/delete the color combinations  of credit text and border to be used in ad blocks and ad units. You can delete an existing combination of credit text color and border color only if it is not used in any ad block or ad unit.

	    </p>
  </ul>

      <?php
}		
if($id==8)
{
?>		
    <p>
      <strong class="heading">Admin's Ad Display</strong>  </p>
	 Admin can create and manage  his ad display code from here. He can also get the xml api related settings from here. Also admin can view the statistics of his adunits over here.
<ul>
    <li> <strong>Create adunit :</strong></li> 
	<p>	In this section you can create a new adunit based on an existing ad block. Every ad unit generated will have an ad display html code which can be pasted in the web pages to show ads from the system. To create an adunit, please specify the following.<br />
	  <u>Target device</u>: The device on which your adcode will be displayed (whether PC or WAP).<br />
	  <u>Language</u>: This is the preferred language in which you want ads. If no preference, you may choose 'any language'.<br />
	  <u>Ad block</u>: This is the parent ad block based on which you would like to create the adunit.<br /> 
	  <u>Tracking name</u>: This is optional and can be used for tracking the statistics later on.	</p>
	<p> Once you have created an adunit, you have the option to override the default color settings of the ad block. After the completion of ad unit creation copy the HTML code to the page where you want to display the ad. 2 types of code are available (except for wap and inline adunits), 'code for content related ads' which shows ads based on meta tags and title and 'code for keyword specific ads'. </p>
	<li><strong> Manage adunits :</strong></li> 
	<p>	This section shows all adunits created by you. You can get the code for these adunits anytime. Also you may edit/delete the adunits from here.</p>
		
	<li><strong>Get XML API :</strong></li> 
	<p>	From here you can get the XML API configuration. A sample code and sample output is provided to help you with the implementation.</p>
		
<li> <strong>Adunit statistics :</strong></li>
		<p>	Here you may view click statistics based on different adunits created by you.	You can also check your XML API statistics.</p>
			
	<li> <strong>Traffic analysis :</strong></li> 		
			<p>If your traffic analysis is enabled under 'Basic Settings', you may check the statistics of the same from here.</p>
  </ul>

       <?php
}		
if($id==9)
{
?>		
      <p>

        <strong  class="heading">Settings</strong>    </p>
        <p>This section contains all the essential configurations & settings related to the script.

        </p>
        <ul>
        <li> <strong>Basic  Configurations :</strong></li>
        You got 4 sections under the basic configurations. The configurations coming under each    section     is described below.
       
        <p><u> Basic Settings</u></p>
        <p>This section contains options for configuring your adserver name, default charset, default language, default advertiser/publisher  status (whether pending/active),  the minimum password length for users and sign-on mode for users (ie, single-sign-on or dual-sign-on).</p>
        <p>        <u> Advanced Settings</u></p>
        <p>
        The following configurations are available under advanced settings. </p>
        <p>
          &#8226;Adserver operation mode : This option allows to adjust the keyword dependency  of the system. Admin can change the system to a fully keyword based (every ad created must have atleast one keyword) or fully keyword independent (keyword related operations are masked from advertisers) or a combination of both (all ads will have a mandatory keyword by default and advertisers can add more keywords if required.).       </p>
        <p> &#8226;Default keyword : You can set a default keyword for all ads created by advertisers. If adserver operation mode is  'keyword independent mode' or 'both' this keyword is automatically assigned for all ads and cannot be deleted. In keyword based mode (if you have set some non null value for this), all ads created by advertiser will have the default keyword automatically. This default keyword will be considered always while ads are shown in web pages. Thus you can ensure that even when you have only less ads in the system, your ad pages will show some ads.</p>
        <p> &#8226;Automatically approve keywords : You can enable/disable the automatic approval of keywords. If it is "No",all new keywords by the advertiser  shall be pending by default and  you should manually approve them.</p>
        <p> &#8226;Ad rotation preference : Here you can decide whether ad rotation must be simply random or click bid based or based on a combination of click bid and ctr or based on a combination of click bid, ctr and ageing.</p>
        <p> &#8226;Fraud detection time interval(Hrs) : This is time interval based on which  duplicate ad clicks and fraud publisher clicks are tracked. 24 hrs would be a recommended value.
          &#8226;Exclude proxy clicks : You can decide whether to consider/exclude the clicks coming from a proxy sever.</p>
        <p> &#8226;Captcha verification for bot clicks : Here you can specify whether captcha verification should be enabled for publishers who sends in clicks using bots.</p>
        <p> &#8226;Duration for captcha verification : This is the period for which captcha must be turned on when a bot click is determined.</p>
        <p> &#8226;Automatically clear raw data  : Configure whether to automatically clean up unwanted raw statistical data from database and recover the disk space.</p>
        <p> &#8226;Admin traffic analysis : You have the option to turn on/off traffic analysis of your ad display pages; We suggest that you turn this off if your CPU is showing high usage.</p>
        <p> &#8226;Enter XML Authentication Id: The XML authentication id is a secret key which has to be passed to the XML API for authentication purpose .You can configure any value of your choice.If you change this value after implementing the XML API then you need to update your implementation with the new value.</p>
        <p> &#8226;    Hard Coded Link Checking Url :     This is used to check for the presence of any hardcoded urls in flash files. The system will replace all possible links with this value and    show a preview. If the clicks on  preview      is taking you to a website other than this, then the ad can be blocked.            </p>
        <p> <u> Email Settings</u></p>
             
             <p>
             These are the primary email settings. Never forget to modify these settings after first time installation.</p>
             <p> &#8226; Admin general notification email : This is the email to which admin receives general notifications about operations like ad creation, user registration etc.</p>
             <p> &#8226; Admin payment notification email : Admin receives notification about payments to this email id.</p>
             <p> &#8226; Minimum advertiser account balance for email notification : When advertiser balance falls below this value, the system will send notification to him/her. If you do not want such notifications please make this zero.</p>
             <p> &#8226; Send acknowledgment email after advertiser payment : Here you can specify whether to send acknowledgement to advertiser after automated payments.          </p>
        <p> <u> Format Settings</u></p>
             
             <p>
             Here you may specify the format of displaying data like numerical figures, 'monetary figures, date and time values  in your system .</p>
             
			 
             <li><strong> Monetary Settings :</strong></li>
        <p>Here you may configure settings like system currency, minimum dick rates, bonus settings, payment option for advertisers and publishers etc.</p>
       
        <p><u> General Monetary</u></p>
          <p>&#8226;Budget period : You can configure the system to allow advertisers to set either monthly or daily budgets for their ads.</p>
            <p> &#8226;Default budget for ad : You can set the default monthly/daily ad budget to be displayed while creating ads; advertisers can change this from their end.</p>
            <p> &#8226;Keyword min. click value : Advertisers won't be able to set click bids smaller than this value.</p>
            <p> &#8226;System currency : Here you can configure the operational currency of your system. Make sure that all payment gateways used in your system can support this.</p>
            <p> &#8226;Currency symbol : The currency symbol will be set automatically based  on the chosen currency.</p>
            <p> &#8226;Publisher profit percentage: This is the percentage of profit you want to share with publishers for each click.If you specify 20% as the publisher profit percentage,and the click value is 1$ you will get 0.8$ and the publisher will get 0.2$ for each click</p>
            <p> &#8226;Premium publisher profit percentage: This is the percentage of profit you want to share with your premium publishers. You can give premium status to a publisher from his profile page.</p>
          <p> <u> Advanced settings</u></p>
            <p> &#8226;Bonus settings : Here you can configure bonus related settings like whether to give account opening bonus or not. Also you can configure whether to show ads of bonus only advertisers in publisher adunits.</p>
            <p> &#8226;Revenue Booster : If you enable the revenue booster feature and set the boosting level, advertisers will be suggested better click rate values for their keywords. For the first few months you may turn it off to attract more advertisers. </p>
          <p><u> Publisher Payment</u></p>
            <p> Here you may specify the minimum withdrawal amount for publishers. Also you may enable/disable desired withdrawal options for publishers.</p>
            <p><u> Advertiser Payment</u></p>
            <p> Here you may specify the minimum amount that can be deposited by advertisers. Also you can enable/disable various payment options for advertiser.</p>
          <p>In case of check/bank payments  you can choose to accept payments in local currency if it is different from your system currency. At the time of approval you can convert the payment to system currency.         </p>
        </ul>
        <?php
}		
if($id==10)
{
?>		
  <p>
        <strong  class="heading">Coupon Codes</strong>	</p>
        <p>This section allows you to configure coupon codes for advertisers. These coupon codes can be used at the time of registration or at the time of adding funds using advertiser account.

	    </p>
		<ul>
        <li><strong> Create Coupon :</strong></li> 
		<p> You can create 2 types of coupons; flat rate coupons and percentage based coupons. Flat rate coupons credit a fixed amount as bonus no matter how much amount is added by an advertiser. A percentage based coupon will credit a particular percentage of the fund added as bonus. Only flat rate coupons can be used at the time of registration.</p>
		<p> Also you can specify the number of times a coupon code can be used in the system (ie, single use or multi use). If you specify it zero then the coupon can be used any number of times. And you can also specify an expiry date for a coupon. In any case a particular coupon code can be used by an advertiser only once.</p>
		<li> <strong>Manage Coupons :</strong></li> 
		<p>From here you may edit the coupons or delete them . You may also activate/block coupons.  </p>
</ul>
        <?php
}		
if($id==11)
{
?>		
  <p>
        <strong  class="heading">Site Content</strong>	</p>
        <p>This section contains some information and settings related to public pages of the site like meta data, page width settings, theme and logo settings etc.

	    </p>
		<ul>
        <li> <strong>Manage Meta Data :</strong></li> 
		<p>In this section you can add meta keywords and description for your advertiser pages and publisher pages. This will help you to index your site in the search engines. In case of single-sign-on you may add meta data for common pages also.

</p>
	<li> <strong>Terms and conditions :</strong></li> 
		<p> This is place to configure the terms and conditions between you and your advertisers/publishers. You may edit the default  terms and conditions here.</p>
	
	<li><strong> Theme & Logo Settings : </strong></li> 
	
		<p>Here you can select the public page (pages viewed by public) settings like page width and color theme. You can also create new color theme. For this please edit the config.inc.php file.  Also you can upload your adserver logo , the size of  logo must be within 300px X 60px. And you can  choose logo display options also.
		</p>
	<li> <strong>SEO urls :</strong></li> 
		<p> You might not like the default names we have given for the files visible in search engine. You may change them here. Configure the desired names and copy the generated code and paste into a .htaccess file and place the file in the adserver installation folder.
</p>
	<li> <strong>Sitemap generator :</strong></li> 
		<p> You may  create a  sitemap.xml file from here. Copy the  xml sitemap content and paste it in a sitemap.xml file  and place it in the root directory of your site. </p>

	<li> <strong>Inoutscripts Affiliate Id :</strong></li> 
		<p>Please login to your member area in inoutscripts and get your affiliate id . This affiliate id will be appended to the footer url of adserver pages, to track your referrals. If your referrals buy any of our products, you earn money .
</p>
		</ul>
 
        <?php
}		
if($id==12)
{
?>		
  <p>
        <strong  class="heading">Announcements</strong>	</p>
        <p>Would you like to display some notifications in the user control panel for a specific period of days? This section allows you to configure such announcements.

	    </p>
		<ul>
        <li> <strong>Create Message :</strong></li> 
        <p>Here you may type in your message and specify the target control panel, ie, advertiser panel, publisher panel or single-sign-on panel. Also you can specify the date up to which the particular message must be displayed.</p>
        <li> <strong>Manage Messages :</strong></li> 
		        <p>From here you may edit the announcements or delete them . You may also activate/block announcements. </p>

</ul></
        <?php
}		
if($id==13)
{
?>		
        
  <p><strong  class="heading">Email Templates</strong> </p>
		<p>This section contains different email templates related to  advertisers, publishers and ads.
	(The variables in the email template shown inside {} will be replaced by its original value. The 

	    variables that can be used in a template are shown in  below the message content of each template. </p>
		<ul>
		<li> <strong>Advertiser Email templates :</strong></li> 

		<p>This section contains 9 predefined email templates. You can edit these subject and content of these  emails.<br />
		  <u> Advertiser Email Verification Mail</u>: This is the mail send to verify whether the advertiser email-id is real.<br />
		  <u> Advertiser Welcome Mail</u>: This is the mail received by the advertiser on confirming his email. <br /> 
		  <u> Advertiser Approval Mail</u>: This email template is used only if the advertiser is in pending state by default . This is used at the time of approving the advertiser .<br />
		  <u> Advertiser Rejection Mail</u>: This email template is used only if the advertiser is in pending state by default . This is used at the time of rejecting the advertiser .<br />
		  <u> Password Recovery Mail</u>: This template is used for sending the new password to the advertisers when they use the 'forgot password' option .<br />
		  <strong>Note:</strong> The above templates are not applicable if single-sign-on is used. <br />
		  <u> Advertiser Block Mail</u>: This is the mail send on blocking the advertiser. In single-sign-on if advertiser status alone is blocked, then also this template is used. <br />
		  <u> Advertiser Activation Mail</u>: This mail is send on activating the advertiser.  In single-sign-on if publisher status alone is blocked, then also this template is used. <br />
		  <u> Advertiser  Payment Acknowledgment</u>: This is the mail sent by the system after automated  payments.	This template is used in both single-sign-on and dual-sign-on.<br />
		  <u> Minimum Account Balance Notification</u>: This is the mail sent by the system when an advertiser's account balance falls below the configured limit.<br />
		  </p>
		
		<li> <strong>Publisher Email templates :</strong></li> 
		<p>This section contains 9 predefined email templates. You can edit these subject and content of these emails.

		   <br />
		  <u> Publisher Email Verification Mail</u>: This is the mail send for verifying whether the publisher email-id is real.<br />		
		  <u>Publisher Welcome Mail</u>: Publisher welcome mail is sent when a publisher confirms his email. <br />
		  <u> Publisher Approval Mail</u>: This email template is used only if the publisher is in pending state by default .This is used at the time of approving the publisher .<br />
		  <u> Publisher Rejection Mail</u>: This email template is used only if the publisher is in pending state by default .This is used at the time of rejecting the publisher .
		   <br />
		  <u> Password Recovery  Mail</u>: This template is used for sending the new password to the publisher when they use the 'forgot password' option .	<br />

		      <strong>Note:</strong> The above templates are not applicable if single-sign-on is used. <br />
		 <u>Publisher Block Mail</u>: Publisher block mail is sent on blocking the publisher. <br />
		  <u> Publisher Activation Mail</u>: Publisher activation mail is sent on activating the publisher.<br />
		  <u> Fraud Publisher Warning Mail</u>: This is sent when you warn the publisher on finding fraud activity.<br />
		  <u> Fraud Publisher Block Mail</u>: This is sent when you block the publisher after finding fraud activity.<br />
	      </p>
		   
		<li><strong> User  email templates (Single-Sign-On Mode):</strong></li> 
		<p>This section contains 8 predefined email templates related to single-sign-on. You can edit these emails here.
<br />
		  <u> Email Verification Mail</u>: This is the mail send on user registration to verify whether the registered email-id is real.<br />		
		  <u> Welcome Mail</u>: The welcome mail is sent when a user confirms his email. <br />
		  <u> Approval Mail</u>: This email template is used only if both advertiser account and publisher account of new user are approved simultaneously .<br />
		  <u> Rejection Mail</u>: This email template is used only if both advertiser account and publisher account  of new user are rejected simultaneously.
		   <br />
		 <u> Block Mail</u>: This email template is used only if both advertiser account and publisher account  of an active user are blocked simultaneously. <br />
		  <u> Activation Mail</u>: This email template is used only if both advertiser account and publisher account  of an inactive user are activated simultaneously.<br />
		  <u> Change Status Mail</u>: This email template is used in 2 cases after a new user registration. Approving advertiser only and approving publisher only. <br />
		  <u> Password Recovery  Mail</u>: This template is used while sending  new passwords to the users when they use the 'forgot password' option .	<br />
		      <strong>Note:</strong> If any individual account related operations are performed on a user account apart from the above mentioned operations (eg: blocking publisher alone for a active user), then the template corresponding to the account type is used.  <br />
	</p>

		<li><strong> Ad email templates :</strong></li> 
		<p>This section contains 4 predefined email templates about the ad. You can edit these emails here.
<br />

		<u> Ad Activation Email</u>: Email to advertiser on ad activation.<br />

		<u> Ad Block Mail</u>: Email to advertiser on blocking of ad.<br />

		<u> Ad Deletion Mail</u>: Email to advertiser on deletion of ad.<br />

		<u> Monthly/Daily Budget Crossed</u>: Email notification to advertiser when any of his ads crosses the monthly/daily budget limit.<br />

		<u> Account balance is low</u>: Email warning for advertiser that his Account balance is low.<br />
	</p>
   </ul>
   
 <br><blink><B>Note</B> </blink>: An email confirmation message is sent upon registration of advertisers/publishers/single-sign-on accounts  . When the user clicks the confirmation link in the message the  welcome mail is sent .You may configure the welcome mail to show appropriate message depending on the default status of advertiser account and publisher account.	<br />


        <?php
}		
if($id==14)
{
?>		
  <p>
        <strong  class="heading">Multi-Language</strong>	</p>
        <p>From here you can configure the languages in which you want your system to function. You may create new language, manage already created languages, and edit messages in different languages. Please note that there are 4 message files corresponding to every language. Eg. for Spanish language (with code 'sp'), 4    files  named publisher-template-sp.inc.php, advertiser-template-sp-inc.php, common-template-sp-inc.php and  messages.sp.inc.php are present in the locale folder. The publisher-template-sp.inc.php file contains messages used in publisher templates, advertiser-template-sp.inc.php file contains messages used advertiser templates, common-template-sp.inc.php file contains messages used common templates,ie, some common sections for advertiser and publisher as well as  the single-sign-on related templates. The messages.sp.inc.php contains some warning/success messages which are used in all sections. 
  
</p>
        <ul>
<li><strong> Create Languages :</strong></li> 
		<p>This section helps to create support for a new language. While creating a new language ,message  files corresponding to new language are also created. You need to edit the messages in these files to the target language using the 'Edit  messages' option below.
</p>
	<li><strong> Manage languages :</strong></li> 
		<p>This section helps to edit,block and delete already created languages. If a language is blocked it will not appear in language options in public side. For default language only editing is allowed. English language you cannot be modified/blocked/deleted. 
	</p>
	<li><strong> Edit  messages :</strong></li> 
		<p>This section helps to edit all message files in different languages.  </p>
</ul>		


          <?php
}		
if($id==15)
{
?>		
         <p> 
          <strong  class="heading">Referral System</strong>        </p>
         <p>Did you know? Your system is equipped with 
         a referral system which when enabled allows your  publishers to send referrals to your 
         system and earn referral commissions based on 
         the activities of referred user. The referral system if enabled is a cost free means of promoting your system among the internet users. </p>
         <p>A publisher can earn by two means using this referral system, viz, advertiser referral and publisher referral. If the referred user creates and advertiser account and receives clicks
           for  his ads account then the referrer earns 
           advertiser referral profit; similarly if the referred user creates publisher account and sends clicks from his adunits, then the referrer earns publisher referral profit. </p>
         <ul>        <li><strong> Referral Banners:</strong></li> 
		 
		<p>In this section you can add new referral banners and can edit the existing referral banners. Publishers can send in referral traffic using these banners <br />
		  <u> Add new banner</u>:
			You can add a new referral banner in this section
		    <u> <br />
		    Manage Referral Banners</u>:
			You can edit/delete/move up or move down the referral banners	    </p>
		<li> <strong>Referral Settings :</strong></li> 
		<p>You can set the referral payment settings in this section.You can set the profit percent for publisher referral and advertiser referral.</p>
		
	<li><strong> Referral Statistics :</strong></li> 	
		<p>This shows referral statistics like unique hits,repeated hits,advertiser signups,publisher signups. It also shows the referral traffic statistics of all publishers</p>
		
  </ul>


        <?php
}		
if($id==16)
{
?>		
  <p>
        <strong  class="heading">Load Balancing</strong>	</p>
        <p>Did you know? Your adserver is scalable, ie, you can setup additional servers and configure load balancing among these servers. Adserver makes use of mysql replication technology to implement this cool feature.  A server with a standard configuration of say 2 GB RAM & 2 Ghz CPU  can handle 1 to 1.5 million impressions per day. Once you reach this mark you may need to setup load balancing. </p>
        <p>There are 2 stages of load balancing in Inout Adserver. </p>
        <p><strong>Stage I :</strong>The first stage is setting up of a new server for running your adserver database, while keeping the application running on the existing server. If you find that your system load has increased considerably with the application and mysql running together on the same machine, then you need to get a new server in the same network as of your existing server,  export the database to the new server and configure the application to connect to mysql database on the new server. That's it, stage I is done. </p>
        <p><strong>Stage II: </strong>If your system hits resource limits issues  after stage I  has been deployed, then you can think of moving to stage II. Here Inout Adserver makes use of mysql replication technology to ensure that ads are fetched by distributing queries uniformly across the replications. The best part is that you can add any number of replications and completely control publisher distribution among these replication from your admin area . </p>
        <p>Your pack contains the load balancing module which has to be setup on the load balance server;  a readme file with proper instructions is also provided . You may not be able to do this all alone unless you are from technical background. Don't worry, our tech team can definitely help you with this. Just make sure that you have an additional server in same network with an external ip. </p>
        <ul>
        <li> <strong>New Server :</strong> </li> 

        <p>Create new servers from here. Basically you need two kinds of servers. One is an application server and the other is a load balancer. You must add one application server before adding load balancers. The application server will be same as the server on which you have been running the system. The application will be mainly used for operations other than ad display and the load balancers will handle ad query requests alone. But initially you can use application server to handle some ad query requests also.</p>
        <p> While adding a server, you need to specify an easy to remember server name, web access url of that server, publisher id range start and end values. Publisher id range can be set as explained below. Say you have 6000 publishers and you are planning to setup two servers, one application server and one load balancer. Assuming you are planning to put first 4000 publishers in the application server, then the start value must be 1 and end value must be 4000 for the first server. When you add the second server, you must specify 4001 as start value and say 10000 as end value. Now the publishers with ids 1-4000 will query ads from the first server which is the application server. And publishers with ids 4001 to 10000 will query ads from second server.</p>
        <p> When publishers with ids greater than 10000 get registered, they will query query ads from the application server unless a new server is assigned, or in general we can say that if a publisher has id which does not fall in range of any of the servers, his ads will be queried from the application server. </p>
        <li> <strong>Manage Servers :</strong></li> 
		
        <p>From here you may edit server configurations which you have done at the time of creation.</p>
        <li> <strong>Server Allotment :</strong></li> 
        <p>The publisher distribution among multiple servers may need to be readjusted if load on a particular server goes high. Of course you can do it by readjusting range from the edit option. But most  often you might need to relocate one or more publishers from their default server to another server due to high traffic send by them. This page will display publishers with most impressions and their current server allocation. You may manually reassign them to another server.</p>
        <p> If a publisher's id falls in the range of server A and he has a specific server B allotted to him from this page, then the ad queries of that publisher will be send to  server B instead of the server that corresponds to his id,ie, A.</p>
  </ul>
  <?php
}		
if($id==17)
{
?>		
<p>
  
  <strong  class="heading">Fraud Control</strong>  </p>
<p>Did you know? Inout adserver can track  six different types of fraud click attempts? The following are the detections available with inout adserver.  </p>
<p>
    <u>Repetitive clicks</u>: Clicks on an ad from a particular ip twice or more in 24 hours (This period is configurable).<br />
    <u>Publisher fraud clicks</u>: Clicks on ads by a publisher himself.<br />
    <u>Invalid IP clicks</u>: Clicks on an ad from in where it was not rendered.<br />
    <u>Invalid geo clicks</u>: Clicks on an ad from a location where ad was not targeted.<br />
    <u>Proxy clicks</u>:Clicks send from proxy sites.<br />
    <u>Bot clicks</u>:Clicks generated using some  software.</p>
<p>Proxy click detection and bot click detection are configurable from Basic Settings. So is the fraud detection time interval which is used in case of repetitive click tracking and publisher fraud tracking. </p>
<ul>
<li><strong> Click Analysis : </strong></li> 
		<p>This section shows the details of clicks received for a specific duration. You can filter the clicks based on advertiser,publisher and the type of clicks.

	    </p>
		<li> <strong>Fraud Analysis :</strong></li> 

		<p>Here you can view the statistics of each type of fraud click attempted by publishers. The results will be grouped by publisher name and sorted in descending order of count.
	    </p>
		<li><strong>Suspicious Publishers :</strong></li>
		<p>You might want to check which all publishers are present with atleast one or more fraud control measures enabled. This is the page where you can find such details.</p>
		<li> <strong>Check Publisher CTR :</strong></li> 
		<p>This section shows the CTR statistics of all publishers who have received at least one click, sorted in descending order of click count. You may warn or block publishers with abnormal CTR.	Please note that click count shown in this page is of the valid clicks only.</p>
</ul>		
		
  <?php
}		
if($id==18)
{
?>		
  
 <p> <strong  class="heading">System Statistics</strong>  </p>
<p>This section shows  the overall statistics of the system.
  
</p>
<ul>
<li> <strong>Overall Click Statistics :</strong></li> 

			
			<p>This page shows the total clicks, impressions, generated click value, click share for publishers, referral share for publishers and your share of click value.
	
	        </p>
	        <li><strong>Verify statistics :</strong></li> 

		<p>Inout adserver collects clicks and impressions as raw data and later builds statistics from these data. Here you can view the statistical data generated so far in the system and compare it with the raw data (if it is not yet cleared). You  can also clear the raw data using the links provided from here, if you have not enabled automatic clearing.</p>
			<li> <strong>Revenue Statistics :</strong></li> 
		<p>

			Shows the summary of cash inflows and outflows of the system. Also it gives a picture about the profit generated so far from the system.</p>
			
			
	<li><strong>Cash Flow Reports :</strong></li> 

		<p>From here you may generate reports like cash inflow, cash outflow net cash flow of the entire system for any particular period. You may also generate the report for any specific user.
        </p>
</ul>
	


<?php
}	
if($id==19)
{
?>		
  
 <p> <strong  class="heading">Publishing Urls</strong>  </p>
<p>This section shows all the publishing urls. In this section you can manage (delete & block) all the publishing urls posted by your publishers. This sorting will help your adserver to dominate in the advertising market.
  
</p>

	


<?php
}	
if($id==20)
{
?>		
  
 <p> <strong  class="heading">Partner's logos</strong>  </p>

In this section you can manage(Add & Delete) all the partner's logos posted by your users. These logos with active status will be displayed in the home page. Admin can also add various logos.

  
</p>

	


<?php
}	
if($id==21)
{
?>		
  
 <p> <strong  class="heading">Text ad template</strong>  </p>
<p>This section shows all the text ad background images. </n>
 These images will act as the text ad template or background image. Please upload image compatible with the adblock width and height.In this section you can manage all the text ad background images.
  
</p>

	


<?php
}	
	
if($id==22)
{
?>		
  
 <p> <strong  class="heading">Backup Data Base</strong>  </p>
<p>This section helps to prevent data loss. </n>
With this exciting option you can periodically back up the database. Through this you can either backup tables individually or as whole. To use this you must enable mysqli and zip. You can contact your server people to enable the same.
  
</p>

	


<?php
}	

if($id==23)
{
?>		
  
 <p> <strong  class="heading">Chat Section</strong>  </p>

</p>This section contains various features regarding with the chat. Here you can change your chat status, online and offline images that will be displayed in the public side. </p>

	


<?php
}	
	
if($id==24)
{
?>		
  
 <p> <strong  class="heading">Public Slide Images</strong>  </p>
<p>This section shows all the slide images in the home page.
That you can manage(Add & Delete) from here. You can add any number of images.
  
</p>

	


<?php
}	
?>

</div>			
<?php 
include("admin.footer.inc.php");
?>
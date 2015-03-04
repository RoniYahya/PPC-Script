<?php 

/*--------------------------------------------------+
|													 |
| Copyright � 2006 http://www.inoutscripts.com/      |
| All Rights Reserved.								 |
| Email: contact@inoutscripts.com                    |
|                                                    |
+---------------------------------------------------*/

error_reporting(0);

// Please login to your inoutscripts account and create a new license key for your Adserver script and paste it here
$license_key="";

// Mysql Information
$mysql_server="localhost";			// MySql Server Name/MySQL server address
$mysql_username="root"; 			// MySql Username
$mysql_password="";					// MySql Password
$mysql_dbname="inoutV2";		// MySql DataBase name that need to be selected.
$mysql_server_type=1;   			// Value 1 for master server;   2 for load balancing replication slave


// Admin login info. Please modify the username and password. You have to use this username and password to login to your admin area.
$username="admin";
$password="admin";

// Server Path where you have copied the script files
$server_dir="http://localhost/inout_adserver_ultimatev6.0/";  // Remember to give the last slash.


// Email settings
$mailserver_type=0; // 0 or 1
$email_encoding="utf-8";

// SMTP Settings
$smtpmailer = 0; 						// 0 for false and 1 for true
$smtp_host= "smtp.yoursite.com";		// specify SMTP mail server
$smtp_port = "25"; 						// specify SMTP Port
$smtp_user = ""; 						// Full SMTP username
$smtp_pass =""; 						// SMTP password
$smtp_secure = "notls";              	// sets the prefix to the servier eg:ssl


// color themes used in public pages

// color0 = header background color1
// color1 = header background color2
// color2 = title background color
// color3 = title color
// color4 = link color

$color_code=array(
"default"=>array("#003388","#003388","#003388","#003388","#003388"),
"green"=>array("#7AB317","#7AB317","#7AB317","#7AB317","#7AB317"),
"seagreen"=>array("#039183","#039183","#039183","#039183","#039183"),
"green visor"=>array("#28AE7B","#28AE7B","#28AE7B","#3E7A5E","#3E7A5E"),
"pink"=>array("#EB6DC9","#EB6DC9","#EB6DC9","#EB6DC9","#EB6DC9"),
"rosebrown"=>array("#AD6749","#AD6749","#AD6749","#AD6749","#AD6749"),
"red"=>array("#B32C05","#B32C05","#B32C05","#B32C05","#B32C05"),
"tomato"=>array("#EA3F35","#EA3F35","#EA3F35","#FF6347","#D43D1A"),
"salmon"=>array("#FA8072","#FA8072","#FA8072","#FA8072","#FA8072"),
"yellow"=>array("#FF9900","#FF9900","#FF9900","#FF9900","#FF9900"),
"gold"=>array("#FBB829","#FBB829","#FBB829","#FBB829","#FBB829"),
"goldenrod"=>array("#C68E17","#7F5217","#C58917","#817339","#805817"),
"chocolate"=>array("#291912","#291912","#291912","#291912","#291912"),
"gray"=>array("#68696E","#68696E","#68696E","#68696E","#68696E"),
"purple"=>array("#660066","#660066","#660066","#660066","#660066"),
"violet"=>array("#8953B5","#8953B5","#8953B5","#C196F2","#C196F2"),
"blue"=>array("#0099CC","#0099CC","#0099CC","#0099CC","#0099CC")
);


// These are the keywords which are ignored when user creates keywords.
$ignoreList=" a able about above abroad according accordingly across actually adj after afterwards again against ago ahead ain't all allow allows almost alone along alongside already also although always am amid amidst among amongst an and another any anybody anyhow anyone anything anyway anyways anywhere apart appear appreciate appropriate are aren't around as a's aside ask asking associated at available away awfully 

back backward backwards be became because become becomes becoming been before beforehand begin behind being believe below beside besides best better between beyond both brief but by 

came can cannot cant can't caption cause causes certain certainly changes clearly c'mon co co. com come comes completely concerning consequently consider considering contain containing contains corresponding could couldn't course c's currently 

dare daren't decrease decreasingly definitely described despite did didn't different directly do does doesn't doing done don't down downwards during 

each eg eight eighty either else elsewhere end ending enough entirely especially et etc even ever evermore every everybody everyone everything everywhere ex exactly example except

fairly far farther few fewer fifth first firstly five followed following follows for forever former formerly forth forward found four from further furthermore 

get gets getting given gives go goes going gone got gotten greetings 

had hadn't half happens hardly has hasn't have haven't having he he'd he'll hello help hence her here hereafter hereby herein here's hereupon hers herself he's hi him himself his hither hopefully how howbeit however hundred 

i i'd ie if ignored i'll i'm immediate in inasmuch inc increase increasingly indeed indicate indicated indicates inner inside insofar instead into inward is isn't it it'd it'll its it's itself i've 

just 

keep keeps kept know known knows 

last lastly lately later latter latterly least less lest let let's like liked likely likewise little look looking looks low lower ltd 

made main mainly make makes many may maybe mayn't me mean meantime meanwhile merely might mightn't mine minus miss more moreover most mostly mr mrs ms much must mustn't my myself 

name namely nd near nearly necessary need needn't needs neither never never neverless nevertheless new next nine ninety no nobody non none nonetheless noone no-one nor normally not nothing notwithstanding novel now nowhere 

obviously of off often oh ok okay old on once one ones one's only onto opposite or other others otherwise ought oughtn't our ours ourselves out outside over overall own 

particular particularly past per perfectly perhaps placed please plus possible presumably probably provided provides 

que quick quickly quite qv 

rather rd re really reasonably recent recently regarding regardless regards relatively respectively right round 

said same saw say saying says second secondly see seeing seem seemed seeming seems seen self selves sensible sent serious seriously seven several shall shan't she she'd she'll she's should shouldn't since six so some somebody someday somehow someone something sometime sometimes somewhat somewhere soon sorry specified specify specifying still sub such sup sure surely 

take taken taking tell tends th than thank thanks thanx that that'll thats that's that've the their theirs them themselves then thence there thereafter thereby there'd therefore therein there'll there're theres there's thereupon there've these they they'd they'll they're they've thing things think third thirty this thorough thoroughly those though three thrice through throughout thru thus thusly till to together too took toward towards tried tries truly try trying t's twice two 

un under underneath undoing unfortunately unless unlike unlikely until unto up upon upwards us use used useful uses using usually utterly value various versus very via viz vs 

want wants was wasn't way we we'd welcome well we'll went were we're weren't we've what whatever what'll what's what've when whence whenever where whereafter whereas whereby wherein where's whereupon wherever whether which whichever while whilst whither who who'd whoever whole wholly who'll whom whomever who's whose why will willing wish with within without wonder wondered wondering won't worst would wouldn't 

yes yet you you'd you'll your you're yours yourself yourselves you've 

zero ";

// Please do not add/edit anything  below this line.

include_once("script.inc.php");
include("load_values.inc.php");
?>
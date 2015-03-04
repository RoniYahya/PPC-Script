<?php

$GLOBALS['classes_folder']="classes"; 

$GLOBALS['admin_folder']="admin";

$GLOBALS['banners_folder']="ppc-banners";

$GLOBALS['ad_logos_folder']= "ad_logos";

$GLOBALS['service_banners_folder']="ppc-service-ads";

$GLOBALS['cache_folder']= "_cache";

// The above configurations are to be modified in case you wish to change any of these folder names.
// Please note that you can only change the names and not the location.
// After modifying the name here, do not forget to rename the actual folder.


$GLOBALS['cache_timeout']=0;
// Specify the value in minutes. This is time for which output from an adunit is cached in server. 
// Don't specify very small values if you are serving large number of impressions per day.


$GLOBALS['maintenance_mode']=array(

'enabled'=>0,  						 
// can be 1 or 0
'allowed_ips'=>'192.168.1.121, 127.0.0.1, 192.168.1.105, 192.168.1.116',               
// here we can specify ips (comma separated) which should be allowed to access the system under maintenance mode
'landing_url'=>'maintenance.html'         
// here we configure the page to which user must be redirected when system is in maintenance mode.

);

?>
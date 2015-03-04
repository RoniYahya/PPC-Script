<?php 



/*--------------------------------------------------+

|													 |

| Copyright � 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php



include_once("config.inc.php");

error_reporting(1);

set_time_limit(0);



 $thispage=substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],"/")+1);

$idb= $mysql->select_one_row("SHOW VARIABLES LIKE 'have_innodb'");
$idbrow=mysql_fetch_array($idb);
if(!strcmp($idbrow['have_innodb'],'yes'))
{
?>
Your database does not support INNODB storage engine. We cannot proceed with installation. Please ask your host to fix this.
<?php
die;
}
elseif( $thispage == 'pre-check.php')	
{

?>
Your database supports INNODB storage engine.<br>
No issues found in installation pre-checking.
<?php
}		

?>
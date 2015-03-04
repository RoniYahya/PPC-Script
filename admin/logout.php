<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php 
include_once("config.inc.php");
setcookie("inout_admin","");
setcookie("inout_pass","");

/*...............................adserver5.4....................................*/

			$in_user_id=$_COOKIE['admin_in_user_id'];
			
			mysql_query("update nesote_chat_login_status set status='0' where user_id='$in_user_id' ");
		mysql_query("update ppc_settings set value='0' where name='chat_status'");

		setcookie("admin_in_user_id","",0,"/");
/*...............................adserver5.4....................................*/





header("Location: index.php?logout=true");
die;
?>
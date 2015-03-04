<?php
/*

"class.user.php" this file is a class which supports some basic mysql operation.
All rights reserved and all the rights of the file and script goes to Jacob Baby[jacobbbc@yahoo.co.in] only.

Created on:15/10/2007
Modified on:15/10/2007

*/


/*

Funtion HELP

*/

class User
{
	var $user_table;
	var $uid_field;
	var $username_field;
	var $password_field;
	var $email_field;
	var $status_field;
	var $active_status;
	var $username_cookie="io_username";
	var $password_cookie="io_password";
	var $type_cookie="io_type";

	function User($user_table, $uid_field="uid" , $username_field="username", $password_field="password", $email_field="email",$status_field="status",$active_status=1)
	{
		$this->user_table=$user_table;
		$this->uid_field=$uid_field;
		$this->username_field=$username_field;
		$this->password_field=$password_field;
		$this->email_field=$email_field;
		$this->status_field=$status_field;
		$this->active_status=$active_status;
	}

	function cookieUser($username, $password ,$type)
	{

		if($username==""||$password=="")
		return FALSE;


		$password=md5($password);

		$condition=	$this->username_field."='$username' AND ".$this->password_field."='$password' AND ".$this->status_field."='".$this->active_status."' LIMIT 0,1";

		//$usercount=mysql::total($this->user_table, $condition);
$usercount=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$condition);


//echo "-------".$aa; exit;
//echo $usercount.",select ".$this->uid_field." from ".$this->user_table." where ".$condition;
		if($usercount!='')
		{
			if($this->user_table=="nesote_inoutscripts_users")
			{
	
				setcookie($this->username_cookie,$username,0,'/');
				setcookie($this->password_cookie,$password,0,'/');
				setcookie($this->type_cookie,md5("Common"),0,'/');
				
				$email=mysql::echo_one("select email from nesote_inoutscripts_users where username='$username'");
				setcookie("io_usermail",$email,0,'/');
				return TRUE;
			}
			else
			{
				setcookie($this->username_cookie,$username,0,'/');
				setcookie($this->password_cookie,$password,0,'/');
				setcookie($this->type_cookie,md5($type),0,'/');
				if($type=="advertiser")
				{
				$email=mysql::echo_one("select email from ppc_users where username='$username'"); 
				
				setcookie("io_usermail",$email,0,'/');
				}
				if($type=="publisher")
				{
				$email=mysql::echo_one("select email from ppc_publishers where username='$username'"); 
				
				setcookie("io_usermail",$email,0,'/');
				}
				
				return TRUE;
			}

		}
		else
		{

			if($type=="advertiser")
			{
				$advusercount=mysql::echo_one("select uid from ppc_users where ". $condition);
				
				//echo $advusercount.",select uid from ppc_users ". $condition;
				if($advusercount!='')
				{
					setcookie($this->username_cookie,$username,0,'/');
					setcookie($this->password_cookie,$password,0,'/');
					setcookie($this->type_cookie,md5($type),0,'/');
					$email=mysql::echo_one("select email from ppc_users where username='$username'");
				setcookie("io_usermail",$email,0,'/');
					mysql_query("update ppc_users set lastlogin =".time()."  where uid=".$advusercount);
					header("Location:ppc-user-control-panel.php");
					exit(0);
				}
				else
				{
					return FALSE;
				}

			}
			elseif($type=="publisher")
			{
				$pubusercount=mysql::echo_one("select uid from ppc_publishers where ". $condition);
				if($pubusercount!=0)
				{
					setcookie($this->username_cookie,$username,0,'/');
					setcookie($this->password_cookie,$password,0,'/');
					setcookie($this->type_cookie,md5($type),0,'/');
						$email=mysql::echo_one("select email from ppc_publishers where username='$username'");
				setcookie("io_usermail",$email,0,'/');
					global $public_ip;
					mysql_query("update ppc_publishers set lastlogin =".time().",lastloginip='$public_ip' where uid=".$pubusercount);
					header("Location:ppc-publisher-control-panel.php");
					exit(0);
				}
				else
				{
					return FALSE;
				}
			}
			elseif($type=="Common")
			{
				$comusercount=mysql::echo_one("select id from nesote_inoutscripts_users where ". $condition);
				if($comusercount!='')
				{

					setcookie($this->username_cookie,$username,0,'/');
					setcookie($this->password_cookie,$password,0,'/');
					setcookie($this->type_cookie,md5($type),0,'/');
					header("Location:show-message.php?id=1005");
					exit(0);
				}
				else
				{
					return FALSE;
				}
			}
			else
			{
				return FALSE;
			}
		}


	}


	function pendingAccount($username, $password )
	{
		$password=md5($password);

		//	$password=md5($password);
		$query=$this->username_field."='$username' AND ".$this->password_field."='$password'";
		$query=$query." AND ".$this->status_field."=-1";

		$query=$query." LIMIT 0,1";
		$pend_count=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$query);
		if($pend_count=='')
		return FALSE;
		else
		return TRUE;
		
	}

	function isEmailVerified($username, $password )
	{

		$password=md5($password);
		$query=$this->username_field."='$username' AND ".$this->password_field."='$password'";
		$query=$query." AND ".$this->status_field."=-2";

		$query=$query." LIMIT 0,1";
		//echo $query;
		$email_count=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$query);
		if($email_count!='')
		return TRUE;
		else
		return FALSE;

	}


	function checkStatus($username, $password,$type=0)
	{

		$password=md5($password);
		$query=$this->username_field."='$username' AND ".$this->password_field."='$password'";
		//$query=$query." AND ".$this->status_field."=1";
$table_count=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$query." LIMIT 0,1");

		if($table_count!='')
				{
			//echo "select status from ".$this->user_table."where ".$this->username_field."='$username' AND ".$this->password_field."='$password'";
			$commonst=mysql::echo_one("select status from ".$this->user_table." where ".$this->username_field."='$username' AND ".$this->password_field."='$password'");
			if($commonst==0)  //main table blocked account
			{
				header("location: show-message.php?id=1017");
				exit;
			}
			elseif($commonst==-1)
			{
				header("location: show-message.php?id=1018");
				exit;
			}
			elseif($commonst==1)
			{

				$advst=mysql::echo_one("select status from ppc_users where ".$this->username_field."='$username' AND ".$this->password_field."='$password'");
				$pubst=mysql::echo_one("select status from ppc_publishers where ".$this->username_field."='$username' AND ".$this->password_field."='$password'");

				if(($advst==0)&&($pubst==0))
				{
					header("location: show-message.php?id=1017");
					exit;

				}
				elseif(($advst==-1)&&($pubst==-1))
				{
					header("location: show-message.php?id=1018");
					exit;
				}
				elseif((($advst==-1)&&($pubst==0))||(($advst==0)&&($pubst==-1)))
				{
					header("location:show-message.php?id=9991");
					exit;
				}
				else
				{
					return FALSE;
				}

			}

		}
		else
		{
			$condition=$query." AND ".$this->status_field."=1 LIMIT 0,1";
			if($type=='advertiser')
			{
				$advusercount=mysql::echo_one("select uid from ppc_users where ".$condition);
				
				$advst=mysql::echo_one("select status from ppc_users where ".$this->username_field."='$username' AND ".$this->password_field."='$password'");
					
			}
			elseif($type=='publisher')
			{
				$advusercount=mysql::echo_one("select uid from ppc_publishers where ".$condition);
				$advst=mysql::echo_one("select status from ppc_publishers where ".$this->username_field."='$username' AND ".$this->password_field."='$password'");
			}


			if($advusercount!='')
			{
				return FALSE;
			}
			else
			{
				if($advusercount=='')
				{
					header("location: show-message.php?id=1005");
				exit;
				}
			elseif($advst==0)
			{
				header("location: show-message.php?id=1017");
				exit;

			}
			elseif($advst==-1)
			{
				header("location: show-message.php?id=1018");
				exit;
			}
			else
			{
				return TRUE;
			}
			}

		}



	}

	function blockedAccount($username, $password)
	{

		$password=md5($password);

		$query=$this->username_field."='$username' AND ".$this->password_field."='$password'";
		$query=$query." AND ".$this->status_field."=0";

		$query=$query." LIMIT 0,1";
		
		$tablecount=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$query);
		if($tablecount!='')
		return TRUE;
		else
		return FALSE;


	}

	function userExists($username,$status=100)
	{
		// Status 100 means do not consider status

		$query=$this->username_field.'=\''.$username.'\'' ;
		if ($status!=100)
		{
			$query=$query." AND ".$this->status_field."=".$status;
		}

		$query=$query." LIMIT 0,1";
		$tablecount=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$query);
		
		if($tablecount!='')
		return TRUE;
		else
		return FALSE;

	}


	function emailExists($email,$id=0)
	{

		$query=$this->email_field.'=\''.$email.'\'' ;
		if($id!=0)
		$query.=' and '.$this->uid_field.'!='.$id;

		$query=$query." LIMIT 0,1";
		//echo $query;
		$tablecount=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$query);
		if($tablecount!='')
		return TRUE;
		else
		return FALSE;

	}


	function allowPassword($password1,$password2,$minlength=6)
	{
		if($password1!=$password2)
		{
			return(8002);
		}
		if(strlen($password1)<$minlength)
		{
			return (8003);
		}
	}
	function isValidEmail($email)
	{
		$result = TRUE;
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email))
		{
			$result = FALSE;
		}
			
		return $result;
	}
	///////////////////////////////////////////////

	function validateUser()
	{

		if($GLOBALS['single_account_mode']==1)      //single account mode
		{


			if(!isset($_COOKIE[$this->username_cookie]) || !isset($_COOKIE[$this->password_cookie]))
			{
				return FALSE;
			}
			$username=$_COOKIE[$this->username_cookie];
			$password=$_COOKIE[$this->password_cookie];
			if(!get_magic_quotes_gpc())
			{
				$username=mysql_real_escape_string($username);
				$password=mysql_real_escape_string($password);
			}
			$condition=	$this->username_field."='$username' AND ".$this->password_field."='$password' AND ".$this->status_field."='".$this->active_status."'";
			$condition_commonid=$this->username_field."='$username' AND ".$this->password_field."='$password'";
			 $usercount=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$condition);
			
			if(($this->user_table=="ppc_users") || ($this->user_table=="ppc_publishers"))  //ppc_users or ppc_publishers
			{
				
				//  echo 	  "select common_account_id from ".$this->user_table." where ".$condition_commonid;
				  $common_id=mysql::echo_one("select common_account_id from ".$this->user_table." where ".$condition_commonid);
				
				if($common_id==0)   //individual account
				{
					if(0==$usercount)
					return FALSE;
					else
					{
						if($this->user_table=="ppc_users")
						{
							if($_COOKIE[$this->type_cookie]!=md5("advertiser"))
							return FALSE;
						}
						if($this->user_table=="ppc_publishers")
						{
							if($_COOKIE[$this->type_cookie]!=md5("publisher"))
							return FALSE;
						}
						
						return TRUE;
					}


				}
				elseif($common_id>0)  //single account users
				{
					//
					//			$condition=	$this->username_field."='$username' AND ".$this->password_field."='$password' AND ".$this->status_field."='".$this->active_status."' AND common_account_id='".$this->active_status."'";
					//			$comcount=mysql::total($this->user_table, $condition);
					$st=mysql::echo_one("select status from " .$this->user_table." where ".$this->username_field."='$username' and ".$this->password_field."='$password' and parent_status=1");
					if($st==1)
					{
							
						if($GLOBALS['portal_system']==1)
						{
							if(!isset($this->type_cookie))
							setcookie($this->type_cookie,md5("Common"),0,'/');
						}
						else
						{

							if($_COOKIE[$this->type_cookie]!=md5("Common"))
							return FALSE;

						}
						
						return TRUE;
						
						
					}
					else
					{
						if($this->user_table=="ppc_publishers")
						{
							if($st==-1)
							{
								header("location: error-message.php?id=9997");
								exit;
							}
							else
							{
								header("location: error-message.php?id=8887");
								exit;
							}
						}
						else
						{

							if($st==-1)
							{
								header("location:error-message.php?id=9998");
								exit;
							}
							else
							{
								header("location:error-message.php?id=8888");
								exit;
							}
						}

					}

				}


			}
			else              ///nesote inout user table
			{
				if($usercount==0)
				return FALSE;
				else
				return TRUE;
			}
		}
		else
		{



			if(!isset($_COOKIE[$this->username_cookie]) || !isset($_COOKIE[$this->password_cookie]) || !isset($_COOKIE[$this->type_cookie]))
			return FALSE;
			$username=$_COOKIE[$this->username_cookie];
			$password=$_COOKIE[$this->password_cookie];
			if(!get_magic_quotes_gpc())
			{
				$username=mysql_real_escape_string($username);
				$password=mysql_real_escape_string($password);
			}
			$condition=	$this->username_field."='$username' AND ".$this->password_field."='$password' AND ".$this->status_field."='".$this->active_status."'";
			$usercount=mysql::echo_one("select ".$this->uid_field." from ".$this->user_table." where ".$condition);
			//echo $usercount;
			if(0==$usercount)
			return FALSE;
			else
			{
				if($this->user_table=="ppc_users")
				{
					if($_COOKIE[$this->type_cookie]!=md5("advertiser"))
					return FALSE;
				}
				if($this->user_table=="ppc_publishers")
				{
					if($_COOKIE[$this->type_cookie]!=md5("publisher"))
					return FALSE;
				}
				
				return TRUE;
			}
		}
	}





	function getUsername($id="")
	{

		if($id==""){
			return $_COOKIE[$this->username_cookie];
		}
		else
		{
			$result=mysql_query("select ".$this->username_field." from ".$this->user_table." where ".$this->uid_field."='$id' LIMIT 0,1");
			$row=mysql_fetch_row($result);
			return $row[0];

		}
	}

	function getEmailID($username="")
	{
		if($username=="")
		{
			$username=$_COOKIE[$this->username_cookie];
		}

		$result=mysql_query("select $this->email_field from $this->user_table where username='$username'");
		if(mysql_num_rows($result)==1)
		{

			$row=mysql_fetch_row($result);

			return $row[0];
		}

		return FALSE;


	}

	function getUserID($username="")
	{
		if($username=="")
		{
			$username=$_COOKIE[$this->username_cookie];
			$password=$_COOKIE[$this->password_cookie];
			if(!get_magic_quotes_gpc())
			{
				$username=mysql_real_escape_string($username);
				$password=mysql_real_escape_string($password);
			}
			//echo "select ".$this->uid_field." from ".$this->user_table." where ".$this->username_field."='$username' LIMIT 0,1";
			if($this->user_table=="nesote_inoutscripts_users")
			{

				$result=mysql_query("select id from ".$this->user_table." where ".$this->username_field."='$username' LIMIT 0,1");
				$row=mysql_fetch_row($result);
			}
			else
			{
				$result=mysql_query("select ".$this->uid_field." from ".$this->user_table." where ".$this->username_field."='$username' LIMIT 0,1");
				$row=mysql_fetch_row($result);
			}

			return $row[0];
		}
		else
		{

			$result=mysql_query("select ".$this->uid_field." from ".$this->user_table." where ".$this->username_field."='$username' LIMIT 0,1");
			$row=mysql_fetch_row($result);
			return $row[0];
		}

	}

	function changePassword($oldpass,$pass1,$pass2,$minlength=6)
	{

		if(strlen($pass1)<$minlength)
		{
			return 1003;
		}

		$oldpass=md5($oldpass);
		$pass1=md5($pass1);
		$pass2=md5($pass2);
		$password=$_COOKIE[$this->password_cookie];
		//		echo $oldpass;
		if($oldpass!=$password)
		{
			return 1008;
		}
		else if($pass1!=$pass2)
		{
			return 1009;
		}
		else
		{
			if($this->user_table=="nesote_inoutscripts_users")
			{mysql_query("BEGIN");
			if(!mysql_query("update ".$this->user_table." set ".$this->password_field."='$pass1' where id='".$this->getUserID()."'"))
			{
				$err_flag=1;
			}
			if(!mysql_query("update ppc_users set ".$this->password_field."='$pass1' where common_account_id='".$this->getUserID()."'"))
			{
				$err_flag=1;
			}
			if(!mysql_query("update ppc_publishers set ".$this->password_field."='$pass1' where common_account_id='".$this->getUserID()."'"))
			{
				$err_flag=1;
			}
			if(	$err_flag==1)
			{
				mysql_query("ROLLBACK");
				return 1004;
				//	header("Location:show-message.php?id=1004");
				//				exit(0);
			}
			else
			{
				mysql_query("COMMIT");
			}
			}
			else
			{
				mysql_query("update ".$this->user_table." set ".$this->password_field."='$pass1' where uid='".$this->getUserID()."'");
			}
			$ini_error_status=ini_get('error_reporting');
			if($ini_error_status!=0)
			{
				echo mysql_error();
			}
			setcookie($this->password_cookie,$pass1,0,'/');
			return 1010;
		}


	}
	function logOut()
	{

		setcookie($this->username_cookie,"",0,'/');
		setcookie($this->password_cookie,"",0,'/');
		setcookie($this->type_cookie,"",0,'/');
		setcookie("io_usermail","",0,'/');

	}
	function sendPassword($email,$from,$subject)
	{
		global $ppc_engine_name,$admin_general_notification_email,$script_mode;
		//		$emailstring = <<<EOD
		//
		//Hello,
		//Your login information is given below.
		//Username : {USERNAME}
		//New Password : {PASSWORD}
		//
		//Please login and change the the temporary password.
		//
		//Thanks.
		//
		//EOD;
		//
		if($this->user_table=='ppc_users')
		{
			$table_id=21;
		}

		if($this->user_table=='ppc_publishers')
		{
			$table_id=22;
		}
		if($this->user_table=='nesote_inoutscripts_users')
		{
			$table_id=23;

		}
		$result=mysql_query("select $this->username_field, $this->email_field,$this->password_field from $this->user_table where $this->username_field='$email'");
		if(mysql_num_rows($result)==1)
		{

			$row=mysql_fetch_row($result);
			$username=$row[0];
			$email=$row[1];
			$oldpass=$row[2];
			//		echo $oldpass;
			$newpass=substr($oldpass,0,7);
			//echo $newpass;
			if($this->user_table=='nesote_inoutscripts_users')
			{mysql_query("update ppc_users set $this->password_field='".md5($newpass)."' where $this->username_field='$username';");

			mysql_query("update ppc_publishers set $this->password_field='".md5($newpass)."' where $this->username_field='$username';");
			mysql_query("update $this->user_table set $this->password_field='".md5($newpass)."' where $this->username_field='$username';");
			}
			else
			{
				mysql_query("update $this->user_table set $this->password_field='".md5($newpass)."' where $this->username_field='$username';");
			}
			//		$emailstring=str_replace("{USERNAME}",$username,$emailstring);
			//		$emailstring=str_replace("{PASSWORD}",$newpass,$emailstring);

			//echo $emailstring;
			//mail($email,$subject,$emailstring);


			// CONFIRM EMAIL ID

			$sub=mysql::echo_one("select email_subject from email_templates where id='$table_id'");
			$sub=str_replace("{USERNAME}",$username,$sub);
			$sub=str_replace("{ENGINE_NAME}",$ppc_engine_name,$sub);
			$body=mysql::echo_one("select email_body from email_templates where id='$table_id'");
			$body=str_replace("{USERNAME}",$username,$body);
			$body=str_replace("{PASSWORD}",$newpass,$body);
			$body=str_replace("{ENGINE_NAME}",$ppc_engine_name,$body);

			$body=html_entity_decode($body,ENT_QUOTES);


			if($script_mode!="demo")
			{
				include($GLOBALS['admin_folder']."/class.Email.php");

				$message = new Email($email, $admin_general_notification_email, $sub, '');
				$message->Cc = '';
				$message->Bcc = '';
				$message->SetHtmlContent(nl2br($body));
				return $message->Send();
			}

			// END CONFIRM EMAIL ID



		}//if(mysql_num_rows($result)==1)

		return FALSE;

	}//function sendPassword($email,$from,$subject)

	function sendFraudAlert($username,$to,$ppc_engine_name)
	{

		$emailstring = "

		Hello,

		A fraud click was attempted by the publisher $username .
		You can find the fraud click statistics of the publisher from admin area .

		Thanks.

".$ppc_engine_name;

		mail("$to", "Fraud Click Alert!!!", $emailstring,"From: webmaster@{$_SERVER['SERVER_NAME']}\r\n"
		."Reply-To: webmaster@{$_SERVER['SERVER_NAME']}\r\n"
		."X-Mailer: PHP/" . phpversion());
	}
}
?>
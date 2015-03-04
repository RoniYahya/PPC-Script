<?php

include("extended-config.inc.php");  
include($GLOBALS['admin_folder']."/config.inc.php");

includeClass("Template");
includeClass("User");
includeClass("Form");
$user=new User("ppc_users");
$template=new Template();

$template->loadTemplate("ppc-templates/payments.tpl.html");
if(!$user->validateUser())
{
header("Location:show-message.php?id=1006");
exit(0);
}




$template->includePage("{TABS}","advertiser-loggedin-header.php");
$template->includePage("{FOOTER}","common-footer.php");

$type=1;
    if(isset($_REQUEST['type']))
    {
    	$type=trim($_REQUEST['type']);
    	phpSafe($type);
    	
    }

if($advertiser_paypalpayment 	==1)
{
    
   $selected="";

    if($type==1)
    {
    	$selected="selected";
    }
    
$option_str="<option value='1' $selected >".$template->checkAdvMsg(1203)."</option>";
    
}

    

if($advertiser_authpayment==1)
{
    
	$selected="";
	if($type==2)
    {
    	$selected="selected";
    }
	$option_str.="<option value='2' $selected >".$template->checkAdvMsg(1204)."</option>";
	
}

if($advertiser_checkpayment==1)
{

    $selected="";
	if($type==3)
    {
    	$selected="selected";
    	$typestr=" where a.pay_type =1 ";
    	$template->setValue("{FIELD}",$template->checkAdvMsg(10007));
    }
	
    $option_str.="<option value='3' $selected >".$template->checkAdvMsg(10162)."</option>";
    
    $option_str.=" $selected ";
	
	
}

if($advertiser_bankpayment==1)
{

    $selected="";
	if($type==4)
    {
    	$selected="selected";
    	$typestr=" where a.pay_type =2 ";
    	$template->setValue("{FIELD}",$template->checkAdvMsg(10008));
    }
    
	$option_str.="<option value='4' $selected >".$template->checkAdvMsg(10163)."</option>";
	
	
	
}

if($single_account_mode==1)
{

    $selected="";
	if($type==5)
    {
    	$selected="selected";
    	$typestr=" where a.pay_type =3 ";
    	$template->setValue("{FIELD}",$template->checkAdvMsg(10009));
    }
    
	$option_str.="<option value='5' $selected >".$template->checkAdvMsg(10164)."</option>";
	
	
}


    $selected="";
	if($type==6)
    {
    	$selected="selected";
    }
    
	$option_str.="<option value='6' $selected >".$template->checkAdvMsg(1206)."</option>";
	

$template->setValue("{OPTION}","$option_str");

$pageno=1;
if(isset($_REQUEST['page']))
	$pageno=getSafePositiveInteger('page');
$perpagesize = 20;
$uid=$user->getUserID();

if($type==1) //paypal
{
 


$total=$mysql->echo_one("select count(*) from inout_ppc_ipns  where userid='$uid' AND result='1' ");
$p=$paging->page($total,$perpagesize,"","payments.php?type=1");
$template->setValue("{HEADER}","$p");

$beg=(($pageno-1)*$perpagesize+1);

if($total>=1) 
{
 if(($pageno*$perpagesize)<=$total) 
    $end=$pageno*$perpagesize;
 else 
  $end=$total;
}

$combined_msg=$template->checkAdvMsg(10011);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW}",$msg_replace2);

if($currency_format=="$$")
	{$template->setValue("{CURRENCY_SYMBOL}",$paypal_currency);}
else
	{$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); }
	
$template->openLoop("ADS","select amount,currency,payeremail,receivedat   from inout_ppc_ipns  where userid='$uid' AND result='1' order by ipnid DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(ADS)-AMOUNT}","amount");
$template->setLoopField("{LOOP(ADS)-EMAIL}","payeremail");
$template->setLoopField("{LOOP(ADS)-TIME}","receivedat");
$template->closeLoop();

}

if($type==2) // auth payment
{
	



$total=$mysql->echo_one("select count(*) from authorize_ipn  where x_cust_id='$uid' ");
$p=$paging->page($total,$perpagesize,"","payments.php?type=2");
$template->setValue("{HEADER}","$p");

$beg=(($pageno-1)*$perpagesize+1);

if($total>=1) 
{
 if(($pageno*$perpagesize)<=$total)
 $end= $pageno*$perpagesize;
 else 
 $end=$total;
}

$combined_msg=$template->checkAdvMsg(10011);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW}",$msg_replace2);
if($currency_format=="$$")
$template->setValue("{CURRENCY_SYMBOL}",$system_currency); 
else
$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); 

$template->openLoop("AUTH","select x_trans_id,x_invoice_num,x_amount,timestamp   from authorize_ipn  where x_cust_id='$uid' order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(AUTH)-AMOUNT}","x_amount");
$template->setLoopField("{LOOP(AUTH)-INVOICE}","x_invoice_num");
$template->setLoopField("{LOOP(AUTH)-TXID}","x_trans_id");
$template->setLoopField("{LOOP(AUTH)-TIME}","timestamp");
$template->closeLoop();


	
}


if($type==3 || $type==4 || $type==5)
{
		

$total=$mysql->echo_one("select count(*) from advertiser_fund_deposit_history a ".$typestr." and a.uid=$uid " );
$p=$paging->page($total,$perpagesize,"","payments.php?type=$type");
$template->setValue("{HEADER}","$p");

$beg=(($pageno-1)*$perpagesize+1);

if($total>=1) 
{ 
 if(($pageno*$perpagesize)<=$total) 
  $end=$pageno*$perpagesize;
 else 
   $end=$total;
}

$combined_msg=$template->checkAdvMsg(10011);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW}",$msg_replace2);

    if($currency_format=="$$")
	{
		$template->setValue("{CURRENCY_SYMBOL}",$system_currency);
		$curren_symbol=$system_currency;
	}
    else
	{
		$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol);
		$curren_symbol=$currency_symbol;
		
	 }
 
if($type==3 || $type==4  )
 {
$template->openLoop("CHECK","select checkno,bankname,accountholdersname,amount,date,status,currency_type,pay_type from advertiser_fund_deposit_history a  ".$typestr." and uid='$uid' order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(CHECK)-CHEKNO}","checkno");
$template->setLoopField("{LOOP(CHECK)-BANK}","bankname");
$template->setLoopField("{LOOP(CHECK)-NAME}","accountholdersname");
$template->setLoopField("{LOOP(CHECK)-AMT}","amount");
$template->setLoopField("{LOOP(CHECK)-DATE}","date");
$template->setLoopField("{LOOP(CHECK)-STATUS}","status");
$template->setLoopField("{LOOP(CHECK)-CURRENCY}","currency_type");
$template->setLoopField("{LOOP(CHECK)-PTYPE}","pay_type");

$template->closeLoop();
  
  }
 else
 {
$template->openLoop("TRANSFER","select  amount,date,status,currency_type,pay_type from advertiser_fund_deposit_history a  ".$typestr." and uid='$uid' order by id DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(TRANSFER)-AMT}","amount");
$template->setLoopField("{LOOP(TRANSFER)-DATE}","date");
$template->setLoopField("{LOOP(TRANSFER)-STATUS}","status");
$template->setLoopField("{LOOP(TRANSFER)-CURRENCY}","currency_type");
$template->setLoopField("{LOOP(TRANSFER)-PTYPE}","pay_type");

$template->closeLoop();
 } 
  
$emass=$template->checkAdvMsg(8945);
$emass1=str_replace("{CURRENCY1}",$curren_symbol,$emass);
$template->setValue("{EMESS}",$emass1);

}

if($type==6)
{
	


$total=$mysql->echo_one("select count(*) from advertiser_bonus_deposit_history where aid=$uid ");
$p=$paging->page($total,$perpagesize,"","payments.php?type=6");
$template->setValue("{HEADER}","$p");
$beg=(($pageno-1)*$perpagesize+1);

if($total>=1) 
{
 if(($pageno*$perpagesize)<=$total) 
   $end=$pageno*$perpagesize;
 else 
  $end=$total;
}
$combined_msg=$template->checkAdvMsg(10011);
$msg_replace=str_replace("{TOTAL1}",$total,$combined_msg);
$msg_replace1=str_replace("{BEG}",$beg,$msg_replace);
$msg_replace2=str_replace("{END}",$end,$msg_replace1);
$template->setValue("{SHOW}",$msg_replace2);
if($currency_format=="$$")
	{$template->setValue("{CURRENCY_SYMBOL}",$system_currency);}
else
	{$template->setValue("{CURRENCY_SYMBOL}",$currency_symbol); }


$template->openLoop("OTH","select amount,type,logtime,comment,username from advertiser_bonus_deposit_history a,ppc_users b where b.uid=a.aid and b.status=1 and a.aid=$uid   order by logtime DESC LIMIT ".(($pageno-1)*$perpagesize).", ".$perpagesize);
$template->setLoopField("{LOOP(OTH)-AMT}","amount");
$template->setLoopField("{LOOP(OTH)-TYPE}","type");
$template->setLoopField("{LOOP(OTH)-DATE}","logtime");
$template->setLoopField("{LOOP(OTH)-COMMENT}","comment");
$template->setLoopField("{LOOP(OTH)-NAME}","username");


$template->closeLoop();


}


$template->setValue("{MODE}",$advertiser_checkpayment);
$template->setValue("{ENGINE_NAME}",$template->checkAdvMsg(0001));                                              
$selected_colorcode=array();
foreach ($color_code as $key=>$value)
 {
  if($key==$color_theme)
  {
  $selected_colorcode=$value;
  break;
  }
  
}
$template->setValue("{COLORTHEME3}","style=\" background-color:$selected_colorcode[2]\"");
$template->setValue("{COLORTHEME4}","style=\" color:$selected_colorcode[3]\"");
$template->setValue("{COLORTHEME5}","style=\" color:$selected_colorcode[4]\"");


$template->setValue("{PAGEWIDTH}",$page_width);     $template->setValue("{ENCODING}",$ad_display_char_encoding);     

eval('?>'.$template->getPage().'<?php ');
?>

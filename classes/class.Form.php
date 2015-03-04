<?php
/*

 "class.Form.php" this file is a class which supports some basic mysql operation.
  All rights reserved and all the rights of the file and script goes to Jacob Baby[jacobbbc@yahoo.co.in] only.
  
  Created on:15/10/2007
  Modified on:15/10/2007
  
*/
/*

Funtion HELP

*/

class Form{

var $formname;
var $formaction;

// Javascript validation related settings.
var $form_validation="";
var $func_set="";

var $func_isPositive=0;
var $func_isEmail=0;
var $func_isNotNull=0;
var $func_isSame=0;
var $func_isNotShort=0;
var $func_isOverMin=0;
var $func_isWhitespacePresent=0;
var $func_isPositiveInteger=0;
var $func_isValidDomain=0;



	function Form($form_name,$actionfile)
	{
		$this->formname=$form_name;
		$this->formaction=$actionfile;
		$this->func_isEmail=0;
		
	}
	

	
	function isDomain($fieldname,$error_message="Invalid format for domain name")
	{
	
			if($this->func_isValidDomain==0) {

	 $this->func_set = $this->func_set."
		
	<!-- Script by hscripts.com -->


if(!(typeof myEqualsIgnoreCase == 'function') )
{

	
String.prototype.equalsIgnoreCase=myEqualsIgnoreCase;

function myEqualsIgnoreCase(arg)
{               
        return (new String(this.toLowerCase())==(new String(arg)).toLowerCase());
}

}

if(!(typeof checkDomain == 'function') )
{


function checkDomain(nname)
{
var arr = new Array(
'.com','.net','.org','.biz','.coop','.info','.museum','.name',
'.pro','.edu','.gov','.int','.mil','.ac','.ad','.ae','.af','.ag',
'.ai','.al','.am','.an','.ao','.aq','.ar','.as','.at','.au','.aw',
'.az','.ba','.bb','.bd','.be','.bf','.bg','.bh','.bi','.bj','.bm',
'.bn','.bo','.br','.bs','.bt','.bv','.bw','.by','.bz','.ca','.cc',
'.cd','.cf','.cg','.ch','.ci','.ck','.cl','.cm','.cn','.co','.cr',
'.cu','.cv','.cx','.cy','.cz','.de','.dj','.dk','.dm','.do','.dz',
'.ec','.ee','.eg','.eh','.er','.es','.et','.fi','.fj','.fk','.fm',
'.fo','.fr','.ga','.gd','.ge','.gf','.gg','.gh','.gi','.gl','.gm',
'.gn','.gp','.gq','.gr','.gs','.gt','.gu','.gv','.gy','.hk','.hm',
'.hn','.hr','.ht','.hu','.id','.ie','.il','.im','.in','.io','.iq',
'.ir','.is','.it','.je','.jm','.jo','.jp','.ke','.kg','.kh','.ki',
'.km','.kn','.kp','.kr','.kw','.ky','.kz','.la','.lb','.lc','.li',
'.lk','.lr','.ls','.lt','.lu','.lv','.ly','.ma','.mc','.md','.mg',
'.mh','.mk','.ml','.mm','.mn','.mo','.mp','.mq','.mr','.ms','.mt',
'.mu','.mv','.mw','.mx','.my','.mz','.na','.nc','.ne','.nf','.ng',
'.ni','.nl','.no','.np','.nr','.nu','.nz','.om','.pa','.pe','.pf',
'.pg','.ph','.pk','.pl','.pm','.pn','.pr','.ps','.pt','.pw','.py',
'.qa','.re','.ro','.rw','.ru','.sa','.sb','.sc','.sd','.se','.sg',
'.sh','.si','.sj','.sk','.sl','.sm','.sn','.so','.sr','.st','.sv',
'.sy','.sz','.tc','.td','.tf','.tg','.th','.tj','.tk','.tm','.tn',
'.to','.tp','.tr','.tt','.tv','.tw','.tz','.ua','.ug','.uk','.um',
'.us','.uy','.uz','.va','.vc','.ve','.vg','.vi','.vn','.vu','.ws',
'.wf','.ye','.yt','.yu','.za','.zm','.zw','.eu','.mobi','.travel',
'.aero','.arpa','.asia','.cat','.jobs','.tel','.me');

//alert(nname.substring(0,7));

if(nname.substring(0,7).equalsIgnoreCase(\"http://\"))
{
nname=nname.substring(7);
}
if(nname.substr(0,8).equalsIgnoreCase (\"https://\"))
{
nname=nname.substr(8);
}

var mai = nname;
var val = true;

var dot = mai.lastIndexOf(\".\");
var dname = mai.substring(0,dot);
var ext = mai.substring(dot,mai.length);
//alert(ext);
	
if(dot>2 && dot<57)
{
	for(var i=0; i<arr.length; i++)
	{
	  if(ext == arr[i])
	  {
	 	val = true;
		break;
	  }	
	  else
	  {
	 	val = false;
	  }
	}
	if(val == false)
	{
	  	// alert(\"Your domain extension \"+ext+\" is not correct\");
		 return false;
	}
	else
	{
		for(var j=0; j<dname.length; j++)
		{
		  var dh = dname.charAt(j);
		  var hh = dh.charCodeAt(0);
		  if((hh > 47 && hh<59) || (hh > 64 && hh<91) || (hh > 96 && hh<123) || hh==45 || hh==46)
		  {
			 if((j==0 || j==dname.length-1) && hh == 45)	
		  	 {
		 	  	// alert(\"Domain name should not begin or end with '-'\");
			      return false;
		 	 }
		  }
		else	{
		  	// alert(\"Your domain name should not have special characters\");
			 return false;
		  }
		}
	}
}
else
{
// alert(\"Your Domain name is too short/long\");
 return false;
}	

return true;
}
}
<!-- Script by hscripts.com -->

";

	$this->func_isValidDomain=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(checkDomain(document.$this->formname.$fieldname.value)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}
	 
	
	function isPositive($fieldname,$error_message="Invalid Number")
	{
	
	if($this->func_isPositive==0) {

	 $this->func_set = $this->func_set.'
if(!(typeof isPositive == "function") )
{	 
	function isPositive(strString)
   //  check for valid numeric strings	
   {
   var strValidChars = "0123456789.";
   var strChar;
   var retResult = true;

   if (strString.length == 0) return false;

   //  test strString consists of valid characters listed above
   for (i = 0; i < strString.length && retResult == true; i++)
      {
      strChar = strString.charAt(i);
      if (strValidChars.indexOf(strChar) == -1)
         {
         retResult = false;
         }
      }
   return retResult;
   }
}   
';
	$this->func_isPositive=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(isPositive(document.$this->formname.$fieldname.value)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}




	function isEmail($fieldname,$error_message="Invalid Email Address")
	{
	
	if($this->func_isEmail==0)
	 {
	 $this->func_set = $this->func_set.'
if(!(typeof isEmail == "function") )
{		 
	function isEmail(emailString)
	{
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(emailString))
		{
		return (true);
		}
		return (false);
	}
}	
';
	$this->func_isEmail=1;
	}
	
	$this->form_validation=$this->form_validation."
	if(isEmail(document.$this->formname.$fieldname.value)==false) 
	{	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}





	
	function isNotNull($fieldname,$error_message="Value cannot be NULL")
	{
	
	if($this->func_isNotNull==0) {
		
	 $this->func_set= $this->func_set.'
if(!(typeof trim == "function") )
{	 
	function trim(stringValue){return stringValue.replace(/(^\s*|\s*$)/, "");}
}

if(!(typeof isNotNull == "function") )
{		
	function isNotNull(strString)
   //  checks wheteher value of field not null 	
   {

  	if(!strString) return false;
    if (trim(strString).length == 0) return false;
   
   	
	
   	}
}   
	
';
	$this->func_isNotNull=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(isNotNull(document.$this->formname.$fieldname.value)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}





	function isWhitespacePresent($fieldname,$error_message="Value cannot contain whitespace.")
	{
	
	if($this->func_isWhitespacePresent==0) 
	{
		
	 $this->func_set= $this->func_set.'
	 
if(!(typeof isWhitespacePresent == "function") )
{		
	function isWhitespacePresent(strString)
   {

	var where_is_space=strString.indexOf(\' \');
	if(where_is_space==-1)
		return true;
	else
		return false;
	}
  }
	
';
	$this->func_isWhitespacePresent=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(isWhitespacePresent(document.$this->formname.$fieldname.value)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}



	function isPositiveInteger($fieldname,$error_message="Value should be a positive integer.")
	{
	
	if($this->func_isPositiveInteger==0) 
	{
		
	 $this->func_set= $this->func_set.'
	 
if(!(typeof isPositiveInteger == "function") )
{	
	function isPositiveInteger(val)
				{
 				  // alert(val.value);
				  if(val==0)
				    {
			        //alert(val);
			        return false;
				    }

			    for (var i = 0; i < val.length; i++) 
				    {
			        var ch = val.charAt(i)

			        if (ch < "0" || ch > "9")
				        {
			            return false
				        }
				    }
			    return true
			}
 } 
	
';
	$this->func_isPositiveInteger=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(isPositiveInteger(document.$this->formname.$fieldname.value)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}



	
	function isSame($fieldname1,$fieldname2,$error_message="Values are not same")
	{
	
	if($this->func_isSame==0) {
		
	 $this->func_set= $this->func_set.'
if(!(typeof isSame == "function") )
{	 
	function isSame(strString1, strString2)
   	//checks wheteher both field values are same
    {   
    	if (strString1 != strString2 ) return false;
   	}

 }  	
 ';
	$this->func_isSame=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(isSame(document.$this->formname.$fieldname1.value,document.$this->formname.$fieldname2.value)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname1.focus();
	return false;
	}
	
	";	
	
	
	}











	function isNotShort($fieldname,$fieldlength,$error_message="Length of the text entered is small")
	{
	
	if($this->func_isNotShort==0) {
		
	 $this->func_set= $this->func_set.'
if(!(typeof isNotShort == "function") )
{	 
	function isNotShort(strString, strLength)
   	//checks wheteher value has minimum length
    {   
    	if (strString.length < strLength ) return false;
   	}
}   	
';
	$this->func_isNotShort=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(isNotShort(document.$this->formname.$fieldname.value, $fieldlength)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}




	function isOverMin($fieldname,$fieldminvalue,$error_message="Value entered is less than minimum value")
	{
	
	if($this->func_isOverMin==0) {
		
	 $this->func_set= $this->func_set.'
if(!(typeof isOverMin == "function") )
{	 
	function isOverMin(strString, minvalue)
   	//checks wheteher value has minimum length
    {   
    	if (strString < minvalue ) return false;
   	}
}   	
';
	$this->func_isOverMin=1;
	
	}
	
	$this->form_validation=$this->form_validation."
	if(isOverMin(document.$this->formname.$fieldname.value, $fieldminvalue)==false) {	
	alert(\"$error_message\");
	document.$this->formname.$fieldname.focus();
	return false;
	}
	";	
	
	
	}








	function formStart()
	{
		$jscript="";
		$returnstring='<form name="'.$this->formname.'" id="'.$this->formname.'" method="post" enctype="multipart/form-data" action="'.$this->formaction.'"';
		if($this->form_validation!="")
		{
		
		
		$jscript='<script type="text/javascript">'.$this->func_set.'function verifyForm_'.$this->formname.'(){'.$this->form_validation.'}</script>';
		
		$returnstring=$returnstring.' onSubmit="return verifyForm_'.$this->formname.'()"';	
		
		}
		
		$returnstring=$jscript.$returnstring.">";
		return $returnstring;
	
	}
	
	function addTextBox($name,$display_value="",$size=25,$max="255")
	{
		return '<input name="'.$name.'" type="text" id="'.$name.'" size="'.$size.'" value="'.$display_value.'" maxlength="'.$max.'">';
	
	}
	function addTextArea($name,$display_value="",$size=25,$lines=5,$max="2500")
	{
		return '<textarea name="'.$name.'"  id="'.$name.'" cols="'.$size.'" rows="'.$lines.'" maxlength="'.$max.'">'.$display_value.'</textarea>';
	
	}
	
	function addHiddenField($name,$value)
	{
		return '<input name="'.$name.'" type="hidden" id="'.$name.'" value="'.$value.'">';
	}
	function addPassword($name,$size=25,$display_value="")
	{
		return '<input name="'.$name.'" type="password" id="'.$name.'" size="'.$size.'" value="'.$display_value.'">';
	}
	
	function addSubmit($display_value,$name="submit")
	{
		return '<input type="submit" name="'.$name.'" value="'.$display_value.'" class="submit">';
	}
	function addButton($display_value,$fun_cal,$name="button")
		{
		if($fun_cal=="")
			{
			return '<input type="button" class="submit" name="'.$name.'" value="'.$display_value.'">';
			}
		else
			{
			return '<input type="button" class="submit"  name="'.$name.'" value="'.$display_value.' "onClick="'.$fun_cal.'">';
			}
		}
	/*
	function addSelectBox()
	{
	
	}
	
	function addRadioButton()
	{
	}
	
	*/
	function formClose()
	{
	 return '</form>';
	}
}
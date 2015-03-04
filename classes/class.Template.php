<?php 

/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/




$advertiser_message=array();
$publisher_message=array();
$common_message=array();
$message=array();


$dif_array_adv=array();
$dif_array_pub=array();
$dif_array_com=array();
$dif_array_msg=array();


$client_language_default="";
$tplvalues=array();
$tplindex=1;
$loopvalues=array();
$loopindex=1;

class Template

{
	var $filepath;
	var $filecontent;
	var $filefolder;
	var $fileindex;
	
	var $loopidx;
	var $loops=array();
	var $loopvariable;

	var $cachepath;
	
	function Template()
	{
		global $tplindex; 
		$tplindex++;
		$this->fileindex=$tplindex;
	}
	
	
	function loadTemplate($file)
	{
		if(!file_exists($file))
		{
			echo "<br><strong>Error</strong> : Cannot load the template => <em>$file</em>. Please check the filename.";
			die;
		}


		global $advertiser_message;
		global $publisher_message;
		global $common_message;
		
		global $dif_array_adv;
		global $dif_array_pub;
		global $dif_array_com;
		
		global $client_language;
		global $client_language_default;
		global $cache_templates,$cache_folder;
		
		if(!$client_language_default)
			$client_language_default=$client_language;
	
		$ind=strpos($file,'/');
		$this->filefolder=substr($file,0,$ind);
		//echo $this->filefolder;
		
		$this->cachepath=$cache_folder."/".md5($file).".php";
		
		
		if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
		{
			$langid=$_COOKIE['language'];
			$result=mysql_query("select code from adserver_languages where id='$langid'");
			$row=mysql_fetch_row($result);
			$langcookie=$row[0];
			
			if($this->filefolder == "ppc-templates")
			{
				if(!file_exists("locale/advertiser-template-".$langcookie."-inc.php"))
				{
					$langcookie=$GLOBALS['client_language'];
				}
			}
			if($this->filefolder == "publisher-templates")
			{
				if(!file_exists("locale/publisher-template-".$langcookie."-inc.php"))
				{
					$langcookie=$GLOBALS['client_language'];
				}
			
			}
			if($this->filefolder == "common-templates")
			{
				if(!file_exists("locale/common-template-".$langcookie."-inc.php"))
				{
					$langcookie=$GLOBALS['client_language'];
				}
			}
		}
		else
		{
			$langcookie=$GLOBALS['client_language'];
		}
		$result1=mysql_query("select direction from adserver_languages where code='$langcookie'");
		$row1=mysql_fetch_row($result1);
		$GLOBALS['direction']=$row1[0];
		$GLOBALS['client_language']=$langcookie;
		
		
		if($this->filefolder == "ppc-templates")
		{
			include_once("locale/advertiser-template-".$langcookie."-inc.php");
			if(!isset($dif_array_adv[0]))
				$dif_array_adv[0]=$advertiser_message;
			
			include_once("locale/advertiser-template-".$client_language_default."-inc.php");
			if(!isset($dif_array_adv[1]))
				$dif_array_adv[1]=$advertiser_message;
		}
		else if($this->filefolder == "publisher-templates")
		{
			include_once("locale/publisher-template-".$langcookie."-inc.php");
			
			if(!isset($dif_array_pub[0]))
				$dif_array_pub[0]=$publisher_message;
			
			include_once("locale/publisher-template-".$client_language_default."-inc.php");
			if(!isset($dif_array_pub[1]))
				$dif_array_pub[1]=$publisher_message;
		
		}
		if($this->filefolder == "common-templates")
		{
		
			include_once("locale/common-template-".$langcookie."-inc.php");
			if(!isset($dif_array_com[0]))
				$dif_array_com[0]=$common_message;
			
			include_once("locale/common-template-".$client_language_default."-inc.php");
			if(!isset($dif_array_com[1]))
				$dif_array_com[1]=$common_message;
			
		}
		
		
		$this->filepath=$file;
		
		
		
		if(	file_exists($this->cachepath)==true && $cache_templates==true)
		{
			$fp=fopen($this->cachepath,"r");
			$this->filecontent=fread($fp, filesize($this->cachepath));
			fclose($fp);
		}
		else
		{
			$fp=fopen($this->filepath,"r");
			$this->filecontent=fread($fp, filesize($this->filepath));
			fclose($fp);
		}
		//echo $this->filepath;
		//echo $this->filecontent;
	}

	function setValue($source,$target)
	{
		preg_match("/^\{[A-Z0-9_]+\}$/",$source,$valid_match);
		if(count($valid_match)==0)
		{
			echo "<br><strong>Error</strong> : Invalid format for template variable => <em>$source</em>. Please use a format similar to {VAR}.";
			die;
		}
		global $tplvalues;
		//echo $source."     ".substr($source,1,strlen($source)-2);die;
		$tplvalues[$this->fileindex][substr($source,1,strlen($source)-2)] = $target;
	}


	

	function getPage()
	{
		global $dif_array_adv;
		global $dif_array_pub;
		global $dif_array_com;
		//global $advertiser_message;
		//global $publisher_message;
		
		global $tplvalues,$loopvalues;
		
		global $cache_templates;
		
		
		if(	file_exists($this->cachepath)==true && $cache_templates==true)
		{//check whether cache page exists and include the cached file.
			return $this->filecontent;
		}	
		
		
		
		
		
		if($this->filefolder == "ppc-templates")
			$this->filecontent=preg_replace('/(\{advertiser_msg:)(.+?)(\})/','<?php if($dif_array_adv[0][$2]!="") echo $dif_array_adv[0][$2]; else echo $dif_array_adv[1][$2]; ?>',$this->filecontent);
		else if($this->filefolder == "publisher-templates")
			$this->filecontent=preg_replace('/(\{publisher_msg:)(.+?)(\})/','<?php if($dif_array_pub[0][$2]!="") echo $dif_array_pub[0][$2]; else echo $dif_array_pub[1][$2]; ?>',$this->filecontent);
		else if($this->filefolder == "common-templates")
			$this->filecontent=preg_replace('/(\{common_msg:)(.+?)(\})/','<?php if($dif_array_com[0][$2]!="") echo $dif_array_com[0][$2]; else echo $dif_array_com[1][$2]; ?>',$this->filecontent);
		
		

		
		if(is_array($loopvalues))
		{	
		foreach ($loopvalues as $loopkey=>$loopval)
		{
			if($loopval['tplindex']!=$this->fileindex) // proceed only if the loop is in the current template
				continue;
		//	print_r($loopval);
			$loopcontent='';
			$matches=array();
			preg_match('/({LOOP\('.$loopval['loopvariable'].'\)-STARTLOOP})(.*)({LOOP\('.$loopval['loopvariable'].'\)-ENDLOOP})/is',$this->filecontent,$matches);
			$loopcontent= $matches[2];
			
	
		//echo $loopcontent."\n";
						
		for($i=0;$i<$loopval['fieldscount'];$i++)	
		{
			//echo $arraykeys[$i]."<br />";
			//echo '${'.$loopval['loopfields'][$i].'}'. '<br>';


			
			$loopcontent=str_replace('${'.$loopval['loopfields'][$i].'}','$val[\''.$loopval['tablefields'][$i].'\']',$loopcontent);
			$loopcontent=str_replace('{'.$loopval['loopfields'][$i].'}','<?php echo $val[\''.$loopval['tablefields'][$i].'\']; ?>',$loopcontent);

			$loopcontent=str_replace('${LOOP('.$loopval['loopvariable'].')-LOOPKEY}','$key',$loopcontent);
			$loopcontent=str_replace('{LOOP('.$loopval['loopvariable'].')-LOOPKEY}','<?php echo $key; ?>',$loopcontent);
			
		}
		
		
		//echo $loopcontent; die;
		
			$norecordstring='<tr align="left" height="25" valign="bottom"><td colspan="'.$loopval['fieldscount'].'">'.$this->checkmsg(6052).'<br/></td></tr>';
			



			
			$loopvalue='
<?php 
$tmp=count($loopvalues['.$loopkey.'][\'arraydata\']);
$loopvar_'.$loopkey.'=0;
if($tmp>0)
{
	/*****************************************************************/
	foreach ($loopvalues['.$loopkey.'][\'arraydata\'] as $key=>$val) 
	{ 
	?>'.$loopcontent.'<?php
	}
	$loopvar_'.$loopkey.'++;
	if($loopvar_'.$loopkey.'>=$loopvalues['.$loopkey.'][\'loopcount\'])
		break;
	/*****************************************************************/
}	
else
{?>
'.$norecordstring.'
<?php
}?>';
			//die;
			
			preg_match('/(.*)({LOOP\('.$loopval['loopvariable'].'\)-STARTLOOP})/is',$this->filecontent,$before);
			preg_match('/({LOOP\('.$loopval['loopvariable'].'\)-ENDLOOP})(.*)/is',$this->filecontent,$after);
			
			$this->filecontent=$before[1].$loopvalue.$after[2];
		
		}
		}
		
		$ifarray=array();
		//$tmpvar="";
		preg_match_all("/((\{if)|(\{elseif)|(\{fn:))(.+?)(\)\})/i",$this->filecontent,$ifarray); // Finding the if-elseif-validate related variables to replace variables in it.

		//print_r($ifarray);die;
		
		$replacertable=array();
		
		for($i=0;$i<count($ifarray[0]);$i+=1)
		{
			$replacertable[$i][0]=$ifarray[0][$i];
			$replacertable[$i][1]=$ifarray[0][$i];			
			
		}						
		$replacertablecount=count($replacertable);
						

		
		
		for($j=0; $j<$replacertablecount; $j+=1)
		{					
			/* order of replacing is important :) */
			//echo $replacertable[$j][1]." ";
			$replacertable[$j][1] = preg_replace('/(\{fn:)(.+?)(\)\})/i','<?php echo $2);  ?>',$replacertable[$j][1]);		
			$replacertable[$j][1] = str_replace('{if','<?php if',$replacertable[$j][1]);
			$replacertable[$j][1] = str_replace('{elseif','<?php } elseif',$replacertable[$j][1]);
			$replacertable[$j][1] = str_replace(')}',') { ?>',$replacertable[$j][1]);
			//echo $replacertable[$j][1]."\n";
		}			
		//die;
		$this->filecontent = str_replace('{else}','<?php } else { ?>',$this->filecontent);
		$this->filecontent = str_replace('{endif}','<?php } ?>',$this->filecontent);
		
		for($i=0; $i<$replacertablecount; $i+=1)
		{			
			$this->filecontent= str_replace($replacertable[$i][0],$replacertable[$i][1],$this->filecontent);
		}			
		
		
		
		$arraykeys=array_keys($tplvalues[$this->fileindex]);						
						
		for($i=0;$i<count($arraykeys);$i++)	
		{
			//echo $arraykeys[$i]."<br />";
		
			$this->filecontent=str_replace('${'.$arraykeys[$i].'}','$tplvalues['.$this->fileindex.'][\''.$arraykeys[$i].'\']',$this->filecontent);
			$this->filecontent=str_replace('{'.$arraykeys[$i].'}','<?php echo $tplvalues['.$this->fileindex.'][\''.$arraykeys[$i].'\'];?>',$this->filecontent);
		/*	$this->filecontent=str_replace('{noescape:$'.$arraykeys[$i].'}','<?php echo $this->tplvalues[\''.$arraykeys[$i].'\'];?>',$this->filecontent);
			$this->filecontent=str_replace('{seo:$'.$arraykeys[$i].'}','<?php echo preg_replace(\'/[^A-Za-z\-0-9]/\', \'\',strip_tags(str_replace(" ","-",$this->tplvalues[\''.$arraykeys[$i].'\'])));?>',$this->filecontent);
			*/
			
		}
		//global $tplvalues;print_r($tplvalues);
		
		
		
		if($cache_templates==true)
		{ // Need to load from cache file is template caching is ON
				
					if($fp=fopen($this->cachepath,"w"))
					{
						fputs($fp,$this->filecontent);
						fclose($fp);
					}
					else
					{
						echo "<br><strong>Error</strong> : Cannot write into cache folder. Give write permission for the script on the <b>".$cache_path."</b> folder.";
						die;
					}
					
		}
	
						
		return $this->filecontent;
		
//		$fp=fopen($GLOBALS['cache_folder']."/templates/".md5($this->filepath);
	}

	function openLoop($loopvariable,$input,$count=1000)
	{
		if($this->loopvariable!="")
		{
			echo "<br><strong>Error</strong> : Cannot open the loop => <em>$loopvariable</em>. Please close any open loop before opening another.";
			die;
		}

		global $loopvalues,$loopindex; 
		$loopindex++;
		$this->loopidx=$loopindex;

		$loopvalues[$this->loopidx]=array();
		$loopvalues[$this->loopidx]['tplindex']=$this->fileindex;
		$loopvalues[$this->loopidx]['fieldscount']=0;
		$loopvalues[$this->loopidx]['loopvariable']=$loopvariable;
		$loopvalues[$this->loopidx]['loopcount']=$count;
		$loopvalues[$this->loopidx]['loopfields']=array();
		$loopvalues[$this->loopidx]['tablefields']=array();
		
		if(is_array($input))
		{
			$loopvalues[$this->loopidx]['looptype']="array";
			$loopvalues[$this->loopidx]['arraydata']=$input;
			
		}
		else
		{
			$loopvalues[$this->loopidx]['looptype']="sql";

			$sqlresult=mysql_query($input);
			$i=0;
			$temparr=array();
			while ( $i<$count && $r = mysql_fetch_assoc($sqlresult)) 
			{
				 $temparr[] = $r;
				 $i++;
			}

			$loopvalues[$this->loopidx]['arraydata']=$temparr;

		}
		
		$this->loopvariable=$loopvariable;


	}
	
	function setLoopField($template_fieldname,$table_fieldname)
	{
		preg_match("/^\{LOOP\(".$this->loopvariable."\)\-[A-Z0-9_]+\}$/",$template_fieldname,$valid_match);
		if(count($valid_match)==0)
		//if(substr($template_fieldname,0,1)!='{' && substr($template_fieldname,0,-1)!='}' )
		{
			echo "<br><strong>Error</strong> : Invalid format for loop variable => <em>$template_fieldname</em>. Please use a format similar to {LOOP(NAME)-VAR}.";
			die;
		}
		
		global $loopvalues;
		$loopvalues[$this->loopidx]['fieldscount']+=1;
		$loopvalues[$this->loopidx]['loopfields'][]=substr($template_fieldname,1,strlen($template_fieldname)-2);
		$loopvalues[$this->loopidx]['tablefields'][]=$table_fieldname;
		
	}
	
	function closeLoop()
	{
		if($this->loopvariable=="")
		{
			echo "<br><strong>Error</strong> : Cannot close the loop. No loops are open.";
			die;
		}



		$this->loopvariable='';

	}
	
	function checkmsg($passmid)
  	{
		global $dif_array_msg;
		global $client_language;
		global $client_language_default;
		
		if(!$client_language_default)
		{
			$client_language_default=$client_language;
			
		if(isset($_COOKIE['language']) && ($_COOKIE['language']!=""))
		{
			$langid=$_COOKIE['language'];
			$result=mysql_query("select code from adserver_languages where id='$langid'");
			$row=mysql_fetch_row($result);
			$langcookie=$row[0];
		}
		else
		{
			$langcookie=$GLOBALS['client_language'];
		}
		$result1=mysql_query("select direction from adserver_languages where code='$langcookie'");
		$row1=mysql_fetch_row($result1);
		$GLOBALS['direction']=$row1[0];
		$GLOBALS['client_language']=$langcookie;
			
			
			
			
			
	    }		
		
		 $lang=$GLOBALS['client_language'];
		
//		if(!file_exists("locale/messages.".$lang.".inc.php"))
//		{
//			include_once("locale/messages.".$client_language_default.".inc.php");
//		}
//		else
//		{

			include_once("locale/messages.".$lang.".inc.php");
			if(!isset($dif_array_msg[0]))
				$dif_array_msg[0]=$message;
		
			include_once("locale/messages.".$client_language_default.".inc.php");
			if(!isset($dif_array_msg[1]))
				$dif_array_msg[1]=$message;
				
			if($dif_array_msg[0][$passmid]!="")
				$return_var=  $dif_array_msg[0][$passmid];
			else
				$return_var=  $dif_array_msg[1][$passmid];


			preg_match_all("/{(.+?)}/i",$return_var,$resultset);
  	        $j=count($resultset[1]);

 	        for($i=0;$i<$j;$i++)
 	        {
     	     $return_var=str_replace("{".$resultset[1][$i]."}",$GLOBALS[$resultset[1][$i]],$return_var);
		     
	        }

			return $return_var;
			
			
  	}
  	
  	
	function checkAdvMsg($passmid)
	{
		global $dif_array_adv;
		if($dif_array_adv[0][$passmid]!="")
			return  $dif_array_adv[0][$passmid];
		else
			return  $dif_array_adv[1][$passmid];
	}
  	
  
	function checkPubMsg($passmid)
	{
		global $dif_array_pub;
		if($dif_array_pub[0][$passmid]!="")
			return  $dif_array_pub[0][$passmid];
		else
			return  $dif_array_pub[1][$passmid];
	}
	
	function checkComMsg($passmid)
	{
		global $dif_array_com;
		if($dif_array_com[0][$passmid]!="")
			return  $dif_array_com[0][$passmid];
		else
			return  $dif_array_com[1][$passmid];
	}
	
	
	function includePage($source,$pagename)
	{
		
		if(	!(file_exists($this->cachepath)==true && $cache_templates==true))

			$this->filecontent=str_replace($source,'<?php include("'.$pagename.'"); ?>',$this->filecontent);		
	}

}



?>
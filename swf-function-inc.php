<?php

// Define SWF tags
define ('swf_tag_identify', 		 "FWS");
define ('swf_tag_compressed', 		 "CWS");
define ('swf_tag_geturl',   		 "\x83");
define ('swf_tag_null',     		 "\x00");
define ('swf_tag_actionpush', 		 "\x96");
define ('swf_tag_actiongetvariable', "\x1C");
define ('swf_tag_actiongeturl2', 	 "\x9A\x01");
define ('swf_tag_actiongetmember', 	 "\x4E");


// Define preferences
$GLOBALS['swf_variable']   = 'alink';		// The name of the ActionScript variable used for urls
$GLOBALS['swf_target_var'] = 'atar';		// The name of the ActionScript variable used for targets




/*-------------------------------------------------------*/
/* Convert hard coded urls                               */
/*-------------------------------------------------------*/

function Inout_FlashConvertFun($buffer, $compress, $allowed)
{

	global $swf_variable, $swf_target_var;

	// Decompress if file is a Flash MX compressed file
	if (Inout_FlashCompressedFun($buffer))
		$buffer = Inout_FlashDecompressFun($buffer);


	$parameters = array();
	$linkcount = 1;
	$allowedcount = 1;
	$final = $buffer;

	$masked_parts = array();

	while (preg_match('/								# begin
							^
							(
							.+?							# match anything from the start
							\x00						# match a swf_tag_null
							)
							(
								\x83						# match a swf_tag_geturl
								..							# match a UI16  (combined length)
								(
									(?:https?:\/\/|javascript:).+?		# match the url
								)
								\x00						# match a swf_tag_null
								(
									.*?						# match the target
								)
								\x00						# match a swf_tag_null
							|							# or
								\x96						# match a swf_tag_actionpush
								..							# match a U16 word (length)
								\x00						# match a swf_tag_null
								(
									(?:https?:\/\/|javascript:).+?		# match the url
								)
								\x00						# match a swf_tag_null
								\x96						# match a swf_tag_actionpush
								..							# match a UI16 (length)
								\x00						# match a swf_tag_null
								(
									.*?						# match the target
								)
								\x00						# match a swf_tag_null
								\x9A\x01					# match a swf_tag_geturl2
							)
						/sx', $buffer, $m))
	{
	
		$geturl_part	= $m[2];
		$previous_part	= $m[1];

		// Replace masked parts with actual content
		if (count($masked_parts)) {
		    $previous_part = strtr($previous_part, array_flip($masked_parts));
		}

		$allowed_types = array(12, 26, 34); // DoAction, PlaceObject2, DefineButton2
		$original = '';
		for ($len = 2; $len < strlen($previous_part); $len++)
		{
			$recordheader = substr($previous_part, -$len);
			$object_tag = substr($recordheader, 0, 2);
			$expected_len = strlen($geturl_part) + $len;

			$tag_type = (ord($object_tag{1}) << 2 | (ord($object_tag{0}) & ~0x3F) >> 6) & 0xFF;

			if (!in_array($tag_type, $allowed_types))
				continue;

			// Check for long RECORDHEADER
			if ($len > 6 && (ord($object_tag{0}) & 0x3F) == 0x3F)
			{
				$object_extended = true;
				$object_len = unpack('V', substr($recordheader, 2, 4));
				$object_len = current($object_len);
				$expected_len -= 6;
			}
			else
			{
				$object_extended = false;
				$object_len = ord($object_tag{0}) & 0x3F;
				$expected_len -= 2;
			}

			if ($object_len >= $expected_len)
			{
				$replacement = $original = $recordheader.$geturl_part;

				break;
			}
		}

		if (!strlen($original))
			die("Error: unsupported tag");

		$geturl2_part = swf_tag_actionpush.
							chr(strlen('_root')+2).
							swf_tag_null.
						swf_tag_null.
							'_root'.
						swf_tag_null.

						swf_tag_actiongetvariable.

						swf_tag_actionpush.
							chr(strlen($swf_variable.$linkcount)+2).
							swf_tag_null.
						swf_tag_null.
							$swf_variable.
							$linkcount.
						swf_tag_null.

						swf_tag_actiongetmember.

						swf_tag_actionpush.
							chr(strlen('_root')+2).
							swf_tag_null.
						swf_tag_null.
							'_root'.
						swf_tag_null.

						swf_tag_actiongetvariable.

						swf_tag_actionpush.
							chr(strlen($swf_target_var.$linkcount)+2).
							swf_tag_null.
						swf_tag_null.
							$swf_target_var.
							$linkcount.
						swf_tag_null.

						swf_tag_actiongetmember.

						swf_tag_actiongeturl2.

						swf_tag_null.

						swf_tag_null;

        // Check for functions (ActionDefineFunction)
        if (preg_match('/^.*(\x9B(..).*?)(..)$/s', $recordheader, $m)) {
			$fheader_len = unpack('v', $m[2]);
			$fheader_len = current($fheader_len);
			$fbody_len = unpack('v', $m[3]);
			$fbody_len = current($fbody_len);
			if ($fheader_len == strlen($m[1]) - 1)
			{
				// getURL is inside an ActionDefineFunction
				$fbody_len += strlen($geturl2_part) - strlen($geturl_part);
				$geturl_part	= $m[1].$m[3].$geturl_part;
				$geturl2_part	= $m[1].pack('v', $fbody_len).$geturl2_part;
			}
		}

		$replacement = str_replace($geturl_part, $geturl2_part, $replacement);

		$object_len2 = $object_len + strlen($geturl2_part) - strlen($geturl_part);

		$replacement = substr($replacement, $object_extended ? 6 : 2);

		if ($object_len2 < 0x3F)
			$replacement = chr(($object_tag{0} && 0xC0) | $object_len2).$object_tag{1}.$replacement;
		else
			$replacement = chr(ord($object_tag{0}) | 0x3F).$object_tag{1}.pack('V', $object_len2).$replacement;

		// Check for DefineSprite
		$definesprite_part = substr($previous_part, -strlen($recordheader) - 10, 10);
		if (preg_match('/^\xFF\x09(....)(....)$/s', $definesprite_part, $m)) {
            // Long DefineSprite recordheader
			$object_len = unpack('V', $m[1]);
			$object_len = current($object_len);

			$object_tag = substr($definesprite_part, 0, 2);

			$object_len += strlen($geturl2_part) - strlen($geturl_part);

			$original = $definesprite_part.$original;

			$replacement = chr(ord($object_tag{0}) | 0x3F).$object_tag{1}.pack('V', $object_len).$m[2].$replacement;
		}

		// Is this link allowed to be converted?
		if (in_array($allowedcount, $allowed))
		{
    		// Convert
		    $final = str_replace($original, $replacement, $final);

			// Fix file size
			$file_size = unpack('V', substr($final, 4, 4));
			$file_size = current($file_size) + strlen($replacement) - strlen($original);

			$final = substr($final, 0, 4).pack('V', $file_size).substr($final, 8);

			$parameters[$linkcount] = $allowedcount;

			$linkcount++;
		} else {
    		$mask = '^'.pack('v', $allowedcount).'|'.str_pad('', strlen($geturl_part) - 5, "\xDE\xAD\xBE\xEF").'$';

    		$masked_parts[$geturl_part] = $mask;
		}

		$allowedcount++;

		$buffer = strtr($final, $masked_parts);
	}


	if ($compress == true)
		$final = Inout_FlashCompressFun($final);
	else
		$final = Inout_FlashUpgradeFun($final);

	return (array($final, $parameters));
}

/*-------------------------------------------------------*/
/* Upgrade version of a Flash file                       */
/*-------------------------------------------------------*/

function Inout_FlashUpgradeFun($buffer)
{
	$version = ord(substr($buffer, 3, 1));

	if ($version < 5)
	{
		 $version = 5;

		$output = substr ($buffer, 0, 3);
		$output .= chr($version);
		$output .= substr ($buffer, 4, 4);
		$output .= substr ($buffer, 8);

		return ($output);
	}
	else
		return ($buffer);
}



/*-------------------------------------------------------*/
/* Is the Flash file compressed?                         */
/*-------------------------------------------------------*/
//define ('swf_tag_compressed', 		 "CWS");
function Inout_FlashCompressedFun($buffer)
{

	if (substr($buffer, 0, 3) == swf_tag_compressed)
		return true;
	else
		return false;
}



/*-------------------------------------------------------*/
/* Compress Flash file                                   */
/*-------------------------------------------------------*/

function Inout_FlashCompressFun($buffer)
{
	$version = ord(substr($buffer, 3, 1));

	if (function_exists('gzcompress') &&
	    substr($buffer, 0, 3) == swf_tag_identify &&
		$version >= 3)
	{
		// When compressing an old file, update
		// version, otherwise keep existing version
		if ($version < 6) $version = 6;

		$output  = 'C';
		$output .= substr ($buffer, 1, 2);
		$output .= chr($version);
		$output .= substr ($buffer, 4, 4);
		$output .= gzcompress (substr ($buffer, 8));

		return ($output);
	}
	else
		return ($buffer);
}

/*-------------------------------------------------------*/
/* Decompress Flash file                                 */
/*-------------------------------------------------------*/

function Inout_FlashDecompressFun($buffer)
{
	if (function_exists('gzuncompress') &&
		substr($buffer, 0, 3) == swf_tag_compressed &&
		ord(substr($buffer, 3, 1)) >= 6)
	{
		$output  = 'F';
		$output .= substr ($buffer, 1, 7);
		$output .= gzuncompress (substr ($buffer, 8));

		return ($output);
	}
	else
		return ($buffer);
}



//**********************************************  Find Hard Coded Links  **********************************************************************

function Inout_FlashInfoFun($buffer)
{
	global $swf_variable, $swf_target_var;

	// Decompress if file is a Flash MX compressed file
	if (Inout_FlashCompressedFun($buffer))
		$buffer = Inout_FlashDecompressFun($buffer);

	$parameters = array();
	$linkcount = 1;

	while (preg_match('/								# begin
							(?<=\x00)					# match if preceded by a swf_tag_null
							(?:
								\x83						# match a swf_tag_geturl
								..							# match a UI16  (combined length)
								(
									(?:https?:\/\/|javascript:).+?		# match the url
								)
								\x00						# match a swf_tag_null
								(
									.*?						# match the target
								)
								\x00						# match a swf_tag_null
							|							# or
								\x96						# match a swf_tag_actionpush
								..							# match a U16 word (length)
								\x00						# match a swf_tag_null
								(
									(?:https?:\/\/|javascript:).+?		# match the url
								)
								\x00						# match a swf_tag_null
								\x96						# match a swf_tag_actionpush
								..							# match a UI16 (length)
								\x00						# match a swf_tag_null
								(
									.*?						# match the target
								)
								\x00						# match a swf_tag_null
								\x9A\x01					# match a swf_tag_geturl2
							)
						/sx', $buffer, $m))
	{
	
		if ($m[0]{0} == chr(0x83))
		{
			$parameter_url = $m[1];
			$parameter_target = $m[2];
		}
		else
		{
			$parameter_url = $m[3];
			$parameter_target = $m[4];
		}

		$parameters[$linkcount] = array(
			$parameter_url, $parameter_target
		);

		$buffer = str_replace($m[0], '', $buffer);

		$linkcount++;
	}
	
	
	
	
	/**********************************/
	
   $regex  = '/(<a\s*'; // Start of anchor tag
   $regex .= '(.*?)\s*'; // Any attributes or spaces that may or may not exist
   $regex .= 'href=[\'"]+?\s*(?P<link>\S+)\s*[\'"]+?'; // Grab the link
   $regex .= '\s*(.*?)\s*>\s*'; // Any attributes or spaces that may or may not exist before closing tag
   $regex .= '(?P<name>\S+)'; // Grab the name
   $regex .= '\s*<\/a>)/i'; // Any number of spaces between the closing anchor tag (case insensitive)

	preg_match_all($regex, $buffer, $matches, PREG_SET_ORDER);
	//print_r($matches);die;
	
	if(count($matches) >0)
	{
	$flg_href=1;
	}
	else
	{
	$flg_href=0;
	}
	
	
	/*
	foreach($matches as $key => $value)               
	{
	
	$parameters[$linkcount] = array(
			$value[3], ""
		);
	$linkcount=$linkcount+1;
	}
	*/
	
	
	
	
	//echo count($parameters);die;
	
	/*********************************/
	

	//if (count($parameters))
		//return ($parameters);
		
		
		return (array($parameters, $flg_href));
	//else
	
	
	//	return false;
		
		
}

//**********************************************  Find Hard Coded Links  **********************************************************************


/*-------------------------------------------------------*/
/* Get the Flash version of the banner                   */
/*-------------------------------------------------------*/

function Inout_FlashVersionFun($buffer)
{
	if (substr($buffer, 0, 3) == swf_tag_identify ||
		substr($buffer, 0, 3) == swf_tag_compressed)
		return ord(substr($buffer, 3, 1));
	else
		return false;
}




function Inout_FlashImageRetrieveFun($name)
{

 $result = '';
            if ($fp = @fopen($name, 'rb')) {
                while (!feof($fp)) {
                    $result .= @fread($fp, 8192);
                }
                @fclose($fp);
            }
			
			
	if (!empty($result))
	 {
	 return ($result);
	 }
	else
	 {
	return false;
	 }


}


/*-------------------------------------------------------*/
/* Store a file on the webserver                         */
/*-------------------------------------------------------*/

function Inout_FlashImageStoreFun($name,$buffer)
{
	// Local mode, get the unique filename
			
		
         
    			// Write the file
    			if ($fp = @fopen($name, 'wb')) {
							
    				@fwrite($fp, $buffer);
    				@fclose($fp);
    			}
				
      
		
}

?>
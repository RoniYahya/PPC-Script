<?php 



/*--------------------------------------------------+

|													 |

| Copyright © 2006 http://www.inoutscripts.com/      |

| All Rights Reserved.								 |

| Email: contact@inoutscripts.com                    |

|                                                    |

+---------------------------------------------------*/







?><?php

/*



 "paging.cls.php" this file is a class used to disaply result in different pages in case of there we have results that we ca't include in one page

  All rights reserved and all the rights of the file and script goes to Jacob Baby[jacobbbc@yahoo.co.in] only.

  

  Created on:13/05/2006

  Modified on:13/05/2006

  

*/





/*



Funtion HELP



 */



class paging

{
	function page($total,$perpage,$pagenumber="",$linkformat="",$linkstyle="paginglinkstyle")
	{	
		$out="";
		if($linkstyle!="") 
			$linkstyle="class=\"".$linkstyle."\"";

		if($total>$perpage)
		{
			if($linkformat=="")
			{
				$linkformat=$_SERVER['PHP_SELF']; $linkformat.="?";
			}
			else
			{
				if(substr_count($linkformat,"?")>0)
					$linkformat.="&"; 
				else
					$linkformat.="?";
			}
			if($pagenumber=="")
			{
				 if(isset($_REQUEST['page']))
					$pagenumber=$_REQUEST['page'];
				 else
					$pagenumber=1;
			}
			if(!is_numeric($pagenumber)||$pagenumber<1)
				$pagenumber=1;
			else
				$pagenumber=intval($pagenumber);
			$lastpage=ceil($total/$perpage);
			
			if($pagenumber>=2)
				$out.="<a href=\"$linkformat"."page=".($pagenumber-1)."\" >"."&laquo; Previous"."</a>&nbsp;"; 
			
			for($i=$pagenumber>5?($pagenumber-5):1,$count=1;($i<=$lastpage && $count<=11); $i++,$count++)
			{
				if($pagenumber==$i)
					$out.=$i."&nbsp;";
				else 
					$out.="<a href=\"$linkformat"."page=".$i."\">".$i."</a>&nbsp;";
			}
			
			if($pagenumber<$lastpage)
		 		$out.="<a href=\"$linkformat"."page=".($pagenumber+1)."\" >"."Next &raquo;"."</a> "; 
		}
		 return '<div '.$linkstyle.'>&nbsp;'.$out.'</div>';
	}
}
?>
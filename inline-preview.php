<?php
ob_flush();
ob_start();



?>


.top, .bottom {display:block; background:transparent; font-size:1px;width:<?php  echo $row['width']; ?>;}
.tb1, .tb2, .tb3, .tb4 {display:block; overflow:hidden;}
.tb1, .tb2, .tb3 {height:1px;}
.tb2, .tb3, .tb4 {background:<?php if($row['credit_text_positioning']==1 && $row['credit_text'] !=0) echo $r1[2]; else echo $row['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>}
.tb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.tb2 {margin:0 3px; border-width:0 2px;}
.tb3 {margin:0 2px;}
.tb4 {height:2px; margin:0 1px;}
.bb1, .bb2, .bb3, .bb4 {display:block; overflow:hidden;}
.bb1, .bb2, .bb3 {height:1px;}
.bb2, .bb3, .bb4 {background:<?php if($row['credit_text_positioning']==0 && $row['credit_text'] !=0) echo $r1[2];  else echo  $row['bg_color']; ?>; border-left:1px solid <?php echo $r1[2]; ?>; border-right:1px solid <?php echo $r1[2]; ?>}
.bb1 {margin:0 5px; background:<?php echo $r1[2]; ?>;}
.bb2 {margin:0 3px; border-width:0 2px;}
.bb3 {margin:0 2px;}
.bb4 {height:1px; margin:0 1px;}
.parenttable {display:block; background:#FFFFFF; border-style:solid; border-color: <?php echo $r1[2]; ?>; border-width:0 1px; padding: 0px;margin:0 0px;}





.inout-inline-innertable

{

padding:0px;

 padding-left:0px;

margin:0px 0px;

table-layout:fixed;

overflow:hidden;

line-height: <?php echo $row['line_height']; ?>px;



	border: 1px solid  <?php echo $r1[2]; ?>;
	<?php 	if($bordertype==0 && $row['credit_text_positioning']==1 && $row['credit_text'] !=0) { ?>
	
		border-bottom:0px;
		<?php } 
		 if($bordertype==0 && $row['credit_text_positioning']==0 && $row['credit_text'] !=0) { ?>
		border-top:0px;
		<?php } 
		if($row['credit_text'] ==0 && $bordertype==0)
		{
		?>
		border-bottom:0px;
		border-top:0px;
		<?php } ?>
background:<?php if($adunittype==2) echo "#FFFFFF";  else echo  $row['bg_color']; ?>;





}
.inout-inline-innertable td
{
vertical-align:middle;
padding:3px;
}

	.inout-title a:link,.inout-title a:visited,.inout-title a:hover,.inout-title a:active,.inout-title a:focus
		{
		font-family:<?php echo $row['title_font'];?>;
		font-size:<?php echo $row['title_size'];?>;
		color:<?php echo $row['tcolor'];?>;
		
		<?php
		if($row['ad_font_weight']==2)
		$f_weight="bold";
		else
		$f_weight="normal";	
		?>
		font-weight:<?php echo $f_weight; ?>;
		<?php 
		if($row['ad_title_decoration']==3)
		$deco="blink";
		elseif($row['ad_title_decoration']==1)
		$deco="none";	
		else
		$deco="underline";		
		?>
		text-decoration:<?php echo $deco; ?>;
		padding:0px;
		}
		


		

		

		.inout-inner-desc 

		{

		font-family:<?php echo $row['desc_font'];?>;

		font-size:<?php echo $row['desc_size'];?>px;

		color:<?php echo $row['dcolor'];?>;

		<?php

		if($row['ad_desc_font_weight']==2)

		$f_weight="bold";

		else

		$f_weight="normal";	

		?>

		font-weight:<?php echo $f_weight; ?>;

		<?php 

		if($row['ad_desc_decoration']==3)

		$deco="blink";

		elseif($row['ad_desc_decoration']==1)

		$deco="none";	

		else

		$deco="underline";		

		?>

		text-decoration:<?php echo $deco; ?>;

		padding:0px;
		margin:0px;

		}

		

		

		.inout-inline-url a:link,.inout-inline-url a:visited,.inout-inline-url a:hover,.inout-inline-url a:active,.inout-inline-url a:focus

		{

		font-family:<?php echo $row['url_font'];?>;

		font-size:<?php echo $row['	url_size'];?>px;

		color:<?php echo $row['ucolor'];?>;

		<?php

		if($row['ad_disp_url_font_weight']==2)

		$f_weight="bold";

		else

		$f_weight="normal";	

		?>

		font-weight:<?php echo $f_weight; ?>;

		<?php 

		if($row['ad_disp_url_decoration']==3)

		$deco="blink";

		elseif($row['ad_disp_url_decoration']==1)

		$deco="none";	

		else

		$deco="underline";		

		?>

		text-decoration:<?php echo $deco; ?>;

		 white-space:nowrap;

		padding:0px;
		margin:0px;
		}





.inout-inline-credit

{





	
	


width:<?php echo $row['width']; ?>px ;

<?php
if($ct ==0)
{
?>
background-color:<?php echo $r1[2]; ?>;

<?php
} ?>

margin:0px;

white-space:nowrap;

padding:0px 0px;

<?php

if($row['credit_text_alignment']==0)

$t_align="left";

else

$t_align="right";	

?>



text-align:<?php echo $t_align; ?>;
table-layout:fixed;
overflow:hidden;


}

.inout-inline-credit td
{

height:15px;

padding:0px;
margin:0px;


}


.inout-inline-credit a:link,.inout-inline-credit a:visited,.inout-inline-credit a:hover,.inout-inline-credit a:active,.inout-inline-credit a:focus

{
padding:0px;
margin:0px;
color:<?php echo $r1[1]; ?>;

font-family:<?php echo $row['credit_font'];?>;

font-size:12px;

line-height: 15px;

<?php

if($row['credit_text_font_weight']==2)

$f_weight="bold";

else

$f_weight="normal";	

?>

font-weight:<?php echo $f_weight; ?>;

<?php 

if($row['credit_text_decoration']==3)

$deco="blink";

elseif($row['credit_text_decoration']==1)

$deco="none";	

else

$deco="underline";		

?>

text-decoration:<?php echo $deco; ?>;

}









<?php



$stylestring = ob_get_contents();	
$stylestring= ereg_replace("(\r\n|\n|\r)", " ", $stylestring);


ob_clean();

$blockheight= $row['height'];
if($row['credit_text']!=0)
   $blockheight=$blockheight;
		


$inlineblock1='<div id="inimage" style="display:none;background-image:url('.$server_dir.'images/loaddata.gif);background-repeat:no-repeat; width:35px; height:35px;z-index:11; position:absolute;"></div>';	

$inlineblock1.='<div id="inline0" style="display:none;width:'.$row['width'].'px;z-index:10; position:absolute;overflow:hidden;padding:0px;margin:0px" >';





//echo $row[3]."fffffF";

if($row['ad_type']==7)
			{
					 $catalog_width=$catalog[2];
					 $catalog_height=$catalog[1];
		
	
			
	
			//	        $inlineblock.='<tr ><td>';
			if($bordertype==0) //rounded corner uses 10px of block height, 5 at top and 5 at bottom
		{
	$inlineblock.= '<b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b>';
		$blockheight= $row['height']-10;
		}


						$inlineblock.= '<table height="'.$blockheight.'px" border="0" cellpadding="0" cellspacing="0" class="inout-inline-innertable" width="'.$row['width'].'px"  >';

	if($row['credit_text_positioning']==1 && $row['credit_text'] !=0)
				$inlineblock.= $credittextpreview;	
			

					//	$currentadcount+=1;
						//$currentcatalogadcount+=1;
 


						$inlineblock.='<tr height="100%"><td >';
						$inlineblock.='<table width="100%" cellpadding="5" height="100%" cellspacing="0" border=0 style="line-height:'.$row['line_height'].'px;table-layout:fixed;overflow:hidden;';
						$inlineblock.='">';
						$inlineblock.='<tr><td align="center"  valign="top" style="width:'.$catalog_width.'px;"><div style="height:'.$catalog_height.'px;width:'.$catalog_width.'px;background: url('.$server_dir.'images/banner.gif) repeat;">';
  						$inlineblock.='</div></td> <td align="left" valign="top">';
						$inlineblock.='<span class="inout-title"><a href="#" target="_blank">'.$ad_title.'</a></span><br>';
						$inlineblock.='<span class="inout-inner-desc">'.$d_description.'</span></td></tr></table>';
						$inlineblock.='</td></tr>';
						if($row['credit_text_positioning']==0 && $row['credit_text'] !=0)
										$inlineblock.= $credittextpreview;	

						
									$inlineblock.='</table>';
										if($bordertype==0)
		{
		$inlineblock.= '<b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b>';
		}

						}
						else if($row['ad_type']==6)
						{
							if($bordertype==0) //rounded corner uses 10px of block height, 5 at top and 5 at bottom
		{
	$inlineblock.= '<b class="top"><b class="tb1"></b><b class="tb2"></b><b class="tb3"></b><b class="tb4"></b></b>';
		$blockheight= $row['height']-10;
		}
		
						$inlineblock.= '<table height="'. $blockheight.'px"  border="0" cellpadding="0" cellspacing="0" class="inout-inline-innertable" width="'.$row['width'].'"  >';
						
						if($row['credit_text_positioning']==1 && $row['credit_text'] !=0)
				$inlineblock.= $credittextpreview;	
						
						$inlineblock.='<tr><td ><span class="inout-title"><a href="#" target="_blank">'.$ad_title.'</a></span>';
						if($row['text_ad_type']!=2)// not title only
					{
						$inlineblock.='<br><span class="inout-inner-desc">'.$d_description.'</span>';
						}
						if($row['text_ad_type']==1) // title/desc/display url
					{
						$inlineblock.='<br><span class="inout-inline-url"><a href="#" target="_blank">'.$ad_display_url.'</a></span>';
						}
						$inlineblock.='</td></tr>';
						
						if($row['credit_text_positioning']==0 && $row['credit_text'] !=0)
					$inlineblock.= $credittextpreview;	
					
					$inlineblock.= '</table>';
					
					
					if($bordertype==0)
		{
		$inlineblock.= '<b class="bottom"><b class="bb4"></b><b class="bb3"></b><b class="bb2"></b><b class="bb1"></b></b>';
		}
						
						}
								
						$inlineblock1.='</div>';			
						$inlineblock= str_replace("{ENC_IP}",$enc_ip,$inlineblock);
				
			//	echo htmlspecialchars($inlineblock);
					

?>

<script language="javascript" type="text/javascript">

	//document.write(alert("<?php  $inlineblock; ?>"));
//alert('fffff');
			var ss1 = document.createElement('style');

			var def = '<?php echo $stylestring; ?>';

			ss1.setAttribute("type", "text/css");

			if (ss1.styleSheet) {   // IE

			ss1.styleSheet.cssText = def;

			} else {                // the world

			var tt1 = document.createTextNode(def);

			ss1.appendChild(tt1);

			}

			var hh1 = document.getElementsByTagName('head')[0];

			hh1.appendChild(ss1);


			//document.write(	'<?php echo $inlineblock; ?>');
		

		


String.prototype.equals=myEquals;



function myEquals(arg)

{

        return (this.toString()==arg.toString());

}











<?php if($driname =="admin")
{
?>
var inlinew="Hello";
<?php
}
else
{
?>

var inlinew = '<?php echo $template->checkPubMsg(1346); ?>';
if(inlinew=="")
{
var inlinew="Hello";
}
else
{

var inlinew = '<?php echo $template->checkPubMsg(1346); ?>';
}

<?php
}
?>

















	var alreadyrunflag=0 //flag to indicate whether target function has already been run
	var index=10;
	var index=10;
	var engine="";

	
	var window_obj = window;
	var ie=document.all && !window.opera;
	var iebody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body ;
	var scr_ht=(ie)? iebody.clientHeight: window.innerHeight ;//screen.height
	var scr_wt=(ie)? iebody.clientWidth : window.innerWidth ;//screen.width
	var op = 0;
	if (window_obj.navigator.userAgent.indexOf("Opera")!=-1)
		op = 1;

	var a_prm = "#27A91F";
	var inline_words ;
	var inline_words_ids ;
	var inline_urls ;
	var inline_evt_names = new Array("onmouseover","onmouseout","onclick")

	var curWord = "";
	var slct_wrd_count = 0;
	var wrd_count;
	var hgl_interv;
	var load_interv;
	
	var inlineadoffsetheight=<?php echo $row['height']; ?>;
    var inlineadoffsetwidth =<?php echo $row['width']; ?>;

	var direction;

	
	



	function startupInline()
	{
			//alert('started');
		var divImgTag = document.createElement("div");
		divImgTag.id = "<?php echo 'inimage';?>";
		divImgTag.setAttribute("style","<?php echo 'display:none; width:35px; height:35px;z-index:11; position:absolute;'; ?>");
		divImgTag.style.display='none';
		divImgTag.style.position='absolute';
		//divImgTag.style.border='1px solid red';
		divImgTag.innerHTML='<img src="<?php echo $server_dir;?>images/loaddata.gif">';
		document.getElementsByTagName('body')[0].appendChild(divImgTag);           
	
		divTag = document.createElement("div");
		divTag.id = "<?php echo 'inline1';?>";
		divTag.setAttribute("style","<?php echo 'width:'.$row['width'].'px;display:none;z-index:10; position:absolute;overflow:hidden;padding:0px;margin:0px;height:'.($row['height']).'px;'; ?>");
		divTag.innerHTML='<?php echo $inlineblock; ?>';
		divTag.style.display='none';
		//divTag.style.border='1px solid red';
		//alert(divTag.style.display);
		document.getElementsByTagName('body')[0].appendChild(divTag);  
		

		inline_words =new Array(inlinew);
		inline_words_ids = new Array('1');
		inline_urls=new Array('#');
		TriggerInlineAds()
	}


	function AttachDivEvtHandlers()
	{
		
		
		
			AddInlineEventHandlers(GetObject("inline1"),  new Array("onmouseover","onmouseout"),  new Array(Show, Hide) );   // events for ad displaying div tags
			
	}
	function AddInlineEventHandlers(target_obj, evt_arr, evt_handler_array)
	{
		var i;
		if(!target_obj || !evt_arr || !evt_handler_array || evt_arr.length!=evt_handler_array.length) return;
		for (i=0;i < evt_arr.length;i++)
			if(ie && !op) 
				target_obj.attachEvent(evt_arr[i],evt_handler_array[i]);
			else
				target_obj.addEventListener(evt_arr[i].replace("on",""),evt_handler_array[i],true);
	}

	function GetObject(s_id)
	{
		//alert(s_id);
		return document.getElementById(s_id);
	}
	
	function Show(e)  // keep showing the div
	{
		if (!e) var e = window.event;
		relTarg = e.srcElement ? e.srcElement : e.target;
		relTargid=relTarg.id;
		relTargid=relTargid.replace('inlinef','inline');
		InlineHideCancel(relTargid);
	}
	
	function InlineHideCancel(idstr) // cancel timer initiated for hiding the div
	{
	
		//alert(idstr);
		if(eval('window.tmr'+idstr)) // ******************************************** commented for  IE
			eval('window_obj.clearTimeout(window.tmr'+idstr+');');
	}
	
	function Hide(e)  // initiate hiding the div
	{
		doc_el = document.documentElement; 
		scrl_top = doc_el.scrollTop ? doc_el.scrollTop : document.body.scrollTop; 
		if (!e) var e = window.event;
		relTarg = e.srcElement ? e.srcElement : e.target;
		relTargid=relTarg.id;
		relTargid=relTargid.replace('inlinef','inline');
		if(((e.clientX<=relTarg.offsetLeft) || (e.clientX>=(relTarg.offsetLeft+inlineadoffsetwidth))) || (((e.clientY+scrl_top)>=(relTarg.offsetTop+inlineadoffsetheight)) || ((e.clientY+scrl_top)<=relTarg.offsetTop)))
		{
			eval('window.tmr'+relTargid+'=window_obj.setTimeout("InlineHide(\''+relTargid+'\')",1000);');
		}
	}

	function InlineHide(x)  // hide the div immediately
	{	
		x="inline1";
		var msg_div = GetObject(x);
	
		if (msg_div) 
			msg_div.style.display = "none";
	}


	function TriggerInlineAds()
	{	
		AttachDivEvtHandlers()
		hgl_interv = window_obj.setInterval("InlineHighlight()",10);
		//alert(1);
	}
	
	//TriggerInlineAds()


	function InlineHighlight()
	{
		if (slct_wrd_count == inline_words.length) 
			window_obj.clearInterval(hgl_interv);
		else 
		{
			try 
			{ 
				wrd_count=0;
				inline_words[slct_wrd_count] = decodeURI(inline_words[slct_wrd_count]);		
				inline_words[slct_wrd_count] = inline_words[slct_wrd_count].replace(/^\s+/g,"");//remove heading whitespace
				inline_words[slct_wrd_count] =inline_words[slct_wrd_count].replace(/\s+$/g,"");//remove trailing whitespace
				InlineRecursive(document.body, inline_words[slct_wrd_count]);
			} 
			catch(e) 
			{	
				//alert(e); 
			}
			slct_wrd_count++;
		}
	}
	

	function InlineRecursive(main_nd, srch_word)
	{
		if(srch_word==curWord) return;
		var o_chld_nd, o_pr_nd, s_nd_nm, tmp_nd_val;
		var i,j,k,m, srch_word_cnt = 0;
		var hi_txt, hi_txt_nd, hi_txt_nd_hold, s_txt;
		var evHandlers = new Array(InlineShow, InlineHideDelay, InlineClick);
		if(main_nd.childNodes && main_nd.childNodes.length && srch_word.length)
		{
			s_txt = ie ? main_nd.innerText : main_nd.textContent;
			if (s_txt.toLowerCase().indexOf(srch_word.toLowerCase()) == -1)
				return;
			for (i=0; i < main_nd.childNodes.length && srch_word_cnt < 4; i++)
			{
				o_chld_nd = main_nd.childNodes[i];
				o_pr_nd = o_chld_nd.parentNode;		
				if (!o_pr_nd || o_pr_nd.getAttribute("tpi"))
					return;
				s_nd_nm = o_pr_nd.nodeName;
				if (s_nd_nm.indexOf("H")==0 || s_nd_nm=="SCRIPT" || s_nd_nm=="STYLE" || s_nd_nm=="A" || s_nd_nm=="TEXTAREA" || s_nd_nm=="INPUT")
					return; 
				if (o_chld_nd.nodeValue && o_chld_nd.nodeValue.length && o_chld_nd.nodeType == 3) 
				{			  	
					tmp_nd_val = o_chld_nd.nodeValue.toLowerCase();
					ni = GetInlineWord(tmp_nd_val,srch_word); 
					if (ni>-1)
					{ 
						wrd_count++; 
						if (wrd_count%2!=0) 
						{ 
							m=1; 
							while(ni>-1 && curWord!=srch_word) 
							{ 
								nv = o_chld_nd.nodeValue; 
								before = document.createTextNode(nv.substr(0,ni)); 
								hi_txt = nv.substr(ni,srch_word.length); 
								after = document.createTextNode(nv.substr(ni+srch_word.length)); 
								hi_txt_nd = document.createTextNode(hi_txt); 
								hi_txt_nd_hold = document.createElement("A"); 
								with(hi_txt_nd_hold) 
								{ 
									style.cssText = "position:relative;padding:0px 0px 1px 0px;border-bottom:1px solid "+a_prm+"; color:"+a_prm+"; text-decoration:underline; cursor:pointer; ";
									
									setAttribute("tpi","1"); 
									setAttribute("id",'inlinex'+inline_words_ids[slct_wrd_count]); 
									setAttribute("href","#"); 
									setAttribute("target","_blank"); 
									//	this.setAttribute("value","inimage"); 
									if (op)
										setAttribute("onmouseover","InlineShow(event)"); 
									appendChild(hi_txt_nd); 
								} 
								AddInlineEventHandlers(hi_txt_nd_hold,inline_evt_names,evHandlers);							
								curWord=srch_word;
								with(o_pr_nd) 
								{
									insertBefore(before,o_chld_nd);
									insertBefore(hi_txt_nd_hold,o_chld_nd);
									insertBefore(after,o_chld_nd);
									removeChild(o_chld_nd);
								}
								o_chld_nd = after;
								tmp_nd_val = o_chld_nd.nodeValue.toLowerCase();
								ni = GetInlineWord(tmp_nd_val,srch_word);
								m++;
								srch_word_cnt++;
							}
							i += m;
						}
					}
				}			
				InlineRecursive(o_chld_nd,srch_word);
			}
		}
		else
			return;
	}


	function GetInlineWord(text_data, word)
	{	
		var srch_word = new RegExp(word, "ig");
		var signs = "\n\ ,.!?\"";
		var chr_before, chr_after, do_ret = 0;
		var tmp=0, add=0, val1=0, val2=text_data.length-1;
		try 
		{
			while (!do_ret) 
			{
				do_ret = 1;//alert(text_data);
				tmp = text_data.search(srch_word);	
				if (tmp > -1) 
				{									
					//if (!ie) 
					{
						val1 = 2;
						val2 = text_data.length - 2;
					}
					if (tmp > val1) 
					{
						chr_before = text_data.charAt(tmp-1);
						if (signs.indexOf(chr_before)==-1) 
							do_ret = 0;           //alert(chr_before+'%'+do_ret);
					}	
					if ((tmp + word.length) <= val2) 
					{			
						chr_after = text_data.charAt(tmp+word.length);
						if (signs.indexOf(chr_after)==-1) 
							do_ret = 0;				//alert(chr_after+'%'+do_ret);
					}
				}
				else
					add = 0;
				if (!do_ret) 
				{
					add += tmp+word.length;
					text_data = text_data.substring(tmp+word.length,text_data.length);
				}

			}//alert(tmp);
			return (tmp + add);
		} 
		catch(e) 
		{ 
				return -1; 
		}
	}
	
	

	function InlineShow(event)
	{	
		var o_child, over, word, tt_data_ref, doc_el;
		var elemOfsX = elemOfsY = y_co = scrl_top = 0;
		var ev = event;
		if (!ev) return;
		var elem1 = elem = ev.srcElement ? ev.srcElement : ev.target;
		var do_renew = 0;
		var o_nd_type = ie ? 1 : elem1.ELEMENT_NODE;
		idstring=elem1.id;
		idstring=idstring.replace('inlinex','inline');
		InlineHideCancel(idstring);
		while(elem1.nodeType != o_nd_type)
			elem1 = elem1.parentNode;
		if (elem1.offsetParent)
			while (elem1.offsetParent) 
			{
				elemOfsX += elem1.offsetLeft;
				elemOfsY += elem1.offsetTop;
				elem1 = elem1.offsetParent;
			}
	
		//alert(GetObject('ifr'+idstring).src);
		msg_div=GetObject('inimage');
		//alert(msg_div);
		GetObject(idstring).style.zIndex=++index;
		
		if(GetObject(idstring).style.display.equals("none"))
		{
			doc_el = document.documentElement; 
			scrl_top = doc_el.scrollTop ? doc_el.scrollTop : document.body.scrollTop; 
			over = (elemOfsX + inlineadoffsetwidth) - document.body.offsetWidth + 10; 
			if(scr_wt>document.body.offsetWidth)	
				over = (elemOfsX + inlineadoffsetwidth) - scr_wt + 10; 
			elemOfsX1=elemOfsX;
			if (over > 0) 
				elemOfsX -= inlineadoffsetwidth; 
			if (elemOfsY - scrl_top < inlineadoffsetheight + 10) 
			{
				y_co = elemOfsY + elem.offsetHeight -2; 
				y_co1=elemOfsY+ elem.offsetHeight;
				direction='down';
			}
			else 
			{
				y_co = elemOfsY- inlineadoffsetheight; 
				y_co1=elemOfsY-25;
				direction='up';
			}
			msg_div.style.left = (elemOfsX1 + "px");
			msg_div.style.top = (y_co1 + "px");
			msg_div.style.display="";
			eval('window.height'+idstring+'=0;');
			
	//	ShowInlineAd(idstring,elemOfsX,y_co);
			eval('window.loadimage'+idstring +'=window_obj.setTimeout("ShowInlineAd(\''+idstring+'\',\''+elemOfsX+'\',\''+y_co+'\')",500);');
			
			
		}
	}
	
	
	
	function ShowInlineAd(idstring,elemOfsX,y_co)
	{
		msg_div=GetObject(idstring);
		image=GetObject('inimage');
		msg_div.style.left = (elemOfsX + "px");
		msg_div.style.top = (y_co + "px");
		msg_div.style.display="block";
		msg_div.style.position='absolute';
		image.style.display="none";
		if(direction=='down')
		{
			if(eval('window.height'+idstring)==0)
			{
				eval('window.loadimage'+idstring +'=window_obj.setInterval("ShowInlineAd(\''+idstring+'\',\''+elemOfsX+'\',\''+y_co+'\')",20);');
				
				}
			eval('window.height'+idstring+'+=5;');
			if(eval('window.height'+idstring) <= inlineadoffsetheight+5)
				msg_div.style.height = eval('window.height'+idstring)+"px";
			else	
			{
				// AddInlineEventHandlers(msg_div,  new Array("onmouseover","onmouseout"),  new Array(Show, Hide) );							
				eval('window_obj.clearInterval(window.loadimage'+idstring+');');
				eval('window.loadimage'+idstring+'=null;');
			}
			//alert(msg_div.style.height);
		}
		else
		{
			if(eval('window.height'+idstring)==0)
			{
				s=y_co+inlineadoffsetheight-5;
				// msg_div.style.top=s+'px'; *********************************************
				eval('window.loadimage'+idstring +'=window_obj.setInterval("ShowInlineAd(\''+idstring+'\',\''+elemOfsX+'\',\''+y_co+'\')",20);');
			}
			eval('window.height'+idstring+'+=5;');
			if(eval('window.height'+idstring) <= inlineadoffsetheight+5)
			{
				msg_div.style.height =eval('window.height'+idstring)+"px";
				temp=eval(y_co+'+'+inlineadoffsetheight+'-'+eval('window.height'+idstring));
				//  msg_div.style.top=temp+'px';*****************************************************
			}
			else	
			{
				// AddInlineEventHandlers(msg_div,  new Array("onmouseover","onmouseout"),  new Array(Show, Hide) );		
				eval('window_obj.clearInterval(window.loadimage'+idstring+');');
				eval('window.loadimage'+idstring+'=null;');
			}
		}
	}
	

	function InlineHideDelay(event)
	{
		if (!event) var event = window.event;
		var hEv = event.srcElement ? event.srcElement : event.target;
		idstring=hEv.id;
		idstring=idstring.replace('inlinex','inline');
		eval('window.tmr'+idstring +'=window_obj.setTimeout("InlineHide(\''+idstring+'\')",1500);');
	}
	
	function InlineClick(event)
	{
		if (!event) var event = window.event;
		clTarg = event.srcElement ? event.srcElement : event.target;
		idstring=clTarg.id;
		idstring=idstring.replace('inlinex','inline');
		InlineHide(idstring);
	}
	
	
	
	/*var hash_old="";
	var hash_new=window.location.hash;
	
	setInterval(function()
	{
		hash_old=hash_new;
		hash_new=window.location.hash;
		//alert(hash_new.substr(1,7));
		if(hash_new.substr(1,7).equals("inouts_"))
		{
		
			InlineHide("inline"+hash_new.substr(8))
			window.location.hash=hash_old;
		}
	}, 100);*/
	
	///////////////////////////////////////////////////////////////////
	
	//alert(1);	


	if ( document.addEventListener ) // Mozilla, Opera and webkit nightlies currently support this event
	{
		// Use the handy event callback
		document.addEventListener( "DOMContentLoaded", function(){
		if (!alreadyrunflag){alreadyrunflag=1; startupInline(); }
		}, false );
	
	}
	else if ( document.attachEvent )  // If IE event model is used
	{
        // ensure firing before onload, maybe late but safe also for iframes
        document.attachEvent("onreadystatechange", function()
		{
                if ( document.readyState === "complete" ) 
				{
                        document.detachEvent( "onreadystatechange", arguments.callee );
						if (!alreadyrunflag){alreadyrunflag=1; startupInline(); }      
                }
        });

        // If IE and not an iframe, continually check to see if the document is ready
        if ( document.documentElement.doScroll && window == window.top ) 
		(function()
		{
			if ( alreadyrunflag )
				return;
			try 
			{
					// If IE is used, use the trick by Diego Perini
					// http://javascript.nwbox.com/IEContentLoaded/
					document.documentElement.doScroll("left");
			} 
			catch( error ) 
			{
					setTimeout( arguments.callee, 0 );
					return;
			}

			// and execute any waiting functions
			if (!alreadyrunflag){alreadyrunflag=1; startupInline(); }
		})();
	}
	
	window.onload=function()
	{
		setTimeout("if (!alreadyrunflag){ alreadyrunflag=1;startupInline()}", 0)
	}
</script>
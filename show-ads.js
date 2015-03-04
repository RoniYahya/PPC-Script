var ie=document.all && !window.opera;
var iebody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body ;
var ht=(ie)? iebody.clientHeight: window.innerHeight ;//screen.height
//alert(ht);
var wt=(ie)? iebody.clientWidth : window.innerWidth ;//screen.width

var today = new Date();
today.setTime( today.getTime() );
today.setHours(today.getHours()+1);
today.setMinutes(0);
today.setSeconds(0);

//alert(today);
//alert(today.getTime());

/*var Url = 
{
 
	// public method for url encoding
	encode : function (string) 
	{
		return escape(this._utf8_encode(string));
	},
 
	// public method for url decoding
	decode : function (string) 
	{
		return this._utf8_decode(unescape(string));
	},
 
	// private method for UTF-8 encoding
	_utf8_encode : function (string) 
	{
		string = string.replace(/\r\n/g,"\n");
		var utftext = "";
 
		for (var n = 0; n < string.length; n++) 
		{
 
			var c = string.charCodeAt(n);
 
			if (c < 128) 
			{
				utftext += String.fromCharCode(c);
			}
			else if((c > 127) && (c < 2048)) 
			{
				utftext += String.fromCharCode((c >> 6) | 192);
				utftext += String.fromCharCode((c & 63) | 128);
			}
			else 
			{
				utftext += String.fromCharCode((c >> 12) | 224);
				utftext += String.fromCharCode(((c >> 6) & 63) | 128);
				utftext += String.fromCharCode((c & 63) | 128);
			}
 
		}
 
		return utftext;
	},
 
	// private method for UTF-8 decoding
	_utf8_decode : function (utftext) 
	{
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
 
		while ( i < utftext.length ) 
		{
 
			c = utftext.charCodeAt(i);
 
			if (c < 128) 
			{
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) 
			{
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else 
			{
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
 
		}
 
		return string;
	}
 
}*/



 
 
function	utf8_encode  (string) 
{
	string = string.replace(/\r\n/g,"\n");
	var utftext = "";

	for (var n = 0; n < string.length; n++) 
	{

		var c = string.charCodeAt(n);

		if (c < 128) 
		{
			utftext += String.fromCharCode(c);
		}
		else if((c > 127) && (c < 2048)) 
		{
			utftext += String.fromCharCode((c >> 6) | 192);
			utftext += String.fromCharCode((c & 63) | 128);
		}
		else 
		{
			utftext += String.fromCharCode((c >> 12) | 224);
			utftext += String.fromCharCode(((c >> 6) & 63) | 128);
			utftext += String.fromCharCode((c & 63) | 128);
		}

	}

	return escape(utftext);
}
 




String.prototype.equalsIgnoreCase=myEqualsIgnoreCase;
String.prototype.equals=myEquals;

function myEquals(arg)
{
        return (this.toString()==arg.toString());
}

function myEqualsIgnoreCase(arg)
{               
        return (new String(this.toLowerCase())==(new String(arg)).toLowerCase());
}

function getstyle_str(iframe_width,str_cwp,spanid,show_ads_id)
{

<!-- Please don't remove Made by Giorgi Matakheria -->
style= "* html div#"+spanid+show_ads_id+"_fl813691 {position: absolute; overflow:hidden;top:expression(eval(document.compatMode &&document.compatMode=='CSS1Compat') ?documentElement.scrollTop+(documentElement.clientHeight-this.clientHeight): document.body.scrollTop+(document.body.clientHeight-this.clientHeight));}#"+spanid+show_ads_id+"_coh963846{display:block; height:15px; line-height:15px; width:"+iframe_width+"px;}#"+spanid+show_ads_id+"_coc67178{float:right; padding:0; margin:0; list-style:none; overflow:hidden; height:15px;border:0px solid grey}#"+spanid+show_ads_id+"_coc67178 li{display:inline;padding:0px;}#"+spanid+show_ads_id+"_coc67178 li a{background-image:url("+str_cwp+"images/button.gif); background-repeat:no-repeat; width:30px; height:0; padding-top:15px; overflow:hidden; float:left;}#"+spanid+show_ads_id+"_coc67178 li a.close{background-position: 0 0;}#"+spanid+show_ads_id+"_coc67178 li a.close:hover{background-position: 0 -15px;}#"+spanid+show_ads_id+"_coc67178 li a.min{background-position: -30px 0;}#"+spanid+show_ads_id+"_coc67178 li a.min:hover{background-position: -30px -15px;}#"+spanid+show_ads_id+"_coc67178 li a.max{background-position: -60px 0;}#"+spanid+show_ads_id+"_coc67178 li a.max:hover{background-position: -60px -15px;}";
return style;

}


function showAdsforContent(show_ads_id,iframe_width,iframe_height,show_ads_url,block_count,span_id)
{
   


	tit=document.title
	metainfo=document.getElementsByTagName('meta');
	ref=document.referrer;
	ref=ref.replace(":","");
	
	var cont='';
	desc = ""; 
	keyword_from_meta = ""; 
	for(i=0; i<metainfo.length;i++)
	{
		if(metainfo[i].httpEquiv.equalsIgnoreCase ('Content-Type'))
		{
			 var tmp=metainfo[i].content;
			 var arr1=tmp.split(";");
			 for(var j=0; j<arr1.length;j++)
			 {
					if(arr1[j].substr(1,7).equalsIgnoreCase ("charset"))
					{
						//alert(arr1[j]);
						var arr2=arr1[j].split("=");
						if(arr2.length>=2)
							cont=arr2[1];
					}
			 }
		}

		if(metainfo[i].name.equalsIgnoreCase ('keywords'))
		{
			keyword_from_meta=metainfo[i].content;
		}
		if((tit=="" ||tit.equalsIgnoreCase ("Untitled Document"))&& metainfo[i].name.equalsIgnoreCase ('title'))
		{
			tit=metainfo[i].content;
		}
		if(metainfo[i].name.equalsIgnoreCase ('description'))
		{
			desc=metainfo[i].content;
		}
	}
keyword_from_meta=keyword_from_meta.substr(1,300)
tit=tit.substr(1,100)
desc=desc.substr(1,200)
	adunitrendered='f';
	var adunits=Get_Cookie('_io_ads');
	if(adunits)
	{
		var adunitsarr=adunits.split(",");
		for(var j=0; j<adunitsarr.length;j++)
		{
			if(adunitsarr[j]==show_ads_id)
			{
				adunitrendered='t';
				break;
			}
		}
		if(adunitrendered=='f')
		{
			adunits=adunits+show_ads_id+',';
		}
	}
	else
		adunits=show_ads_id+',';

	//alert(adunitrendered+adunits);

	Set_Cookie( '_io_ads', adunits, 0 , "/") ;

	var url=show_ads_url;
	url=url+"?id="+show_ads_id;	
	url=url+"&ht="+iframe_height;	
		hostname=window.location.hostname;
	url=url+"&hostname="+utf8_encode(hostname);
	url=url+"&r="+adunitrendered;	
	url=url+"&blockcount="+block_count;
	url=url+"&content_type="+cont;
	url=url+"&search="+utf8_encode(keyword_from_meta);
	url=url+"&title="+utf8_encode(tit);
	url=url+"&desc="+utf8_encode(desc);
	url=url+"&ref="+utf8_encode(ref);
	//alert(url); 

	frame="";
	ad_pos=eval(span_id+"_"+show_ads_id+"_position");
	//alert(ad_pos);
	var sticky=false;
	if(ad_pos)
	{
		horiz_center=((wt/2 )- (iframe_width/2))+"px";
		ver_center=((ht/2 )- (iframe_height/2))+"px";
		switch(ad_pos)
		{
			case 1:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\"  style=\"z-index:900;position:fixed;_position: absolute;left:0;top:0\" >";
			sticky=true;
			break;
			case 2:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:"+horiz_center+";top:0\" >";
			sticky=true;
			break;
			case 3:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;top:0;right:0; \" >";
			sticky=true;
			break;
			case 7:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:0;bottom:0\" >";
			sticky=true;
			break;
			case 8:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:"+horiz_center+";bottom:0\" >";
			sticky=true;
			break;
			case 9:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;right:0;bottom:0\" >";
			sticky=true;
			break;
case 10:
            frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:10000;position:fixed;_position: absolute;left:"+horiz_center+";top:"+ver_center+"\" >";
            sticky=true;
            break;
			default:
			break;
		}
	}
	//alert("hai");
	if(sticky==true)
	{
		var get_str_cwp=get_current_working_path(show_ads_url);
		//frame=getstyle_str(iframe_width,get_str_cwp,span_id,show_ads_id)+frame;
		if(ad_pos==10)
		{
			frame+='<div id="'+span_id+show_ads_id+'_coh963846"><ul id="'+span_id+show_ads_id+'_coc67178"><li id="'+span_id+show_ads_id+'_pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Show this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652close"><a class="close" href="javascript:pf204652clickclose(\''+span_id+show_ads_id+'\');" title="Close this window">?</a></li></ul></div>';
		}
		if(ad_pos>4)
		{
			if(ad_pos!=10)
			{
			frame+='<div id="'+span_id+show_ads_id+'_coh963846"><ul id="'+span_id+show_ads_id+'_coc67178"><li style="display: inline;" id="'+span_id+show_ads_id+'_pf204652hide"><a class="min" href="javascript:pf204652clickhide(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Hide this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Show this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652close"><a class="close" href="javascript:pf204652clickclose(\''+span_id+show_ads_id+'\');" title="Close this window">?</a></li></ul></div>';
			}
		}
		frame+="<iframe frameborder=\"0\" src=\""+url+"\" allowtransparency=\"true\" height=\""+iframe_height+"\" width=\""+iframe_width+"\" scrolling=\"no\" ></iframe>";
		if(ad_pos<4)
		{
			frame+='<div id="'+span_id+show_ads_id+'_coh963846"><ul id="'+span_id+show_ads_id+'_coc67178"><li style="display: inline;" id="'+span_id+show_ads_id+'_pf204652hide"><a class="min" href="javascript:pf204652clickhide(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Hide this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Show this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652close"><a class="close" href="javascript:pf204652clickclose(\''+span_id+show_ads_id+'\');" title="Close this window">?</a></li></ul></div>';
		}
		frame+="</div>"
	}
	else
		frame+="<iframe frameborder=\"0\" src=\""+url+"\" allowtransparency=\"true\" height=\""+iframe_height+"\" width=\""+iframe_width+"\" scrolling=\"no\" ></iframe>";
	//alert(frame);







//document.open();
//document.write(frame);
//document.close();




	document.getElementById("show_"+span_id+'_'+show_ads_id).innerHTML=frame;









	if(sticky==true)
	{
		var ss1 = document.createElement('style');
		var def = getstyle_str(iframe_width,get_str_cwp,span_id,show_ads_id);
		ss1.setAttribute("type", "text/css");
		if (ss1.styleSheet) {   // IE
			ss1.styleSheet.cssText = def;
		} else {                // the world
			var tt1 = document.createTextNode(def);
			ss1.appendChild(tt1);
		}
		var hh1 = document.getElementsByTagName('head')[0];
		hh1.appendChild(ss1);
		eval("this.originalTop"+span_id+show_ads_id+"  =iframe_height*-1;");
		eval("this.tempTop"+span_id+show_ads_id+"  =iframe_height*-1 -14;");

		eval("this.pf204652maxHeight"+span_id+show_ads_id+"  =ht- iframe_height-14;");
		eval("this.pf204652curHeight"+span_id+show_ads_id+"=ht;");
		eval("this.pf204652minHeight"+span_id+show_ads_id+"= ht-15;");

		eval("this.pf204652bottomLayer"+span_id+show_ads_id+" = document.getElementById(span_id+show_ads_id+'_fl813691');");
		//alert(eval("this.pf204652bottomLayer"+span_id+show_ads_id));
		eval("this.pf204652IntervalId"+span_id+show_ads_id+" = setInterval ( 'pf204652show(\"'+span_id+'\",'+ad_pos+','+show_ads_id+')', 5 );");
	}
}



function showAdsforKeyword(show_ads_id,iframe_width,iframe_height,show_ads_url,block_count,span_id)
{
	//alert(show_ads_id);
	tit = document.title; 
	metainfo=document.getElementsByTagName('meta');
	ref=document.referrer;
	ref=ref.replace(":","");

	
	var cont='';
	for(i=0; i<metainfo.length;i++)
	{
		if(metainfo[i].httpEquiv.equalsIgnoreCase('Content-Type'))
		{
			 var tmp=metainfo[i].content;
			 var arr1=tmp.split(";");
			 for(var j=0; j<arr1.length;j++)
			 {
					if(arr1[j].substr(1,7).equalsIgnoreCase ("charset"))
					{
						//alert(arr1[j]);
						var arr2=arr1[j].split("=");
						if(arr2.length>=2)
							cont=arr2[1];
					}
			 }
		}
	}

	adunitrendered='f';
	var adunits=Get_Cookie('_io_ads');
	if(adunits)
	{
		var adunitsarr=adunits.split(",");
		for(var j=0; j<adunitsarr.length;j++)
		{
			if(adunitsarr[j]==show_ads_id)
			{
				adunitrendered='t';
				break;
			}
		}
		if(adunitrendered=='f')
		{
			adunits=adunits+show_ads_id+',';
		}
	}
	else
		adunits=show_ads_id+',';

	//alert(adunitrendered+adunits);

	Set_Cookie( '_io_ads', adunits, 0 , "/") ;


	//alert(keyword);
	var url=show_ads_url;
	url=url+"?id="+show_ads_id;	
	url=url+"&ht="+iframe_height;	
	url=url+"&r="+adunitrendered;	
	url=url+"&blockcount="+block_count;
	url=url+"&content_type="+cont;
	url=url+"&search="+utf8_encode(keyword);
	url=url+"&ref="+utf8_encode(ref);
	hostname=window.location.hostname;
	url=url+"&hostname="+utf8_encode(hostname);
	//alert(url);
	frame="";
	ad_pos=eval(span_id+"_"+show_ads_id+"_position");
	var sticky=false;
	if(ad_pos)
	{
		horiz_center=((wt/2 )- (iframe_width/2))+"px";
		ver_center=((ht/2 )- (iframe_height/2))+"px";
		switch(ad_pos)
		{
			case 1:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\"  style=\"z-index:900;position:fixed;_position: absolute;left:0;top:0\" >";
			sticky=true;
			break;
			case 2:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:"+horiz_center+";top:0\" >";
			sticky=true;
			break;
			case 3:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;top:0;right:0; \" >";
			sticky=true;
			break;
			
			case 7:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:0;bottom:0\" >";
			sticky=true;
			break;
			case 8:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;left:"+horiz_center+";bottom:0\" >";
			sticky=true;
			break;
			case 9:
			frame="<div  id=\""+span_id+show_ads_id+"_fl813691\" style=\"z-index:900;position:fixed;_position: absolute;right:0;bottom:0\" >";
			sticky=true;
			break;
			default:
			break;
		}
	}
	if(sticky==true)
	{
		var get_str_cwp=get_current_working_path(show_ads_url);
		//frame=getstyle_str(iframe_width,get_str_cwp,span_id,show_ads_id)+frame;
		if(ad_pos>4)
		{
			frame+='<div id="'+span_id+show_ads_id+'_coh963846"><ul id="'+span_id+show_ads_id+'_coc67178"><li style="display: inline;" id="'+span_id+show_ads_id+'_pf204652hide"><a class="min" href="javascript:pf204652clickhide(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Hide this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Show this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652close"><a class="close" href="javascript:pf204652clickclose(\''+span_id+show_ads_id+'\');" title="Close this window">?</a></li></ul></div>';
		}

		frame+="<iframe frameborder=\"0\" src=\""+url+"\" allowtransparency=\"true\" height=\""+iframe_height+"\" width=\""+iframe_width+"\" scrolling=\"no\" ></iframe>";
		if(ad_pos<4)
		{
			frame+='<div id="'+span_id+show_ads_id+'_coh963846"><ul id="'+span_id+show_ads_id+'_coc67178" ><li style="display: inline;" id="'+span_id+show_ads_id+'_pf204652hide"><a class="min" href="javascript:pf204652clickhide(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Hide this window">X</a></li><li id="'+span_id+show_ads_id+'_pf204652show" style="display: none;"><a class="max" href="javascript:pf204652clickshow(\''+span_id+'\','+ad_pos+','+show_ads_id+');" title="Show this window">?</a></li><li id="'+span_id+show_ads_id+'_pf204652close"><a class="close" href="javascript:pf204652clickclose(\''+span_id+show_ads_id+'\');" title="Close this window">?</a></li></ul></div>';
		}
		frame+="</div>"
	}
	else
		frame+="<iframe frameborder=\"0\" src=\""+url+"\" allowtransparency=\"true\" height=\""+iframe_height+"\" width=\""+iframe_width+"\" scrolling=\"no\" ></iframe>";
	
	//alert(frame);
	


//document.open();
//document.write("<iframe frameborder=\"0\" src=\""+url+"\" height=\""+iframe_height+"\" width=\""+iframe_width+"\" scrolling=\"no\" ></iframe>");
//document.close();





document.getElementById("show_"+span_id+'_'+show_ads_id).innerHTML=frame;





	
	if(sticky==true)
	{
		
		var ss1 = document.createElement('style');
		var def = getstyle_str(iframe_width,get_str_cwp,span_id,show_ads_id);
		ss1.setAttribute("type", "text/css");
		if (ss1.styleSheet) {   // IE
			ss1.styleSheet.cssText = def;
		} else {                // the world
			var tt1 = document.createTextNode(def);
			ss1.appendChild(tt1);
		}
		var hh1 = document.getElementsByTagName('head')[0];
		hh1.appendChild(ss1);


		eval("this.originalTop"+span_id+show_ads_id+"  =iframe_height*-1;");
		eval("this.tempTop"+span_id+show_ads_id+"  =iframe_height*-1 -14;");

		eval("this.pf204652maxHeight"+span_id+show_ads_id+"  =ht- iframe_height-14;");
		eval("this.pf204652curHeight"+span_id+show_ads_id+"=ht;");
		eval("this.pf204652minHeight"+span_id+show_ads_id+"= ht-15;");

		eval("this.pf204652bottomLayer"+span_id+show_ads_id+" = document.getElementById(span_id+show_ads_id+'_fl813691');");
		//alert(eval("this.pf204652bottomLayer"+span_id+show_ads_id));
		eval("this.pf204652IntervalId"+span_id+show_ads_id+" = setInterval ( 'pf204652show(\"'+span_id+'\",'+ad_pos+','+show_ads_id+')', 5 );");
	}
}


function showContentAds(metainfo)
{
	var cont='';
	desc = ""; 
	keyword_from_meta = ""; 
	for(i=0; i<metainfo.length;i++)
	{
		if(metainfo[i].httpEquiv.equalsIgnoreCase ('Content-Type'))
		{
			 var tmp=metainfo[i].content;
			 var arr1=tmp.split(";");
			 for(var j=0; j<arr1.length;j++)
			 {
					if(arr1[j].substr(1,7).equalsIgnoreCase ("charset"))
					{
						//alert(arr1[j]);
						var arr2=arr1[j].split("=");
						if(arr2.length>=2)
							cont=arr2[1];
					}
			 }
		}
		
		
		if(metainfo[i].name.equalsIgnoreCase ('keywords'))
		{
			keyword_from_meta=metainfo[i].content;
		}
		if((title=="" ||title.equalsIgnoreCase ("Untitled Document"))&& metainfo[i].name.equalsIgnoreCase ('title'))
		{
			title=metainfo[i].content;
		}
		if(metainfo[i].name.equalsIgnoreCase ('description'))
		{
			desc=metainfo[i].content;
		}
	}
	
	adunitrendered='f';
	var adunits=Get_Cookie('_io_ads');
	if(adunits)
	{
		var adunitsarr=adunits.split(",");
		for(var j=0; j<adunitsarr.length;j++)
		{
			if(adunitsarr[j]==show_ads_id)
			{
				adunitrendered='t';
				break;
			}
		}
		if(adunitrendered=='f')
		{
			adunits=adunits+show_ads_id+',';
		}
	}
	else
		adunits=show_ads_id+',';

	//alert(adunitrendered+adunits);

	Set_Cookie( '_io_ads', adunits, 0 , "/") ;


	var url=show_ads_url;
	url=url+"?id="+show_ads_id;	
	url=url+"&ht="+iframe_height;	
	url=url+"&r="+adunitrendered;	
	url=url+"&blockcount="+block_count;
	url=url+"&content_type="+cont;
	url=url+"&search="+utf8_encode(keyword_from_meta);
	url=url+"&title="+utf8_encode(title);
	url=url+"&desc="+utf8_encode(desc);
	url=url+"&ref="+utf8_encode(ref);
	hostname=window.location.hostname;
	url=url+"&hostname="+utf8_encode(hostname);
	//alert(url); 
	frame="<iframe frameborder=\"0\" src=\""+url+"\" allowtransparency=\"true\" height=\""+iframe_height+"\" width=\""+iframe_width+"\" scrolling=\"no\" ></iframe>";
	
	document.getElementById("show_ads_"+show_ads_id).innerHTML=frame;
		
}



function showKeywordAds()
{
	var cont='';
	for(i=0; i<metainfo.length;i++)
	{
		if(metainfo[i].httpEquiv.equalsIgnoreCase('Content-Type'))
		{
			 var tmp=metainfo[i].content;
			 var arr1=tmp.split(";");
			 for(var j=0; j<arr1.length;j++)
			 {
					if(arr1[j].substr(1,7).equalsIgnoreCase ("charset"))
					{
						//alert(arr1[j]);
						var arr2=arr1[j].split("=");
						if(arr2.length>=2)
							cont=arr2[1];
					}
			 }
		}
	}

	adunitrendered='f';
	var adunits=Get_Cookie('_io_ads');
	if(adunits)
	{
		var adunitsarr=adunits.split(",");
		for(var j=0; j<adunitsarr.length;j++)
		{
			if(adunitsarr[j]==show_ads_id)
			{
				adunitrendered='t';
				break;
			}
		}
		if(adunitrendered=='f')
		{
			adunits=adunits+show_ads_id+',';
		}
	}
	else
		adunits=show_ads_id+',';

	//alert(adunitrendered+adunits);

	Set_Cookie( '_io_ads', adunits, 0 , "/") ;


	var url=show_ads_url;
	url=url+"?id="+show_ads_id;	
	url=url+"&ht="+iframe_height;	
	url=url+"&r="+adunitrendered;	
	url=url+"&blockcount="+block_count;
	url=url+"&content_type="+cont;
	url=url+"&search="+utf8_encode(keyword);
	url=url+"&ref="+utf8_encode(ref);
	
	hostname=window.location.hostname;
	url=url+"&hostname="+utf8_encode(hostname);
	//alert(url);
	frame="<iframe frameborder=\"0\" src=\""+url+"\" allowtransparency=\"true\" height=\""+iframe_height+"\" width=\""+iframe_width+"\" scrolling=\"no\" ></iframe>";
	document.getElementById("show_ads_"+show_ads_id).innerHTML=frame;
	
}

/*
function URLEncode (clearString) 
{
  var output = '';
  var x = 0;
  clearString = clearString.toString();
  var regex = /(^[a-zA-Z0-9_.]*)/;
  while (x < clearString.length) 
  {
    var match = regex.exec(clearString.substr(x));
    if (match != null && match.length > 1 && match[1] != '') 
	{
    	output += match[1];
      x += match[1].length;
    } else 
	{
      if (clearString[x] == ' ')
        output += '+';
      else 
	  {
        var charCode = clearString.charCodeAt(x);
        var hexVal = charCode.toString(16);
        output += '%' + ( hexVal.length < 2 ? '0' : '' ) + hexVal.toUpperCase();
      }
      x++;
    }
  }
  return output;
}

function getCookie(name)
{
	var cookies = document.cookie;
	if (cookies.indexOf(name) != -1)
	{
		var startpos = cookies.indexOf(name)+name.length+1;
		var endpos = cookies.indexOf(";",startpos)-1;
		if (endpos == -2) endpos = cookies.length;
		return unescape(cookies.substring(startpos,endpos));
	}
	else
	{
		return false;
	}
}

function setCookie(name, value, expires)
{
	// no expiration date specified? use this date and it will just be deleted soon.
	if (!expires) expires = new Date(); 
		document.cookie = name + "=" + escape(value) + "; expires=" + expires.toGMTString() + "; path=/";
}

function delete_cookie ( cookie_name )
{
  var cookie_date = new Date ( );  // current date & time
  cookie_date.setTime ( cookie_date.getTime() - 1 );
  document.cookie = cookie_name += "=; expires=" + cookie_date.toGMTString();
}
*/


function pf204652show(span_id,ad_pos,show_ads_id)
{
if (ad_pos == 10) { return; }
	//alert(eval("this.pf204652bottomLayer"+span_id+show_ads_id));
	if(ad_pos>4)
	{
		eval("this.pf204652curHeight"+span_id+show_ads_id+" -= 2;");
		eval("if (this.pf204652curHeight"+span_id+show_ads_id+" < this.pf204652maxHeight"+span_id+show_ads_id+"){clearInterval ( this.pf204652IntervalId"+span_id+show_ads_id+" );}this.pf204652bottomLayer"+span_id+show_ads_id+".style.top = this.pf204652curHeight"+span_id+show_ads_id+"+'px';");
	}
	else		
	{
		eval("this.tempTop"+span_id+show_ads_id+" += 2;");
		eval("if (this.tempTop"+span_id+show_ads_id+" >=0){clearInterval ( this.pf204652IntervalId"+span_id+show_ads_id+" );}this.pf204652bottomLayer"+span_id+show_ads_id+".style.top = this.tempTop"+span_id+show_ads_id+"+'px';");
	}
}


function pf204652hide(span_id,ad_pos,show_ads_id )
{
	if(ad_pos>4)
	{
		eval("this.pf204652curHeight"+span_id+show_ads_id+" += 3;");
		eval("if (this.pf204652curHeight"+span_id+show_ads_id+" > this.pf204652minHeight"+span_id+show_ads_id+"){clearInterval ( this.pf204652IntervalId"+span_id+show_ads_id+" );}this.pf204652bottomLayer"+span_id+show_ads_id+".style.top = this.pf204652curHeight"+span_id+show_ads_id+"+'px';");
	}
	else
	{
		eval("this.tempTop"+span_id+show_ads_id+" -= 3;");
		eval("if (this.tempTop"+span_id+show_ads_id+" < this.originalTop"+span_id+show_ads_id+"){clearInterval ( this.pf204652IntervalId"+span_id+show_ads_id+" );}this.pf204652bottomLayer"+span_id+show_ads_id+".style.top = this.tempTop"+span_id+show_ads_id+"+'px';");
	}
}


function pf204652clickhide(span_id,ad_pos,show_ads_id)
{
	//alert(this.style);
	document.getElementById(span_id+show_ads_id+'_pf204652hide').style.display='none';
	document.getElementById(span_id+show_ads_id+'_pf204652show').style.display='inline';
	eval("pf204652IntervalId"+span_id+show_ads_id+" = setInterval ( 'pf204652hide(\"'+span_id+'\",'+ad_pos+','+show_ads_id+')', 5 );");
}
function pf204652clickshow(span_id,ad_pos,show_ads_id)
{
	document.getElementById(span_id+show_ads_id+'_pf204652hide').style.display='inline';
	document.getElementById(span_id+show_ads_id+'_pf204652show').style.display='none';
	eval("pf204652IntervalId"+span_id+show_ads_id+" = setInterval ( 'pf204652show(\"'+span_id+'\",'+ad_pos+','+show_ads_id+')', 5 );");
}
function pf204652clickclose(span_id)
{
	document.body.style.marginBottom = '0px';
	eval("this.pf204652bottomLayer"+span_id+".style.display = 'none';");
}
function get_current_working_path(str)
{
	var end_pos=(str.search(/publisher-show-ads.php/));
	var url_str_var;
	if(end_pos!=-1)
	{
		url_str_var=(str.substring(0,end_pos));
	}
	else
	{
		var end_pos=(str.search(/show-ads.php/));
		if(end_pos!=-1)
			url_str_var=(str.substring(0,end_pos));
	}
	return(url_str_var);
}



function Set_Cookie( name, value, expires, path, domain, secure ) 
{	

	if ( expires )
	{
		expires = expires * 1000 * 60 * 60 ;
	}
	var expires_date = new Date( today.getTime() + (expires) );
//alert(expires_date);
	document.cookie = name + "=" +escape( value ) +

		 ";expires=" + expires_date.toGMTString()  + //expires.toGMTString()

		( ( path ) ? ";path=" + path : "" ) + 

		( ( domain ) ? ";domain=" + domain : "" ) +

		( ( secure ) ? ";secure" : "" );

}


function Get_Cookie( name )
 {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) )
	{
		return null;
	}

	if ( start == -1 ) return null;

	var end = document.cookie.indexOf( ";", len );

	if ( end == -1 ) end = document.cookie.length;

	return unescape( document.cookie.substring( len, end ) );

}



function Delete_Cookie( name, path, domain ) 
{
	if ( Get_Cookie( name ) ) document.cookie = name + "=" +

			( ( path ) ? ";path=" + path : "") +

			( ( domain ) ? ";domain=" + domain : "" ) +

			";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}

var gc4ca4238a0b923820dcc509a6f75849b=1; 

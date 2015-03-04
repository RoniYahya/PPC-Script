var today = new Date();
today.setTime( today.getTime() );
today.setHours(today.getHours()+1);
today.setMinutes(0);
today.setSeconds(0);





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

 


var Url = {

 

	// public method for url encoding

	encode : function (string) {

		return escape(this._utf8_encode(string));

	},

 

	// public method for url decoding

	decode : function (string) {

		return this._utf8_decode(unescape(string));

	},

 

	// private method for UTF-8 encoding

	_utf8_encode : function (string) {

		string = string.replace(/\r\n/g,"\n");

		var utftext = "";

 

		for (var n = 0; n < string.length; n++) {

 

			var c = string.charCodeAt(n);

 

			if (c < 128) {

				utftext += String.fromCharCode(c);

			}

			else if((c > 127) && (c < 2048)) {

				utftext += String.fromCharCode((c >> 6) | 192);

				utftext += String.fromCharCode((c & 63) | 128);

			}

			else {

				utftext += String.fromCharCode((c >> 12) | 224);

				utftext += String.fromCharCode(((c >> 6) & 63) | 128);

				utftext += String.fromCharCode((c & 63) | 128);

			}

 

		}

 

		return utftext;

	},

 

	// private method for UTF-8 decoding

	_utf8_decode : function (utftext) {

		var string = "";

		var i = 0;

		var c = c1 = c2 = 0;

 

		while ( i < utftext.length ) {

 

			c = utftext.charCodeAt(i);

 

			if (c < 128) {

				string += String.fromCharCode(c);

				i++;

			}

			else if((c > 191) && (c < 224)) {

				c2 = utftext.charCodeAt(i+1);

				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));

				i += 2;

			}

			else {

				c2 = utftext.charCodeAt(i+1);

				c3 = utftext.charCodeAt(i+2);

				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));

				i += 3;

			}

 

		}

 

		return string;

	}

 

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








function showInlineAds(show_ads_id,url_name)

{
//alert(show_ads_id);

	ref=document.referrer;
	ref=ref.replace(":","");
	
if (window.adspay_inline){return;}else{var adspay_inline=1;}

//alert(inlinekeyword);
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

metainfo=document.getElementsByTagName('meta');

var keyword='';

	for(i=0; i<metainfo.length;i++)

	{

	if(metainfo[i].name.equalsIgnoreCase ('keywords'))

		{

			keyword=metainfo[i].content;

		}
	}
//if(inlinekeyword=="")
//inlinekeyword=keyword;

//	var url="http://localhost/workspace/ads/show-inline-ads.php";
	
	//var url="http://www.inoutdemo.com/labs/ads-ultimate/show-inline-ads.php";
	
	var url=url_name;

	url=url+"?id="+show_ads_id;	
	url=url+"&r="+adunitrendered;	
	
  	url=url+"&search="+utf8_encode(inlinekeyword);
	url=url+"&hostname="+utf8_encode(window.location.href);
    url=url+"&ref="+utf8_encode(ref);
	hostname=window.location.hostname;
	url=url+"&hostname="+utf8_encode(hostname);

	
		





document.open();
//alert("<"+"script type=\"text/javascript\" src=\""+url+"\" language=\"javascript\"><"+"/script>");
document.write("<"+"script type=\"text/javascript\" src=\""+url+"\" language=\"javascript\"><"+"/script>");

document.close();


//

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
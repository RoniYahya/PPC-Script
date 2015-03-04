// JavaScript Document
var xmlhttp16;
var ajaxkeyword;
function clearSearch(str) {
	document.getElementById('s_keyword').value = str;
	document.getElementById('livesearch').innerHTML = "";
	document.getElementById("livesearch").style.border = "";
	document.getElementById("livesearch").style.display = "none";
}
function clearadvSearch(str,forms) {
var aa=forms.elements["kwd1"].value;
var myResult = str_replace("\r\n",",",aa);
myResult = str_replace("\r",",",myResult);
myResult = str_replace("\n",",",myResult);
var res=explode(",",myResult);
var nn=res.count();                                                            
var i=0;
var sx="";
var str1='';
//alert(nn);
if(str==res[nn-1])
 str1=str ;
 else
 str1=str +"\n"+res[nn-1];
	for(i=0;i<=(nn-2);i++)
	{ 
		if(res[i]!="" &&res[i]!=str &&res[i]!=res[nn-1]  )
		{
			if(sx=='')
			 sx=res[i];
			else
		     sx+="\n"+res[i];
		 }

		
	}
	if(sx!='' && str1!='')
		sx+='\n';
	document.getElementById('kwd1').value = sx + str1;

}

function showResult(str) {
	
	if (str.length == 0) {
		document.getElementById("livesearch").innerHTML = "";
		document.getElementById("livesearch").style.border = "0px";
		document.getElementById("livesearch").style.display = "none";
		return;
	}
	xmlhttp16 = GetXmlHttpObject()
	if (xmlhttp16 == null) {
		alert("Your browser does not support XML HTTP Request");
		return;
	}
	var url = "livesearch.php";
	url = url + "?q=" + str;
	url = url + "&sid=" + Math.random();
		xmlhttp16.onreadystatechange = stateChanged16;
	xmlhttp16.open("GET", encodeURI(url), true);
	xmlhttp16.send(null);
	
}
function showloadResult(str) 
{
	if(ajaxkeyword==str)
	{
		if(document.getElementById("livesearch").innerHTML !='')
		{
			document.getElementById("livesearch").style.display='block';
		}
		return;
	}
	else
		ajaxkeyword=str;	
	
	if (str.length == 0) { 
		document.getElementById("livesearch").innerHTML = "";
		document.getElementById("livesearch").style.border = "0px";
		document.getElementById("livesearch").style.display ="none";
		return;
	}
	
	var url="";
	
	xmlhttp16 = GetXmlHttpObject()
	if (xmlhttp16 == null) {
		alert("Your browser does not support XML HTTP Request");
		return;
	}
	
	 url = "show-keyword-search.php";
	url = url + "?q=" + str;
	url = url + "&sid=" + Math.random();
		
	xmlhttp16.onreadystatechange = stateChanged16;
	xmlhttp16.open("GET", encodeURI(url), true);
	xmlhttp16.send(null);
	
	document.getElementById("livesearch").style.display = "none";
		
	
}
function stateChanged16() {

	if (xmlhttp16.readyState == 4) { 
	document.getElementById("livesearch").style.display="block";
		document.getElementById("livesearch").innerHTML = xmlhttp16.responseText;
		if (xmlhttp16.responseText != "no suggestion")
			document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
		else
			document.getElementById("livesearch").style.border = "";
		document.getElementById("livesearch").style.display = "";
	}
}

function GetXmlHttpObject() {
	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		return new XMLHttpRequest();
	}
	if (window.ActiveXObject) {
		// code for IE6, IE5
		return new ActiveXObject("Microsoft.XMLHTTP");
	}
	return null;
}

function setSelection(obj) {
	obj.style.color = "#fff";
	obj.style.cursor = "pointer";
}
function resetSelection(obj) {
	obj.style.color = "#444444";
}
//added on 03 september 2009

function showAdvertiser(str) {
	if (str.length == 0) {
		document.getElementById("livesearch").innerHTML = "";
		document.getElementById("livesearch").style.border = "0px";
		document.getElementById("livesearch").style.display = "none";
		return;
	}
	xmlhttp16 = GetXmlHttpObject()
	if (xmlhttp16 == null) {
		alert("Your browser does not support XML HTTP Request");
		return;
	}
	
	var s_type=document.getElementById('s_type').value;
	var url = "search_advertiser.php";
	url = url + "?q=" + str;
	url = url + "&s_type=" + s_type;
	xmlhttp16.onreadystatechange = stateChanged16;
	xmlhttp16.open("GET", encodeURI(url), true);
	xmlhttp16.send(null);
}

function showPublisher(str) {

	if (str.length == 0) {
		document.getElementById("livesearch").innerHTML = "";
		document.getElementById("livesearch").style.border = "0px";
		document.getElementById("livesearch").style.display = "none";
		return;
	}
	xmlhttp16 = GetXmlHttpObject()
	if (xmlhttp16 == null) {
		alert("Your browser does not support XML HTTP Request");
		return;
	}
	
	var s_type=document.getElementById('s_type').value;
	var url = "search_publisher.php";
	url = url + "?q=" + str;
	url = url + "&s_type=" + s_type;
	xmlhttp16.onreadystatechange = stateChanged16;
	xmlhttp16.open("GET", encodeURI(url), true);
	xmlhttp16.send(null);
}

function showUsers(str) {
//alert(str);
	if (str.length == 0) {
		document.getElementById("livesearch").innerHTML = "";
		document.getElementById("livesearch").style.border = "0px";
		document.getElementById("livesearch").style.display = "none";
		return;
	}
	xmlhttp16 = GetXmlHttpObject()
	if (xmlhttp16 == null) {
		alert("Your browser does not support XML HTTP Request");
		return;
	}
	
	var s_type=document.getElementById('s_type').value;
	var url = "show-users.php";
	url = url + "?q=" + str;
	url = url + "&s_type=" + s_type;
	xmlhttp16.onreadystatechange = stateChanged16;
	xmlhttp16.open("GET", encodeURI(url), true);
	xmlhttp16.send(null);
}
function str_replace (search, replace, subject, count) {
    // Replaces all occurrences of search in haystack with replace  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/str_replace    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = r instanceof Array,        sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    } 
    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }        for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {                this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];}
    function explode (delimiter, string, limit) {
    // Splits a string on string separator and return array of components. If limit is positive only limit number of components is returned. If limit is negative all components except the last abs(limit) are returned.  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/explode    // +     original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: kenneth
    // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +     improved by: d3x
    // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)    // *     example 1: explode(' ', 'Kevin van Zonneveld');
    // *     returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
    // *     example 2: explode('=', 'a=bc=d', 2);
    // *     returns 2: ['a', 'bc=d']
    var emptyArray = {        0: ''
    };
 
    // third argument is not required
    if (arguments.length < 2 || typeof arguments[0] == 'undefined' || typeof arguments[1] == 'undefined') {        return null;
    }
 
    if (delimiter === '' || delimiter === false || delimiter === null) {
        return false;    }
 
    if (typeof delimiter == 'function' || typeof delimiter == 'object' || typeof string == 'function' || typeof string == 'object') {
        return emptyArray;
    } 
    if (delimiter === true) {
        delimiter = '1';
    }
     if (!limit) {
        return string.toString().split(delimiter.toString());
    } else {
        // support for limit argument
        var splitted = string.toString().split(delimiter.toString());        var partA = splitted.splice(0, limit - 1);
        var partB = splitted.join(delimiter.toString());
        partA.push(partB);
        return partA;
    }}
    Array.prototype.count = function() {
	return this.length;
};
    
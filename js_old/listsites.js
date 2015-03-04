// JavaScript Document
var xmlhttp;

function cancelEdit(kwd_id)
	{
		
		//loadKeywords();
	
	edit_key1="edit_key_"+kwd_id;
	edit_val1="edit_val_"+kwd_id;
	update='update_'+kwd_id;
	cancel='cancel_'+kwd_id;
	document.getElementById(cancel).style.display="none";
	document.getElementById(edit_key1).style.display="none";
	document.getElementById(edit_val1).style.display="none";
	document.getElementById(update).style.display="none";
	
	show_name="show_name_"+kwd_id;
	show_val="show_val_"+kwd_id;
	//alert(show_name);
		document.getElementById(show_name).style.display="";
	document.getElementById(show_val).style.display="";

	
	}

function showSites(str)
{

 //alert("Hai");
//alert(document.getElementById('site').value);

if(verifyForm_NewSite()==false)
								{
									return false;
								}
		
			//alert(document.getElementById('site').value);
			//var id=document.getElementById('id').value;
			var site=document.getElementById('site').value;
//			alert(site); exit;
		//	document.getElementById('kwd1').value="";
		//var clv=document.getElementById('maxcv1').value;
	//	document.getElementById('maxcv1').value="";

xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }
 
var url="list-site.php";
url=url+"?site="+site;


xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",encodeURI(url),true);
xmlhttp.send(null);

document.getElementById("disp").style.display="";
document.getElementById("errormsg").innerHTML="";
}

function loadSite()
	{
		//alert("jjjjj");
		xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }
 
var url="list-site.php";



xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",encodeURI(url),true);
xmlhttp.send(null);

document.getElementById("disp").style.display="";
document.getElementById("errormsg").innerHTML="";
		
		}
function allowEdit(kwd_id)
	{
		

		edit_key1="edit_key_"+kwd_id;
		edit_val1="edit_val_"+kwd_id;
		update='update_'+kwd_id;
		cancel='cancel_'+kwd_id;
					document.getElementById(cancel).style.display="";
		document.getElementById(edit_key1).style.display="";
		document.getElementById(edit_val1).style.display="";
		document.getElementById(update).style.display="";
		
		show_name="show_name_"+kwd_id;
		show_val="show_val_"+kwd_id;
	
			document.getElementById(show_name).style.display="none";
		document.getElementById(show_val).style.display="none";

		
	}
function allowEditKeywordBased(kwd_id)
	{
		
		edit_key1="edit_key_"+kwd_id;
		edit_val1="edit_val_"+kwd_id;
		update='update_'+kwd_id;
		cancel='cancel_'+kwd_id;
		document.getElementById(cancel).style.display="";
		document.getElementById(edit_key1).style.display="";
		document.getElementById(edit_val1).style.display="";
		document.getElementById(update).style.display="";
		
		show_name="show_name_"+kwd_id;
		show_val="show_val_"+kwd_id;
		//alert(show_name);
			document.getElementById(show_name).style.display="none";
		document.getElementById(show_val).style.display="none";

		
	}
function updateKeywords(kwd_id)
	{ 
		
		document.getElementById('disp_'+kwd_id).style.display="";
		edit_key1="edit_key_"+kwd_id;
		edit_val1="edit_val_"+kwd_id;
		
		if(verifyForm_EditKeywords(kwd_id)==false)
								{
									return false;
								}
		edit_key=document.getElementById(edit_key1).value;
		edit_val=document.getElementById(edit_val1).value;
		 temp1=document.getElementById('id').value;
		xmlhttp1=GetXmlHttpObject();
		if (xmlhttp1==null)
		  {
			  alert ("Browser does not support HTTP Request");
			  return;
		  }
		var url="list-keywords.php";
	//	url=url+"?id="+temp1;
		url=url+"?kid="+kwd_id;
		url=url+"&key="+edit_key;
		url=url+"&click_val="+edit_val;
	
			xmlhttp1.onreadystatechange=function (){ 
				if (xmlhttp1.readyState==4)
				{
	//alert(xmlhttp1.readyState);
		
	//alert(e_key+","+e_val);
		document.getElementById("disp_"+kwd_id).style.display="none";
		document.getElementById("edit_val_"+kwd_id).style.display="none";
		document.getElementById("edit_key_"+kwd_id).style.display="none";
		document.getElementById('cancel_'+kwd_id).style.display="none";
		document.getElementById('update_'+kwd_id).style.display="none";
		
		document.getElementById("show_name_"+kwd_id).style.display="";
		document.getElementById("show_val_"+kwd_id).style.display="";
		//alert("show_name_"+kwd_id);
		//alert(isNaN(xmlhttp1.responseText));
			if(!isNaN(xmlhttp1.responseText))
				//if(xmlhttp1.responseText.length==0)
			{
				
					
			document.getElementById("show_val_"+kwd_id).innerHTML=edit_val;
			document.getElementById("show_name_"+kwd_id).innerHTML=edit_key;
			document.getElementById("errormsg").innerHTML='';
			
			if(parseInt(xmlhttp1.responseText)==1)
			{
			document.getElementById("show_name_"+kwd_id).style.color='#666666';
			}
			/*if(parseInt(xmlhttp1.responseText)==0)
			{
			document.getElementById("show_name_"+kwd_id).style.color='#FF0000';
			}*/
			if(parseInt(xmlhttp1.responseText)==-1)
			{
			document.getElementById("show_name_"+kwd_id).style.color='#FF9933';
			}
		
		
			
			}
			else
			{
				document.getElementById("errormsg").innerHTML=xmlhttp1.responseText;
			}
	document.getElementById("errormsg").setAttribute("class", "already");
	document.getElementById("disp").style.display="none";
	}

			}
	//	xmlhttp1.onreadystatechange=updateresponce(kwd_id,edit_key,edit_val);
//		alert(url)
		xmlhttp1.open("GET",encodeURI(url),true);
		xmlhttp1.send(null);
	}
	
	function updateresponce(uk_id,e_key,e_val)
	{
		
		//
	
	}
function stateChanged()
{
if (xmlhttp.readyState==4)
{
	
	
	
document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
document.getElementById("disp").style.display="none";

//document.getElementById(edit_val1).value=edit_val;





}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}
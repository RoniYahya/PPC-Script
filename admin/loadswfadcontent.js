function LoadSwfImage(fpath,fwidth,fheight,furl,fstring)
{
	//alert(fpath+"aaaaaa"+fwidth+"gggg"+fheight+"jjjj"+furl);
	//alert(fstring);
	showLightBox(fpath,fwidth,fheight,furl,fstring);
	
	
}
	
	
	
	
	
	
	


//var ad=id;
//var data=data;


	function showLightBox(flpath,flwidth,flheight,flurl,flstring)
	{
		
//alert(flurl);
		var ie=document.all && !window.opera;
		//var dom=document.getElementById
		var iebody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body ;
		ht=(ie)? iebody.clientHeight: window.innerHeight ;//screen.height
		//alert(ht);
		wt=(ie)? iebody.clientWidth : window.innerWidth ;//screen.width
		
		
    document.getElementById("light").style.top=ht/2-275 + 'px';
	document.getElementById("light").style.left=wt/2-370 + 'px';
	
	if(flheight <=200)
	document.getElementById("light").style.height=(flheight+100)+"px";
	else
	document.getElementById("light").style.height=(flheight+65)+"px";
	
	if(flwidth <= 400)
	document.getElementById("light").style.width=(flwidth+200)+"px";
	else
	document.getElementById("light").style.width=flwidth+"px";
	

if(flheight > ht-100)
{
document.getElementById("light").style.overflow="scroll";
document.getElementById("light").style.height=(ht-125)+"px";
}
else
{
	document.getElementById("light").style.overflow="visible";
}		
document.getElementById("light").innerHTML='<table  width="100%" border="0" cellpadding="0" cellspacing="0" style="table-layout:fixed; overflow:hidden;" ><tr  valign="bottom" ><td  align="right" colspan="2">'+'<div   style="text-align:right;padding:0px;margin:0px;"><a href = "javascript:void(0)" onclick = "javascript:hideLightBox()"><img src="images/closelabel.gif" border="0" ></a></div></td></tr><tr><td colspan="2" class="note">All links in the banner which were detected by the system are now replaced with the "Hard Coded Link Checking Url",which can be configured from "Basic Configurations => Advanced Settings". Any undetected links will not be replaced. You may reject the ad if you find such links.</td></tr>'+'<tr  width="100%"><td colspan="2"  width="100%"> <div id="myFlashDiv"></div>	</td></tr></table>';
	
	//alert(data);
	







  		  var flashvars = {};
		  var params = {};
		  var attributes = {};
		  var i=1;
		  
		  flashvars=flstring;
		  
		  flashvars.clickTag = flurl;
		  
          flashvars.clickTAG = flurl;
		  flashvars.clickTARGET = "_blank";
		 		  
          //flstring;
		 	
				
			
		
			//	alert(flashvars.atar1);
				
	      swfobject.embedSWF(flpath, "myFlashDiv", flwidth, flheight, "9.0.0", "",flashvars,params,attributes);


  
		// flashvars.alink1='http://www.nesote1.com';flashvars.atar1='_blank';
		// flashvars.alink1='http://www.nesote1.com';flashvars.atar1='_blank';
	
		

		
	
	document.getElementById('light').style.display='block';
	document.getElementById('fade').style.display='block'
	

	
}
	
	
		
		
		
	function hideLightBox(type)
	{	

	document.getElementById('light').style.display='none';document.getElementById('fade').style.display='none'
	
	
	}
	

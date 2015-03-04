
/*
  NESOTE NEW JAVASCRIPT PLUGIN
**************************************** Nesote-Jquery-lib.js 1.0.0 beta**************************
*/

//popup//
function popup(w,h,s,str){
	
						var safecopyofitemData =$(str).html();
						var tempstr=str.split(".");
						var safecopyofitem='<div class=\"'+tempstr[1]+' nesotePopupnone\">'+safecopyofitemData+'</div>' // variable probelem ajoy can fix 
					
						var popupdata ="<div class='darkenBg'></div><div class='popUpbox'></div>"
						var winHeight=$(window).height();
						var winWidth=$(window).width();
						
						var initialscrollTop = $(window).scrollTop();
						var initialTop =initialscrollTop+(winHeight-20)/2;
						
						var leftPos=((winWidth-w)/2);
						var topPos=((winHeight-h)/2);
						$(popupdata).appendTo("body");
						
						$(".popUpbox").css({top:initialTop+"px"});
						
						$(".darkenBg").fadeIn(100,function(){
							$(".popUpbox").html("");
							var closeButton="<a class='nesote-popup-close-button'>X</a>"
							$(str).appendTo(".popUpbox");
							$(closeButton).appendTo(".popUpbox");
							$(".popUpbox").fadeIn(100,function(){
								var srolltopatthtime = $(window).scrollTop();
								var firstTop=srolltopatthtime+topPos;
									$(this).animate({"width":w+"px", "minHeight":h+"px" , "left":leftPos+"px" , "top":firstTop+"px"},300,function(){							
										$(this).css({height:"auto"});
										$(str).fadeIn(500,function(){
											var itemheight =$(".popUpbox").outerHeight();
											var scrollTop = $(window).scrollTop();
											var netTop =scrollTop+22;
												if(itemheight>winHeight)
												{
													$(".popUpbox").animate({"top":netTop+"px"});	
												}
												else
												{
													var fixedTopPos =(winHeight-itemheight)/2;
													$(".popUpbox").css({position:"fixed"});
													$(".popUpbox").animate({"top":fixedTopPos+"px"},200);
													
												}
												$(".popUpbox").css({"overflow":"visible"});
												$(".nesote-popup-close-button").fadeIn(200);
										});																  
									});
							});
							
							$(".nesote-popup-close-button, .darkenBg").click(function(){
							
						$(safecopyofitem).appendTo("body");
						$(".popUpbox").fadeOut(100,function(){
							$(this).remove();
							$(".darkenBg").fadeOut(100,function(){
							$(this).remove();									
							});
						});
						
					});
							
						});
					
					
				}
		
		
		
function autoPopout(time){
    var t=time*1000;
    setTimeout(function(){popout();},t);
}

//popup//

function nesotejslib()
{



//textbox placeholder//	
/***** Add Value as title and add a class 'place holder'*****/
	$('.placeholder').each(function() {
    this.value = $(this).attr('title');

    $(this).focus(function() {
        if(this.value == $(this).attr('title')) {
            this.value = '';
            $(this).removeClass('text-label');
        }
    });
 
    $(this).blur(function() {
        if(this.value == '') {
            this.value = $(this).attr('title');
        }
    });
});
//textbox placeholder Ends//	

/**** Popout ***/			
$(".darkenBg").click(function(){
			popout();
		});
/**** Popout Ends ***/

/**** Tool tip ***/
  $('.nesote-tooltip').hover(function(){
		
		  var thisWidth=$(this).outerWidth()/2;
		  var offset=$(this).position();
		  var thisHeight=$(this).outerHeight();
		  var tool_topPos=offset.top;
		  var tool_leftPos=offset.left; 
		  var tool_rtPos=($(window).width()-tool_leftPos)-$(this).outerWidth(); 
          var tooltip_msg=$(this).attr('title');
		  $(this).attr('title',"");
		  var elmt="<span class='nesote-tip'><strong>"+tooltip_msg+"</strong><b>&nbsp</b></span>";
		  $(this).after(elmt); 
		  $(".nesote-tip").css({left:tool_leftPos+"px",maxWidth:"300px"});
		  var tooltipheight=$(this).next(".nesote-tip").height();
		  var tipheight=$(this).next(".nesote-tip b").height()+10;
		  var hltooltipwidth=$(this).next(".nesote-tip").outerWidth()/2;
		  var tooltipwidth=($(this).next(".nesote-tip").outerWidth()/2)-thisWidth;
		  var finalTop=(tool_topPos-tooltipheight)-($(this).outerHeight()+5);
		  var finalTop2 =tool_topPos+thisHeight+tipheight;
		 
		  if ( (tool_leftPos < hltooltipwidth) && (tool_topPos-tooltipheight)<0)
		  {
			 $(".nesote-tip").css({left:tool_leftPos+"px",top:finalTop2+20+"px",marginLeft:+"0px",opacity:"0",zIndex:9999});
		  	 $(".nesote-tip").animate({top:finalTop2+"px",opacity:"1"},200); 
			 $(".nesote-tip b").addClass("top-lft");  
		  }
		  
		 if (tool_leftPos < hltooltipwidth && (tool_topPos-tooltipheight)>0)
		  {
			  $(".nesote-tip").css({left:tool_leftPos+"px", top:finalTop-tipheight+"px",marginLeft:-tooltipwidth+"px",opacity:"0",zIndex:9999});
			  $(".nesote-tip").animate({top:finalTop+"px",opacity:"1"},200);  
			  $(".nesote-tip b").addClass("lft");
		  }
		  
		  
		if ( (tool_leftPos > hltooltipwidth)&&(tool_topPos-tooltipheight)<0 )
		  {
			 $(".nesote-tip").css({top:finalTop2+20+"px",marginLeft:-tooltipwidth+"px",opacity:"0",zIndex:9999});
		  	 $(".nesote-tip").animate({top:finalTop2+"px",opacity:"1"},200); 
			 $(".nesote-tip b").addClass("top");
		  }
		  
		  

		  
	 	if( (tool_leftPos > hltooltipwidth) && (tool_topPos-tooltipheight)>0 )
		  {
	
		  $(".nesote-tip").css({top:finalTop-tipheight+"px",marginLeft:-tooltipwidth+"px",opacity:"0",zIndex:9999});
		  $(".nesote-tip").animate({top:finalTop+"px",opacity:"1"},200);  
		  }



		  
		  
		  
	},function(){
		var tooltip_msg_rtn=$(this).next('.nesote-tip').children("strong").html();
		$(".nesote-tip").remove();
		$(this).attr('title',tooltip_msg_rtn);
		});
/**** Tool tip Ends ***/			

/** Centered Element ***/
$(".centered").each(function(){
var cbtdwidth = 0-($(this).outerWidth()/2);
$(this).css({left:"50%",position:"absolute",marginLeft:cbtdwidth+"px"});
});

/** Centered Element Ends***/

/*** Tab****/
$(".nesote-tab").after("<div class='nesoteClr'></div><div class='nsoteTabContent'></div>");
var fsttabHtml =$(".nesote-tab > .tab:first").next(".tab-content").html();
$(".nesote-tab > .tab:first").addClass("nesote-tab-active");
$(".nsoteTabContent").html(fsttabHtml);
$(".nesote-tab .tab").hover(function(){
	$(this).addClass("nesote-tab-hover");									 
},
function(){
	$(this).removeClass("nesote-tab-hover");
	}
);
$(".nesote-tab .tab").click(function(){
					$(".nesote-tab .tab").removeClass("nesote-tab-active");
					$(this).addClass("nesote-tab-active");
					 var tabHtml =$(this).next(".tab-content").html();
					 $(".nsoteTabContent").html(tabHtml); 
});


/***Tab ends****/


/**** drop down menu ******/

$("ul.nesote-drop-menu li").hover(function(){
	$(this).children("ul").css({display:"block"});									   
},
function(){
	$(this).children("ul").css({display:"none"});
	}
);

$("input:text").addClass("txt");
$("input:password").addClass("txt");
$("input:button, input:submit").addClass("button");

/**** drop down menu Ends ******/

/**** Price Drag ****/
/*var clicking = false;

$('.dragging').mousedown(function(){
    clicking = true;
});

$('.dragging').mouseup(function(){
    clicking = false;
});

   $(document).mousemove(function(e){
		var xPos =e.pageX
    if(clicking == true)
	{
	  $(".dragging").stop(true,true).css({left:xPos+"px"});	 
	}
}); */

/*** Grid Table ****/
$(".gridTable").each(function(){
		$(".gridTable tr:even").addClass("alterTr");
	});
	
	
	
/***** image zoom ****/

var imageWidth;
var imageHeight;

var timesofWidth;
var timesofHeight;

var framePosXpos;
var framePosYpos;


$(".nesote-img-zoom").hover(function(){
									 
	var newImageUrl=$(".nesote-img-zoom img").attr("src");
	var theImage = new Image();
	theImage.src = newImageUrl;
	imageWidth = theImage.width;
	imageHeight = theImage.height;
									 
	var frameWidth=$(".nesote-img-zoom").width();
	var frameHeight=$(".nesote-img-zoom").height();
	var framePos=$(".nesote-img-zoom").offset();
	
	if ( frameWidth < imageWidth || frameHeight < imageHeight)
	{
	
	framePosXpos=(framePos.left)+frameWidth+10;
	framePosYpos=framePos.top;
	
	var resizedimgWidth=$(this).children("img").width();
	var resizedimgHeight=$(this).children("img").height();
	
	timesofWidth =imageWidth/resizedimgWidth;
	timesofHeight=imageHeight/resizedimgHeight;
	

	
	
	var imgUrl=$(this).children("img").attr("src");
	var pointer ='<div class="nesote-zoom-pointer"></div>'
	var popimage='<div class="nesote-zoom-pop"></div>'
	
	$(popimage).appendTo("body");
	$(pointer).appendTo(".nesote-img-zoom");
	$(".nesote-zoom-pop").css({'backgroundImage': 'url(' + imgUrl + ')', 'left':framePosXpos+"px", 'top':framePosYpos+"px"});
	
	 $(".nesote-img-zoom").mousemove(function(e){
										  
        var parentOffset = $(this).parent().offset();
        var relativeXPosition = (e.pageX - parentOffset.left);
        var relativeYPosition = (e.pageY - parentOffset.top);
		var fstframeWidth=$(".nesote-img-zoom").width();
		var fstframeHeight=$(".nesote-img-zoom").height();
		var poupdimensionX =$(".nesote-zoom-pop").width();
		var poupdimensionY =$(".nesote-zoom-pop").height();
		
		$(".nesote-zoom-pointer").css({'width': poupdimensionX/timesofWidth+"px", height:poupdimensionY/timesofHeight+"px"});
		
		var pointerWidth=$(".nesote-zoom-pointer").width()/2;
		var pointerHeight=$(".nesote-zoom-pointer").height()/2;
		

		
		var relativeXPosition1=0-((relativeXPosition*timesofWidth)+((fstframeWidth/pointerWidth)-(pointerWidth*timesofWidth)));
		var relativeYPosition1=0-((relativeYPosition*timesofHeight)+((fstframeHeight/pointerHeight)-(pointerHeight*timesofHeight)));
		
		var bgPos =''+relativeXPosition1+'px  '+relativeYPosition1+'px';
		var bgPosX ='0 '+relativeYPosition1+'px';
		var bgPosY =''+relativeXPosition1+'px  0';
		
		var relativeXPosition2=relativeXPosition-(pointerWidth);
		var relativeYPosition2=relativeYPosition-(pointerHeight);
		
		if ( poupdimensionX > imageWidth && poupdimensionX > imageHeight)
		{
			$(".nesote-zoom-pointer").css({"left":"0" , "top":"0" , "opacity":".5", "width":"100%", "height":"100%"});
			$(".nesote-zoom-pop").css('backgroundPosition', 'center center');	
		}
		
		else if(relativeYPosition2<=0 && relativeXPosition2<=0)
		{
			$(".nesote-zoom-pointer").css({"left":"0px" , "top":"0px" , "opacity":".5"});
			//$(".nesote-zoom-pop").css('backgroundPosition', bgPos);
		}
		
		else if(relativeXPosition2<=0)
		{
			$(".nesote-zoom-pointer").css({"left":"0px" , "top":relativeYPosition2+"px" , "opacity":".5"});
			$(".nesote-zoom-pop").css('backgroundPosition', bgPosX);
		}
		else if(relativeYPosition2<=0)
		
		{
			
		$(".nesote-zoom-pointer").css({"left":relativeXPosition2+"px" , "top":"0px" , "opacity":".5"});
			$(".nesote-zoom-pop").css('backgroundPosition', bgPosY);
		}
		
		
		
		else
		{
		$(".nesote-zoom-pointer").css({"left":relativeXPosition2+"px" , "top":relativeYPosition2+"px" , "opacity":".5"});
			$(".nesote-zoom-pop").css('backgroundPosition', bgPos);	
		}
		
		
       
    });
	}
	
	
},function(){
	$(".nesote-zoom-pointer, .nesote-zoom-pop").remove();
	
	});

 
 
/***** Image Zoom Ends ****/



/***** image Self zoom ****/
var imageWidthLense;
var imageHeightLense;

var timesofWidthLense;
var timesofHeightLense;

var newImageUrlLense=$(".nesote-img-lense-zoom img").attr("src");
var theImageLense = new Image();
theImageLense.src = newImageUrlLense;

imageWidthLense = theImageLense.width;
imageHeightLense = theImageLense.height;


$(".nesote-img-lense-zoom").hover(function(){
	
	var resizedimgWidthLense=$(this).children("img").width();
	var resizedimgHeightLense=$(this).children("img").height();
	
	timesofWidthLense =imageWidthLense/resizedimgWidthLense;
	timesofHeightLense=imageHeightLense/resizedimgHeightLense;
	

	
	
	var imgUrlLense=$(this).children("img").attr("src");
	var pointerLense ='<div class="nesote-zoom-pointerLense"></div>'
	//var popimage='<div class="nesote-zoom-pop"></div>'
	
	//$(this).after(popimage);
	$(pointerLense).appendTo(".nesote-img-lense-zoom");
	$(".nesote-zoom-pointerLense").css('backgroundImage', 'url(' + imgUrlLense + ')');
},function(){
	$(".nesote-zoom-pointerLense").remove();
	
	});

  $(".nesote-img-lense-zoom").mousemove(function(e){
										  
        var parentOffsetLense = $(this).parent().offset();
        var relativeXPositionLense = (e.pageX - parentOffsetLense.left); 
        var relativeYPositionLense = (e.pageY - parentOffsetLense.top);
		var relativeXPositionLense1=0-((relativeXPositionLense*timesofWidthLense)+(400/200));
		var relativeYPositionLense1=0-((relativeYPositionLense*timesofHeightLense)+(400/200));
		
		var bgPosLense =''+relativeXPositionLense1+'px  '+relativeYPositionLense1+'px'
		var pointerLenseXposLense =relativeXPositionLense;
		var pointerLenseYposLense =relativeYPositionLense;
		
		$(".nesote-zoom-pointerLense").css({"left":pointerLenseXposLense+"px" , "top":pointerLenseYposLense+"px"});
		
		$(".nesote-zoom-pointerLense").css('backgroundPosition', bgPosLense);
		
     
    });
  
  
   $('.nesote-img-lense-zoom').click(function(e){
      $('.nesote-zoom-pointerLense').animate({"height": '+=50',"width": '+=50'}, 200);
    });
/***** Slide Show ***/
/*.nesoteSlideshow */
$(".nesoteSlideshow").each(function(){
var thisItem =$(this);
var slideUl=$(this).outerWidth();
var numerOfLis=$(this).children("li").size();
var createdLongWidth=slideUl*numerOfLis;
var animatetoLeft=-slideUl;
var animatetoRight=slideUl;
$(this).width(createdLongWidth);
$(this).children("li").width(slideUl);
$(this).wrap('<div class="nesoteSlideshowOuter nesoteHas" />');
$(this).parent(".nesoteSlideshowOuter").width(slideUl);
var numController ="<div class='nesoteSlideNumController'></div>"
$(numController).appendTo($(this).parent(".nesoteSlideshowOuter"));

for(i = 0; i < numerOfLis; i++) {
$(document).ready(function(){
    $('.nesoteSlideNumController').append('<a/>');
});
}


var controllers="<div class='nesoteSlideController'><a class='nesoteSlideshowPrev'>Prev</a><a class='nesoteSlideshowNext'>Next</a></div>";
$(controllers).appendTo($(this).parent(".nesoteSlideshowOuter"));
$(this).children("li:first").fadeIn(100,function(){
	
	
	
	$(this).children("span.nesoteSlideDes").fadeIn(2000);
	$(this).addClass("nesoteCurrent");
	
	
	if ($(thisItem).children("li.nesoteCurrent").is(":first-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeOut();
				}
				else{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeIn();
					}
	
	
	/**** Auto *****/
	
	var hovering;
	var hovering=false;
	$(".nesoteSlideshowOuter").hover(
    function() {
       hovering = true;
    },
    function() {
       hovering = false;
    }
);
	
	
	setInterval(function() {		
			
			if (hovering == false)
			{
			if($("li.nesoteCurrent").next().is('li')) {	
				
				$(thisItem).children("li.nesoteCurrent").next("li").fadeIn(1,function(){
				$(this).addClass("nesoteCurrent");
				$(this).prev("li").removeClass("nesoteCurrent");
				$(this).parent(".nesoteSlideshow").animate({"marginLeft":'+='+animatetoLeft+'px'},1000);
				
				/**** Controller Visibility ***/
				if ($(thisItem).children("li.nesoteCurrent").is(":last-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowNext").fadeOut();
				}
				
				if ($(thisItem).children("li.nesoteCurrent").is(":first-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeOut();
				}
				else{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeIn();
					}
				/**** Controller Visibility Ends ***/	
					
				
				});
				
				
				
			}
			else
			{
				
				
				
				$(thisItem).children("li.nesoteCurrent").removeClass("nesoteCurrent");
				$(thisItem).animate({"marginLeft":'0px'},1000,function(){
					$(thisItem).children("li:first").addClass("nesoteCurrent");		
					
					/**** Controller Visibility ***/
					
					if ($(thisItem).children("li.nesoteCurrent").not(":last-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowNext").fadeIn();
				}
				
				if ($(thisItem).children("li.nesoteCurrent").is(":first-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeOut();
				}
				else{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeIn();
					}
					/**** Controller Visibility  Ends***/
				
				});
			}
			}
    }, 8000);
	
	/**** Auto Ends *****/
	
	
	
	$(this).parent("ul").siblings(".nesoteSlideController").children("a.nesoteSlideshowNext").click(function(){
				$(this).parent().siblings("ul").children("li.nesoteCurrent").next("li").fadeIn(1,function(){
				$(this).addClass("nesoteCurrent");
				$(this).prev("li").removeClass("nesoteCurrent");
				$(this).parent(".nesoteSlideshow").animate({"marginLeft":'+='+animatetoLeft+'px'},1000);
				
				/**** Controller Visibility ***/
				if ($(thisItem).children("li.nesoteCurrent").is(":last-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowNext").fadeOut();
				}
				
				if ($(thisItem).children("li.nesoteCurrent").is(":first-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeOut();
				}
				else{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeIn();
			
				}
				/**** Controller Visibility Ends ***/
		});
	});
	$(this).parent("ul").siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").click(function(){											 
				$(this).parent().siblings("ul").children("li.nesoteCurrent").prev("li").fadeIn(1,function(){
				$(this).addClass("nesoteCurrent");
				$(this).next("li").removeClass("nesoteCurrent");
				$(this).parent(".nesoteSlideshow").animate({"marginLeft":'-='+animatetoLeft+'px'},1000);
				/**** Controller Visibility ***/
					
					if ($(thisItem).children("li.nesoteCurrent").not(":last-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowNext").fadeIn();
				}
				
				if ($(thisItem).children("li.nesoteCurrent").is(":first-child"))
				{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeOut();
				}
				else{
					$(thisItem).siblings(".nesoteSlideController").children("a.nesoteSlideshowPrev").fadeIn();
					}
					/**** Controller Visibility  Ends***/
		});
	});
	
	
	
	
	
	
				
	
	
	
	
	$(".nesoteSlideNumController a").click(function(){
		var indexofButton=$(this).index()+1;
		$("ul.nesoteSlideshow li:nth-child("+indexofButton+")").addClass("haiii");
	});
});
});


/***** Slide Show Ends ***/
  
/***** Image Self Zoom Ends ****/
}

$(document).ready(function(){				   
 nesotejslib();
});



/*-----Developed by Sijith Chandran, Harikrishnan-----*/

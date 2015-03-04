jQuery.noConflict()

var stepCarousel={
	ajaxloadingmsg: '<div style="margin: 1em; font-weight: bold"><img src="ajaxloadr.gif" style="vertical-align: middle" /> Fetching Content. Please wait...</div>', //customize HTML to show while fetching Ajax content
	defaultButtonsfade: 0.4, //Fade degree for disabled nav buttons (0=completely transparent, 1=completely opaque)
	configholder: {},

	getCSSValue:function(val){ //Returns either 0 (if val contains 'auto') or val as an integer
		return (val=="auto")? 0 : parseInt(val)
	},

	getremotepanels:function($, config){ //function to fetch external page containing the panel DIVs
		config.$belt.html(this.ajaxloadingmsg)
		$.ajax({
			url: config.contentType[1], //path to external content
			async: true,
			error:function(ajaxrequest){
				config.$belt.html('Error fetching content.<br />Server Response: '+ajaxrequest.responseText)
			},
			success:function(content){
				config.$belt.html(content)
				config.$panels=config.$gallery.find('.'+config.panelClass)
				stepCarousel.alignpanels($, config)
			}
		})
	},

	getoffset:function(what, offsettype){
		return (what.offsetParent)? what[offsettype]+this.getoffset(what.offsetParent, offsettype) : what[offsettype]
	},

	getCookie:function(Name){ 
		var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
		if (document.cookie.match(re)) //if cookie found
			return document.cookie.match(re)[0].split("=")[1] //return its value
		return null
	},

	setCookie:function(name, value){
		document.cookie = name+"="+value
	},

	fadebuttons:function(config, currentpanel){
		config.$leftnavbutton.fadeTo('fast', currentpanel==0? this.defaultButtonsfade : 1)
		config.$rightnavbutton.fadeTo('fast', currentpanel==config.lastvisiblepanel? this.defaultButtonsfade : 1)
		if (currentpanel==config.lastvisiblepanel){
			stepCarousel.stopautoStep(config)
		}

	},

	addnavbuttons:function($, config, currentpanel){
		config.$leftnavbutton=$('<img src="'+config.defaultButtons.leftnav[0]+'">').css({zIndex:50, position:'absolute', left:config.offsets.left+config.defaultButtons.leftnav[1]+'px', top:config.offsets.top+config.defaultButtons.leftnav[2]+'px', cursor:'hand', cursor:'pointer'}).attr({title:'Back '+config.defaultButtons.moveby+' panels'}).appendTo('body')
		config.$rightnavbutton=$('<img src="'+config.defaultButtons.rightnav[0]+'">').css({zIndex:50, position:'absolute', left:config.offsets.left+config.$gallery.get(0).offsetWidth+config.defaultButtons.rightnav[1]+'px', top:config.offsets.top+config.defaultButtons.rightnav[2]+'px', cursor:'hand', cursor:'pointer'}).attr({title:'Forward '+config.defaultButtons.moveby+' panels'}).appendTo('body')
		config.$leftnavbutton.bind('click', function(){ //assign nav button event handlers
			stepCarousel.stepBy(config.galleryId, -config.defaultButtons.moveby)
		})
		config.$rightnavbutton.bind('click', function(){ //assign nav button event handlers
			stepCarousel.stepBy(config.galleryId, config.defaultButtons.moveby)
		})
		if (config.panelBehavior.wraparound==false){ //if carousel viewer should stop at first or last panel (instead of wrap back or forth)
			this.fadebuttons(config, currentpanel)
		}
		return config.$leftnavbutton.add(config.$rightnavbutton)
	},

	alignpanels:function($, config){
		var paneloffset=0
		config.paneloffsets=[paneloffset] //array to store upper left offset of each panel (1st element=0)
		config.panelwidths=[] //array to store widths of each panel
		config.$panels.each(function(index){ //loop through panels
			var $currentpanel=$(this)
			$currentpanel.css({float: 'none', position: 'absolute', left: paneloffset+'px'}) //position panel
			$currentpanel.bind('click', function(e){return config.onpanelclick(e.target)}) //bind onpanelclick() to onclick event
			paneloffset+=stepCarousel.getCSSValue($currentpanel.css('marginRight')) + parseInt($currentpanel.get(0).offsetWidth || $currentpanel.css('width')) //calculate next panel offset
			config.paneloffsets.push(paneloffset) //remember this offset
			config.panelwidths.push(paneloffset-config.paneloffsets[config.paneloffsets.length-2]) //remember panel width
		})
		config.paneloffsets.pop() //delete last offset (redundant)
		var addpanelwidths=0
		var lastpanelindex=config.$panels.length-1
		config.lastvisiblepanel=lastpanelindex
		for (var i=config.$panels.length-1; i>=0; i--){
			addpanelwidths+=(i==lastpanelindex? config.panelwidths[lastpanelindex] : config.paneloffsets[i+1]-config.paneloffsets[i])
			if (config.gallerywidth>=addpanelwidths){
				config.lastvisiblepanel=i //calculate index of panel that when in 1st position reveals the very last panel all at once based on gallery width
			}
		}
		config.$belt.css({width: paneloffset+'px'}) //Set Belt DIV to total panels' widths
		config.currentpanel=(config.panelBehavior.persist)? parseInt(this.getCookie(config.galleryId+"persist")) : 0 //determine 1st panel to show by default
		config.currentpanel=(typeof config.currentpanel=="number" && config.currentpanel<config.$panels.length)? config.currentpanel : 0
		var endpoint=config.paneloffsets[config.currentpanel]+(config.currentpanel==0? 0 : config.beltoffset)
		config.$belt.css({left: -endpoint+'px'})
		if (config.defaultButtons.enable==true){ //if enable default back/forth nav buttons
			var $navbuttons=this.addnavbuttons($, config, config.currentpanel)
			$(window).bind("load resize", function(){ //refresh position of nav buttons when page loads/resizes, in case offsets weren't available document.oncontentload
				config.offsets={left:stepCarousel.getoffset(config.$gallery.get(0), "offsetLeft"), top:stepCarousel.getoffset(config.$gallery.get(0), "offsetTop")}
				config.$leftnavbutton.css({left:config.offsets.left+config.defaultButtons.leftnav[1]+'px', top:config.offsets.top+config.defaultButtons.leftnav[2]+'px'})
				config.$rightnavbutton.css({left:config.offsets.left+config.$gallery.get(0).offsetWidth+config.defaultButtons.rightnav[1]+'px', top:config.offsets.top+config.defaultButtons.rightnav[2]+'px'})
			})
		}
		if (config.autoStep && config.autoStep.enable){ //enable auto stepping of Carousel?		
			var $carouselparts=config.$gallery.add(typeof $navbuttons!="undefined"? $navbuttons : null)
			$carouselparts.bind('click', function(){
				config.autoStep.status="stopped"
				stepCarousel.stopautoStep(config)
			})
			$carouselparts.hover(function(){ //onMouseover
				stepCarousel.stopautoStep(config)
				config.autoStep.hoverstate="over"
			}, function(){ //onMouseout
				if (config.steptimer && config.autoStep.hoverstate=="over" && config.autoStep.status!="stopped"){
					config.steptimer=setInterval(function(){stepCarousel.autorotate(config.galleryId)}, config.autoStep.pause)
					config.autoStep.hoverstate="out"
				}
			})
			config.steptimer=setInterval(function(){stepCarousel.autorotate(config.galleryId)}, config.autoStep.pause) //automatically rotate Carousel Viewer
		} //end enable auto stepping check
		this.createpaginate($, config)
		this.statusreport(config.galleryId)
		config.oninit()
		config.onslideaction(this)
	},

	stepTo:function(galleryId, pindex){ /*User entered pindex starts at 1 for intuitiveness. Internally pindex still starts at 0 */
		var config=stepCarousel.configholder[galleryId]
		if (typeof config=="undefined"){
			//alert("There's an error with your set up of Carousel Viewer \""+galleryId+ "\"!")
			return
		}
		stepCarousel.stopautoStep(config)
		var pindex=Math.min(pindex-1, config.paneloffsets.length-1)
		var endpoint=config.paneloffsets[pindex]+(pindex==0? 0 : config.beltoffset)
		if (config.panelBehavior.wraparound==false && config.defaultButtons.enable==true){ //if carousel viewer should stop at first or last panel (instead of wrap back or forth)
			this.fadebuttons(config, pindex)
		}
		config.$belt.animate({left: -endpoint+'px'}, config.panelBehavior.speed, function(){config.onslideaction(this)})
		config.currentpanel=pindex
		this.statusreport(galleryId)
	},

	stepBy:function(galleryId, steps, isauto){
		var config=stepCarousel.configholder[galleryId]
		if (typeof config=="undefined"){
			//alert("There's an error with your set up of Carousel Viewer \""+galleryId+ "\"!")
			return
		}
		if (!isauto) //if stepBy() function isn't called by autorotate() function
			stepCarousel.stopautoStep(config)
		var direction=(steps>0)? 'forward' : 'back' //If "steps" is negative, that means backwards
		var pindex=config.currentpanel+steps //index of panel to stop at
		if (config.panelBehavior.wraparound==false){ //if carousel viewer should stop at first or last panel (instead of wrap back or forth)
			pindex=(direction=="back" && pindex<=0)? 0 : (direction=="forward")? Math.min(pindex, config.lastvisiblepanel) : pindex
			if (config.defaultButtons.enable==true){ //if default nav buttons are enabled, fade them in and out depending on if at start or end of carousel
				stepCarousel.fadebuttons(config, pindex)
			}	
		}
		else{ //else, for normal stepBy behavior
			if (pindex>config.lastvisiblepanel && direction=="forward"){
				//if destination pindex is greater than last visible panel, yet we're currently not at the end of the carousel yet
				pindex=(config.currentpanel<config.lastvisiblepanel)? config.lastvisiblepanel : 0
			}
			else if (pindex<0 && direction=="back"){
				//if destination pindex is less than 0, yet we're currently not at the beginning of the carousel yet
				pindex=(config.currentpanel>0)? 0 : config.lastvisiblepanel /*wrap around left*/
			}
		}
		var endpoint=config.paneloffsets[pindex]+(pindex==0? 0 : config.beltoffset) //left distance for Belt DIV to travel to
		if (config.panelBehavior.wraparound==true && config.panelBehavior.wrapbehavior=="pushpull" && (pindex==0 && direction=='forward' || config.currentpanel==0 && direction=='back')){ //decide whether to apply "push pull" effect
			config.$belt.animate({left: -config.paneloffsets[config.currentpanel]-(direction=='forward'? 100 : -30)+'px'}, 'normal', function(){
				config.$belt.animate({left: -endpoint+'px'}, config.panelBehavior.speed, function(){config.onslideaction(this)})
			})
		}
		else
			config.$belt.animate({left: -endpoint+'px'}, config.panelBehavior.speed, function(){config.onslideaction(this)})
		config.currentpanel=pindex
		this.statusreport(galleryId)
	},

	autorotate:function(galleryId){
		var config=stepCarousel.configholder[galleryId]
		this.stepBy(galleryId, config.autoStep.moveby, true)
	},

	stopautoStep:function(config){
		clearTimeout(config.steptimer)
	},

	statusreport:function(galleryId){
		var config=stepCarousel.configholder[galleryId]
		if (config.statusVars.length==3){ //if 3 status vars defined
			var startpoint=config.currentpanel //index of first visible panel 
			var visiblewidth=0
			for (var endpoint=startpoint; endpoint<config.paneloffsets.length; endpoint++){ //index (endpoint) of last visible panel
				visiblewidth+=config.panelwidths[endpoint]
				if (visiblewidth>config.gallerywidth){
					break
				}
			}
			startpoint+=1 //format startpoint for user friendiness
			endpoint=(endpoint+1==startpoint)? startpoint : endpoint //If only one image visible on the screen and partially hidden, set endpoint to startpoint
			var valuearray=[startpoint, endpoint, config.panelwidths.length]
			for (var i=0; i<config.statusVars.length; i++){
				window[config.statusVars[i]]=valuearray[i] //Define variable (with user specified name) and set to one of the status values
				config.$statusobjs[i].text(valuearray[i]+" ") //Populate element on page with ID="user specified name" with one of the status values
			}
		}
		stepCarousel.selectpaginate(jQuery, galleryId)
	},

	createpaginate:function($, config){
		if (config.$paginatediv.length==1){
			var $templateimg=config.$paginatediv.find('img["data-over"]:eq(0)') //reference first matching image on page
			var controlpoints=[], controlsrc=[], imgarray=[], moveby=$templateimg.attr("data-moveby") || 1
			var asize=(moveby==1? 0:1) + Math.floor((config.lastvisiblepanel+1) / moveby) //calculate # of pagination links to create
			var imghtml=$('<div>').append($templateimg.clone()).html() //get HTML of first matching image
			srcs=[$templateimg.attr('src'), $templateimg.attr('data-over'), $templateimg.attr('data-select')] //remember control's over and out, and selected image src
			for (var i=0; i<asize; i++){
				var moveto=Math.min(i*moveby, config.lastvisiblepanel)
				imgarray.push(imghtml.replace(/>$/, ' data-index="'+i+'" data-moveto="'+moveto+'" title="Move to Panel '+(moveto+1)+'">') +'\n')
				controlpoints.push(moveto) //store panel index each control goes to when clicked on
			}
			var $controls=$('<span></span>').replaceAll($templateimg).append(imgarray.join('')).find('img') //replace template link with links and return them
			$controls.css({cursor:'pointer'})
			config.$paginatediv.bind('click', function(e){
				var $target=$(e.target)
				if ($target.is('img') && $target.attr('data-over')){
					stepCarousel.stepTo(config.galleryId, parseInt($target.attr('data-moveto'))+1)
				}
			})
			config.$paginatediv.bind('mouseover mouseout', function(e){
				var $target=$(e.target)
				if ($target.is('img') && $target.attr('data-over')){
					if (parseInt($target.attr('data-index')) != config.pageinfo.curselected) //if this isn't the selected link
						$target.attr('src', srcs[(e.type=="mouseover")? 1 : 0])
				}
			})
			config.pageinfo={controlpoints:controlpoints, $controls:$controls,  srcs:srcs, prevselected:null, curselected:null}
		}
	},

	
	selectpaginate:function($, galleryId){
		var config=stepCarousel.configholder[galleryId]
		if (config.$paginatediv.length==1){
			for (var i=0; i<config.pageinfo.controlpoints.length; i++){
				if (config.pageinfo.controlpoints[i] <= config.currentpanel) //find largest control point that's less than or equal to current panel shown
					config.pageinfo.curselected=i
			}
			if (typeof config.pageinfo.prevselected!=null) //deselect previously selected link (if found)
				config.pageinfo.$controls.eq(config.pageinfo.prevselected).attr('src', config.pageinfo.srcs[0])
			config.pageinfo.$controls.eq(config.pageinfo.curselected).attr('src', config.pageinfo.srcs[2]) //select current paginate link
			config.pageinfo.prevselected=config.pageinfo.curselected //set current selected link to previous
		}
	},


	loadcontent:function(galleryId, url){
		var config=stepCarousel.configholder[galleryId]
		config.contentType=['ajax', url]
		stepCarousel.stopautoStep(config)
		stepCarousel.resetsettings($, config)
		stepCarousel.init(jQuery, config)

	},

	init:function($, config){
		config.gallerywidth=config.$gallery.width()
		config.offsets={left:stepCarousel.getoffset(config.$gallery.get(0), "offsetLeft"), top:stepCarousel.getoffset(config.$gallery.get(0), "offsetTop")}
		config.$belt=config.$gallery.find('.'+config.beltClass) //Find Belt DIV that contains all the panels
		config.$panels=config.$gallery.find('.'+config.panelClass) //Find Panel DIVs that each contain a slide
		config.panelBehavior.wrapbehavior=config.panelBehavior.wrapbehavior || "pushpull" //default wrap behavior to "pushpull"
		config.$paginatediv=$('#'+config.galleryId+'-paginate') //get pagination DIV (if defined)
		if (config.autoStep)
			config.autoStep.pause+=config.panelBehavior.speed
		config.onpanelclick=(typeof config.onpanelclick=="undefined")? function(target){} : config.onpanelclick //attach custom "onpanelclick" event handler
		config.onslideaction=(typeof config.onslide=="undefined")? function(){} : function(beltobj){$(beltobj).stop(); config.onslide()} //attach custom "onslide" event handler
		config.oninit=(typeof config.oninit=="undefined")? function(){} : config.oninit //attach custom "oninit" event handler
		config.beltoffset=stepCarousel.getCSSValue(config.$belt.css('marginLeft')) //Find length of Belt DIV's left margin
		config.statusVars=config.statusVars || []  //get variable names that will hold "start", "end", and "total" slides info
		config.$statusobjs=[$('#'+config.statusVars[0]), $('#'+config.statusVars[1]), $('#'+config.statusVars[2])]
		config.currentpanel=0
		stepCarousel.configholder[config.galleryId]=config //store config parameter as a variable
		if (config.contentType[0]=="ajax" && typeof config.contentType[1]!="undefined") //fetch ajax content?
			stepCarousel.getremotepanels($, config)
		else
			stepCarousel.alignpanels($, config) //align panels and initialize gallery
	},

	resetsettings:function($, config){
		config.$gallery.unbind()
		config.$belt.stop()
		config.$panels.remove()
		if (config.$leftnavbutton){
			config.$leftnavbutton.remove()
			config.$rightnavbutton.remove()
		}
		if (config.$paginatediv.length==1){
			config.$paginatediv.unbind()
			config.pageinfo.$controls.eq(0).attr('src', config.pageinfo.srcs[0]).removeAttr('data-index').removeAttr('data-moveto').removeAttr('title') //reset first pagination link so it acts as template again
			.end().slice(1).remove() //then remove all but first pagination link
		}
		if (config.autoStep)
			config.autoStep.status=null
		if (config.panelBehavior.persist){
			stepCarousel.setCookie(window[config.galleryId+"persist"], 0) //set initial panel to 0, overridden w/ current panel if window.unload is invoked
		}
	},

	setup:function(config){
		//Disable Step Gallery scrollbars ASAP dynamically (enabled for sake of users with JS disabled)
		document.write('<style type="text/css">\n#'+config.galleryId+'{overflow: hidden;}\n</style>')
		jQuery(document).ready(function($){
			config.$gallery=$('#'+config.galleryId)
			stepCarousel.init($, config)
		}) //end document.ready
		jQuery(window).bind('unload', function(){ //clean up on page unload
			stepCarousel.resetsettings($, config)
			if (config.panelBehavior.persist)
				stepCarousel.setCookie(config.galleryId+"persist", config.currentpanel)
			jQuery.each(config, function(ai, oi){
				oi=null
			})
			config=null
		})
	}
}

stepCarousel.setup({
	galleryId: 'mygallery',
	beltClass: 'belt',
	panelClass: 'panel',
	autoStep: {enable:true, moveby:1, pause:100},
	panelBehavior: {speed:1500, wraparound:true, wrapbehavior:'slide', persist:false},
	defaultButtons: {enable: false, moveby: 1, leftnav: ['images/leftnav.gif', -20, 55], rightnav: ['images/rightnav.gif', 15, 55]},
	statusVars: ['statusA', 'statusB', 'statusC'],
	contentType: ['inline']
})
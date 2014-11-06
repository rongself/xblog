(function(jQuery) {
	var pop_up=jQuery('.pop_up'),
		ul=jQuery('.popupSliderContainer ul'), 
		li=jQuery('.popupSliderContainer ul li'), 
		sliderContainer=jQuery('.popupSliderContainer');
	var infoContainer=jQuery('.infoContainer');
	var left=jQuery('#moveLeft'), 
		right=jQuery('#moveRight'),
		info=jQuery('.info');
	var liCount=1;
    jQuery.fn.initSlider = function(elem, src) {

		jQuery('body').append('<div class="pop_up"></div> <div class="pop_up_container"><div class="close"></div><div class="popupSliderContainer"><ul></ul></div><div class="infoContainer"><div class="moveButtons"><div id="moveLeft"></div><div id="moveRight"></div></div></div></div>').hide().fadeIn(600);
		jQuery('.infoContainer').append('<div class="info"><h2 id="title"></h2> <p id="caption"></p> <p id="alt"></p></div>')

		info=jQuery('.info');
		pop_up=jQuery('.pop_up');
		ul=jQuery('.popupSliderContainer ul');
		sliderContainer=jQuery('.popupSliderContainer');
		left=jQuery('#moveLeft');
		right=jQuery('#moveRight');

		jQuery.each(elem.find('img:not(".wp-socializer img , wp-socializer-buttons img")'), function() {
			if(!jQuery(this).parents().hasClass('simpleSlider')){
				var current=jQuery( this );
				var caption=" "
				if(current.next().hasClass('wp-caption-text')){
					caption=current.next().text();
				}else if(current.parent().parent().hasClass('wp-caption-text')) {
					caption=current.parent().parent().text();
				}
				var theImage = new Image();
				theImage.src = current.attr("src");
				var imageWidth = theImage.width;
				var imageHeight = theImage.height;

				var minWidth=10, minHeight=10;
				if(imageHeight<imageWidth){
					if(imageWidth<sliderContainer.width/2){
						minWidth=20;
					}else minWidth=100;
				}else{
					minWidth=10;
					minHeight=100;
				}
				var tooltip='';
				if(jQuery( this ).attr('title')){
					if(jQuery( this ).attr('title')!='undefined' && jQuery( this ).attr('title').length>1)
					{
						tooltip=jQuery( this ).attr('title');
					}
				}
					else{
						tooltip=' '
					}
					
				ul.append('<li><img style="min-width:'+minWidth+'%; min-height:'+minHeight+'%;" src="'+jQuery( this ).attr('src')+'" alt="'+jQuery( this ).attr('alt')+'" title="'+tooltip+'" data-caption="'+caption+'"></li>')
				if(jQuery( this ).attr('src')==src) liCount=jQuery('.popupSliderContainer ul li').length;
			}
		});
		li=jQuery('.popupSliderContainer ul li');
		li.css('width',sliderContainer.width()+1);
		ul.css({
			'height':sliderContainer.height(),
			'width':li.length*li.width()+100,
			'left':-li.width()*(liCount-1)
			});
		imageInfo(li.eq(liCount-1).find('img'));
    }
	function is_touch_device() {
	  return 'ontouchstart' in window 
		  || 'onmsgesturechange' in window; 
	};
	 pop_up.live('click', function (e) {
		close();
	});

	left.live('click', function (e) {
		slideLeft();
	});
	right.live('click', function (e) {
		slideRight();
	});

	ul.live('click', function (e) {
		if(!is_touch_device()){
			slideRight();
		}
	});
	 jQuery('.close').live('click', function (e) {	
		close();
	 });
	 jQuery(document).on('keydown',function(e) {
		if(jQuery('.pop_up').length>0){
			if (e.which == 39) {
			  slideRight();
			} else if (e.which == 37) {
			  slideLeft();
			}   else if(e.which==27){
				close();
			}
	   }
	});

	var doubleTouchTime = 0;
	jQuery('.pop_up, .pop_up_container').live('touchstart', function(ev){
		var e = ev.originalEvent;
		var touch=e.touches[0];
		 var now = +(new Date());
		if (doubleTouchTime + 500 > now) {
			e.preventDefault();
		}
		doubleTouchTime = now;
	});


	 var posX,posY,moveLength,interactionStart=0;
	 var lastMove=0;
	sliderContainer.live('touchstart',ul, function(ev){
		var e = ev.originalEvent;
		var touch=e.touches[0];
		moveLength = 0;
		posX =touch.pageX;
	});

	sliderContainer.live('touchmove',ul, function(ev){
		var e = ev.originalEvent;
		e.preventDefault();
		lastMove=e;
	});


	sliderContainer.live('touchend',ul, function(ev){
		slow(function(){
		lastMove.preventDefault();
		var touch=lastMove.touches[0];
		moveLength = touch.pageX - posX;
		if(Math.abs(moveLength)>70){
			if(moveLength>0){
					slideLeft();
				}else{
					slideRight();
				}
			}else {slideRight();}
			},250);
	});

	jQuery('#moveLeft, #moveRight').live('touchstart', function(){
		jQuery(this).css('background-color','#e85e50');
	});
	jQuery('#moveLeft, #moveRight').live('touchend', function(){
		jQuery(this).css('background-color','#a6a6a6');
	});
	jQuery('.close').live('touchstart', function(){
		jQuery('.close').addClass('closeTouchHover');
	});
	jQuery('.close').live('touchend', function(){
		jQuery('.close').removeClass('closeTouchHover');
	});

	pop_up.live('touchend', function(ev){
		jQuery('.close').removeClass('closeTouchHover');
		close();
	});
	 function close(){
		liCount=1;
		jQuery('.pop_up, .pop_up_container').fadeOut(700, function() { $(this).remove();});
	}

	function slideLeft(){
		if(!ul.is(':animated') && li.length!=1){
			if(liCount!=1){
				liCount--;
				ul.animate({
					left: -li.width()*(liCount-1)
				  }, 300);
				imageInfo(li.eq(liCount-1).find('img'));
			}else{
				liCount=li.length;
				ul.animate({
					left: -li.width()*(liCount-1)
				  }, 300);
				imageInfo(li.eq(liCount-1).find('img'));
			}
		}
	}

	function slideRight(){
		if(!ul.is(':animated') && li.length!=1){
			if(liCount!=li.length){
				ul.animate({
						left: -li.width()*liCount
					  }, 300);
				imageInfo(li.eq(liCount).find('img'));
				liCount++;
			}else{
				liCount=0;
				ul.animate({
						left: 0
					  }, 300);
				
				imageInfo(li.eq(liCount).find('img'));
				liCount++;
			}
		}
	}

	function imageInfo(currentImg){
		info.hide();
		var title
		if(currentImg.attr('title')=='undefined')
			title=' ';
			else title=currentImg.attr('title');
		jQuery('#title').text(title);
		jQuery('#caption').text(currentImg.attr('data-caption'));
		if(currentImg.attr('alt')=='' || currentImg.attr('alt')=='undefined')currentImg.attr('alt',' ');
		jQuery('#alt').text(currentImg.attr('alt'));
		resizeSlider();
		info.fadeIn(700);
	}

	function resizeSlider(){
	sliderContainer=jQuery('.popupSliderContainer');
	infoContainer=jQuery('.infoContainer');
			if(jQuery(window).width()>jQuery(window).height()){
				infoContainer.removeClass('infoContainerVertical');
				sliderContainer.removeClass('popupSliderContainerVertical');
				jQuery('.close').removeClass('closeVertical');
				jQuery('.close').css({'right':'2%'});
				jQuery('.moveButtons').css({
					left:'0'
				});
				jQuery('.info').css({
					width:'80%',
					margin:'50px 10px 0 10px'
				});
		}else{
			infoContainer.addClass('infoContainerVertical');
			sliderContainer.addClass('popupSliderContainerVertical');
			jQuery('.close').addClass('closeVertical');
			jQuery('.close').css({'right':jQuery('#moveLeft').width()-10});
			jQuery('.moveButtons').css({
				left:'auto'
			});
			jQuery('.info').css({
				margin:'0 10px 0 10px',
				width:'60%'
			});
		}
			li.css('width',sliderContainer.width()+1);
			ul.css({
				'height':sliderContainer.height(),
				'width':li.length*li.width()+100,
				left: -li.width()*(liCount-1)
			});
	}

	var to = true,
	slow = function(func, delay){
		if (to) {
		window.clearTimeout(to);
		}
		to = window.setTimeout(func, delay);
	};

	window.onresize = function(){
		slow(function(){
		   resizeSlider();
		},250);
	};

}(jQuery));
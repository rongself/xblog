jQuery.easing['jswing'] = jQuery.easing['swing'];

jQuery.extend( jQuery.easing,
    {
        def: 'easeOutQuad',
        easeOutCubic: function (x, t, b, c, d) {
            return c*((t=t/d-1)*t*t + 1) + b;
        }
    });

var $ = window.jQuery;
var imgSize = 290;
var smallImgSize = 265;
var s = 400;

$(function(){
	if($('.gallery').length == 0 || $('.gallery').find('img').length == 0)
		return false;
	
	$('.gallery').wrap('<div id="slideWrapper" class="slideWrapper"/>');
	$('#slideWrapper').wrap('<div id="relative"/>');
	$('#slideWrapper').append('<p id="toLeft" class="arrowSlide leftArrowSlide"></p>');
	$('#slideWrapper').append('<p id="toRight" class="arrowSlide rightArrowSlide"></p>');
	$('#slideWrapper').append('<div class="forWidth"></div>');
	$('.gallery').find('br').remove();
	
	$('#slideWrapper').bind('mouseover', function(e){
		$(window).bind('mousewheel', function(e){
			e.preventDefault();
		});
	});
	$('#slideWrapper').bind('mouseleave', function(e){
		$(window).unbind('mousewheel');
	});
	
	var currentIndex = 0;
	
	var windowWidth;
	var images = $('dl', '.gallery');
	var length = images.length;
	
	// simple slider construction // start
	$('#relative').append('<div id="simpleSlider" class="simpleSlider"></div>');
	$('#simpleSlider').wrap('<div id="simpleSlideWrap" class="simpleSlideWrap"></div>');
	
	images.each(function(i, el){
		var curEl = $('<a href="' + $(this).find('a').attr('href') +'"></a>');
		$('#simpleSlider').append(curEl);
		curEl.append('<img src="' + $(this).find('img').attr('src') + '">');
	});
	$('#simpleSlider').append('<div class="clearBoth"></div>');
	
	var imgsInSimpleSlide = $('#simpleSlider').find('img');
	$('#simpleSlider').css({'width': (imgsInSimpleSlide.length * imgSize) + 'px'});
	
	$('#relative').append('<div id="buttonWrap" class="buttonWrap"></div>');
	for(var j = 0; j < imgsInSimpleSlide.length; j++){
		$('#buttonWrap').append('<p id="s' + j + '" class="slideButton"></p>');
	}
	var imgIndex = 0;
	var p = $('#buttonWrap').find('p');
	p.each(function(i, el){
		if(i == 0)
			$(el).css({'background-color': '#3db2ff'});
		$(this).click(function(e){
			p.css({'background-color': '#aaaaaa'});
			$(this).css({'background-color': '#3db2ff'});
			$('#simpleSlider').css({'left': (-i * smallImgSize) + 'px'});
			imgIndex = i;
			currentIndex = i; //////new added
		});
	});
	imgsInSimpleSlide.click(function(e){
		e.preventDefault();
		e.stopPropagation();
	});
	// end
	
	////new added
	var simpleSlideIsVisible = false;
	var slideIsVisible = false;
	if($('#simpleSlider').is(':visible'))
		simpleSlideIsVisible = true;
	else
		slideIsVisible = true;
	
	var index = 0;
	var prev = null;
	var cur = images[index];
	var next = images[index + 1];

	var k = 0;
	var deltaNext;
	var deltaPrev;
	var maxScroll;
	
	var imLength = images.find('img').length;
	var preloaded = 0;
	var imgHeight = 50;
	
	// getting sizes when loading images // start
	if($(window).width() > 567){
		images.find('img')
			.filter(function() {
				return this.complete;
			}).each(loadHandler)
			.end()
			.load(loadHandler);
	}
	else{
		imgsInSimpleSlide.filter(function() {
				return this.complete;
			}).each(loadHandler)
			.end()
			.load(loadHandler);
	}
	function loadHandler(){
		var curImgHeight = ($(this).height()) / ($(this).width()) * imgSize;
		if(curImgHeight > imgHeight){
			imgHeight = curImgHeight;
		}
		if(++preloaded == imLength){
			if($('#slideWrapper').length > 0){
				$('#slideWrapper').css({'height': (imgHeight + 4) + 'px'});
				$('.arrowSlide').css({'top': ((imgHeight + 4 - 33) / 2) + 'px'});
			}
			if($('#simpleSlideWrap').length > 0){
				$('#simpleSlideWrap').css({'height': (imgHeight * smallImgSize / imgSize) + 'px'});
			}
			$('.gallery').find('dt').css({'height': imgHeight + 'px'});
			
			/////
			if($(window).width() <= 768)
				windowWidth = $(window).width() - 52;
			else
				windowWidth = $('#relative').width(); // sometimes(when <= 768) gets wrong value. ?
			
			if(windowWidth <= 270)
				k = 120;
			else if(windowWidth >= 640)
				k = 0;
			else
				k = 120 - (12 * (windowWidth - 270)) / 46;
			deltaNext = 225 - k;
			deltaPrev = 135 - k;
			
			$('.forWidth').width((length + Math.floor(windowWidth / s) - 1) * s + windowWidth % s);
			maxScroll = length * s + windowWidth % s - windowWidth;
			
			$(cur).css({'z-index': 82, 'left': ((windowWidth - (imgSize + 2)) / 2) + 'px'})
				.find('dt')
				.css({'transform': 'rotateY(0deg)', '-webkit-transform': 'rotateY(0deg)', 'text-indent': '0px'})
				.find('img')
				.css({'width': imgSize});
			$(next).css({'z-index': 81, 'left': ((windowWidth - (imgSize + 2)) / 2 + deltaNext) + 'px'})
				.find('dt')
				.css({'transform': 'rotateY(-30deg)', '-webkit-transform': 'rotateY(-30deg)', 'text-indent': '-30px'})
				.find('img')
				.css({'width': (imgSize-90)});
			$(cur).show();
			$(next).show();
			///////
			
			$.each(images, function(i, el){
				if(i != 0 && i != 1){
					$(el).css({'z-index': 80, 'left': ((windowWidth - (imgSize + 2)) / 2 + deltaNext) + 'px'})
						.find('dt')
						.css({'transform': 'rotateY(-30deg)', '-webkit-transform': 'rotateY(-30deg)', 'text-indent': '-30px'})
						.find('img')
						.css({'width': (imgSize-90)});
					$(el).hide();
				}
			});
			
			/////// arrows		
			if($('#slideWrapper').scrollLeft() < maxScroll)
				$('#toRight').removeClass('rightArrowSlide')
					.addClass('rightActive');
			else
				$('#toRight').removeClass('rightActive rightHover')
					.addClass('rightArrowSlide');
			if($('#slideWrapper').scrollLeft() > 0)
				$('#toLeft').removeClass('leftArrowSlide')
					.addClass('leftActive');
			else
				$('#toLeft').removeClass('leftActive leftHover')
					.addClass('leftArrowSlide');
			///////
		}
	}
	// end
	
	var dist, startX, startY, threshold = 70, allowedTime = 400, elapsedTime, startTime;
	var scrolled;
	var slide = document.getElementById('slideWrapper');
	var slideStart = 0;

	slide.addEventListener('touchstart', function(e){
		e.preventDefault();
		$('#slideWrapper').stop();
		var obj = e.changedTouches[0];
		dist = 0;
		startX = obj.pageX;
		slideStart = obj.pageX;
		startY = obj.pageY;
		startTime = new Date().getTime();
	});

	slide.addEventListener('touchmove', function(e){
		e.preventDefault();
		var obj = e.changedTouches[0];
		if(Math.abs(obj.pageX - startX) < Math.abs(dist))
			startX = obj.pageX;
		dist = obj.pageX - startX;
		scrolled = $('#slideWrapper').scrollLeft() - dist * 2.5;
		$('#slideWrapper').scrollLeft(scrolled);
		startX = obj.pageX;
	});

	slide.addEventListener('touchend', function(e){
		e.preventDefault();
		var obj = e.changedTouches[0];
		var scrollPosition = $('#slideWrapper').scrollLeft();
		dist = obj.pageX - slideStart;
		distY = Math.abs(obj.pageY - startY);
		elapsedTime = new Date().getTime() - startTime;
		
		var isSwiped = ((Math.abs(dist) >= threshold) && (distY < 100) && (elapsedTime < allowedTime) && (elapsedTime>100));
		if(isSwiped){
			$('#slideWrapper').css({'text-indent' : scrollPosition});
			var animTo = 0;
			if(dist > 0){
				animTo = scrollPosition - scrollPosition % s;
				if(animTo < 0)
					animTo = 0;
			}
			else{
				animTo = scrollPosition - scrollPosition % s + s;
				if(animTo > maxScroll)
					animTo = maxScroll;
			}
			$('#slideWrapper').animate({'text-indent' : animTo},{
				step: function(now, fx){
					$('#slideWrapper').scrollLeft(now);
				},
				duration: 300,
				easing: 'easeOutCubic',
				complete: function(){
					currentIndex = Math.floor($('#slideWrapper').scrollLeft() / s);
				}
			});
		}
	});
	
	$('.attachment-thumbnail, .simpleSlider img, .entry-content img').on('touchend', function(e){
		var touchTime = new Date().getTime() - startTime;
		if(touchTime<=100){
			jQuery.fn.initSlider(jQuery('.entry-content'),jQuery(this).attr('src'));
			}
	});
	//small slider touch events
	var smallSlide = document.getElementById('simpleSlideWrap');
	smallSlide.addEventListener('touchstart', function(e){
		e.preventDefault();
		var obj = e.changedTouches[0];
		dist = 0;
		startX = obj.pageX;
		slideStart = obj.pageX;
		startY = obj.pageY;
		startTime = new Date().getTime();
	});
	smallSlide.addEventListener('touchmove', function(e){
		e.preventDefault();
	});
	var animIsFinished = true;
	smallSlide.addEventListener('touchend', function(e){
		e.preventDefault();
		var obj = e.changedTouches[0];
		dist = obj.pageX - slideStart;
		distY = Math.abs(obj.pageY - startY);
		elapsedTime = new Date().getTime() - startTime;
		var isSwiped = ((Math.abs(dist) >= threshold) && (distY < 100) && (elapsedTime < allowedTime));
		if(isSwiped && animIsFinished){
			animIsFinished = false;
			var l = parseInt($('#simpleSlider').css('left'));
			if(dist > 0 && l < 0){
				$('#simpleSlider').css('left', (l + smallImgSize) + 'px');
				imgIndex--;
				p.each(function(i, el){
					if(i == imgIndex)
						$(this).css({'background-color': '#3db2ff'});
					else
						$(this).css({'background-color': '#aaaaaa'});
				});
			}
			else if(dist < 0 && l > -(smallImgSize * ($('#simpleSlideWrap').find('img').length - 1))){
				$('#simpleSlider').css('left', (l - smallImgSize) + 'px');
				imgIndex++;
				p.each(function(i, el){
					if(i == imgIndex)
						$(this).css({'background-color': '#3db2ff'});
					else
						$(this).css({'background-color': '#aaaaaa'});
				});
			}
			currentIndex = imgIndex; /////new added
			setTimeout(function(){
				animIsFinished = true;
			}, 420);
		}
	});
	
	
	var mousewheelevt;
	if((/Firefox/i.test(navigator.userAgent)))
		mousewheelevt = 'DOMMouseScroll';
	else
		mousewheelevt = 'mousewheel';
		
	slide.addEventListener(mousewheelevt, $.debounce(100, function(e){
		var scrollPosition = $('#slideWrapper').scrollLeft();
		$('#slideWrapper').css({'text-indent' : scrollPosition});
		var animTo = 0;
		
		var rolled = 0;
		if(e.wheelDelta)
			rolled = e.wheelDelta;
		else if(e.detail)
			rolled = -40 * e.detail;
		if(rolled > 0){
			var u = 0
			if(scrollPosition%s == 0)
				u = s;
			animTo = scrollPosition - scrollPosition % s - u;
			if(animTo < 0)
				animTo = 0;
		}
		else if(rolled < 0){
			animTo = scrollPosition - scrollPosition % s + s;
			if(animTo > maxScroll)
				animTo = maxScroll;
		}
		$('#slideWrapper').animate({'text-indent' : animTo},{
			step: function(now, fx){
				$('#slideWrapper').scrollLeft(now);
			},
			duration: 300,
			easing: 'easeOutCubic',
			complete: function(){
				currentIndex = Math.floor($('#slideWrapper').scrollLeft() / s);
			}
		});
		e.preventDefault();
	}), false);

	slide.addEventListener('MozMousePixelScroll', function(e){
		e.preventDefault();
	}, false);
	
	function toLeft(){
		var scrollPosition = $('#slideWrapper').scrollLeft();
		$('#slideWrapper').css({'text-indent' : scrollPosition});
		var u = 0
		if(scrollPosition%s == 0)
			u = s;
		var animTo = scrollPosition - scrollPosition % s - u;
		$('#slideWrapper').animate({'text-indent' : animTo},{
			step: function(now, fx){
				$('#slideWrapper').scrollLeft(now);
			},
			duration: 300,
			easing: 'easeOutCubic',
			complete: function(){
				currentIndex = Math.floor($('#slideWrapper').scrollLeft() / s);
			}
		});
	}
	function toRight(){
		var scrollPosition = $('#slideWrapper').scrollLeft();
		$('#slideWrapper').css({'text-indent' : scrollPosition});
		var animTo = scrollPosition - scrollPosition % s + s;
		$('#slideWrapper').animate({'text-indent' : animTo},{
			step: function(now, fx){
				$('#slideWrapper').scrollLeft(now);
			},
			duration: 300,
			easing: 'easeOutCubic',
			complete: function(){
				currentIndex = Math.floor($('#slideWrapper').scrollLeft() / s);
			}
		});
	}
	var timerLeft, timerRight;

	$('#toLeft').on('mouseenter', function(e){
		if($(this).hasClass('leftActive'))
			$(this).addClass('leftHover');
	});
	$('#toLeft').on('mouseleave', function(e){
		$(this).removeClass('leftHover');
	});
	
	$('#toRight').on('mouseenter', function(e){
		if($(this).hasClass('rightActive'))
			$(this).addClass('rightHover');
	});
	$('#toRight').on('mouseleave', function(e){
		$(this).removeClass('rightHover');
	});
	
	$('#toLeft').on('click', function(e){
		clearInterval(timerRight);
		toLeft();
		e.preventDefault();
		e.stopPropagation();
	});
	$('#toRight').on('click', function(e){
		clearInterval(timerLeft);
		toRight();
		e.preventDefault();
		e.stopPropagation();
	});
	
	$('#toLeft').on('mousedown', function(e){
		clearInterval(timerRight);
		timerLeft = setInterval(function(){
			toLeft();
		}, 800);
		e.preventDefault();
		e.stopPropagation();
	});
	$('#toLeft').on('mouseup', function(e){
		clearInterval(timerLeft);
		e.preventDefault();
	});

	$('#toRight').on('mousedown', function(e){
		clearInterval(timerLeft);
		timerRight = setInterval(function(){
			toRight();
		}, 800);
		e.preventDefault();
		e.stopPropagation();
	});
	$('#toRight').on('mouseup', function(e){
		clearInterval(timerRight);
		e.preventDefault();
	});
	
	var leftTouchStart;
	$('#toLeft').on('touchstart', function(e){
		//toLeft();
		leftTouchStart = new Date().getTime();
		
		if($(this).hasClass('leftActive'))
			$(this).addClass('leftHover');
		
		clearInterval(timerRight);
		timerLeft = setInterval(function(){
			toLeft();
		}, 800);
		e.preventDefault();
		e.stopPropagation();
	});
	$('#toLeft').on('touchend', function(e){
		clearInterval(timerLeft);
		
		$(this).removeClass('leftHover');
		
		if((new Date().getTime() - leftTouchStart) < 800)
			toLeft();
	});

	var rightTouchStart;
	$('#toRight').on('touchstart', function(e){
		//toRight();
		rightTouchStart = new Date().getTime();
		
		if($(this).hasClass('rightActive'))
			$(this).addClass('rightHover');
		
		clearInterval(timerLeft);
		timerRight = setInterval(function(){
			toRight();
		}, 800);
		e.preventDefault();
		e.stopPropagation();
	});
	$('#toRight').on('touchend', function(e){
		clearInterval(timerRight);
		
		$(this).removeClass('rightHover');
		
		if((new Date().getTime() - rightTouchStart) < 800)
			toRight();
	});
	
	$('#toLeft, #toRight').on('touchmove', function(e){
		e.preventDefault();
		e.stopPropagation();
	});
	
	var postScroll = 0;
	document.getElementById('slideWrapper').addEventListener('scroll', function(e){
		/////// arrows		
		if($('#slideWrapper').scrollLeft() < maxScroll)
			$('#toRight').removeClass('rightArrowSlide')
				.addClass('rightActive');
		else
			$('#toRight').removeClass('rightActive rightHover')
				.addClass('rightArrowSlide');
		if($('#slideWrapper').scrollLeft() > 0)
			$('#toLeft').removeClass('leftArrowSlide')
				.addClass('leftActive');
		else
			$('#toLeft').removeClass('leftActive leftHover')
				.addClass('leftArrowSlide');
		///////
		
		postScroll = $('#slideWrapper').scrollLeft();
		animateScroll(postScroll);
		e.preventDefault();
	}, false);
	
	function animateScroll(value){
		var posNum = value%s;
		var tmpIndex = Math.floor(value/s);
		currentIndex = tmpIndex;//new added
		
		$.each(images, function(i, el){
			if(i < (tmpIndex - 1) || i > (tmpIndex + 2)){
				$(el).css({'z-index': 80, 'left': ((windowWidth - (imgSize + 2)) / 2 + deltaNext) + 'px'})
					.find('dt')
					.css({'transform': 'rotateY(-30deg)', '-webkit-transform': 'rotateY(-30deg)', 'text-indent': '-30px'})
					.find('img')
					.css({'width': (imgSize-90)});
				$(el).hide();
			}
			else{
				$(el).show();
			}
		});
		
		var curLeft = (windowWidth - (imgSize + 2)) / 2 - posNum * deltaPrev / (s - 1);
		var curAngle = posNum * 30 / (s - 1);
		var curMarginTop = 13 + posNum * 46 / (s - 1);
		var curWidth = imgSize - posNum * 90 / (s - 1);
		
		var pLeft = (windowWidth - (imgSize + 2)) / 2 - deltaPrev;
		var pAngle = 30;
		var pMarginTop = 59;
		var pWidth = imgSize - 90;
		
		var nLeft = (windowWidth - (imgSize + 2)) / 2 + deltaNext - posNum * deltaNext / (s - 1);
		var nAngle = -30 + posNum * 30 / (s - 1);
		var nMarginTop = 59 - posNum * 46 / (s - 1);
		var nWidth = (imgSize - 90) + posNum * 90 / (s - 1);
		
		var curZIndex = 82, nZIndex = 81;
		if(posNum > s/2){
			curZIndex = 81;
			nZIndex = 82;
			currentIndex++; ///// new added
		}
		
		$(images[tmpIndex]).css({'z-index':curZIndex, 'left':curLeft})
			.find('dt')
			.css({'transform':'rotateY(' + curAngle + 'deg)', '-webkit-transform':'rotateY(' + curAngle + 'deg)'})
			.find('img')
			.css({'width':curWidth});
		$(images[tmpIndex-1]).css({'z-index':81, 'left':pLeft})
			.find('dt')
			.css({'transform':'rotateY(' + pAngle + 'deg)', '-webkit-transform':'rotateY(' + pAngle + 'deg)'})
			.find('img')
			.css({'width':pWidth});
		$(images[tmpIndex+1]).css({'z-index':nZIndex, 'left':nLeft})
			.find('dt')
			.css({'transform':'rotateY(' + nAngle + 'deg)', '-webkit-transform':'rotateY(' + nAngle + 'deg)'})
			.find('img')
			.css({'width':nWidth});
		$(images[tmpIndex+2]).css({'z-index':80, 'left':((windowWidth - (imgSize + 2)) / 2 + deltaNext) + 'px'})
			.find('dt')
			.css({'transform':'rotateY(-30deg)', '-webkit-transform':'rotateY(-30deg)'})
			.find('img')
			.css({'width':imgSize-90});
		
		if(posNum < 2 || postScroll < 2)
			$(images[tmpIndex+2]).hide();
	}
	
	$(window).resize(function(e){
		/////// arrows	
		if($('#slideWrapper').scrollLeft() < maxScroll)
			$('#toRight').removeClass('rightArrowSlide')
				.addClass('rightActive');
		else
			$('#toRight').removeClass('rightActive rightHover')
				.addClass('rightArrowSlide');
		if($('#slideWrapper').scrollLeft() > 0)
			$('#toLeft').removeClass('leftArrowSlide')
				.addClass('leftActive');
		else
			$('#toLeft').removeClass('leftActive leftHover')
				.addClass('leftArrowSlide');
		////////
	
		var scr = $('#slideWrapper').scrollLeft();
		var tmpIndex = Math.floor(scr / s);
		var posNum = scr % s;
		
		windowWidth = $('#relative').width();
		$('.forWidth').width((length + Math.floor(windowWidth / s) - 1) * s + windowWidth % s);
		maxScroll = length * s + windowWidth % s - windowWidth;
		
		if(windowWidth <= 270)
			k = 120;
		else if(windowWidth >= 640)
			k = 0;
		else
			k = 120 - (12 * (windowWidth - 270)) / 46;
		
		deltaNext = 225 - k;
		deltaPrev = 135 - k;
		
		//////new added
		if($('#simpleSlider').is(':visible') && !simpleSlideIsVisible){
			simpleSlideIsVisible = true;
			slideIsVisible = false;
			
			$('#simpleSlider').css({'left': (-currentIndex * smallImgSize) + 'px'});
			p.css({'background-color': '#aaaaaa'});
			p.eq(currentIndex).css({'background-color': '#3db2ff'});
		}
		if(!$('#simpleSlider').is(':visible') && !slideIsVisible){
			simpleSlideIsVisible = false;
			slideIsVisible = true;
			
			var scrollTo = currentIndex * s
			if(currentIndex == length - 1)
				scrollTo = maxScroll;
			$('#slideWrapper').scrollLeft(scrollTo);
		}
		//
		
		var curLeft = (windowWidth - (imgSize + 2)) / 2 - posNum * deltaPrev / (s - 1);
		var pLeft = (windowWidth - (imgSize + 2)) / 2 - deltaPrev;
		var nLeft = (windowWidth - (imgSize + 2)) / 2 + deltaNext - posNum * deltaNext / (s - 1);
	
		$(images[tmpIndex]).css({'left':curLeft});
		$(images[tmpIndex-1]).css({'left':pLeft});
		$(images[tmpIndex+1]).css({'left':nLeft});
		$(images[tmpIndex+2]).css({'left':((windowWidth - (imgSize + 2)) / 2 + deltaNext) + 'px'});
	});

	images.each(function(i, el){
		$(this).find('img').on('click', function(e){
			e.preventDefault();
			var scrollPosition = $('#slideWrapper').scrollLeft();
			$('#slideWrapper').css({'text-indent' : scrollPosition});
			var animTo = ($(this).closest('dl').index()) * s;
			if(scrollPosition != animTo){
				e.stopPropagation();
				$('#slideWrapper').animate({'text-indent' : animTo},{
					step: function(now, fx){
						$('#slideWrapper').scrollLeft(now);
					},
					duration: 300,
					easing: 'easeOutCubic',
					complete: function(){
						currentIndex = Math.floor($('#slideWrapper').scrollLeft() / s);
					}
				});
			}
		});
	});
});



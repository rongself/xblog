jQuery(document).ready(function() {
  if (jQuery('.content').length > 0)
    rightTwitTop = parseInt(jQuery('.content').offset().top);

  var maxTop;
 
  jQuery(window).resize(function(e) {
	 throttle(function() {
		windowHeight = jQuery(window).height();
		menuSideBarWrapper.addClass("displayNone");
		menu.addClass("displayNone");
		initSideBar();
		youtubeRatio();
	    imagesRatio();
	  }, 250);

    rightTwitPosition();
	
	  setTimeout(function() {
		bottomDiv();
	  }, 1000);
  });
  
  jQuery(window).scroll(function(e) {
	rightTwitPosition();
  });

  var menu = jQuery("#smallMenuItems");
  jQuery("#smallMenuIcon").on("click", function() {
    jQuery("#searchInSmallHeader").hide(); /////////////// show/hide menu

    //var orient = Math.abs(window.orientation) == 90 ? 'landscape' : 'portrait';

    if (jQuery(window).width() > jQuery(window).height()) {
		
      if (menuSideBarWrapper.hasClass("displayNone")) {
        menuSideBarWrapper.removeClass("displayNone");
      }
      else {
        menuSideBarWrapper.addClass("displayNone");


      }
    } else {
      if (menu.hasClass("displayNone")) {
        menu.removeClass("displayNone");
      }
      else {
        menu.addClass("displayNone");
      }
    }

    jQuery('.downArrow').each(function(i, el) {
      jQuery(this).removeClass('downArrow')
              .addClass('addArrow');
      jQuery(this).siblings('ul').hide();
    });
  });

  jQuery("#findIconInSmall").on("click", function(e) {
    menu.addClass("displayNone");
    menuSideBarWrapper.addClass("displayNone");
    jQuery("#searchInSmallHeader").toggle();
  });
  jQuery("#findIconInLarge").on("click", function(e) {
    jQuery("#searchInLargeHeader").toggle();
  });

  jQuery('#smallMenuItems li, #menuSideBar li').each(function(index, el) {
    var subMenu = jQuery(this).children('ul:nth(0)').hide();

    if (subMenu.length > 0) {
      var self = jQuery(this);

      jQuery(this).on('click', 'a', function(e) {
        if (subMenu.is(':visible') || jQuery(this).siblings('ul').length <= 0)
          return true;

        var leftPadding = parseInt(jQuery(this).css('padding-left')) + 10 + "px";	//sub-menu indent
        self.children('ul')
                .children('li')
                .children('a')
                .css('padding-left', leftPadding);									//

        subMenu.toggle();
        self.children('a').toggleClass('addArrow');
        jQuery(this).toggleClass('downArrow');

        return false;
      });

      jQuery(this).children('a').addClass('addArrow');
    }
  });

  var largeMenu = jQuery("#largeMenu > div:first-child > ul");
  var li = largeMenu.find("li");
  li.addClass("relativePos");

  largeMenu.find('li').each(function(index, el) {
    var subMenuLarge = jQuery(this).children('ul:nth(0)').hide();

    jQuery(this).on('mouseenter', function(e) {
      var left = jQuery(this).closest("ul").width();
      var top = -6;
      if (jQuery(this).closest("ul").parent().is("div"))
      {
        left = 0;
        top = 56;
      }
      jQuery(this).children('ul')
              .addClass('largeSubmenu')
              .css({left: left, top: top});
      subMenuLarge.show();
    });
    jQuery(this).on('mouseleave', function(e) {
      subMenuLarge.hide();
    });

    if (subMenuLarge.length > 0) {
      var s = jQuery(this); // li which has sub-menu

      jQuery(this).on('click', 'a', function(e) {
        if (subMenuLarge.is(':visible') || jQuery(this).siblings('ul').length <= 0)
          return true;

        var left = s.closest("ul").width();
        var top = -6;
        if (s.closest("ul").parent().is("div"))
        {
          left = 0;
          top = 56;
        }
        s.children('ul')
                .addClass('largeSubmenu')
                .css({left: left, top: top});
        s.children('ul')
                .children('li')
                .children('ul')
                .addClass('displayNone');

        subMenuLarge.toggle();
        return false;
      });
      if (!(s.closest("ul").parent().is("div"))) {
        jQuery(this).children('a').addClass('addArrow');
      }
    }
  });

  var $container = jQuery('.content');
  if (jQuery('.posts').length >= 1) {
    $container.imagesLoaded(function() {
	 youtubeRatio();
	 imagesRatio();
      $container.masonry({
        itemSelector: '.posts',
        singleMode: false,
        isResizable: false,
        isAnimated: true,
		columnWidth: ".posts",
        gutter: 7,
        animationOptions: {
          queue: true,
          duration: 500
        }
      });
      bottomDiv();
    });



    jQuery.expresscurate({
      container: '.content',
      item: '.posts',
      pagination: '.content .navigation',
      next: '.nav-previous a',
      triggerPageThreshold: 300000000,
      appendItems: false,
      loader: '<ul class="loader">\
                  <li></li>\
                  <li></li>\
                  <li></li>\
              </ul>',
      onRenderComplete: function(items) { 
        $container.imagesLoaded(function() {
		  imagesRatio();
		  //youtubeRatio();
          $container.masonry('appended', items, true);
          $(items).hide().css('visibility', 'visible').show('slow');
          setTimeout(function() {
            bottomDiv();
          }, 600);
        });

      }
    });
  }
   

  if (jQuery(".smallCats").length > 0) {
    if (jQuery(".smallCats span").text().length == 0) {
      jQuery(".smallCats span").text(jQuery(".smallCats a").first().text());
    }
    jQuery(".smallCats a").hover(function() {
      jQuery(".smallCats span").text(jQuery(this).text());
    });
  }

  jQuery('#up').on("click", function(e)
  {
    jQuery('html, body').animate({
      'scrollTop': 0
    }, 500);

  });

  var $allVideos= jQuery("iframe[src*='youtube']");

	/**/
	jQuery('.entry-content').on('click','img',function(){
		//if(!jQuery(this).parent('a').length>0){
			jQuery.fn.initSlider(jQuery('.entry-content'),jQuery(this).attr('src'));
		//}
	});
	menuSideBarWrapper = jQuery('.menuSideBarWrapper');
	menuSideBar = jQuery('#menuSideBar');
	//
	jQuery.each(jQuery('body').find('img:not(".wp-socializer img , wp-socializer-buttons img")'), function() {
	  if(jQuery( this ).attr('title')=='undefined')
			jQuery( this ).attr('title','');
	});

    // new added
    jQuery('.entry-content img').each(function (i, el){
        if (jQuery(this).closest('.wp-socializer').length == 0){
            var tagName = 'p';
            if (jQuery(this).hasClass('alignleft')){
                if (jQuery(this).parent('a').length != 0){
                    if (jQuery(this).parent('a').parent('p').length != 0 && jQuery(this).parent('p').text().replace(/\s+/g, '').length == 0){
                        tagName = 'p';
                    } else {
                        tagName = 'a';
                    }
                } else {
                    if (jQuery(this).parent('p').length != 0 && jQuery(this).parent('p').text().replace(/\s+/g, '').length == 0){
                        tagName = 'p';
                    } else {
                        tagName = 'img';
                    }
                }
                jQuery(this).css({
                    'width': 'auto',
                    'margin-bottom': '0',
                    'max-width': '100%'
                });
                jQuery(this).closest(tagName).css({
                    'display': 'block',
                    'float': 'left',
                    'margin': '5px 10px 5px 0px',
                    'clear': 'left',
                    'max-width': '100%'
                });
            } else if (jQuery(this).hasClass('alignright')){
                if (jQuery(this).parent('a').length != 0){
                    if (jQuery(this).parent('a').parent('p').length != 0 && jQuery(this).parent('p').text().replace(/\s+/g, '').length == 0){
                        tagName = 'p';
                    } else {
                        tagName = 'a';
                    }
                } else {
                    if (jQuery(this).parent('p').length != 0 && jQuery(this).parent('p').text().replace(/\s+/g, '').length == 0){
                        tagName = 'p';
                    } else {
                        tagName = 'img';
                    }
                }
                jQuery(this).css({
                    'width': 'auto',
                    'margin-bottom': '0',
                    'max-width': '100%'
                });
                jQuery(this).closest(tagName).css({
                    'display': 'block',
                    'float': 'right',
                    'margin': '5px 0px 5px 10px',
                    'clear': 'right',
                    'max-width': '100%'
                });
            }
        }
    });
    //

	//ready end
});

 //YouTube videos
 function youtubeRatio(){
	$allVideos = jQuery('iframe[src*="youtube.com/embed"]');
	$allVideos.each(function() {
		jQuery(this)
			.data('aspectRatio', 1.90)
			.removeAttr('height')
			.removeAttr('width');
	});
    var newWidth;
	if(jQuery('.entry-content').length>0) newWidth= jQuery('.entry-content').width();
		else newWidth= jQuery('.entry-summary').width();
    $allVideos.each(function() {
        var $el = $(this);
        $el
          .width(newWidth)
          .height(newWidth / $el.data('aspectRatio'));
    });
 }
 //images sizes
 function imagesRatio(){
	 jQuery('.entry_featured img, .fimgContainer img').each(function() {
        jQuery(this).parent('a').css({
            width:'100%',
            height:jQuery('.entrySummaryContent ').width()*3/4,
            display:'block',
            overflow:'hidden',
            'max-height':jQuery(this).height()
        });
	});
 }

	var rightTwitTop;
	var windowHeight;
	var menuSideBarWrapper = jQuery('.menuSideBarWrapper');
	var menuSideBar = jQuery('#menuSideBar');
	
jQuery(window).on('scroll', function() {
  if (jQuery(window).scrollTop() > 55) {
    menuSideBarWrapper.css('padding-top', 0);
    menuSideBar.css('height', windowHeight - 57);
    jQuery('#up').css('display', 'block');
  } else {
    menuSideBarWrapper.css('padding-top', 55);
    menuSideBar.css('height', windowHeight - 107);
    jQuery('#up').css('display', 'none');

  }
});

var to = true,
        throttle = function(func, delay) {
  if (to) {
    window.clearTimeout(to);
  }
  to = window.setTimeout(func, delay);
};

jQuery(window).on("orientationchange", function() {
  menuSideBarWrapper.addClass("displayNone");

  jQuery('#smallMenuItems').addClass("displayNone");
});
 
jQuery(window).on('load', function() {
  windowHeight = jQuery(window).height();
  initSideBar();
  youtubeRatio();
  
});

function initSideBar() {
  if (jQuery.browser.safari) {
    windowHeight = jQuery(window).height() + 60;
  } else {
    windowHeight = jQuery(window).height();
  }
  menuSideBar.css('height', windowHeight - 100);
  menuSideBarWrapper.css({
    'height': windowHeight - 50,
    'min-height': windowHeight - 50
  });
}

function bottomDiv() {
  jQuery('#added_padding').remove();
  var lastPost = jQuery(".post:last");
  var bLast = jQuery(".post:last").prev();

  var top1 = parseInt(lastPost.css('top')) + lastPost.height() + 20;
  var top2 = parseInt(bLast.css('top')) + bLast.height() + 20;
  if (jQuery(window).width() > 765 && jQuery(".post:last").prev().length) {
    var max = Math.max(top1, top2);
    jQuery(".content").append('<div id="added_padding" style="position:absolute; top:' + max + 'px; height:70px; width: 100%;"></div>');
  } else {
    jQuery(".content").append('<div id="added_padding" style="position:absolute; top:' + top1 + 'px; height:70px; width: 100%;"></div>');
  }
}
function rightTwitPosition(){
	var righttwit=jQuery('.rightTwit');
    if (righttwit.length > 0) {
	  var scrolltop=jQuery(document).scrollTop();
      maxTop = Math.max(rightTwitTop, parseInt(scrolltop));
	  if(jQuery('#wpadminbar').length>0 && scrolltop>jQuery('#container').offset().top){
		righttwit.css({'top': maxTop+jQuery('#wpadminbar').height() + 'px'});
	  }else{
		righttwit.css({'top': maxTop + 'px'});
	  }
    }
 }
//Search
function sumbit_search(link) {
  jQuery(link).closest("form").submit();
}
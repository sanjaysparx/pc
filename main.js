/* ==========================================================================
   GLOBAL NAMESPACE - Site
   ========================================================================== */

/* we declare a single namespace in the global scope to minimise 
clashes and help with portability */
var Site = Site || {};


Site.supportedProps = {
	transitions: Modernizr.csstransforms
}

/* ==========================================================================
   HOMEPAGE
   ========================================================================== */


/* PRODUCT FEATURE SLIDER WITH GLOBES
   ========================================================================== */

if (Site.bxr !== "ie6") {
	Site.productSlides = (function() {
		var product_slides = $("#product-slides");
		product_slides.flexslider({
			animation: "slide",
			animationDuration: 5000,
			directionNav: true,
			pausePlay: true,
			start: function(){
				product_slides.find('.slide').removeClass('vh');
				// Give the pause play button abiulity to be focused and activated via keyboard
				product_slides.find('.flex-pauseplay a').attr({
					'href'		: '#'
				});
				//product_slides.find('.slide .hero-globe-hemisphere').hide();
				$.publish( 'globe/slide-advance', 1 );
			},
			before: function(slider){
				var dest = slider.animatingTo+1;
				// Trigger slide advance event
				$.publish( 'globe/slide-advance', dest );
			}
		}); 
	})();
}



/* PRODUCT (ICON) LIST
   ========================================================================== */

Site.productList = {

	init: function() {

		if (Site.bxr === "ie6") return;
		
		this.current_box;
		this.current_box_index 	= 0;
		this.is_scrolled		= false;

		this.loader_html	= $('<div class="ajax-loader product-box-loader">Content Loading</div>');


		this.bAnimating = false;


		// Cache DOM selections and vars
		this.cached();

		this.setTabIndex();


		// Setup the rollover states
		this.setupRollovers();

		// Setup observers
		this.addObservers();


	},

	cached: function() {

		this.product_list 			= $('.product-list');
		this.product_boxes 			= this.product_list.find('.product-box');
		this.product_icons 			= this.product_list.find('.product-icons');
		this.product_box_content 	= this.product_list.find('.product-box-content');
		this.product_list_offset 	= parseInt(this.product_list.css('margin-left'),10);
	

		// Product box width (no units) including padding and margins
		this.product_box_width 		= this.product_boxes.eq(1).outerWidth(true);

		// Product width only (no units) - no padding or margin
		this.product_box_width_pure = this.product_boxes.first().width();

		this.product_box_height		= this.product_boxes.first().height();
	},

	setTabIndex: function(element,bActive) {
		// Sets all focusable elements to be non-focusable by keyboard unless the element (or box) is active
		var ele = element || this.product_box_content;

		if (bActive) {
			ele.attr({
				tabindex: "0"
			}).find('a').attr({
				tabindex: "0"
			});
		} else {
			ele.attr({
				tabindex: "-1"
			}).find('a').attr({
				tabindex: "-1"
			});
		}
	},

	addObservers: function() {
		var _this = this;

		this.product_list.on('click', '.product-box', $.proxy(this.swtichToBox, Site.productList));

		this.product_list.on('click', '.product-box .close', function() {
			_this.resetBoxes(true);
		});

		
		// Close box for keyboard users
		$(document).bind('keyup', function(event) {
            var keycode = event.keyCode;
            //console.log(document.activeElement);
            if(keycode === 27) {
            	_this.resetBoxes(true);
            }
        });

	},

	setupRollovers: function() {
		/**
		 * Each icon box has a rollover state
		 * To minimise http we combine into a Sprite
		 * We then use JS to clone icon activate the
		 * "inverse" state and then place over the
		 * original icon. 
		 */

		this.product_icons.each(function() {
			
			// Gather required attr's
			var parent 			= $(this).parent(),
				height 			= $(this).height(),
				sClass 			= $(this).attr('class'),
				clone  			= $(this).clone(),
				reversed_class 	= sClass + '-r';

			
	
			// Loop each icon and clone
			// Switch class to "reversed" sprite state (adding "-r")
			// Insert after standard icon and shift up to sit underneath
	
			clone.addClass(reversed_class)
				.removeClass(sClass)
				.addClass('product-icons-r')
				.addClass('product-icons')
				.css ({
					'margin-top': -(height)
				})
				.insertAfter($(this));

			
		});
	},

	swtichToBox: function(evt) {
		/**
		 * Handles switching to the new feature box,
		 * positioning it and then opening it
		 */
		
		if (this.bAnimating) return;
		
		
		


		// Make sure we access the <div class="product-box" />
		var _this		= this,
			evt_target 	= $(evt.target),			
			// Check that the element that was clicked was a product box
			target		= (evt_target.is('.product-box')) ? evt_target : evt_target.parents('.product-box'),
			index		= target.index(),
			box_width 	= this.product_box_width;

		// If the click is a "btn" then allow the link to work as usual
		// otherwise cancel the event
		if (!evt_target.hasClass('btn')) {
			evt.preventDefault();
		}
		


		if(this.current_box_index && this.current_box_index === (index+1)  ) {
			return;
		}

		// If we're not scrolled down then do it for us
		if (!this.is_scrolled) {
			$('html, body').animate({scrollTop: _this.product_list.offset().top-120}, 1000);

			this.is_scrolled = true;
		}




		// Reset the current box
		this.resetBoxes();
		

		// How far should we move the <ul> left?
		// The index of the current box multiplied
		// by the width of one box
		var move_left = index*box_width;


		// Hide the icon box
		target.find('.product-icon-box').hide();

		// Animate the <ul class="product-list">
		// so that it's left position is flush with the left hand edge
		// then set it's width to be 960 and add an "expanded" class
		this.product_list.animate({
			'margin-left': _this.product_list_offset - (move_left)
		},
		{
			complete: function() {
				target.animate({
					width: 960
				}).addClass('product-box-expanded');

				_this.current_box 		= target;
				_this.current_box_index = target.index() + 1;



				_this.showBoxContent(target);

			}
		});
	},



	showBoxContent: function(box) {

		if (this.bAnimating) return;
		
		this.bAnimating 		= true;
		

		var _this				= this,
			box 				= box;
			content 			= box.find('.product-box-content'),
			icon_box 			= box.find('.product-icon-box'),
			loader 				= box.find('.product-box-loader');

		if (!content.length) return;

		// Insert the Loading GIF if not present		
		if (!loader.length) {
			loader =  this.loader_html.insertAfter(icon_box);
		}



		// Either way show the loader...
		loader.show();
		
		// Animate the height of the box to show the content
		// Todo: calculate the height() of content via JS
		box.animate({
			height: content.outerHeight() 
		},
		{	
			duration: 500,
			complete: function() {
				
				_this.bAnimating = false;
				// Show the content
				content.removeClass('vh').show();

				// Hide the loader
				loader.fadeOut(300);
				_this.bBoxOpen			= true;

				_this.setTabIndex(content,true);
				content.focus();

				
			}
		});

		

	},

	hideBoxContent: function() {


		var _this 		= this;

		var box 		= this.current_box,
			content 	= box.find('.product-box-content'),
			icon_box 	= box.find('.product-icon-box'),
			loader 		= box.find('.product-box-loader');


		loader.show();

		box.css({
			height: _this.product_box_height
		});

		content.hide().addClass('vh');
		icon_box.show();

		setTimeout(function() {
			loader.fadeOut(1000);
		}, 500);

		this.bBoxOpen = false;
		_this.setTabIndex(content,false);
		
	},

	resetBoxes: function(bReset) {
		/**
		 * Returns current box to it's original dimesnsions
		 */
		var _this = this,
			original_width = this.product_box_width_pure;

		
		if (!this.current_box || !this.bBoxOpen) return;
		
		this.hideBoxContent(this.current_box);

		this.current_box.removeClass('product-box-expanded').animate({
			width: original_width
		},
		{	
			duration: 300,
			complete: function() {
				if (bReset) {
					_this.product_list.animate({
						'margin-left': (_this.product_list_offset)
					});
					_this.current_box 		= 0;
					_this.current_box_index = 0;
				}

			}
		});
	}
};

Site.productList.init();










/* ROTATING GLOBE
   ========================================================================== */

Site.rotatingGlobe = {

	init: function() {


		// Set base vars
		this.globe_width  = 473; // width of single globe img in "px"
		this.globe_height = 371; // height of single globe img in "px"


		
		// Build required DOM
		this.enhanceGlobes();

		// Cache eles
		this.cached();

		// Set Event subscriptions
		this.subscriptions();

		this.spin_ele.css({
			'font-size' : 0
		}); 

		
		
	},

	enhanceGlobes: function() {
		/**
		 * Grab Globes from DOM and enhance in order to render
		 * rotating magic globes!
		 */

		var container 		= $('<div id="hero-globes" class="hero-globes">'),
			spin_ele 		= $('<div class="globes-spin"></div>').height(this.globe_height*2).width(this.globe_width);


		container.css({
			'height' : this.globe_height,
			'margin-left' : -(this.globe_width-50)
		});

		


		// Build initial DOM structure
		var globe_html 		= container.append(spin_ele);

		// Collect our globe images omitting clone generated by Flexslider
		var globes 			= $('#product-slides .slide').not('.clone').find('.hero-globe-hemisphere');

		

		

		// Append to the spin element
		globes.appendTo(spin_ele);


		// Collect pairs of elements and wrap them in a "hero-globe" <div />
		// We filter through globes for "even" elements
		// We then append that element and it's following sibling (ie: .next())
		// to the wrap and append to the parent
		globes.filter(':even').each(function(index,ele) {	
			var wrap 	= $('<div class="hero-globe hero-globe-' + (index+1) + '">'),	
			 	ele 	= $(ele),
			 	next	= ele.next();

			// Append current element and it's next sibling to the wrapper div 	
			wrap.append(ele).append(next);

			// Append that to the <div class="globes-spin">
			wrap.appendTo(spin_ele);
		});

		// Once done then add to the DOM tree in a single go
		globe_html.insertAfter($('#product-slides'));	



	},

	cached: function() {
		/**
		 * Cache a load of elements for reuse
		 */
		
		this.container		= $('#hero-globes');
		this.spin_ele		= this.container.find('.globes-spin');
		this.globes 		= this.spin_ele.find('.hero-globe-hemisphere');
		this.current_globe	= 1;
		this.globe_1		= this.globes.eq(0);
		this.globe_2		= this.globes.eq(1);
		this.globe_3		= this.globes.eq(2).addClass('hemisphere-hidden');
		this.globe_4		= this.globes.eq(3).addClass('hemisphere-hidden');

		this.productSlides 	= $("#product-slides .slide");

	},

	rotateTo: function(dest_globe) {
		
		
		/**
		 * Animate the Globe
		 *
		 * Use jQuery built in animate method 
		 * We animate an unused property (ie: font-size) to the required degree of rotation
		 * We use the "step" callback to alter the CSS3 rotation
		 * We publish that a rotation has occured every 20 steps and pass the current angle
		 * The current angle is used to show/hide globes as appropriate 
		 */
		
		// Cache
		var current = this.current_globe;


		
		// calculate degrees of rotation required
		var rotations 	= dest_globe-current,
			deg 		= rotations*180;
	

		// Animate to the required rotation and publish events
		this.spin_ele.animate ({"font-size": deg + "px"}, {
			step: function (angle, fx) {
				if(1 || (Math.floor(angle)%5) == 0) {
					$.publish( 'globe/rotation', angle );
				}
				
			    $(this).css ({"-moz-transform":"rotate("+angle+"deg)",
			                  "-webkit-transform":"rotate("+angle+"deg)",
			                  "-ms-transform":"rotate("+angle+"deg)",
			                  "-o-transform":"rotate("+angle+"deg)",
			                  "transform":"rotate("+angle+"deg)"
			              });
			}, 
			duration: 1500,
			queue: false // set the animation to begin immediately
		}, "linear");


	},




	subscriptions: function() {
		var _this = this;

		// Suscription: Rotation of Globe elements
		// Show/hide globes depending on position of animation
		$.subscribe( 'globe/rotation', function(e, rotation) {
			if (rotation >=360) {
				_this.globe_1.addClass('hemisphere-hidden');
				_this.globe_2.addClass('hemisphere-hidden');
				_this.globe_3.removeClass('hemisphere-hidden');
				_this.globe_4.removeClass('hemisphere-hidden');

			} else if ( (rotation >=180) && (rotation < 360) ) {
				_this.globe_1.addClass('hemisphere-hidden');
				_this.globe_2.removeClass('hemisphere-hidden');
				_this.globe_3.removeClass('hemisphere-hidden');
				_this.globe_4.addClass('hemisphere-hidden');
			} else {
				_this.globe_1.removeClass('hemisphere-hidden');
				_this.globe_2.removeClass('hemisphere-hidden');
				_this.globe_3.addClass('hemisphere-hidden');
				_this.globe_4.addClass('hemisphere-hidden');
			}
		});

		// Suscription: when slider advances
		$.subscribe( 'globe/slide-advance', function(e, dest) {
			_this.rotateTo(dest);
		});
			
	}
};

/**
 * Checks each homepage slide for the prescence of a CTA link 
 * if found then it hides the "View Products" down arrow
 */
Site.checkForLink = (function() {
	var product_slides 	= 	$("#product-slides .slide"),
		product_cta		=	$('#skip-to-products').hide();

	$.subscribe( 'globe/slide-advance', function(e, dest) {
		var slide = product_slides.eq(dest),
			bLink = slide.find('.slide-link').length;
		
		if(bLink) {
			product_cta.fadeOut({
				duration: (dest === 1) ? 0 : 1000
			});
		} else {
			product_cta.fadeIn({
				duration: 1500
			});
		}
	});
}());



	



// Init if the device can support these transitions
if (Site.supportedProps.transitions) {
	Site.rotatingGlobe.init();
}



/**
 * One time init's
 */

(function() {
	
	// Smoothscroll to products on homepage
	$('#skip-to-products').click(function(e) {

		e.preventDefault();
		var href 		= $(this).attr('href'),
			scroll_top	= $(href);
		// Scroll window to the solutions section			
		$('html, body').animate({scrollTop: scroll_top.offset().top}, 1300);
	});

	$('.ps-leader-item').click(function(e) {
		e.preventDefault();
		var href 		= $(this).attr('href'),
			scroll_top	= $(href);
		// Scroll window to the solutions section			
		$('html, body').animate({scrollTop: scroll_top.offset().top}, 500);
	});




	// Subnav modifications
	$('.nav-secondary li ul.children .current_page_item').parent('ul.children').show();
	
	// archives sidebar functions
	var current_page_yr = $("#current-year").val();
	var current_yr = $("#curr-year").val();
	
	$(".nav-secondary .archive li").each( function(){
		if($(this).children('a').attr('title') == current_page_yr){
			$(this).addClass('current_page_item');
		}
		if($(this).children('a').attr('title') == current_yr){
			$(this).remove();
		}
		
	} );
	
	// Cookie law GA compliance	
	$.ws.jpecrga({
		gaKey: 'UA-30550008-1',
		geolocate: true
		/*,debug: true*/
	});




	$(document).ready(function() {
		$('.nav-primary > ul').setup_navigation();
	}); 	
}());

/* Newsletter section on footer*/
$(window).load(function(){
	$('input[name="gsom_email_field"]').focus(function(){
	if($(this).val() == 'Enter email address here...'){$(this).val('');}
});
$('input[name="gsom_email_field"]').blur(function(){
	if($(this).val() == ''){$(this).val('Enter email address here...');}
});
$('input[name="gsom_email_field"]').attr('id','gsom_email_field');
$('label.gsom-optin-div-label').attr('for','gsom_email_field');
});

/* binding fancybox function on fancybox class */

$(document).ready(function() {
    $('.fancybox').fancybox({
        'autoScale' : false,
        'type' : 'iframe',
        helpers:  {
        title:  null
        }
    });
    $('.page-pdf-download .fancybox-style').css({'left':'0','width':'100%', 'height':'560px'});
    $('.page-pdf-download .hide-noscript').css('display','block');
    $('.page-pdf-download #wsjpecrga').css('display','none');
});

/* Adding collapsible archive for news section */

$(document).ready(function(){
	$('.cat-archive.nav-secondary > li span').click(function(){
		if($(this).hasClass('collapsable')){
			$(this).parent('li').children('.children').slideUp('slow');
			$(this).removeClass('collapsable').addClass('expandable');
		}else if($(this).hasClass('expandable')){
			$(this).parent('li').children('.children').slideDown('slow');
			$(this).removeClass('expandable').addClass('collapsable');
		}
	})
	$('.cat-archive.nav-secondary > li ul.archive').each(function(){
		//if($(this).html().trim().length == 0){
		//
		if ( $.trim($(this).html()).length === 0 ) {
			$(this).parent('li').children('span').removeClass('collapsable').removeClass('expandable').addClass('no-archive');
		}
	})
});
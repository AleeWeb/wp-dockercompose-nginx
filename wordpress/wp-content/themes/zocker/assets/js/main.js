(function($) {
	"use strict";

	/*===========================================
	      Table of contents
	  01. On Load Function
	  02. Preloader
	  03. Mobile Menu Active
	  04. Sticky fix
	  05. Scroll To Top
	  06. Set Background Image
	  07. Popup Sidemenu
	  08. Search Box Popup
	  09. Magnific Popup
	  10. Filter Menu
	  11. Indicator
	  12. Title Rotate
	  13. Slider Tab
	  14. Range Slider
	  15. Procut Zoom Img
	  16. Quantity Adder
	  17. Rating Input Class Added
	  18. Tab Animation
	  19. Button Hove Effect
	  00. Right Click Disable
	  00. Inspect Element Disable
	=============================================*/

	/*---------- 01. On Load Function ----------*/
	$(window).on('load', function() {
		$('.preloader').hide();
		setTimeout(function () {
            if ($('.slick-slider'.length)) {
                $('.slick-slider').each(function () {
                    $(this).slick('refresh');
                });
            }
        }, 100);
	});

	/*---------- 02. Preloader ----------*/
	if ($('.preloader').length > 0) {
		$('.preloaderCls').each(function() {
			$(this).on('click', function(e) {
				e.preventDefault();
				$('.preloader').css('display', 'none');
			})
		});
	};

	$(window).on('resize', function () {
        setTimeout(function () {
            if ($('.slick-slider'.length)) {
                $('.slick-slider').each(function () {
                    $(this).slick('refresh');
                });
            }
        }, 100)
    });

	/*---------- 03. Mobile Menu Active ----------*/
	$.fn.vsmobilemenu = function(options) {
		var opt = $.extend({
			menuToggleBtn: '.vs-menu-toggle',
			bodyToggleClass: 'vs-body-visible',
			subMenuClass: 'vs-submenu',
			subMenuParent: 'vs-item-has-children',
			subMenuParentToggle: 'vs-active',
			meanExpandClass: 'vs-mean-expand',
			subMenuToggleClass: 'vs-open',
			toggleSpeed: 400,
		}, options);

		return this.each(function() {
			var menu = $(this); // Select menu

			// Menu Show & Hide
			function menuToggle() {
				menu.toggleClass(opt.bodyToggleClass);

				// collapse submenu on menu hide or show
				var subMenu = '.' + opt.subMenuClass;
				$(subMenu).each(function() {
					if ($(this).hasClass(opt.subMenuToggleClass)) {
						$(this).removeClass(opt.subMenuToggleClass);
						$(this).css('display', 'none')
						$(this).parent().removeClass(opt.subMenuParentToggle);
					};
				});
			};

			// Class Set Up for every submenu
			menu.find('li').each(function() {
				var submenu = $(this).find('ul');
				submenu.addClass(opt.subMenuClass);
				submenu.css('display', 'none');
				submenu.parent().addClass(opt.subMenuParent);
				submenu.prev('a').addClass(opt.meanExpandClass);
				submenu.next('a').addClass(opt.meanExpandClass);
			});

			// Toggle Submenu
			function toggleDropDown($element) {
				if ($($element).next('ul').length > 0) {
					$($element).parent().toggleClass(opt.subMenuParentToggle);
					$($element).next('ul').slideToggle(opt.toggleSpeed);
					$($element).next('ul').toggleClass(opt.subMenuToggleClass);
				} else if ($($element).prev('ul').length > 0) {
					$($element).parent().toggleClass(opt.subMenuParentToggle);
					$($element).prev('ul').slideToggle(opt.toggleSpeed);
					$($element).prev('ul').toggleClass(opt.subMenuToggleClass);
				};
			};

			// Submenu toggle Button
			var expandToggler = '.' + opt.meanExpandClass;
			$(expandToggler).each(function() {
				$(this).on('click', function(e) {
					e.preventDefault();
					toggleDropDown(this);
				});
			});

			// Menu Show & Hide On Toggle Btn click
			$(opt.menuToggleBtn).each(function() {
				$(this).on('click', function() {
					menuToggle();
				})
			})

			// Hide Menu On out side click
			menu.on('click', function(e) {
				e.stopPropagation();
				menuToggle()
			})

			// Stop Hide full menu on menu click
			menu.find('div').on('click', function(e) {
				e.stopPropagation();
			});

		});
	};

	$('.vs-menu-wrapper').vsmobilemenu();

	/*---------- 04. Sticky fix ----------*/
	// var lastScrollTop = '';
	var scrollToTopBtn = '.scrollToTop';

	$(window).on("scroll", function() {
		if ($(this).scrollTop() > 400) {
			$(scrollToTopBtn).addClass('show');
		} else {
			$(scrollToTopBtn).removeClass('show');
		}
	});

	/*---------- 05. Scroll To Top ----------*/
	$(scrollToTopBtn).on('click', function(e) {
		e.preventDefault();
		$('html, body').animate({
			scrollTop: 0
		}, 3000);
		return false;
	});

	/*---------- 06.Set Background Image ----------*/
	if ($('[data-bg-src]').length > 0) {
		$('[data-bg-src]').each(function() {
			var src = $(this).attr('data-bg-src');
			$(this).css({
				'background-image': 'url(' + src + ')'
			});
		});
	};

	/*---------- 07. Popup Sidemenu ----------*/
	function popupSideMenu($sideMenu, $sideMunuOpen, $sideMenuCls, $toggleCls) {
		// Sidebar Popup
		$($sideMunuOpen).on('click', function(e) {
			e.preventDefault();
			$($sideMenu).addClass($toggleCls);
		});
		$($sideMenu).on('click', function(e) {
			e.stopPropagation();
			$($sideMenu).removeClass($toggleCls)
		});
		var sideMenuChild = $sideMenu + ' > div';
		$(sideMenuChild).on('click', function(e) {
			e.stopPropagation();
			$($sideMenu).addClass($toggleCls)
		});
		$($sideMenuCls).on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$($sideMenu).removeClass($toggleCls);
		});
	};
	popupSideMenu('.sidemenu-wrapper', '.sideMenuToggler', '.sideMenuCls', 'show');

	/*---------- 08. Search Box Popup ----------*/
	function popupSarchBox($searchBox, $searchOpen, $searchCls, $toggleCls) {
		$($searchOpen).on('click', function(e) {
			e.preventDefault();
			$($searchBox).addClass($toggleCls);
		});
		$($searchBox).on('click', function(e) {
			e.stopPropagation();
			$($searchBox).removeClass($toggleCls);
		});
		$($searchBox).find('form').on('click', function(e) {
			e.stopPropagation();
			$($searchBox).addClass($toggleCls);
		});
		$($searchCls).on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			$($searchBox).removeClass($toggleCls);
		});
	};
	popupSarchBox('.popup-search-box', '.searchBoxTggler', '.searchClose', 'show');

	/*----------- 09. Magnific Popup ----------*/
	/* magnificPopup img view */
	$('.popup-image').magnificPopup({
		type: 'image',
		gallery: {
			enabled: true
		}
	});

	/* magnificPopup video view */
	$('.popup-video').magnificPopup({
		type: 'iframe'
	});

	/*----------- 10. Filter Menu ----------*/
	$('.filter-active').imagesLoaded(function() {
		var $filter = '.filter-active',
			$filterItem = '.grid-item',
			$filterMenu = '.filter-menu-active';

		if ($($filterMenu).length > 0) {
			var $grid = $($filter).isotope({
				itemSelector: $filterItem,
				filter: '*',
				masonry: {
					// use outer width of grid-sizer for columnWidth
					columnWidth: $filterItem
				}
			});

			// filter items on button click
			$($filterMenu).on('click', 'button', function() {
				var filterValue = $(this).attr('data-filter');
				$grid.isotope({
					filter: filterValue
				});
			});

			// Menu Active Class 
			$($filterMenu).on('click', 'button', function(event) {
				event.preventDefault();
				$(this).addClass('active');
				$(this).siblings('.active').removeClass('active');
			});
		};
	});


	/*----------- 11. Indicator ----------*/
	$.fn.indicator = function() {
		var $menu = $(this),
			$linkBtn = $menu.find('a'),
			$btn = $menu.find('button');
		// Append indicator
		$menu.append('<span class="indicator"></span>');
		var $line = $menu.find('.indicator');
		// Check which type button is Available
		if ($linkBtn.length) {
			var $currentBtn = $linkBtn;
		} else if ($btn.length) {
			var $currentBtn = $btn
		}
		// On Click Button Class Remove
		$currentBtn.on('click', function(e) {
			e.preventDefault();
			$(this).addClass('active');
			$(this).siblings('.active').removeClass('active');
			linePos()
		})
		// Indicator Position
		function linePos() {
			var $btnActive = $menu.find('.active'),
				$height = $btnActive.css('height'),
				$width = $btnActive.css('width'),
				$top = $btnActive.position().top + 'px',
				$left = $btnActive.position().left + 'px';
			$line.css({
				top: $top,
				left: $left,
				width: $width,
				height: $height,
			})
		}

		if ($menu.hasClass('vs-slider-tab')) {
			var linkslide = $menu.data('asnavfor');
			$(linkslide).on('afterChange', function(event, slick, currentSlide, nextSlide) {
				setTimeout(linePos, 10)
			});
		}
		linePos()
	}

	// Call On Load
	if ($('.filter-menu-style1').length) {
		$('.filter-menu-style1').indicator();
	}
	if ($('.tab-indicator').length) {
		$('.tab-indicator').indicator();
	}


	/*----------- 12. Title Rotate ----------*/
	if ($('.title-rotate').length) {
		$('.title-rotate').each(function() {
			var $title = $(this);
			var $letter = $title.text().split('');
			$title.html('')
			for (var i = 0; i < $letter.length; i++) {
				$title.prepend('<span class="letter">' + $letter[i] + '</span>')
			}
		})
	}

	/*----------- 13. Slider Tab   ----------*/
	$.fn.vsslideTab = function(btn) {
		$(this).each(function() {
			var $menu = $(this),
				slider = $menu.data('asnavfor'),
				$btn = $menu.find(btn);
			var i = 0;
			// Change Slide on Btn Click
			$btn.each(function() {
				$(this).attr('data-slide-go-to', i)
				i++
				$(this).on('click', function(e) {
					e.preventDefault();
					var slideno = $(this).data('slide-go-to');
					$(slider).slick('slickGoTo', slideno);
				})
			})
			// Change Btn Active Class On Slide Change
			$(slider).on('afterChange', function(event, slick, currentSlide, nextSlide) {
				$btn.removeClass('active');
				$('[data-slide-go-to=' + currentSlide + ']').addClass('active');
			});
		});
	};

	if ($('.vs-slider-tab').length) {
		$('.vs-slider-tab').vsslideTab('.tab-btn');
	}
    
    $(document).on( 'click', '.quantity-plus,.quantity-minus', function(e) {
        e.preventDefault();
       // Get current quantity values
       var qty = $( this ).closest( '.product-quantity' ).find( '.qty' );
       var val = parseFloat(qty.val());
       var max = parseFloat(qty.attr( 'max' ));
       var min = parseFloat(qty.attr( 'min' ));
       var step = parseFloat(qty.attr( 'step' ));
   
       // Change the value if plus or minus
       if ( $( this ).is( '.quantity-plus' ) ) {
           if ( max && ( max <= val ) ) {
               qty.val( max );
           } else {
               qty.val( val + step );
           }
       } else {
           if ( min && ( min >= val ) ) {
               qty.val( min );
           } else if ( val > 0 ) {
               qty.val( val - step );
           }
       }
       $('.cart_table button[name="update_cart"]').prop('disabled',false);
   });
   
	/*----------- 17. Rating Input Class Added ----------*/
	if ($('.vs-rating-input').length > 0) {
		$('.vs-rating-input').each(function() {
			$(this).find('span').on('click', function() {
				$('.vs-rating-input span').addClass('active');
				$(this).nextAll('span').removeClass('active');
			});
		});
	};

	/*----------- 18. Tab Animation ----------*/
	$.fn.tabAnimation = function() {
		$(this).on('hide.bs.tab', function(e) {
			var $old_tab = $($(e.target).attr("href"));
			var $new_tab = $($(e.relatedTarget).attr("href"));

			if ($new_tab.index() < $old_tab.index()) {
				$old_tab.css('position', 'relative').css("bottom", "0").show();
				$old_tab.animate({
					"bottom": "-200px"
				}, 300, function() {
					$old_tab.css("bottom", 0).removeAttr("style");
				});
			} else {
				$old_tab.css('position', 'relative').css("top", "0").show();
				$old_tab.animate({
					"top": "-200px"
				}, 300, function() {
					$old_tab.css("top", 0).removeAttr("style");
				});
			}
		});
		$(this).on('show.bs.tab', function(e) {
			var $new_tab = $($(e.target).attr("href"));
			var $old_tab = $($(e.relatedTarget).attr("href"));

			if ($new_tab.index() > $old_tab.index()) {
				$new_tab.css('position', 'relative').css("bottom", "-200px");
				$new_tab.animate({
					"bottom": "0"
				}, 500);
			} else {
				$new_tab.css('position', 'relative').css("top", "-200px");
				$new_tab.animate({
					"top": "0"
				}, 500);
			}
		});
	}

	$('a[data-bs-toggle="tab"]').tabAnimation();

	/*----------- 19. Button Hove Effect ----------*/
	$.fn.hoverClass = function(element, eleClass) {
		$(this).each(function() {
			$(this).on('mouseenter', function() {
				$(element).addClass(eleClass);
			}).on('mouseleave', function() {
				$(element).removeClass(eleClass);
			})
		})
	};

	$('.vs-btn, .ls-arrow, .slick-arrow').hoverClass('.vs-cursor, .vs-cursor2', 'style2');

	$('.blog-img-slider').slick({
		dots: false,
		infinite: true,
		arrows: true,
		prevArrow: '<button type="button" class="slick-prev"><i class="far fa-chevron-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="far fa-chevron-right"></i></button>',
		autoplay: true,
		autoplaySpeed: 6000,
		fade: false,
		speed: 1000,
		slidesToShow: 1,
		slidesToScroll: 1,
	});

	$('.product-big-img.vs-carousel').slick({
		dots: true,
		infinite: true,
		arrows: false,
		prevArrow: '<button type="button" class="slick-prev"><i class="far fa-arrow-left"></i></button>',
		nextArrow: '<button type="button" class="slick-next"><i class="far fa-arrow-right"></i></button>',
		autoplay: true,
		autoplaySpeed: 6000,
		fade: false,
		speed: 1000,
		slidesToShow: 1,
		slidesToScroll: 1,
	});
	$('.logo-img').each(function () {
	   var $img = jQuery(this);
	   var imgID = $img.attr('id');
	   var imgClass = $img.attr('class');
	   var imgURL = $img.attr('src');
   
	   jQuery.get(imgURL, function (data) {
		   // Get the SVG tag, ignore the rest
		   var $svg = jQuery(data).find('svg');
   
		   // Add replaced image's ID to the new SVG
		   if (typeof imgID !== 'undefined') {
			   $svg = $svg.attr('id', imgID);
		   }
		   // Add replaced image's classes to the new SVG
		   if (typeof imgClass !== 'undefined') {
			   $svg = $svg.attr('class', imgClass + ' replaced-svg');
		   }
   
		   // Remove any invalid XML tags as per http://validator.w3.org
		   $svg = $svg.removeAttr('xmlns:a');
   
		   // Check if the viewport is set, else we gonna set it if we can.
		   if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
			   $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'));
		   }
   
		   // Replace image with new SVG
		   $img.replaceWith($svg);
   
	   }, 'xml');
   });

})(jQuery);
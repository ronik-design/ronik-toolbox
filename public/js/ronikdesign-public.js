// Global Function.

function checkPasswordStrength($) {
	// Loop through all the passwords inputs
	$( ".adv-passwordchecker" ).each(function( index ) {
		// Lets Create a wrapper container
		$(this).wrap( "<span class='ronik-password__container'> </span>" );
		// Lets Create a message container.
		var $div = $("<span>", {"class": "ronik-password__message"});
		$(this).parent().append($div);
		// Trigger our function every keyup event.
		$( this ).keyup(function() {
			passwordChecker( $(this).val(), $(this));
		});
	});
	// Simple password checker that checks for the password strength.
	function passwordChecker($password, THIS){
		var number = /([0-9])/;
		var alphabets = /([a-zA-Z])/;
		var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
		var hasCaps = /[A-Z]/;
		var hasLower = /[a-z]/;
		var password_message = $(THIS).parent().find('.ronik-password__message');
		var submitButton;

		if ($password.length < 12) {
			password_message.attr('class', 'ronik-password__message');
			password_message.addClass('weak-password');
			password_message.text("Weak (should be atleast 12 characters.)");
			submitted = false;

			if($password.length == 0){
				password_message.removeClass('weak-password');
				password_message.text("");
				submitted = true;
			}
		} else {
			if ($password.match(number) && $password.match(alphabets) && $password.match(special_characters) && $password.match(hasCaps) && $password.match(hasLower)) {
				password_message.attr('class', 'ronik-password__message');
				password_message.addClass('strong-password');
				password_message.text("Strong");
				submitted = true;
			}
			else {
				password_message.attr('class', 'ronik-password__message');
				password_message.addClass('medium-password');
				password_message.text("Medium (should include alphabets uppercase and lowercase numbers and special characters.)");
				submitted = false;
			}
		}


		// This is best guess effort
		// First attempt to check for the closest "form" tag.
		if(THIS.closest('form').length !== 0){
			findSubmitButton(THIS.closest('form'), submitted);
		// If not we look for the closest class called .form
		} else if(this.closest('.form').length !== 0) {
			findSubmitButton(THIS.closest('.form', submitted));
		// If not we return false to kill the process.
		} else {
			return false;
		}

		function findSubmitButton($target, $submitted){
			if ( $target.find('submit').length !== 0 ) {
				submitButton = $target.find('submit');
			} else if( $target.find('.submit').length !== 0 ) {
				submitButton = $target.find('.submit');
			} else if( $target.find('button[type="submit"]').length !== 0 ) {
				submitButton = $target.find('button[type="submit"]');
			} else if( $target.find('input[type="submit"]').length !== 0 ) {
				submitButton = $target.find('input[type="submit"]');
			} else {
				return false;
			}
			if(!$submitted){
				submitButton.addClass('ronik-password-disabled');
			} else {
				submitButton.removeClass('ronik-password-disabled');
			}
		}
	}
}


function verificationProcess($validationType, $validationValue, $strict=false){
	// Lets wrap the ajax call if either api are empty we just return empty function.
	jQuery.ajax({
		type: 'post',
		url: wpVars.ajaxURL,
		data: {
			action: 'do_verification',
			nonce: wpVars.nonce,
			validationType: $validationType,
			validationValue: $validationValue,
			validationStrict: $strict,
		},
		dataType: 'json',
		success: data => {
			if(data.success){
				var $get_csp = $("span").data( 'csp' )
				var object = {valType: $validationType, timestamp: new Date().getTime(), valNonce: $get_csp}
				localStorage.setItem("validator", JSON.stringify(object));
				return data;
			} else{
			  console.log('error');
			  console.log(data);
			  return false;
			}
		},
		error: err => {
		  console.log(err);
		  return false;
		}
	});
}

// This is critical if site has inline-js. We gather all script tags and add nonce from our span
function addNonce($){
	$get_csp = $("span").data( 'csp' )
	$("script").each(function(){
		$(this).attr('nonce', $get_csp);
	})
}



function log_tracker_action( ) {
	// Pretty much we track the landing on the page.
	if( origin.length > 0 && (origin.length > 0)){
		jQuery.ajax({
			type: 'POST',
			url: wpVars.ajaxURL,
			data: {
				nonce: wpVars.nonce,
				action: 'do_init_urltracking',
				point_origin: window.location.href,
				triggerType: 'load'
			}
		});
	}
	// Track click events..
	$( "a" ).each(function( index ) {	
		if( $( this ).attr('href') ){
			if( ($( this ).attr('href').indexOf('/') === 0) || ($( this ).attr('href').includes(window.location.hostname)) ) {
				$( this ).on('click', function(){
					var href = $( this ).attr('href');
					jQuery.ajax({
						type: 'POST',
						url: wpVars.ajaxURL,
						data: {
							nonce: wpVars.nonce,
							action: 'do_init_urltracking',
							point_origin: href,
							triggerType: 'click'
						}
					});
				});
			}
		}
	});
}


function spaceReducerMobile($){
	// Dynamically we load in data-flex-establishment.
	$('[data-flex-establishment="outter"]').each(function () {
		blockResize($(this), $(this).find('[data-flex-establishment="inner"]'));
	});

	function blockResize( $block, $blockInner ) {
		var width = $(window).width();

		$($block).each(function (i) {
			var increment = 1;				
			if( $block){
				if(width > 1050){
					$targetScreenSize = 'desktop';
				} else if(width < 1050 && width > 750){
					$targetScreenSize = 'tablet';
				} else {
					$targetScreenSize = 'mobile';
				}

				function BlockSpacingAssignment($target, $type, $targetScreenSize){		
					var $f_type;
					if(  $type == 'pt'){
						$f_type = 'padding-top';
					} else if( $type == 'pbtm'){
						$f_type = 'padding-bottom';
					} else if( $type == 'mt'){
						$f_type = 'margin-top';
					} else if( $type == 'mbtm'){
						$f_type = 'margin-bottom';
					} 
					if($target.attr('data-'+$type+'_'+$targetScreenSize)){
						$target.css($f_type, $target.attr('data-'+$type+'_'+$targetScreenSize));

						return $target.attr('data-'+$type+'_'+$targetScreenSize).replace('px', '')
					} else {
						if($target.attr('data-'+$type+'_desktop')){
							$target.css($f_type, $target.attr('data-'+$type+'_desktop'));

							return $target.attr('data-'+$type+'_desktop').replace('px', '')
						} else {
							// return false;
							if( $target.css($f_type).includes("px") ){
								return $target.css($f_type).replace('px', '');
							} else {
								return 'invalid';
							}
						}
					}
				}
				var BlockMarginTop = BlockSpacingAssignment($(this), 'mt' ,$targetScreenSize);
				var BlockMarginBtm = BlockSpacingAssignment($(this), 'mbtm' ,$targetScreenSize);
				var BlockPaddingTop = BlockSpacingAssignment($(this), 'pt' ,$targetScreenSize);
				var BlockPaddingBtm = BlockSpacingAssignment($(this), 'pbtm' ,$targetScreenSize);
				var BlockAdvancedSpaceResize = $(this).attr('data-adv-resize') ? $(this).attr('data-adv-resize') : 'invalid';
				var BlockMirrorTopSiblingMobileMarginTopTop , BlockMirrorTopSiblingMobileMarginTopBtm , BlockMirrorTopSiblingMobileMarginBtmTop , BlockMirrorTopSiblingMobileMarginBtmBtm , BlockMirrorTopSiblingMobilePaddingTopTop , BlockMirrorTopSiblingMobilePaddingTopBtm , BlockMirrorTopSiblingMobilePaddingBtmTop , BlockMirrorTopSiblingMobilePaddingBtmBtml , BlockMirrorBtmSiblingMobileMarginTopTop , BlockMirrorBtmSiblingMobileMarginTopBtm , BlockMirrorBtmSiblingMobileMarginBtmTop , BlockMirrorBtmSiblingMobileMarginBtmBtm , BlockMirrorBtmSiblingMobilePaddingTopTop , BlockMirrorBtmSiblingMobilePaddingTopBtm , BlockMirrorBtmSiblingMobilePaddingBtmTop , BlockMirrorBtmSiblingMobilePaddingBtmBtm;

				function BlockMirrorSibling($target, $siblingPos, $cssSiblingTarget, $cssTarget ) {
					if($siblingPos == 'top') {
						$siblingPos = $target.prev();
					} else {
						$siblingPos = $target.next();
					}
					var $cssTargetValue = 0;
					if( $($siblingPos[0]).css($cssSiblingTarget).length > 0 ) {
						// $cssTargetValue = parseInt( $($siblingPos[0]).css($cssSiblingTarget) );
						$cssTargetValue = parseInt( $($siblingPos[0]).css($cssSiblingTarget) );
					}
					setTimeout(() => {
						console.log("Delayed for 1 second.");
						$target.css($cssTarget, $cssTargetValue );
					  }, 50);
				}

				if (width < 750 ){
					BlockMirrorTopSiblingMobileMarginTopTop = $(this).hasClass('mirror-top-sibling-mobile-margin-top-to-top') ? BlockMirrorSibling($(this), 'top' , 'margin-top' , 'margin-top') : 'invalid';
					BlockMirrorTopSiblingMobileMarginTopBtm = $(this).hasClass('mirror-top-sibling-mobile-margin-top-to-btm') ? BlockMirrorSibling($(this), 'top' , 'margin-top' , 'margin-bottom') : 'invalid';
					BlockMirrorTopSiblingMobileMarginBtmTop = $(this).hasClass('mirror-top-sibling-mobile-margin-btm-to-top') ? BlockMirrorSibling($(this), 'top' , 'margin-bottom' , 'margin-top') : 'invalid';
					BlockMirrorTopSiblingMobileMarginBtmBtm = $(this).hasClass('mirror-top-sibling-mobile-margin-btm-to-btm') ? BlockMirrorSibling($(this), 'top' , 'margin-bottom' , 'margin-bottom') : 'invalid';
					
					BlockMirrorTopSiblingMobilePaddingTopTop = $(this).hasClass('mirror-top-sibling-mobile-padding-top-to-top') ? BlockMirrorSibling($(this), 'top' , 'padding-top' , 'padding-top') : 'invalid';
					BlockMirrorTopSiblingMobilePaddingTopBtm = $(this).hasClass('mirror-top-sibling-mobile-padding-top-to-btm') ? BlockMirrorSibling($(this), 'top' , 'padding-top' , 'padding-bottm') : 'invalid';
					BlockMirrorTopSiblingMobilePaddingBtmTop = $(this).hasClass('mirror-top-sibling-mobile-padding-btm-top') ? BlockMirrorSibling($(this), 'top' , 'padding-bottom' , 'padding-top') : 'invalid';
					BlockMirrorTopSiblingMobilePaddingBtmBtml = $(this).hasClass('mirror-top-sibling-mobile-padding-btm-btm') ? BlockMirrorSibling($(this), 'top' , 'padding-bottom' , 'padding-bottom') : 'invalid';
	
					BlockMirrorBtmSiblingMobileMarginTopTop = $(this).hasClass('mirror-btm-sibling-mobile-margin-top-to-top') ? BlockMirrorSibling($(this), 'top' , 'margin-top' , 'margin-top') : 'invalid';
					BlockMirrorBtmSiblingMobileMarginTopBtm = $(this).hasClass('mirror-btm-sibling-mobile-margin-top-to-btm') ? BlockMirrorSibling($(this), 'top' , 'margin-top' , 'margin-bottom') : 'invalid';
					BlockMirrorBtmSiblingMobileMarginBtmTop = $(this).hasClass('mirror-btm-sibling-mobile-margin-btm-to-top') ? BlockMirrorSibling($(this), 'top' , 'margin-bottom' , 'margin-top') : 'invalid';
					BlockMirrorBtmSiblingMobileMarginBtmBtm = $(this).hasClass('mirror-btm-sibling-mobile-margin-btm-to-btm') ? BlockMirrorSibling($(this), 'top' , 'margin-bottom' , 'margin-bottom') : 'invalid';
					
					BlockMirrorBtmSiblingMobilePaddingTopTop = $(this).hasClass('mirror-btm-sibling-mobile-padding-top-to-top') ? BlockMirrorSibling($(this), 'top' , 'padding-top' , 'padding-top') : 'invalid';
					BlockMirrorBtmSiblingMobilePaddingTopBtm = $(this).hasClass('mirror-btm-sibling-mobile-padding-top-to-btm') ? BlockMirrorSibling($(this), 'top' , 'padding-top' , 'padding-bottom') : 'invalid';
					BlockMirrorBtmSiblingMobilePaddingBtmTop = $(this).hasClass('mirror-btm-sibling-mobile-padding-btm-to-top') ? BlockMirrorSibling($(this), 'top' , 'padding-bottom' , 'padding-top') : 'invalid';
					BlockMirrorBtmSiblingMobilePaddingBtmBtm = $(this).hasClass('mirror-btm-sibling-mobile-padding-btm-to-btm') ? BlockMirrorSibling($(this), 'top' , 'padding-bottom' , 'padding-bottom') : 'invalid';

				}	



		

				var BlockMarginTopDisable = $(this).hasClass('disable-margin-top') ? 'valid' : 'invalid';
				var BlockMarginBtmDisable = $(this).hasClass('disable-margin-btm') ? 'valid' : 'invalid';
				var BlockPaddingTopDisable = $(this).hasClass('disable-padding-top') ? 'valid' : 'invalid';
				var BlockPaddingBtmDisable = $(this).hasClass('disable-padding-btm') ? 'valid' : 'invalid';
				

				// Lets store OG values this is critical for resting values and resizing browser
				if(!$(this).attr('data-margin-top')){
					$(this).attr('data-margin-top', BlockMarginTop) 
				}
				if(!$(this).attr('data-margin-btm')){
					$(this).attr('data-margin-btm', BlockMarginBtm)
				}
				if(!$(this).attr('data-padding-top')){
					$(this).attr('data-padding-top', BlockPaddingTop)
				}
				if(!$(this).attr('data-padding-btm')){
					$(this).attr('data-padding-btm', BlockPaddingBtm)
				}
			}
			if($blockInner){
				var BlockMinHeight = $(this).find($blockInner).css('min-height').includes("px") ? $(this).find($blockInner).css('min-height').replace('px', '') : 'invalid';
				var BlockMaxHeight = $(this).find($blockInner).css('max-height').includes("px") ? $(this).find($blockInner).css('max-height').replace('px', '') : 'invalid';
				var BlockMinWidth = $(this).find($blockInner).css('min-width').includes("px") ? $(this).find($blockInner).css('min-width').replace('px', '') : 'invalid';
				var BlockMaxWidth = $(this).find($blockInner).css('max-width').includes("px") ? $(this).find($blockInner).css('max-width').replace('px', '') : 'invalid';

				// Lets store OG values this is critical for resting values and resizing browser
				if(!$(this).find($blockInner).attr('data-min-h')){
					$(this).find($blockInner).attr('data-min-h', BlockMinHeight) 
				}
				if(!$(this).find($blockInner).attr('data-max-h')){
					$(this).find($blockInner).attr('data-max-h', BlockMaxHeight)
				}
				if(!$(this).find($blockInner).attr('data-min-w')){
					$(this).find($blockInner).attr('data-min-w', BlockMinWidth)
				}
				if(!$(this).find($blockInner).attr('data-max-w')){
					$(this).find($blockInner).attr('data-max-w', BlockMaxWidth)
				}
			}

			if (width < 250 ) {
				increment = .5;
			} 
			else if (width < 450 ) {
				increment = .65;
			} 
			else if (width < 750 ) {
				increment = .75;
			} 
			else if (width < 1000 ) {
				increment = .85;
			} 
			else if (width < 1250 ) {
				increment = .90;
			} 
			else if (width < 2000 ) {
				increment = 1
			}
			else if(width < 3000 ) {
				increment = 1.5;

			}
			else if(width < 4000 ) {
				increment = 2;
			}
			else if(width < 5000 ) {
				increment = 2.5;
			   }
			else if(width < 6000 ) {
				increment = 3;
			}
			else if(width < 7000 ) {
				increment = 3.5;
			} else {
				increment = 4;
			}
			// increment = 1;




			if (width < 250) {
				if( $block && BlockAdvancedSpaceResize == 'valid'){
					if(BlockMarginTop !== 'invalid' && BlockMarginTopDisable == 'invalid') {
						if($(this).attr('data-margin-top') >= 100){
							$(this).css('margin-top', $(this).attr('data-margin-top') * .5 + 'px');
						} else {
							$(this).css('margin-top', $(this).attr('data-margin-top') + 'px');
						}
					}
					if(BlockMarginBtm !== 'invalid' && BlockMarginBtmDisable == 'invalid') {
						if($(this).attr('data-margin-top') >= 100){
							$(this).css('margin-bottom', $(this).attr('data-margin-btm') * .5 + 'px');
						} else {
							$(this).css('margin-bottom', $(this).attr('data-margin-btm') + 'px');
						}
					}
					if(BlockPaddingTop !== 'invalid' && BlockPaddingTopDisable == 'invalid') {
						if($(this).attr('data-margin-top') >= 100){
							$(this).css('padding-top', $(this).attr('data-padding-top') * .5 + 'px');
						} else {
							$(this).css('padding-top', $(this).attr('data-padding-top') + 'px');
						}
					}
					if(BlockPaddingBtm !== 'invalid' && BlockPaddingBtmDisable == 'invalid') {
						if($(this).attr('data-margin-top') >= 100){
							$(this).css('padding-bottom', $(this).attr('data-padding-btm') * .5 + 'px');
						} else {
							$(this).css('padding-bottom', $(this).attr('data-padding-btm') + 'px');
						}
					}
				}

				if($blockInner){
					if(BlockMinHeight !== 'invalid') {
						$(this).find($blockInner).css('min-height', $(this).find($blockInner).attr('data-min-h') + 'px');
					}
					if(BlockMaxHeight !== 'invalid') {
						$(this).find($blockInner).css('max-height', $(this).find($blockInner).attr('data-max-h') + 'px');
					}
					if(width > 1050){
						if(BlockMinWidth !== 'invalid') {
							$(this).find($blockInner).css('min-width', $(this).find($blockInner).attr('data-min-w') + 'px');
						}
						if(BlockMaxWidth !== 'invalid') {
							$(this).find($blockInner).css('max-width', $(this).find($blockInner).attr('data-max-w') + 'px');
						}
					}
				}
			} else {
				if(  $block && BlockAdvancedSpaceResize == 'valid' ){
					if(BlockMarginTop !== 'invalid' && BlockMarginTopDisable == 'invalid') {
						$(this).css('margin-top', $(this).attr('data-margin-top') * increment + 'px');
					}
					if(BlockMarginBtm !== 'invalid' && BlockMarginBtmDisable == 'invalid') {
						$(this).css('margin-bottom', $(this).attr('data-margin-btm') * increment + 'px');
					}
					if(BlockPaddingTop !== 'invalid' && BlockPaddingTopDisable == 'invalid') {
						$(this).css('padding-top', $(this).attr('data-padding-top') * increment + 'px');
					}
					if(BlockPaddingBtm !== 'invalid' && BlockPaddingBtmDisable == 'invalid') {
						$(this).css('padding-bottom', $(this).attr('data-padding-btm') * increment + 'px');
					}
				}
				if($blockInner){
					if(BlockMinHeight !== 'invalid') {
						$(this).find($blockInner).css('min-height', $(this).find($blockInner).attr('data-min-h') * increment + 'px');
					}
					if(BlockMaxHeight !== 'invalid') {
						$(this).find($blockInner).css('max-height', $(this).find($blockInner).attr('data-max-h') * increment + 'px');
					}
					if(width > 1050){
						if(BlockMinWidth !== 'invalid') {
							$(this).find($blockInner).css('min-width', $(this).find($blockInner).attr('data-min-w') * increment + 'px');
						}
						if(BlockMaxWidth !== 'invalid') {
							$(this).find($blockInner).css('max-width', $(this).find($blockInner).attr('data-max-w') * increment + 'px');
						}
					}
				}
			}
	  	});
	}
}



function initFontSize($) {
    // Lets select all font elements. And then give the parents and unique identifier.
    $("p, a, h1, h2, h3, h4, h5, span, ol, ul, li, button, label , div , strong").contents().filter(function(){
        if( this.nodeType !== 1 ){
            // Double clean up...
            if(this.textContent.search( '\n' )){
                return this;
            }
        }
    }).parent().attr('originalFontSize','validated');
    
    // Store original font sizes before any modifications
    $('[originalFontSize]').each(function() {
        if (!$(this).attr('data-original-font-size')) {
            var currentFontSize = $(this).css('font-size');
            if (currentFontSize && currentFontSize !== 'inherit') {
                var pxValue = parseInt(currentFontSize.replace('px', ''));
                if (pxValue > 0) {
                    $(this).attr('data-original-font-size', pxValue);
                }
            }
        }
    });

    function tweakFontSize($, times) {
        // Loop through the unique identifier and swap the px to vw.
        $('[originalFontSize]').each(function() {
			// Use stored original font size instead of clearing and retrieving
			var originalFontSize = $(this).attr('data-original-font-size');
			
			if (originalFontSize && originalFontSize > 0) {
				// We must throttle the font size.
				setTimeout(() => {
					var vwValue = (pxTOvw(parseInt(originalFontSize)) * times);
					
					// Debug: Log the values
					console.log('Font Size Debug:', {
						originalPx: originalFontSize,
						vwValue: vwValue,
						times: times,
						element: $(this)[0]
					});
					
					// Apply font-size with !important using attr() method
					$(this).attr('style', function(i, style) {
						var newStyle = style || '';
						// Remove existing font-size from style attribute
						newStyle = newStyle.replace(/font-size\s*:\s*[^;!]+[^;]*;?/gi, '');
						// Ensure proper semicolon separation
						if (newStyle.trim() && !newStyle.trim().endsWith(';')) {
							newStyle += ';';
						}
						// Add new font-size with !important
						return newStyle + ' font-size: ' + vwValue + 'vw !important;';
					});
				}, 500);
			} else {
				console.log('No valid original font size found for element:', $(this)[0]);
			}
		});
    }

    var width = $(window).width();
    var height = $('.image-text-block__inner').css('min-height');

    if (width < 2000 ) {
        // Standard Size.
        tweakFontSize($, 1);
        $('.image-text-block__inner').css('min-height', height * 1+'px' );
    }
    else if(width < 5000 ) {
        tweakFontSize($, 1.8);
        $('.image-text-block__inner').css('min-height', height * 1.5+'px' );
    }
    else if(width < 6000 ) {
        tweakFontSize($, 2);
        $('.image-text-block__inner').css('min-height', height * 1.8+'px' );
    }
    else if(width < 7000 ) {
        tweakFontSize($, 1.8);
        $('.image-text-block__inner').css('min-height', height * 2+'px' );
    }
    else if(width < 8000 ) {
        tweakFontSize($, 2);
        $('.image-text-block__inner').css('min-height', height * 2.3+'px' );
    }
    else if(width < 9000 ) {
        tweakFontSize($, 3);
        $('.image-text-block__inner').css('min-height', height * 3+'px' );
    } else {
        tweakFontSize($, 4);
        $('.image-text-block__inner').css('min-height', height * 5+'px' );
    }

    function pxTOvw(value) {
        var w = window,
          d = document,
          e = d.documentElement,
          g = d.getElementsByTagName('body')[0],
          x = w.innerWidth || e.clientWidth || g.clientWidth;          
        return (100*value)/x;
        
    }
}

function initMaxWidth($) {
    // Select all elements with measure classes and give them a unique identifier
    $('[class*="is-style-measure-"], [class*="is-layout-constrained"]').each(function() {
        // Skip elements with alignfull class
        if ($(this).hasClass('alignfull')) {
            return true; // continue to next iteration
        }
        
        $(this).attr('originalMaxWidth', 'validated');
        
        // Store the original max-width value if not already stored
        if (!$(this).attr('data-original-max-width')) {
            // Get max-width from computed styles (includes CSS rules)
            var currentMaxWidth = $(this).css('max-width');
            
            // If this is a constrained layout but has no max-width, use width as baseline
            if ((!currentMaxWidth || currentMaxWidth === 'none') && 
                ($(this).hasClass('is-layout-constrained') || $(this).attr('class') && $(this).attr('class').indexOf('is-layout-constrained') !== -1)) {
                // For constrained layouts without explicit max-width, use current width
                var computedWidth = $(this).width();
                if (computedWidth && computedWidth > 0) {
                    currentMaxWidth = computedWidth + 'px';
                }
            }
            
            // Skip if still no valid max-width
            if (!currentMaxWidth || currentMaxWidth === 'none') {
                return true;
            }
            
            var pxValue = parseInt(currentMaxWidth.replace('px', ''));
            if (pxValue > 0) {
                $(this).attr('data-original-max-width', pxValue);
            }
        }
    });

    function tweakMaxWidth($, times) {
        // Loop through elements with measure classes and convert px to vw
        $('[originalMaxWidth]').each(function() {
            var originalMaxWidth = $(this).attr('data-original-max-width');
            if (originalMaxWidth) {
                $(this).css('max-width', '');
                // We must throttle the max-width conversion
                setTimeout(() => {
                    var vwValue = (pxTOvw(parseInt(originalMaxWidth)) * times);
                    // Apply max-width with !important using attr() method
                    $(this).attr('style', function(i, style) {
                        var newStyle = style || '';
                        // Remove existing max-width from style attribute
                        newStyle = newStyle.replace(/max-width\s*:\s*[^;!]+[^;]*;?/gi, '');
                        // Ensure proper semicolon separation
                        if (newStyle.trim() && !newStyle.trim().endsWith(';')) {
                            newStyle += ';';
                        }
                        // Add new max-width with !important
                        return newStyle + ' max-width: ' + vwValue + 'vw !important;';
                    });
                }, 500);
            }
        });
    }

    var width = $(window).width();

    if (width < 2000) {
        // Standard Size
        tweakMaxWidth($, 1);
    }
    else if (width < 3000) {
        tweakMaxWidth($, 1.2);
    }
    else if (width < 4000) {
        tweakMaxWidth($, 1.4);
    }
    else if (width < 5000) {
        tweakMaxWidth($, 1.6);
    }
    else if (width < 6000) {
        tweakMaxWidth($, 1.8);
    }
    else if (width < 7000) {
        tweakMaxWidth($, 2.0);
    }
    else if (width < 8000) {
        tweakMaxWidth($, 2.2);
    }
    else if (width < 9000) {
        tweakMaxWidth($, 2.5);
    } else {
        tweakMaxWidth($, 3.0);
    }

    function pxTOvw(value) {
        var w = window,
          d = document,
          e = d.documentElement,
          g = d.getElementsByTagName('body')[0],
          x = w.innerWidth || e.clientWidth || g.clientWidth;          
        return (100*value)/x;
    }
}


(function( $ ) {
	'use strict';
	// Load JS once windows is loaded.
	$(window).on('load', function(){
		spaceReducerMobile($);
		initFontSize($);
		initMaxWidth($);
		// SetTimeOut just incase things havent initialized just yet.
		setTimeout(() => {
			addNonce($);
			log_tracker_action($);
		}, 50);



		var throttleResponse;
		window.addEventListener('resize', function() {
			clearTimeout(throttleResponse);
			throttleResponse = setTimeout(() => {
				initFontSize($);
				initMaxWidth($);
				spaceReducerMobile($);
				console.log('addEventListener - resize');
			}, 100);
		}, true);

	});
	
})( jQuery );

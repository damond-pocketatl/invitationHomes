// remap jQuery to $
;(function($){

	$.fn.attentionGrabber = function(options) {

		var	defaults = {
			duration	: 500,
			position	: 'top',
			closeable	: true,
			showAfter	: 0,

			keepHidden	: false,

			borderSize	: 3,
			height		: 40,
			easing		: "linear"
		},
		settings = $.extend({}, defaults, options);

		if( settings.easing == "swing" ){ settings.easing = ''; }

		settings.totalHeight	= parseInt( settings.height, 10 ) + parseInt( settings.borderSize, 10 );
		settings.duration		= parseInt( settings.duration, 10 );
		settings.showAfter		= parseInt( settings.showAfter, 10 )*1000;

		settings.foundCookie	= (settings.foundCookie === 'true') ? true : false;

		// Disable attentionGrabber on Safari Mobile when placed at the bottom
		var deviceAgent = navigator.userAgent.toLowerCase();
		var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
		if ( agentID && settings.position == 'bottom_fixed' ) { return false; }

		// Main elements
		var	wrap					= $(this),
			grabber					= wrap.find("#attentionGrabber"),
			link					= grabber.find(".link"),

			closeBtn				= grabber.find("#closeAttentionGrabber"),
			openBtn					= wrap.find("#openAttentionGrabber"),

			animationParam			= {},
			animationProperty		= "",

			buttonAnimationParam	= {},
			buttonAnimationProperty = "",

			showOpenButton		= function(){
				buttonAnimationParam[buttonAnimationProperty] = settings.totalHeight;
				openBtn.animate( buttonAnimationParam, (settings.duration/2), settings.easing );
				wrap.removeClass("openGrabber");
			},
			hideOpenButton		= function(){
				buttonAnimationParam[buttonAnimationProperty] = - Math.abs(34 - settings.height);
				openBtn.animate( buttonAnimationParam, (settings.duration/2), function(){ showGrabber(); } );
			},

			// The show/hide animations
			showGrabber			= function(){
				animationParam[animationProperty] = 0;
				wrap.animate( animationParam , settings.duration, settings.easing ,function(){
					wrap.addClass("openGrabber");
					// If buddypress bar is enabled
					if( $("#wp-admin-bar").length ) {
						$("#wp-admin-bar").css({ top : settings.totalHeight });
					}
				} );
			},
			hideGrabber			= function(){
				animationParam[animationProperty] = -settings.totalHeight;
				wrap.animate( animationParam , settings.duration, function(){
					showOpenButton();
					// If buddypress bar is enabled
					if( $("#wp-admin-bar").length ) {
						$("#wp-admin-bar").css({ top : 0 });
					}
				} );
			};

		// If the admin bar is enabled
		if( $("#wpadminbar").length && settings.position == 'top_fixed' ){
			wrap.addClass("admBar");
		}

		// Initialize the property for the slide animation
		switch( settings.position ){
			case "top" :
				animationProperty = "marginTop";
				buttonAnimationProperty = "top";
			break;

			case "top_fixed" :
				animationProperty = "top";
				buttonAnimationProperty = "top";
			break;

			case "bottom_fixed" :
				animationProperty = "bottom";
				buttonAnimationProperty = "bottom";
			break;
		}

		// Extra functionalities
		attentionGrabberExtras = {
			multipleMessages : {

				clearTimer	: function(){
					clearTimeout( this.timer );
				},

				setTimer	: function(){
					var obj = this;
					this.clearTimer();
					this.timer = setTimeout( function(){ obj.showNext(); }, this.pause );
				},

				showNext	: function(){
					var obj	= this;
					this.currentMsg.fadeOut( this.speed, function(){
						obj.nextMsg.fadeIn(obj.speed, function(){
							obj.prepareNext();
						});
					});
				},

				prepareNext	: function(){
					var nextIndex = this.nextMsg.index();
					this.currentMsg	= this.nextMsg;

					if( nextIndex == this.msgLength ){
						// If this is the last one
						if( this.loop ){
							// If the loop is enabled
							nextIndex = 0;
						}else{
							return false;
						}
					}else{
						nextIndex++;
					}

					this.nextMsg = this.messages.eq( nextIndex );
					this.setTimer();
				},

				init		: function(){
					this.main		= grabber.find(".multiMessages");
					this.messages	= this.main.find(".singleMessage");
					this.currentMsg	= this.messages.eq(0);
					this.nextMsg	= this.messages.eq(1);
					this.msgLength	= this.messages.length -1;

					this.pause		= this.main.data("pause");
					this.speed		= Math.round( this.main.data("speed")/2 );
					this.hoverPause	= this.main.data("pauseonhover");
					this.loop		= this.main.data("loop");

					// Show the first message
					this.currentMsg.addClass("current");

					if( this.msgLength > 0 ){
						this.setTimer();
					}else{
						// If there are no other messages
						// Do nothing
					}

					if( this.hoverPause ){

						var obj = this;

						grabber.hover(function(){
							obj.clearTimer();
						}, function(){
							obj.setTimer();
						});

					}
				}

			}
		};

		// Remove it from the DOM
		wrap.detach();

		// Move it to the right position
		wrap.prependTo("body").css({ display : "block" });

		// Execute any extra functionalities	
		if( grabber.find(".multiMessages").length ){
			attentionGrabberExtras.multipleMessages.init();
		}

		if( settings.foundCookie && settings.keepHidden && settings.closeable ){
			// Show only the open button
			setTimeout( function(){ showOpenButton(); }, settings.showAfter );
		}else {
			// Show the grabber for the first time
			setTimeout( function(){ showGrabber(); }, settings.showAfter );
		}

		// Close grabber
		closeBtn.click(function(){
			hideGrabber();
			setCookie();
		});

		// Open grabber
		openBtn.click(function(){
			hideOpenButton();
			setCookie();
		});

		// Set the cookie
		function setCookie(){
			if( settings.keepHidden ){

				var dataObj = {
						'action'    : 'ag_do_ajax',
						'function'  : 'unset_cookie'
					},
					cb	= function(){
						settings.foundCookie = false;
					};

				if( !settings.foundCookie ){
					dataObj['function'] = 'set_cookie';
					cb = function(){
						settings.foundCookie = true;
					};
				}

				$.ajax({
					type		: 'post',
					dataType    : 'JSON',
					url			: settings.ajaxUrl,
					data		: dataObj,
					success		: function(results){
						console.log(results);
						cb();
					}
				});
			}
		}

		// Add a click counter
		link.click(function(){
			var dataObj = {
					'action'    : 'ag_do_ajax',
					'function'  : 'register_click'
				};
			$.ajax({
				type		: 'post',
				dataType    : 'JSON',
				url			: settings.ajaxUrl,
				data		: dataObj,
				success		: function(results){
					console.log(results);
					cb();
				}
			});
			return true;
		});

	};

})(window.jQuery);

jQuery(document).ready(function($){
	// Custom jQuery Easing
	if( !$.easing.hasOwnProperty( 'easeOutBounce' ) ){

		$.extend( $.easing,{
			easeOutBounce: function (x, t, b, c, d) {
				if ((t/=d) < (1/2.75)) {
					return c*(7.5625*t*t) + b;
				} else if (t < (2/2.75)) {
					return c*(7.5625*(t-=(1.5/2.75))*t + 0.75) + b;
				} else if (t < (2.5/2.75)) {
					return c*(7.5625*(t-=(2.25/2.75))*t + 0.9375) + b;
				} else {
					return c*(7.5625*(t-=(2.625/2.75))*t + 0.984375) + b;
				}
			}
		});

	}

	if( window.attentionGrabber_params && typeof window.attentionGrabber_params === 'object' ) {

        $("#attentionGrabberWrap").attentionGrabber( window.attentionGrabber_params );

    }
});
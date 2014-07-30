/*
 *
 * Plugin: attentionGrabber
 *
 * ADMIN INTERFACE SCRIPTS
 *
 * Author: MTD - http://themeforest.net/user/MTD
 *
 */

(function(){

    jQuery.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        jQuery.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

}());

jQuery(document).ready(function($) {

	var attentionGrabber = {

		// Main elements
		loader					: $("#attentionGrabberAdmin_Loader"),
		slider					: $("#attentionGrabberAdmin_Slider"),
		listTitle				: $("#attentionGrabberAdmin_Title").find(".listTitle"),
		settingsTitle			: $("#attentionGrabberAdmin_Title").find(".settingsTitle"),
		grabberList				: $("#attentionGrabberAdmin_List"),
		message					: $("#attentionGrabberAdmin_Message"),
		messageTimeout			: "",
		editorForm				: $("#grabberForm"),
		settingsForm			: $("#grabberSettingsForm"),

		ajaxUrl					: window.ajaxurl,

		// Hidden values
		grabberName				: $("#grabber_name"),
		grabberNamePlaceholder	: $("#grabber_name_placeholder"),
		grabberNew				: $("#grabber_new"),
		nextID					: $("#grabber_nextID"),
		grabberID				: $("#grabber_id"),

		coreSettings			: $("#grabber_core_settings"),
		contentType				: $("#grabber_type"),

		siteURL					: $("#attentionGrabberAdmin_SiteURL"),

		// Buttons
		createNew				: $("#attentionGrabberAdmin_CreateNew"),
		save					: $("#attentionGrabberAdmin_Save"),
		back					: $("#attentionGrabberAdmin_Back"),
		viewSettings			: $("#attentionGrabberAdmin_ViewSettings"),
		launchPreview			: $("#attentionGrabber_LaunchPreview"),

		tabs					: $(".tabList").find("li"),

		themeField				: $("#grabber_theme"),
		themeList				: $("#grabber_themesList"),

		// Fields
		colorFields				: $("#attentionGrabberAdmin").find(".colorInput"),
		selectFields			: $("#attentionGrabberAdmin").find("select"),
		sizeFields				: $("#attentionGrabberAdmin").find(".sizeField"),
		previewCss				: $("#attentionGrabber_PreviewCss"),
		additionalCss			: $("#grabber_additionalCss"),

		// Useful Objects
		settings				: {},

		previewObj				: { },

		defaultGrabber			: {

			name					: "",
			type					: "custom", // twitter, feedRss

			custom_messageText		: "",
			custom_linkText			: "",
			custom_linkUrl			: "",

			twitter_username		: "",
			twitter_linkText		: "",

			feed_feedURL			: "",
			feed_linkText			: "",

			advanced_content		: "",

			clickCount				: 0,

			theme					: "grabberTheme_1" // custom

		},
		themes					: {

			grabberTheme_1	: {

				style_bgColor			: "f38713",
				style_textColor			: "ffffff",
				style_linkColor			: "2B1B0A",
				style_linkHoverColor	: "d10000",
				style_borderColor		: "7A4A17",

				style_fontFamily		: "Lucida Grande, Lucida Sans Unicode, sans-serif",
				style_fontSize			: 15,
				style_fontStyle			: "normal",
				style_borderSize		: 2,

				style_textShadowSize	: 1,
				style_textShadowColor	: "986c00",

				style_linkShadowSize	: 1,
				style_linkShadowColor	: "f1ba4d",

				style_height			: 40

			},
			grabberTheme_2	: {

				style_bgColor			: "f1f1ea",
				style_textColor			: "3B3B36",
				style_linkColor			: "FF9900",
				style_linkHoverColor	: "ff8400",
				style_borderColor		: "7d7d7a",

				style_fontFamily		: "Futura, sans-serif", // georgia, helvetica, times new roman
				style_fontSize			: 15,
				style_fontStyle			: "normal",
				style_borderSize		: 3,

				style_textShadowSize	: 0,
				style_textShadowColor	: "ffffff",

				style_linkShadowSize	: 0,
				style_linkShadowColor	: "ffffff",

				style_height			: 40

			},
			grabberTheme_3	: {

				style_bgColor			: "485469",
				style_textColor			: "ffffff",
				style_linkColor			: "99CC33",
				style_linkHoverColor	: "ade63a",
				style_borderColor		: "343F51",

				style_fontFamily		: "georgia, serif",
				style_fontSize			: 16,
				style_fontStyle			: "italic",
				style_borderSize		: 3,

				style_textShadowSize	: 1,
				style_textShadowColor	: "343F51",

				style_linkShadowSize	: 1,
				style_linkShadowColor	: "343F51",

				style_height			: 40

			},
			grabberTheme_4	: {

				style_bgColor			: "d1e4e4",
				style_textColor			: "444444",
				style_linkColor			: "4584A5",
				style_linkHoverColor	: "276da3",
				style_borderColor		: "444444",

				style_fontFamily		: "Trebuchet MS, sans-serif",
				style_fontSize			: 15,
				style_fontStyle			: "normal",
				style_borderSize		: 3,

				style_textShadowSize	: 1,
				style_textShadowColor	: "e4f7f7",

				style_linkShadowSize	: 1,
				style_linkShadowColor	: "e4f7f7",

				style_height			: 40

			},
			grabberTheme_5	: {

				style_bgColor			: "dc383b",
				style_textColor			: "ffffff",
				style_linkColor			: "5A1214",
				style_linkHoverColor	: "911a1e",
				style_borderColor		: "5e4b79",

				style_fontFamily		: "arial, sans-serif",
				style_fontSize			: 15,
				style_fontStyle			: "normal",
				style_borderSize		: 3,

				style_textShadowSize	: 1,
				style_textShadowColor	: "ae001a",

				style_linkShadowSize	: 1,
				style_linkShadowColor	: "ff4a65",

				style_height			: 40

			},
			grabberTheme_6	: {

				style_bgColor			: "e2e2da",
				style_textColor			: "38B438",
				style_linkColor			: "373D36",
				style_linkHoverColor	: "636e61",
				style_borderColor		: "2E992E",

				style_fontFamily		: "Lucida Console, Monaco, monospace",
				style_fontSize			: 12,
				style_fontStyle			: "normal",
				style_borderSize		: 3,

				style_textShadowSize	: 1,
				style_textShadowColor	: "f5f5f5",

				style_linkShadowSize	: 1,
				style_linkShadowColor	: "f5f5f5",

				style_height			: 40

			}
		}

	};


		
	
	// ------ ----- ---- --- -- - ---------- - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - UI RELATED - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - ---------- - -- --- ---- ----- ------
	
	
	function showLoader(){
		attentionGrabber.loader.fadeIn(200).addClass("loading");
	}
	
	function hideLoader(){
		attentionGrabber.loader.fadeOut(200).removeClass("loading");
	}
	
	// When the page is ready, the buttons are clickable
	function is_ready(){
		if( attentionGrabber.loader.hasClass("loading") ){
			return false;
		}
	}
	
	// Placeholder for the grabberName field
	function placeholderForGrabberName(){
	
		var placeholder	= attentionGrabber.grabberNamePlaceholder.attr("placeholder");
		
		attentionGrabber.grabberNamePlaceholder.val( placeholder ).focus(function(){
		
			if( $(this).val() == placeholder ) { $(this).val(""); }
			
		}).blur(function(){
		
			if( $(this).val() == "" ) { $(this).val( placeholder ); }
			
		});
	
	}
	
	
	// ------ ----- ---- --- -- - COLOR PICKER - -- --- ---- ----- ------ //
	
	// Initialize the color picker
	function colorInit(){
		attentionGrabber.colorFields.each(function(){
			var el			= $(this),
				detail		= el.parents(".colorField").find(".colorInputDetail"),
				defautltVal	= el.val(),
				property	= el.attr("id").replace(/grabber_/, ""),
				callBack = function( hex ){
					// Set the theme to custom
					setCustomTheme();
				};
			
			// If this field belongs to the settings page
			if( el.hasClass("settingsColor") ){
				callBack	= function( hex ){ attentionGrabber.settings.previewBg = hex; };
				property	= false;
			}
				
			el.css({
				backgroundColor	: '#' + defautltVal,
				color			: '#' + defautltVal
			});
			detail.text("#" + defautltVal);
			el.ColorPicker({			
				color			: el.val(),
				onShow			: function(colpkr){
					$(colpkr).fadeIn(300);
					return false;
				},
				onHide			: function(colpkr){
					$(colpkr).fadeOut(300);
					return false;
				},
				onBeforeShow	: function(){
					$(this).ColorPickerSetColor( el.val() );
				},
				onChange		: function(hsb, hex, rgb){
					el.css({
						backgroundColor	: '#' + hex,
						color			: '#' + hex
					});
					detail.text("#" + hex);
					el.val( hex );
					
					// Update the preview
					updatePreviewObj( property, hex );
					
					callBack( hex );					
				}
			});
		});
	}
	colorInit();
	
	
	
	// ------ ----- ---- --- -- - SELECT - -- --- ---- ----- ------ //
	
	// Initialize the select fields
	$.fn.customSelect = function( callBack ){
		var select		= $(this),
			holder		= select.parent(".selectHolder"),
			span		= holder.find("span"),
			property	= select.attr("id").replace(/grabber_/, "");
		span.text( select.find(":selected").text() );
		select.css({ opacity: 0 }).change(function(){
			
			// Update the value
			span.text( select.find(":selected").text() );
			
			callBack( select, property);
			
		});
	};
	attentionGrabber.selectFields.each(function(){
		
		var callBack = function( select, property ){};
		
		// If this is a select in the editor view
		if( $(this).hasClass("styleSelect") ){
			callBack = function( select, property ){
				
				// Set the theme to custom
				setCustomTheme();
			
				// Update the preview
				updatePreviewObj( property, select.val() );
			
			};
		}
		
		$(this).customSelect( callBack );
		
	});
	
	
	
	// ------ ----- ---- --- -- - CHECKBOXES - -- --- ---- ----- ------ //
	
	// Inizialize checkboxes
	$.fn.customCheckbox = function(){

		return this.each(function(){
			var el		= $(this),
				labels	= [],
				checked = el[0].checked ? ' checked' : '';
			
			// Get the labels
			labels[0] = el.data('on');
			labels[1] = el.data('off');

			// Build the new markup
			var checkBox = $('<span>',{
				"class"	: 'checkboxWrap' + checked,
				html:	'<span class="labelUnChecked">'+ labels[1] + '</span><span class="checkboxHolder"><span class="checkboxHandle"></span></span><span class="labelChecked">'+ labels[0] + '</span>'
			});

			// Insert the new checkbox, and hide the original
			checkBox.insertAfter( el.hide() );

			checkBox.find(".checkboxHolder").click(function(){
				checkBox.toggleClass('checked');
				el[0].checked = checkBox.hasClass('checked');
			});

			// Listen for changes
			el.change(function(){
				checkBox.find(".checkboxHolder").trigger("click");
			});
			
		});
	};
	$(".customCheckbox").customCheckbox();
	
	
	
	// ------ ----- ---- --- -- - SIZE SLIDER - -- --- ---- ----- ------ //
	
	// Initialize the size fields
	attentionGrabber.sizeFields.each(function(){
		
		$(this).change(function(){
	
			var el			= $(this),
				holder		= el.parent(".sizeHolder"),
				detail		= holder.find(".sizeValue"),
				sliderEl	= holder.find(".sizeSlider"),
				property	= el.attr("id").replace(/grabber_/, "");
			detail.text( el.val() + "px" );
		
			sliderEl.slider({
				value	: el.val(),
				min		: el.data("min"),
				max		: el.data("max"),
				step	: 1,
				slide	: function( event, ui ) {
					detail.text( ui.value + "px" );
					el.val( ui.value );
					// Set the theme to custom
					setCustomTheme();
					
					// Update the preview
					updatePreviewObj( property, ui.value );
				}
			});
		
		});
		
	});
	
	

	// ------ ----- ---- --- -- - TABS - -- --- ---- ----- ------ //
	
	// Tab selectors
	attentionGrabber.tabs.click(function(){
		var parent = $(this).parents(".tabContainer"),
			index = $(this).index();
		
		// If you are changing the contet type, update the input field
		if( $(this).parents("#grabberContentSelector").length ){
			var contentType = $(this).attr("rel");
			attentionGrabber.contentType.val(contentType);
		}
		
		parent.find(".selected").removeClass("selected");
		parent.find(".tabPanels").children("li").eq(index).addClass("selected");
		$(this).addClass("selected");
		
		return false;
	});
		
	// When the type value is changed, select the right tab
	attentionGrabber.contentType.change(function(){
	
		var contentType = $(this).val();
		$("#grabberContentSelector").find("li." + contentType ).trigger("click");
		
	});





	// ------ ----- ---- --- -- - ------- - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - PREVIEW - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - ------- - -- --- ---- ----- ------
	
	// Instantly update the preview when changing any value
	function updatePreviewObj( property, value ){
		
		if( property ) {
			attentionGrabber.previewObj[property] = value;
			buildPreviewCss();
		}
		
	}
	
		
	// Initialize the preview
	function initPreview( obj ){
		
		// Initialize the preview object
		attentionGrabber.previewObj = obj;
		
		// Update some of the parameter from the settings object
		attentionGrabber.previewObj.previewBg = attentionGrabber.settings.previewBg;
		attentionGrabber.previewObj.closeButtonStyle = attentionGrabber.settings.closeButtonStyle;
		
		buildPreviewCss();
		
	}
	
	
	// Build and append the new css code for the preview
	function buildPreviewCss(){
	

		var CSS = "",
			obj = attentionGrabber.previewObj;
		
		CSS += '#attentionGrabber{';
			CSS += attentionGrabber.settings.position.replace(/_fixed/,"")+': 0;';
			CSS += 'height: '+obj.style_height+'px;';
			CSS += 'background: #'+obj.style_bgColor+';';
			CSS += 'color: #'+obj.style_textColor+';';
			CSS += 'font: '+obj.style_fontStyle+' '+obj.style_fontSize+'px/'+obj.style_height+'px '+obj.style_fontFamily+';';
			if( obj.style_textShadowSize ){
				CSS += 'text-shadow: 0 '+obj.style_textShadowSize+'px 0 #'+obj.style_textShadowColor+';';
			}else {
				CSS += 'text-shadow: none;';
			}
			CSS += 'border-color: #'+obj.style_borderColor+';';
			CSS += 'border-'+attentionGrabber.settings.borderPosition+'-width: '+obj.style_borderSize+'px;';
		CSS += '}';
	
		CSS += '#attentionGrabber a{';
			CSS += 'color: #'+obj.style_linkColor+';';
			if( obj.style_linkShadowSize ){
				CSS += 'text-shadow: 0 '+obj.style_linkShadowSize+'px 0 #'+obj.style_linkShadowColor+';';
			}else{
				CSS += 'text-shadow: none;';
			}
		CSS += '}';
		
		CSS += '#attentionGrabber a:hover{';
			CSS += 'color: #'+obj.style_linkHoverColor+';';
		CSS += '}';
		
		CSS += '#grabberPreviewArea{';
			CSS += 'background: #'+attentionGrabber.settings.previewBg+';';
		CSS += '}';
		
		// Reset prvious css rules
		attentionGrabber.previewCss.html("");
		// Add styles to the preview
    	$.rule( CSS ).appendTo(attentionGrabber.previewCss);
	
	}
	
	
	// Launch preview inside a lightbox
	attentionGrabber.launchPreview.click(function(){
		
		if( !$(this).hasClass("disabled") ){
		
			var W = $(window).width() -60,
				H = $(window).height() - 60,
				siteUrl = attentionGrabber.siteURL.val();
			siteUrl += '?previewAttentionGrabber='+attentionGrabber.grabberID.val();
			
			// Open tickbox
			tb_show( 'Attention Grabber Preview', siteUrl+'&width=' + W + '&height=' + H + '&TB_iframe=true' );
		
		}
		return false;
		
	});
	
	
	
	
	
	// ------ ----- ---- --- -- - ------ - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - COMMON - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - ------ - -- --- ---- ----- ------
	

	// Addslashes for javascript
	function addslashes(str) {
		str=str.replace(/\\/g,'\\\\');
		//str=str.replace(/\'/g,'\\\'');
		str=str.replace(/\"/g,'\\"');
		str=str.replace(/\0/g,'\\0');
		return str;
	}
	
	
	// Stripslahes for javascript
	function stripslashes( str ){
		return (str + '').replace(/\\(.?)/g, function (s, n1){
			switch (n1) {
				case '\\':
					return '\\';
				case '0':
					return '\u0000';
				case '':
					return '';
				default:
					return n1;
			}
		});
	}
	
	
	// Save changes with ajax
	function ajaxSubmit( fn, data, callBack ){

		var dataObj = {
				'action'    : 'ag_do_admin_ajax',
				'function'  : fn,
				'data'		: data
			};
			$.ajax({
				type		: 'post',
				dataType    : 'JSON',
				url			: attentionGrabber.ajaxUrl,
				data		: dataObj,
				success		: function(results){
					showMessage(results.description);
					callBack();
				},
				error: function(){
					showMessage("Some errors occurred, please try again or contact support");
				}
			});
			return true;
		
		// $.ajax({
		// 	type	: 'post',
		// 	url		: attentionGrabber.ajaxUrl,
		// 	data	: data,
		// 	success	: function(results){
		// 		showMessage(results);
		// 		callBack();
		// 	},
		// 	error: function(){
		// 		showMessage("Some errors occurred, please try again or contact support");
		// 	}
		// });
		
	}
	
	
	// Show notification message
	function showMessage( message ){
	
		clearTimeout( attentionGrabber.messageTimeout );
		
		attentionGrabber.message.html( message ).animate({
			
			backgroundColor : "#57c0fc"
			
		}, 250, function(){
		
			$(this).animate({
			
				backgroundColor : "#fafafa"
				
			}, 750, function(){
				
				 attentionGrabber.messageTimeout = setTimeout(clearMessage, 3000);
				 
			});
			
		});
			
	}
	
	
	// Clear message
	function clearMessage(){
		attentionGrabber.message.html("");
	}




	// ------ ----- ---- --- -- - --------- - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - LIST VIEW - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - --------- - -- --- ---- ----- ------


	// Click on the create new button
	attentionGrabber.createNew.click(function(){
		
		is_ready();
		
		loadEditorView( false );
		
		return false;
		
	});
	
	
	// Trigger the createNew button when clicking on the "Create a new Attention Grabber" message
	attentionGrabber.grabberList.find(".noGrabbers").click(function(){
	
		attentionGrabber.createNew.trigger("click");
	
	});
	
	
	// Click on the back button
	attentionGrabber.back.click(function(){
		
		is_ready();
		
		loadListView( false, false, false );
		
		return false;
		
	});
	
	
	// Click on the viewSettings button
	attentionGrabber.viewSettings.click(function(){
		
		is_ready();
		
		loadSettingsView();
		
		return false;
		
	});
	

	// Add click event on the attentionGrabbers
	attentionGrabber.grabberList.find(".name").live("click", function(){
		
		is_ready();
		
		var ID = $(this).parents("li").attr("id").match(/\d+$/)[0];
		
		loadEditorView( ID );
		
	});
	
	
	// Load back the List view
	function loadListView(){
		
		// Show the right Button
		attentionGrabber.save.hide().removeClass("saveSettings");
		attentionGrabber.back.hide().removeClass("right");
		attentionGrabber.createNew.show();
		attentionGrabber.viewSettings.show();
		
		// Show the list title
		attentionGrabber.settingsTitle.hide();
		attentionGrabber.grabberNamePlaceholder.hide();
		attentionGrabber.listTitle.show();
		
		attentionGrabber.slider.animate({
			left: -760
		}, 400, function(){
			hideLoader();
		});
		
	}
	
	
	// Add click event on the switches
	attentionGrabber.grabberList.find(".switch").live("click", function(){
		
		is_ready();
		
		showLoader();
		
		var	el			= $(this),
			newHandleX	= -1,
			newBgX		= -37,
			callBack	= function(){
				el.addClass("active");
				activateGrabber( el.parents("li").attr("id").match(/\d+$/)[0] );
			};
		
		if( $(this).hasClass("active") ){
			// If this is the active grabber
			newHandleX	= 36;
			newBgX		= 0;
			callBack	= function(){
				el.removeClass("active");
				activateGrabber( false );
			};
		}else{
			// Else, deactivate the active one
			var currentActive = attentionGrabber.grabberList.find(".active");
			toggleSwitch( currentActive, 36, 0, function(){
				attentionGrabber.grabberList.find(".active").removeClass("active");
			} );
		}
		
		// Do the animation
		toggleSwitch( el, newHandleX, newBgX, callBack );
		
	});
	
	
	// Toggle switches on/off
	function toggleSwitch( el, newHandleX, newBgX, callBack ){
		
		var handle		= el.find(".switchHandle"),
			bg			= el.find(".switchBg");
			
		if(!$.browser.msie){
			handle.animate({ left: newHandleX }, 300 );
			bg.animate({ backgroundPosition: newBgX+'px 0px' }, 300, function(){
				callBack();	
			});
		}else{
			// IE needs its own code because it doesn't understand backgroundPosition
			handle.animate({ left: newHandleX }, 300 );
			bg.animate({ 'background-position-x': newBgX+'px' }, 300, function(){
				callBack();	
			});
		}
		
	}
	
	
	// Activate/deactivate attentionGrabbers
	function activateGrabber( ID ){
		var data		= {
				'id'	: ID
			},
			fn			= 'deactivate',
			callBack	= function(){
				hideLoader();
			};
		
		if( ID !== false ){
			// Activate the attentionGrabber with this ID
			fn	= 'activate';
		}
		
		ajaxSubmit( fn, data, callBack );
		
	}
	
	
	// Delete attentionGrabber
	attentionGrabber.grabberList.find(".delete").live("click", function(){
		
		is_ready();
		
		showLoader();
		
		var li			= $(this).parents("li"),
			name		= li.find(".name").text(),
			ID			= li.attr("id").match(/\d+$/)[0],
			fn			= 'delete',
			data		= {
				'id'		: ID,
				'isActive'	: false
			},
			callBack	= function(){
				li.css({ position : "relative" }).animate({
					opacity : 0
				}, 200, function(){
					li.animate({
						marginTop : -66
					}, 200, function(){
						li.remove();
						if( !attentionGrabber.grabberList.find(".attentionGrabberListItem").length ){
							// If this was the last attentionGrabber
							attentionGrabber.grabberList.find(".noGrabbers").removeClass("hidden");
						}
						hideLoader();
					});
				});
			};
		
		// If this is the active one, it needs to be also deactivated
		if( $(this).parents("li").find(".switch").hasClass("active") ){
			data.isActive = true;
		}
			
		if( confirm('Are you sure you want to delete "'+name+'"?') ){
		
			ajaxSubmit( fn, data, callBack );
			
		}else{
		
			hideLoader();
			return false;
			
		}
		
	});





	// ------ ----- ---- --- -- - ----------- - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - EDITOR VIEW - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - ----------- - -- --- ---- ----- ------


	// Load the editor view
	// ID is false when you are creating a new grabber
	function loadEditorView( ID ){
		
		showLoader();
		
		// Show the right Button
		attentionGrabber.createNew.hide();
		attentionGrabber.viewSettings.hide();
		attentionGrabber.back.show();
		attentionGrabber.save.show();
		
		// Show the title form
		attentionGrabber.listTitle.hide();
		attentionGrabber.grabberNamePlaceholder.show().removeClass("invalid");
		
		// Clean the message area
		attentionGrabber.message.html("");
		
		// Update the grabber_id field
		updateGrabberID( ID );
		
		// Load the value in the editor page
		loadGrabberOptions( ID );
		
		// If the ID is not defined set the value of the grabberNew field to true
		attentionGrabber.grabberNew.val( (ID) ? 0 : 1 );
		
		attentionGrabber.slider.animate({
			left: -1520
		}, 400, function(){
			hideLoader();
		});
		
	}
	
	
	// Update the grabber_id field
	function updateGrabberID( ID ){
		
		var newID = 1;
		
		if( ID ){
			// If the ID is set, use it as the new id
			newID = ID;
		}else{
			// Else, get it from the nextID field			
			newID = attentionGrabber.nextID.val();
		}
		// Update the value
		attentionGrabber.grabberID.val( newID );
		
	}
	
	
	// Load all the options of a grabber in the editor view
	// ID is false when you are creating a new grabber
	function loadGrabberOptions( ID ){
		
		var grabberObj = {};
		if( ID ){
			grabberObj = $.parseJSON( $("#grabber_id_"+ID).find(".grabberData").html() );
			attentionGrabber.launchPreview.removeClass("hidden");
		}else{
			// If this is a new attention grabber
			grabberObj = jQuery.extend(true, {}, attentionGrabber.defaultGrabber);
			
			placeholderForGrabberName();
			
			attentionGrabber.launchPreview.addClass("hidden");
		}
		
		// If the grabber is using one of the default theme, use its values
		if( attentionGrabber.themes.hasOwnProperty(grabberObj.theme) ){
		
			var themeObj = attentionGrabber.themes[grabberObj.theme];
		
			$.each( themeObj , function( key, value ){
			
				grabberObj[key] = themeObj[key];
			
			});
		
		}
		
		updateInterface( grabberObj );
		
		// Set the themename in the hidden field
		attentionGrabber.themeField.val( grabberObj.theme ).trigger("change");
		
	}
	
	
	// Update the interface and the preview
	function updateInterface( obj ){
	
		// Update the fields
		$.each( obj , function( key, value ){
		
			var el = $("#grabber_" + key );
			
			if( el.hasClass("customCheckbox") ){
				el[0].checked = value;
				el.trigger("change");
			}else{
				el.val( value ).trigger('change');
			}
			
		});
		
		// Initialize the preview
		initPreview( obj );
		
		// Update colorPickers
		colorInit();
	
	}
	
	// Set the theme on custom, when any value in the style are changed
	function setCustomTheme(){
		
		if( attentionGrabber.themeField.val() !== "custom" ){
		
			attentionGrabber.themeField.val("custom").trigger("change");
		
		}
		
	}
	
	
	// Listen to changes to the grabber_theme field
	attentionGrabber.themeField.change(function(){
		
		var theme = $(this).val();
			themeLi = attentionGrabber.themeList.find("#"+theme);
			
		attentionGrabber.themeList.find(".active").removeClass("active");
		
		// If that theme exists, set it as selected
		if( themeLi.length ){
			themeLi.addClass("active");
		}
		
	});
	
	
	// Load the theme
	attentionGrabber.themeList.find("li").click(function(){
		
		var themeName = $(this).attr("id"),
			grabberObj = {};
			
		
		// If the theme exists in the themes object
		if( attentionGrabber.themes.hasOwnProperty(themeName) && !$(this).hasClass("active") ){
			
			grabberObj = jQuery.extend(true, {}, attentionGrabber.themes[themeName]);
			
			updateInterface( grabberObj );
			
			attentionGrabber.themeField.val( themeName ).trigger("change");
			
		}
		
	});
	
	
	// Validate the grabberName field
	function validateGrabberName(){
	
		var name		= attentionGrabber.grabberNamePlaceholder.val(),
			placeholder	= attentionGrabber.grabberNamePlaceholder.attr("placeholder");
		
		if( (name != placeholder) && (name != "") ){
		
			attentionGrabber.grabberNamePlaceholder.removeClass("invalid");
			attentionGrabber.grabberName.val( name );
			return true;
			
		}else{
		
			attentionGrabber.grabberNamePlaceholder.addClass("invalid");
			return false;
		}
	
	}
	
	// Click on the save button
	attentionGrabber.save.click(function(){
		
		is_ready();
		
		showLoader();
		
		if( $(this).hasClass("saveSettings") ){
		
			saveSettings();
		
		}else{
		
			// A name for the grabber is required
			if( validateGrabberName() ){
				
				// If this is a new attentionGrabber
				if( attentionGrabber.grabberNew.val() == 1 ){
				
					var nextID = attentionGrabber.nextID.val();
					nextID = parseInt( nextID, 10 ) + 1;
					attentionGrabber.nextID.val( nextID );
				
				}
				
				var data		= attentionGrabber.editorForm.serializeObject(),
					fn			= 'saveChanges',
					callBack	= function(){
						saveChanges( data );
					};
				
				// Save changes to the DB
				ajaxSubmit( fn, data, callBack );
				
			}else{
				hideLoader();
			}
		
		}
		
		return false;
		
	});
	
	
	// Save changes made to the attentionGrabber
	function saveChanges( data ){

		var clearData = {};

		$.each( data , function( key, value ){					
			key = key.replace(/grabber_/, "");
			clearData[key] = value;
		});
	
		var ID		= attentionGrabber.grabberID.val(),
			name	= attentionGrabber.grabberName.val(),
			jsonObj	= JSON.stringify( clearData );
			
		
		if( attentionGrabber.grabberNew.val() == 1 ){
			// If a new attentionGrabber was created
			
			attentionGrabber.grabberList.find(".noGrabbers").addClass("hidden");
			
			var newListItem = $("<li />", {
					"class"	: "attentionGrabberListItem cf",
					id			: "grabber_id_"+ID
				}).append('<span class="name">'+name+'</span><span class="clickCount"><strong>Clicks:</strong> 0</span><span class="switch"><span class="switchHandle"></span><span class="switchBg"></span></span><span class="delete"></span><span class="grabberData">'+jsonObj+'</span>');
			
			attentionGrabber.grabberList.append( newListItem );
			
			attentionGrabber.grabberNew.val(0);
			
			// Enable the preview
			attentionGrabber.launchPreview.removeClass("hidden");
			
		}else{
			
			// Update the name in the grabber list
			$("#grabber_id_"+ID).find(".name").text( name );
			
			// Update the json object in the grabber list
			$("#grabber_id_"+ID).find(".grabberData").html( jsonObj );
			
		}
		hideLoader();
	
	}
	
	
	// Save the grabber name into the right input
	attentionGrabber.grabberName.change(function(){ attentionGrabber.grabberNamePlaceholder.val( attentionGrabber.grabberName.val() ); });





	// ------ ----- ---- --- -- - ------------- - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - SETTINGS VIEW - -- --- ---- ----- ------
	// ------ ----- ---- --- -- - ------------- - -- --- ---- ----- ------
	
	
	// Get the setting changes and store them inside an object
	function loadSettings(){
		attentionGrabber.settings = $.parseJSON( attentionGrabber.coreSettings.text() );
	}
	
	
	// Load the settings view
	function loadSettingsView(){
		
		showLoader();
		
		// Show the right Button
		attentionGrabber.createNew.hide();
		attentionGrabber.viewSettings.hide();
		attentionGrabber.back.addClass("right").show();
		attentionGrabber.save.addClass("saveSettings").show();
		
		// Show the settingsTitle
		attentionGrabber.listTitle.hide();
		attentionGrabber.settingsTitle.show();
		
		// Clean the message area
		attentionGrabber.message.html("");
		
		attentionGrabber.slider.animate({
			left: 0
		}, 400, function(){
			hideLoader();
		});
		
	}
	
	
	// Save the settings
	function saveSettings(){
	
		var data		= attentionGrabber.settingsForm.serializeObject(),
			fn			= 'saveSettings',
			callBack	= function(){
				// Update the settings object
				$.each( data , function( key, value ){					
					key = key.replace(/grabberSettings_/, "");
					attentionGrabber.settings[key] = value;
				});
				// Adjust the position of the border
				attentionGrabber.settings.borderPosition = ( attentionGrabber.settings.position.match(/^bottom/) ) ? "top" : "bottom";

				loadListView();
			};
		
		// Save changes to the DB
		ajaxSubmit( fn, data, callBack );
	
	}
	
	
	// Reset everything
	$("#grabberSettings_resetAll").click(function(){
	
		is_ready();
		
		showLoader();
		
		var data		= {'dummy':'data'},
			fn			= 'resetAll',
			callBack	= function(){
				hideLoader();
				window.location.reload();
			};
			
		if( confirm('Are you sure you want to delete the settings and all the Attention Grabbers?') ){
		
			ajaxSubmit( fn, data, callBack );
			
		}else{
		
			hideLoader();
			
		}
		
		return false;
	
	});
	
	
	
	
	
	// ------ ----- ---- --- -- - UTILS - -- --- ---- ----- ------
	
	
	// Check for updates via ajax
	$.checkAttentionGrabberUpdates = function( installedVersion ){
		
		$.ajax({
			dataType	: 'jsonp',
			data		: 'version='+installedVersion,
			jsonp		: 'jsonp_callback',
			url			: 'http://attentiongrabber.tommasoraspo.com/checkUpdate.php',
			success		: function(results){
			
				if( results == 'updated' ){
					console.log(results);
				}else{
					showNotification(results);
				}
			
				
			},
			error: function(results){ }
		});
		
	}
	
	function showNotification( html ){
		
		$("#attentionGrabberAdmin_Notification").html( html ).fadeIn(400);
		
	}
	
	
	// ------ ----- ---- --- -- - INIT - -- --- ---- ----- ------
	hideLoader();
	attentionGrabber.back.hide();
	
	loadSettings();


});
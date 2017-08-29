var Util = {
	/**
	 *
	 * @returns {boolean}
	 */
	isTouch: function() {
		return ! ! (
			"ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch
		);
	},

	handleCustomCSS: function( $container ) {
		var $elements = typeof $container !== "undefined" ? $container.find( "[data-css]" ) : $( "[data-css]" );

		if ( $elements.length ) {
			$elements.each( function( i, obj ) {
				var $element = $( obj ),
					css = $element.data( 'css' );

				if ( typeof css !== "undefined" ) {
					$element.replaceWith( '<style type="text/css">' + css + '</style>' );
				}
			} );
		}
	},


	/**
	 * Search every image that is alone in a p tag and wrap it
	 * in a figure element to behave like images with captions
	 *
	 * @param $container
	 */
	unwrapImages: function( $container ) {

		$container = typeof $container !== "undefined" ? $container : $( 'body' );

		$container.find( 'p > img:first-child:last-child' ).each( function( i, obj ) {
			var $image = $( obj ),
				className = $image.attr( 'class' ),
				$p = $image.parent();

			if ( $.trim( $p.text() ).length ) {
				return;
			}

			$image
			.removeAttr( 'class' )
			.unwrap()
			.wrap( '<figure />' )
			.parent()
			.attr( 'class', className );
		} );

		$container.find( '.entry-content > .gallery' ).wrap( '<div class="gallery-wrapper" />' );

	},

	wrapEmbeds: function( $container ) {
		$container = typeof $container !== "undefined" ? $container : $( 'body' );
		$container.children( 'iframe, embed, object' ).wrap( '<p>' );
	},

	/**
	 * Initialize video elements on demand from placeholders
	 *
	 * @param $container
	 */
	handleVideos: function( $container ) {
		$container = typeof $container !== "undefined" ? $container : $( 'body' );

		$container.find( '.video-placeholder' ).each( function( i, obj ) {
			var $placeholder = $( obj ),
				video = document.createElement( 'video' ),
				$video = $( video ).addClass( 'c-hero__video' );

			// play as soon as possible
			video.onloadedmetadata = function() {
				video.play();
			};

			video.src = $placeholder.data( 'src' );
			video.poster = $placeholder.data( 'poster' );
			video.muted = true;
			video.loop = true;

			$placeholder.replaceWith( $video );
		} );
	},

	smoothScrollTo: function( to, duration, easing ) {
		to = to || 0;
		duration = duration || 1000;
		easing = easing || 'swing';

		$( "html, body" ).stop().animate( {
			scrollTop: to
		}, duration, easing );

	},

	// Returns a function, that, as long as it continues to be invoked, will not
	// be triggered. The function will be called after it stops being called for
	// N milliseconds. If `immediate` is passed, trigger the function on the
	// leading edge, instead of the trailing.
	debounce: function( func, wait, immediate ) {
		var timeout;
		return function() {
			var context = this, args = arguments;
			var later = function() {
				timeout = null;
				if ( ! immediate ) {
					func.apply( context, args );
				}
			};
			var callNow = immediate && ! timeout;
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
			if ( callNow ) {
				func.apply( context, args );
			}
		};
	},

	// Returns a function, that, when invoked, will only be triggered at most once
	// during a given window of time. Normally, the throttled function will run
	// as much as it can, without ever going more than once per `wait` duration;
	// but if you'd like to disable the execution on the leading edge, pass
	// `{leading: false}`. To disable execution on the trailing edge, ditto.
	throttle: function( callback, limit ) {
		var wait = false;
		return function() {
			if ( ! wait ) {
				callback.call();
				wait = true;
				setTimeout( function() {
					wait = false;
				}, limit );
			}
		}
	},

	mq: function( direction, string ) {
		var $temp = $( '<div class="u-mq-' + direction + '-' + string + '">' ).appendTo( 'body' ),
			response = $temp.is( ':visible' );

		$temp.remove();
		return response;
	},

	below: function( string ) {
		return this.mq( 'below', string );
	},

	above: function( string ) {
		return this.mq( 'above', string );
	},

	getParamFromURL: function( param, url ) {
		var parameters = (
			url.split( '?' )
		)[1];

		if ( typeof parameters === "undefined" ) {
			return parameters;
		}

		parameters = parameters.split( '&' );

		for ( var i = 0; i < parameters.length; i ++ ) {
			var parameter = parameters[i].split( '=' );
			if ( parameter[0] === param ) {
				return parameter[1];
			}
		}
	},

	reloadScript: function( filename ) {
		var $old = $( 'script[src*="' + filename + '"]' ),
			$new = $( '<script>' ),
			src = $old.attr( 'src' );

		if ( ! $old.length ) {
			return;
		}

		$old.replaceWith( $new );
		$new.attr( 'src', src );
	},

	// here we change the link of the Edit button in the Admin Bar
	// to make sure it reflects the current page
	adminBarEditFix: function( id, editString, taxonomy ) {
		// get the admin ajax url and clean it
		var baseEditURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'post.php' ),
			baseEditTaxURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'edit-tags.php' ),
			$editButton = $( '#wp-admin-bar-edit a' );

		if ( ! empty( $editButton ) ) {
			if ( id !== undefined && editString !== undefined ) { //modify the current Edit button
				if ( !empty( taxonomy ) ) { //it seems we need to edit a taxonomy
					$editButton.attr( 'href', baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit' );
				} else {
					$editButton.attr( 'href', baseEditURL + '?post=' + id + '&action=edit' );
				}
				$editButton.html( editString );
			} else { // we have found an edit button but right now we don't need it anymore since we have no id
				$( '#wp-admin-bar-edit' ).remove();
			}
		} else { // upss ... no edit button
			// lets see if we need one
			if ( id !== undefined && editString !== undefined ) { //we do need one after all
				//locate the New button because we need to add stuff after it
				var $newButton = $( '#wp-admin-bar-new-content' );

				if ( !empty( $newButton ) ) {
					if ( !empty( taxonomy ) ) { //it seems we need to generate a taxonomy edit thingy
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit">' + editString + '</a></li>' );
					} else { //just a regular edit
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditURL + '?post=' + id + '&action=edit">' + editString + '</a></li>' );
					}
				}
			}
		}

		//Also we need to fix the (no-)customize-support class on body by running the WordPress inline script again
		// The original code is generated by the wp_customize_support_script() function in wp-includes/theme.php @3007
		var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp('(^|\\s+)(no-)?'+cs+'(\\s+|$)');

		// No CORS request
		request = true;

		b[c] = b[c].replace( rcs, ' ' );
		// The customizer requires postMessage and CORS (if the site is cross domain)
		b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;

		//Plus, we need to change the url of the Customize button to the current url
		var $customizeButton = $( '#wp-admin-bar-customize a' ),
			baseCustomizeURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php','customize.php' );
		if ( ! empty( $customizeButton ) ) {
			$customizeButton.attr( 'href', baseCustomizeURL + '?url=' + encodeURIComponent( window.location.href ) );
		}

	},

	//similar to PHP's empty function
	empty: function( data ) {
		if ( typeof( data ) == 'number' || typeof( data ) == 'boolean' ) {
			return false;
		}

		if ( typeof( data ) == 'undefined' || data === null ) {
			return true;
		}

		if ( typeof( data.length ) != 'undefined' ) {
			return data.length === 0;
		}

		var count = 0;

		for ( var i in data ) {
			// if (data.hasOwnProperty(i))
			//
			// This doesn't work in ie8/ie9 due the fact that hasOwnProperty works only on native objects.
			// http://stackoverflow.com/questions/8157700/object-has-no-hasownproperty-method-i-e-its-undefined-ie8
			//
			// for hosts objects we do this
			if ( Object.prototype.hasOwnProperty.call( data, i ) ) {
				count ++;
			}
		}
		return count === 0;
	},

	// here we change the link of the Edit button in the Admin Bar
	// to make sure it reflects the current page
	adminBarEditFix: function( id, editString, taxonomy ) {
		// get the admin ajax url and clean it
		var baseEditURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'post.php' ),
			baseEditTaxURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'edit-tags.php' ),
			$editButton = $( '#wp-admin-bar-edit a' );

		if ( ! Util.empty( $editButton ) ) {
			if ( id !== undefined && editString !== undefined ) { //modify the current Edit button
				if ( ! Util.empty( taxonomy ) ) { //it seems we need to edit a taxonomy
					$editButton.attr( 'href', baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit' );
				} else {
					$editButton.attr( 'href', baseEditURL + '?post=' + id + '&action=edit' );
				}
				$editButton.html( editString );
			} else { // we have found an edit button but right now we don't need it anymore since we have no id
				$( '#wp-admin-bar-edit' ).remove();
			}
		} else { // upss ... no edit button
			// lets see if we need one
			if ( id !== undefined && editString !== undefined ) { //we do need one after all
				//locate the New button because we need to add stuff after it
				var $newButton = $( '#wp-admin-bar-new-content' );

				if ( ! Util.empty( $newButton ) ) {
					if ( ! Util.empty( taxonomy ) ) { //it seems we need to generate a taxonomy edit thingy
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditTaxURL + '?tag_ID=' + id + '&taxonomy=' + taxonomy + '&action=edit">' + editString + '</a></li>' );
					} else { //just a regular edit
						$newButton.after( '<li id="wp-admin-bar-edit"><a class="ab-item dJAX_internal" href="' + baseEditURL + '?post=' + id + '&action=edit">' + editString + '</a></li>' );
					}
				}
			}
		}

		//Also we need to fix the (no-)customize-support class on body by running the WordPress inline script again
		// The original code is generated by the wp_customize_support_script() function in wp-includes/theme.php @3007
		var request, b = document.body, c = 'className', cs = 'customize-support', rcs = new RegExp( '(^|\\s+)(no-)?' + cs + '(\\s+|$)' );

		// No CORS request
		request = true;

		b[c] = b[c].replace( rcs, ' ' );
		// The customizer requires postMessage and CORS (if the site is cross domain)
		b[c] += ( window.postMessage && request ? ' ' : ' no-' ) + cs;

		//Plus, we need to change the url of the Customize button to the current url
		var $customizeButton = $( '#wp-admin-bar-customize a' ),
			baseCustomizeURL = boilerplate_js_strings.ajaxurl.replace( 'admin-ajax.php', 'customize.php' );
		if ( ! Util.empty( $customizeButton ) ) {
			$customizeButton.attr( 'href', baseCustomizeURL + '?url=' + encodeURIComponent( window.location.href ) );
		}

	},

	/**
	 * returns version of IE or false, if browser is not Internet Explorer
	 */
	getIEversion: function() {
		var ua = window.navigator.userAgent;

		// Test values; Uncomment to check result …

		// IE 10
		// ua = 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)';

		// IE 11
		// ua = 'Mozilla/5.0 (Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko';

		// Edge 12 (Spartan)
		// ua = 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.71 Safari/537.36 Edge/12.0';

		// Edge 13
		// ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586';

		var msie = ua.indexOf('MSIE ');
		if (msie > 0) {
			// IE 10 or older => return version number
			return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
		}

		var trident = ua.indexOf('Trident/');
		if (trident > 0) {
			// IE 11 => return version number
			var rv = ua.indexOf('rv:');
			return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
		}

		var edge = ua.indexOf('Edge/');
		if (edge > 0) {
			// Edge (IE 12+) => return version number
			return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
		}

		// other browser
		return false;
	}

};

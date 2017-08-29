var Boilerplate = new pixelgradeTheme(),
	resizeEvent = "ontouchstart" in window && "onorientationchange" in window ? "pxg:orientationchange" : "pxg:resize",
	$window = $( window ),
	$html = $( 'html' ),
	$body = $( 'body' ),
	ieVersion = Util.getIEversion();

Boilerplate.init = function() {
	Boilerplate.log( 'Boilerplate.init' );

	Boilerplate.Slider = new Slider();
	Boilerplate.Logo = new Logo();
	Boilerplate.Navbar = new Navbar();

	Boilerplate.Parallax = new Parallax( '.c-hero__background, .c-hero__background-mask', {
		bleed: 20,
		scale: 1.2,
		container: '.c-hero__background-mask'
	} );

	Boilerplate.Parallax.disabled = "ontouchstart" in window && "onorientationchange" in window;

	var $body = $( 'body' );

	if ( ! $body.is( '.customizer-preview' ) && typeof $body.data( 'ajaxloading' ) !== 'undefined' ) {
		Boilerplate.initializeAjax();
	}

	// on document.ready
	$( function() {
		Boilerplate.handleContent();
		Boilerplate.eventHandlersOnce();
		Boilerplate.eventHandlers();
		Boilerplate.adjustLayout();
		Boilerplate.fadeIn();
	} );
};

Boilerplate.update = function() {
	Boilerplate.log( 'Boilerplate.update' );

	Boilerplate.readingProgress.onScroll( Boilerplate.getScroll() );
};

Boilerplate.handleScrollArrow = function( $container ) {
	$container = typeof $container !== "undefined" ? $container : $( 'body' );

	var $arrow = $container.find( '.c-hero__scroll-arrow' ).first(),
		$hero = $arrow.closest( '.c-hero' ),
		top;

	if ( ! $arrow.length ) {
		return;
	}

	top = $hero.offset().top + $hero.outerHeight() - $( '.u-site-header-sticky .site-header' ).outerHeight()

	$arrow.off( 'click .scroll' );
	$arrow.on( 'click .scroll', function() {
		Util.smoothScrollTo( top, 500 );
	} );
};

Boilerplate.adjustLayout = function() {
	performance.mark( "adjustLayout:start" );

	Boilerplate.log( 'Boilerplate.adjustLayout' );
	Boilerplate.readingProgress.onResize();

	Boilerplate.Gallery = new Gallery();

	// use header height as spacing measure for specific elements
	var $updatable = $( '.js-header-height-padding-top' ),
		headerHeight = $( '.c-navbar' ).outerHeight() || $( '.c-navbar__middle' ).outerHeight();

	// set padding top to certain elements which is equal to the header height
	$updatable.css( 'paddingTop', headerHeight );

	performance.mark( 'adjustLayout:end' );
	performance.measure( 'adjustLayout', 'adjustLayout:start', 'adjustLayout:end' );
};

Boilerplate.handleContent = function( $container ) {
	performance.mark( "handleContent:start" );

	Boilerplate.log( 'Boilerplate.handleContent', $container );

	Boilerplate.readingProgress = new readingProgress();

	$container = typeof $container !== 'undefined' ? $container : $( 'body' );

	Util.unwrapImages( $container.find( '.entry-content' ) );
	Util.wrapEmbeds( $container.find( '.entry-content' ) );
	Util.handleVideos( $container );
	Util.handleCustomCSS( $container );

	Boilerplate.handleScrollArrow( $container );

	Boilerplate.Parallax.init( $container );

	$container.find( '#comments-toggle' ).prop( 'checked', window.location.href.indexOf( '#comment' ) !== -1 );

	$container.find( '.js-taxonomy-dropdown' ).each( function( i, obj ) {
		var $select = $( obj ),
			selected = $select.find( '[selected]' ).first().attr( 'value' );

		$select.val( selected ).resizeselect().addClass( 'is-loaded' );
	} );

	performance.mark( "handleContent:end" );
	performance.measure( 'handleContent', 'handleContent:start', 'handleContent:end' );
};

Boilerplate.eventHandlersOnce = function() {
	Boilerplate.log( 'Boilerplate.eventHandlersOnce' );

	$( window ).on( resizeEvent, Boilerplate.adjustLayout );
	$( window ).on( 'beforeunload', Boilerplate.fadeOut );

	$( 'body' ).on( 'change', '.js-taxonomy-dropdown.is-loaded', function( e ) {
		var destination = this.value;

		if ( typeof destination !== "undefined" && destination !== "#" ) {
			if ( typeof Boilerplate.Ajax !== "undefined" && typeof Barba !== "undefined" && typeof Barba.Pjax !== "undefined" ) {
				Barba.Pjax.goTo( destination );
			} else {
				window.location.href = destination;
			}
		}
	} );

	Boilerplate.ev.on( 'render', Boilerplate.update );
};

Boilerplate.eventHandlers = function( $container ) {
	Boilerplate.log( 'Boilerplate.eventHandlers', $container );

	$container = typeof $container !== 'undefined' ? $container : $( 'body' );

	// add every image on the page the .is-loaded class
	// after the image has actually loaded
	$container.find( 'img' ).each( function( i, obj ) {
		var $each = $( obj );
		$each.imagesLoaded( function() {
			$each.addClass( 'is-loaded' );
		} );
	} );

	$container.find( '.u-gallery-type--masonry' ).each( function( i, obj ) {
		var $gallery = $( obj );

		$gallery.imagesLoaded( function() {
			$gallery.masonry( {
				transitionDuration: 0
			} );
		} );
	} );
};

Boilerplate.initializeAjax = function() {
	Boilerplate.log( 'Boilerplate.initializeAjax' );

	Boilerplate.Ajax = new AjaxLoading();

	Boilerplate.Ajax.ev.on( 'beforeOut', function( ev, container ) {
		Boilerplate.Navbar.close();
		Boilerplate.fadeOut();
	} );

	Boilerplate.Ajax.ev.on( 'afterOut', function( ev, container ) {
		$html.addClass( 'no-transitions' );
	} );

	Boilerplate.Ajax.ev.on( 'afterIn', function( ev, container ) {
		var $container = $( container );

		// Util.reloadScript( 'related-posts.js' );

		Boilerplate.handleContent( $container );
		Boilerplate.eventHandlers( $container );
		Boilerplate.adjustLayout();
		Boilerplate.update();
		Boilerplate.fadeIn();
	} );
};

Boilerplate.fadeOut = function() {
	Boilerplate.log( 'Boilerplate.fadeOut' );

	$html.removeClass( 'fade-in' ).addClass( 'fade-out' );
};

Boilerplate.fadeIn = function() {
	Boilerplate.log( 'Boilerplate.fadeIn' );

	$html.removeClass( 'fade-out no-transitions' ).addClass( 'fade-in' );
};

$.Boilerplate = Boilerplate;
$.Boilerplate.init();

performance.mark( "app:init" );

$( function() {
	performance.mark( "app:ready" );
	performance.measure( 'Ready', 'app:start', 'app:ready' );
} );

$window.load( function() {
	performance.mark( "app:load" );
	performance.measure( 'Load', 'app:start', 'app:load' );
} );

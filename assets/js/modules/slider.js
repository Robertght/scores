/*!
 * pixelgradeTheme v1.0.2
 * Copyright (c) 2017 PixelGrade http://www.pixelgrade.com
 * Licensed under MIT http://www.opensource.org/licenses/mit-license.php/
 */
var Slider = function() {

	this.options = {
		dots: true,
		infinite: true,
		speed: 600,
		fade: true,
		useTransform: false,
		ease: 'easeInOutCirc'
	};

};

Slider.prototype.init = function( $container ) {

	var _this = this,
		$sliders = $container.find( '.c-hero__slider, .js-pixslider' );

	$sliders.each( function( i, obj ) {

		var $slider = $( obj ),
			autoplay = typeof $slider.data( 'autoplay' ) !== "undefined",
			autoplaySpeed = typeof $slider.data( 'autoplay-delay' ) == "number" ? $slider.data( 'autoplay-delay' ) : 2000;

		if ( autoplay ) {
			$.extend( _this.options, {
				autoplay: autoplay,
				autoplaySpeed: autoplaySpeed
			} );
		}

		if ( $slider.is( '.c-hero__slider' ) && $( '.js-front-page-sidebar' ).length ) {
			$.extend( _this.options, {
				dots: true
			} );
		}

		if ( $slider.children().length > 1 ) {
			$slider.slick( _this.options );
			$slider.find( '.slick-slide' ).first().focus();
		}

	} );

};

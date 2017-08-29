var readingProgress = function( $element, $target, scrollY ) {
	this.$element = $element || $( '.entry-content' );
	this.$target = $target || $( '.js-reading-progress' );
	this.scrollY = scrollY || (window.pageYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0);
};

readingProgress.prototype.onResize = function( windowWidth, windowHeight ) {
	windowWidth = windowWidth || window.innerWidth;
	windowHeight = windowHeight || window.innerHeight;

	if ( typeof $element === "undefined" ) {
		return;
	}

	this.start = this.$element.offset().top;
	this.stop = this.start + this.$element.outerHeight();
};

readingProgress.prototype.onScroll = function( scrollY ) {
	var that = this,
		progress = 0;

	scrollY = scrollY || (window.pageYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0);
	progress = (scrollY - this.start) / (this.stop - this.start);
	progress = Math.min(Math.max(progress, 0), 1);

	this.$target.css( 'width', progress * 100 + '%' );
};

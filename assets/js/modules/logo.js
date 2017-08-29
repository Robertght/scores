Logo = function( scrollY ) {

	this.$clone = null;
	this.distance = null;
	this.initialized = false;

	this.$logo = $( '.c-branding' ).closest( '.c-navbar__zone' ).addClass( 'c-navbar__zone--branding' );
	this.$clone = this.$logo.clone().appendTo( '.c-navbar' );

//	this.onResize( scrollY );
};

Logo.prototype.onResize = function( scrollY ) {

	var that = this;

	scrollY = scrollY || (window.pageYOffset || document.documentElement.scrollTop) - (document.documentElement.clientTop || 0);

	if ( that.$logo.length ) {

		that.$logo.imagesLoaded(function() {

			if ( that.$clone === null ) {
				that.$clone = that.$logo.clone().appendTo( '.mobile-logo' );
			} else {
				that.$clone.removeAttr( 'style' );
			}

			that.logoMid = that.$logo.offset().top + that.$logo.height() / 2;
			that.cloneMid = that.$clone.offset().top + that.$clone.height() / 2 - scrollY;
			that.distance = that.logoMid - that.cloneMid;

			that.initialized = true;

			that.update( scrollY );

			that.$clone.css( 'opacity', 1 );

		});
	}
};

Logo.prototype.update = function( scrollY ) {

	if ( ! this.initialized ) {
		return;
	}

	if ( this.distance < scrollY ) {
		this.$clone.css( 'transform', 'none' );
		return;
	}

	this.$clone.css( 'transform', 'translateY(' + ( this.distance - scrollY ) + 'px)' );
};

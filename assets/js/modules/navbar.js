var Navbar = function() {
	this.$handle = $( '#menu-toggle' );
	this.bindEvents();
};

Navbar.prototype.bindEvents = function() {

	$( '.c-navbar' ).on( 'click', 'a', function( e ) {

		var $link = $( this ),
			$item = $link.parent(),
			$submenu = $link.siblings( 'ul' );

		if ( $submenu.length && ! $item.hasClass( 'is-active' ) ) {
			e.preventDefault();
			e.stopPropagation();
			$item.addClass( 'is-active' );
		}

	} );

};

Navbar.prototype.open = function() {
	this.$handle.prop( 'checked', true );
	this.$handle.trigger( 'change' );
};

Navbar.prototype.close = function() {
	this.$handle.prop( 'checked', false );
	this.$handle.trigger( 'change' );
};

Navbar.prototype.onChange = function() {

	var $body = $( 'body' );

	if ( this.$handle.prop( 'checked' ) ) {
		// Open navbar and prevent scrolling
		$body.width( $body.width() );
		$body.css( 'overflow', 'hidden' );
	} else {
		// Close navigation and allow scrolling
		$body.css( 'overflow', '' );

		// Close all open submenus
		$( '.menu-item' ).removeClass( 'hover' );
	}

};

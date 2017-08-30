(function( $, window, document, undefined ) {


$( function() {

	$( '.js-back' ).click( function() {
		window.history.back();
	} );

	$( '.add-match-increase' ).click( function( e ) {
		e.preventDefault();

		var $this = $( this ),
			$input = $this.closest( '.add-match-team' ).find( '.add-match-score' ),
			value = parseInt( $input.val(), 10 );

		$input.val( value + 1 );
	} );

	$( '.add-match-decrease' ).click( function( e ) {
		e.preventDefault();

		var $this = $( this ),
			$input = $this.closest( '.add-match-team' ).find( '.add-match-score' ),
			value = parseInt( $input.val(), 10 );

		$input.val( value == 0 ? 0 : value - 1 );
	} );

	$( '.add-match-player' ).on( 'change', function() {
		var $this = $( this ),
			$selected = $this.find( ':selected' ),
			avatar = $selected.data( 'avatar' ),
			$container = $this.closest( '.add-match-team' ).find( '.add-match-avatar' ),
			$img = $( '<img src="' + avatar + '">' );

		$container.empty().append( $img );
	} ).trigger( 'change' );

} );

})( jQuery, window, document );

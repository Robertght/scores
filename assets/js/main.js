(function( $, window, document, undefined ) {


$(function() {

	$( '.js-back' ).click( function() {
		window.history.back();
	} );

	$('.add-match-increase').click(function(e) {
		e.preventDefault();
		var $this = $(this),
			$input = $this.closest('.add-match-team').find('.add-match-score'),
			value = parseInt( $input.val(), 10 );
		$input.val( value + 1 );
	});

	$('.add-match-decrease').click(function(e) {
		e.preventDefault();
		var $this = $(this),
			$input = $this.closest('.add-match-team').find('.add-match-score'),
			value = parseInt( $input.val(), 10 );
		$input.val( value == 0 ? 0 : value - 1 );
	});

});

})( jQuery, window, document );

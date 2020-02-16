( function( $ ) {
	$( document ).on( 'focus', '.o2-editor-text', function( event ) {
		var relatedTarget = $( event.relatedTarget ).closest( '.comment' ).find( '.comment-author' ).first();
		var currentTarget = $( event.currentTarget );

		if ( relatedTarget.length ) {
			var username = relatedTarget.prop( 'href' ).replace(/\/$/g, '' ).split( '/' ).pop();

			if ( -1 === currentTarget.val().indexOf( '@' + username ) ) {
				currentTarget.val( '@' + username + ' ' + currentTarget.val() );
			}
		}
	} );
} )( jQuery );

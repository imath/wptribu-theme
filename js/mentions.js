( function( $, wp, Tribute ) {
	var tribute = new Tribute( {
		values: function( search, results ) {
			wp.apiRequest( {
				path: 'wp/v2/users',
				type: 'GET',
				dataType: 'json',
				data: {
					context: 'view',
					search: search,
				}
			} ).done( function( users ) {
				results( users );
			} ).fail( function( error ) {
				results( [ error.responseJSON ] );
			} );
		},
		lookup: 'name',
		fillAttr: 'slug',
		menuItemTemplate: function( user ) {
			var template = '';

			if ( user.original.avatar_urls && user.original.avatar_urls[48] ) {
				template = '<img src="' + user.original.avatar_urls[48] + '" />';
			}

			template += '<span class="username">' + user.string + '</span><small>@' + user.original.slug + '</small>';
			return template;
		},
	} );

	$( document ).on( 'focus', '.o2-editor-text', function( e ) {
		if ( ! e.currentTarget.hasAttribute( 'data-tribute' ) ) {
			tribute.attach( e.currentTarget );
		}
	} );


} )( jQuery, window.wp || {}, window.Tribute );

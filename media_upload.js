
jQuery( function ( $ ) {

	$( document ).on( 'click' , '.custom_media_upload' , function ( e ) {
		e.preventDefault();
		var $target = $( e.target );

		var widget_id = $target.attr('data-id');

		var custom_uploader = wp.media( {
			title    : 'Выберите изображение' ,
			button   : {
				text : 'Обновить'
			} ,
			multiple : false  // Set this to true to allow multiple files to be selected
		})
		.on( 'select' , function () {
			var attachment = custom_uploader.state().get( 'selection' ).first().toJSON();
			
//			console.log( widget_id);
			var $widget_box = $('.custom_media_upload[data-id="' + widget_id + '"]').parents( '.widget-content' );

			$widget_box.find( '.dl-image-preview' ).attr( 'src' , attachment.sizes.thumbnail.url );
			$widget_box.find( '.dl-image-id' ).val( attachment.id );
		})
		.open();
	} );

	$( document ).on( 'click' , '.dl-clean-image' , function ( e ) {
		e.preventDefault();
		var $widget_box = $( e.target ).parents( '.widget-content' );
		
//		console.log( $widget_box );

		$widget_box.find('.dl-image-preview' ).attr( 'src' , '' );
		$widget_box.find('.dl-image-id' ).val('');
	} );
} );



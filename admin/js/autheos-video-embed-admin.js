(function( $ ) {
	'use strict';
	$(function() {
		let set_thumbnail_btn = $('#select_default_thumbnail');
		let set_input_val = $('#default_thumbnail');
		let preview_thumbnail = $('#preview_thumbnail');
		let remove_thumbnail = $('#remove_thumbnail');

		//set preview image
		function autheos_set_preview_image(image_html) {
			preview_thumbnail.empty();
			preview_thumbnail.append(image_html);
			remove_thumbnail.removeClass('button-disabled');
		}

		// get preview image from server
		function autheos_get_preview_image (image_id) {
			var data = {
				action: 'authoes_get_thumbnail',
				image_id: image_id
			};
			$.post(ajaxurl, data, function (response) {
				autheos_set_preview_image(response);
			});
		}
		// remove selcted thumbnail 
		remove_thumbnail.on('click',function (e){
			e.preventDefault();
			preview_thumbnail.empty();
			set_input_val.val('');
			$(this).addClass('button-disabled');
		});
		/**
		 * open the media manager
		 */
		set_thumbnail_btn.on('click',function (e) {
			e.preventDefault();
			var frame = wp.media({
				title : 'Select Default Thumbnail',
				multiple : false,
				library : { type : 'image' },
				button : { text : 'Set Thumbnail' }
			});
			// close event media manager
			frame.on('close', function () {
				var images = frame.state().get('selection');
				// set the images
				images.each(function (image) {
					console.log(image)
					autheos_get_preview_image(image.id);
					set_input_val.val(image.id);
				});
			});

			// open event media manager
			frame.on('open', function () {
				var attachment,
					selection = frame.state().get('selection'),
					id = set_input_val.value;

				attachment = wp.media.attachment(id);
				attachment.fetch();

				selection.add(attachment ? [ attachment ] : []);
			});

			// everything is set open the media manager
			frame.open();
		});
	});
})( jQuery );

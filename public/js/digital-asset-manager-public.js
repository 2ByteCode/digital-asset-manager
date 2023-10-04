(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(function ($) { // DOM ready and $ alias in scope

		/**
		 * Option dropdowns. Slide toggle
		 */
		$(".dam-dropdown").on('click', function () {
			$(this).toggleClass('is-active');
		});

		function ProgressCountdown(timeleft, bar, text) {
			return new Promise((resolve, reject) => {
				var countdownTimer = setInterval(() => {
					timeleft--;
		
					document.getElementById(bar).value = timeleft;
					document.getElementById(text).textContent = timeleft;
		
					if (timeleft <= 0) {
						clearInterval(countdownTimer);
						resolve(true);
					}
				}, 1000);
			});
		}

		$('.dam-single-download').on( 'click', function(){
			var assetid = $(this).data('assetid');
			jQuery.ajax({
                type: 'POST',
                dataType:"json",
                url: dam_ajax.ajaxurl,
                data: {
                    action: "dam_retrieve_asset_download_url",
                    assetid: assetid,
					dam_nonce: dam_ajax.dam_nonce,
                },
				beforeSend: function() {
					jQuery('body').append("<div class='gdpr-loading'></div>");
				},
                success: function (response) {
                    if (response.status == "error" ){
                        $('#error-msg').empty().append(response.message);
						$('#myErrorModal').modal('show');
                    } else if ("success" == response.status ) {
						$('#download-link').attr('href', response.downloads_link);
                        $('#drive-link').attr('href', response.drive_link);
                        
                        $('.begin-countdown').css('display', 'block');
                        $('#exampleModal').modal('show');
                        $('#exampleModal').removeClass('fade');
                        
						ProgressCountdown(10, 'pageBeginCountdown', 'pageBeginCountdownText').then(function() {
                            $('#download-link')[0].click();
                        });
                    }
                },
				complete: function() {
					jQuery('.gdpr-loading').remove();
				},
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError);
                }
            });
            return false;
	  } );

	});

})(jQuery);

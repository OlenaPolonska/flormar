( function( $ ) {
	$(document).on('ready', function() {
		let carousel = $('.flormar-carousel');
		let seats = $('.flormar-carousel-seat');

		let next = function(el) {
			if ( el.next().length > 0 ) {
				return el.next();
			} else {
				return seats.first();
			}
		};

		let prev = function(el) {
			if ( el.prev().length > 0 ) {
				return el.prev();
			} else {
				return seats.last();
			}
		};
				
		$('.flormar-carousel-controls').on( 'click', function(e) {
			let new_seat;
			let el = $('.is-ref').removeClass('is-ref');
			let lastIndex;

			if ( $(e.currentTarget).hasClass('next') ) {
				new_seat = next(el);
				carousel.removeClass('is-reversing');
				lastIndex = 1;
			} else {
				new_seat = prev(el);
				carousel.addClass('is-reversing');
				lastIndex = 2;
			}

			new_seat.addClass('is-ref').css('order', 1);

			for ( let i = lastIndex, j = lastIndex, ref = seats.length; 
				 ref >= lastIndex ? j <= ref : j >= ref; 
				 i = (ref >= lastIndex) ? ++j : --j ) {
				new_seat = next(new_seat).css('order', i);
			}
			
			carousel.removeClass('is-set');
			return setTimeout( (function() {
				return carousel.addClass('is-set');
			}), 50);
		});
	});

	
	$(document).on('pageinit', function(event){
		$(document).on( "swipeleft", function(e) {
			if ( $(e.target).closest('.flormar-carousel-container').length ) {
				$('.flormar-carousel-controls.prev').trigger('click');
			}
			return true;
		});
		$(document).on( "swiperight", function(e) {
			if ( $(e.target).closest('.flormar-carousel-container').length ) {
				$('.flormar-carousel-controls.next').trigger('click');
			}
			return true;
		});
	});
}( jQuery ) );
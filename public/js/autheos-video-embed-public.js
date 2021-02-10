function get_slick_slider() {
	jQuery('.autheos-thumbnail-wrap').slick({
		dots: false,
		slidesToShow: 3,
		infinite: false,
		lazyLoad: 'ondemand',
		responsive: [
		{
			breakpoint: 480,
			settings: {
			slidesToShow: 2,
		}
		}
		]
	});
}
(function( $ ) {
	'use strict';
	jQuery('.autheos-thumbnail-wrap').ready(function(){
		setTimeout(function(){
			let wrapper_slider = document.querySelector(".autheos-thumbnail-wrap");
			if(wrapper_slider){
				if(wrapper_slider.childElementCount >= 2 ){
					get_slick_slider();
				}
			}
		},2000);
	});
})( jQuery );

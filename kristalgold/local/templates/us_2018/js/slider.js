jQuery(document).ready(function($) {
  var slider = $('#lightSlider').lightSlider({
  	
  	item:1,
  	gallery:true,
  	loop:true,
  	thumbItem:4,
  	vertical:false,
	galleryMargin: 10,
        thumbMargin: 0,
        slideMargin: 0,
        speed: 600, //ms'
  	
  	onSliderLoad: function (el) {
  		
  	//	el.lightGallery({
  	//		selector: '#lightSlider .lslide'
  	//	})
  		
  	},
  }); 
  
  $('#button').click(function () {
  	slider.goToPrevSlide();	
  }); 
});


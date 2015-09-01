jQuery('select').addClass('chosen-rtl');

jQuery(window).load(function() {

/* Owl Carousel ( 1 to 6 Columns )
  http://www.owlcarousel.owlgraphic.com/demos/responsive.html
	============================== */
     
  // Carousel: 1 Column
  jQuery('.owl-carousel-1').owlCarousel({
  	rtl: 						true,
	  items: 					1,
	  margin: 				30,
	  loop: 					false,
	  navSpeed: 			600,
	  dragEndSpeed: 	600,
	  nav: 						true,
	  dots: 					false,
	  autoHeight: 		true,
	  navText: 				['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
  });
 
  // Carousel: 2 Columns
  jQuery('.owl-carousel-2').owlCarousel({
	  rtl: 						true,
	  items: 					2,
	  margin: 				30,
	  loop: 					true,
	  navSpeed: 			600,
	  dragEndSpeed: 	600,
	  nav: 						true,
	  dots: 					false,
	  autoHeight: 		true,
	  navText: 				['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
	  responsiveClass:true,
    responsive: 		{
							        0: {
							            items: 1,
							            nav: true
							        },
							        992: {
							            items: 2
							        }
							    }
  });
  
  // Carousel: 3 Columns
  jQuery('.owl-carousel-3').owlCarousel({
	  rtl: 						true,
	  items: 					3,
	  margin: 				30,
	  loop: 					true,
	  navSpeed: 			600,
	  dragEndSpeed: 	600,
	  nav: 						true,
	  dots: 					false,
	  autoHeight: 		true,
	  navText: 				['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
	  responsiveClass:true,
    responsive: 		{
							        0: {
							            items: 1,
							            nav: true
							        },
							        992: {
							            items: 3
							        }
							    }
  });
  
  // Carousel: 4 Columns
  jQuery('.owl-carousel-4').owlCarousel({
	  rtl: 						true,
	  items: 					4,
	  margin: 				30,
	  loop: 					true,
	  navSpeed: 			600,
	  dragEndSpeed: 	600,
	  nav: 						true,
	  dots: 					false,
	  autoHeight: 		true,
	  navText: 				['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
	  responsiveClass:true,
    responsive: 		{
							        0: {
							            items: 1,
							            nav: true
							        },
							        992: {
							            items: 4
							        }
							    }
  });
  
  // Carousel: 5 Columns
  jQuery('.owl-carousel-5').owlCarousel({
	  rtl: 						true,
	  items: 					5,
	  margin: 				30,
	  loop: 					true,
	  navSpeed: 			600,
	  dragEndSpeed: 	600,
	  nav: 						true,
	  dots: 					false,
	  autoHeight: 		true,
	  navText: 				['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
	  responsiveClass:true,
    responsive: 		{
							        0: {
							            items: 1,
							            nav: true
							        },
							        992: {
							            items: 5
							        }
							    }
  });
  
  // Carousel: 6 Columns
  jQuery('.owl-carousel-6').owlCarousel({
	  rtl: 						true,
	  items: 					6,
	  margin: 				30,
	  loop: 					true,
	  navSpeed: 			600,
	  dragEndSpeed: 	600,
	  nav: 						true,
	  dots: 					false,
	  autoHeight: 		true,
	  navText: 				['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
	  responsiveClass:true,
    responsive: 		{
							        0: {
							            items: 1,
							            nav: true
							        },
							        992: {
							            items: 6
							        }
							    }
  });
  
});
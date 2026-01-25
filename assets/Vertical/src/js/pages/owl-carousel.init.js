/**
* Theme:  Velonic - Responsive Bootstrap 4 Admin & Dashboard
* Author: Coderthemes
* File:   Owl-Carousel
*/


 //owl carousel
 $("#velonic-slider,#velonic-slider-2").owlCarousel({
  loop:true,  
  margin:10,
  slideSpeed : 300,
  paginationSpeed : 400,
  singleItem : true,
  autoPlay:true,
  responsive:{
    0:{
        items:1
    }
  }
});
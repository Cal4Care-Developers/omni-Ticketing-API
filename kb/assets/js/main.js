/* ========================================== 
  scrollTop() >= 200
  Should be equal the the height of the header
  ========================================== */
  
  $(window).scroll(function(){
      if ($(window).scrollTop() >= 200) {
          $('.navbar-brand').addClass('scroll-header');
      }
      else {
          $('.navbar-brand').removeClass('scroll-header');
      }
  });
  
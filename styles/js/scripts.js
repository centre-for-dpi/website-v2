document.addEventListener('DOMContentLoaded', function (event) {
  // Your code to run since DOM is loaded and ready

  $(window).scroll(function () {
    var scroll = $(window).scrollTop();
    if (scroll >= 350) {
      $('.header-announcement-desktop').addClass('d-none');
    } else {
      $('.header-announcement-desktop').removeClass('d-none');
    }
  });

  $('.stolo-video-close-button').click(function () {
    $('.stolo-video-play')[0].src = ' ';
  });

  $('.close').click(function () {
    $('.embed-responsive-item')[0].src += '?autoplay=0';
  });

  jQuery('.slick_slider_class').slick({
    infinite: true,
    arrows: false,
    dots: true,
    mobileFirst: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 780,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
        },
      },
      {
        breakpoint: 1024,
        settings: 'unslick',
      },
    ],
  });

  jQuery('.close').click(function () {
    jQuery('.embed-responsive-item')[0].src += '?autoplay=1';
  });

  jQuery('.ldMore').click(function () {
    if (jQuery('.moreBlogs').css('visibility') == 'hidden') {
      jQuery('.moreBlogs').addClass('moreBlogsDisp');
    }
  });
});

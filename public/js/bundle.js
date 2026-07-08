/******/ (() => { // webpackBootstrap
/******/ 	// The require scope
/******/ 	var __webpack_require__ = {};
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other entry modules.
(() => {
/*!***********************!*\
  !*** ./js/scripts.js ***!
  \***********************/
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
    responsive: [{
      breakpoint: 780,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1
      }
    }, {
      breakpoint: 1024,
      settings: 'unslick'
    }]
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
})();

// This entry needs to be wrapped in an IIFE because it needs to be in strict mode.
(() => {
"use strict";
/*!************************!*\
  !*** ./scss/main.scss ***!
  \************************/
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin

})();

/******/ })()
;
//# sourceMappingURL=bundle.js.map
<script type="text/javascript">
  $(document).ready(function () {
    // Initialize slick sliders
    const initSlickSlider = (selector, settings) => {
      if ($(selector).length) {
        $(selector).slick(settings);
      }
    };

    // Doc card slider
    initSlickSlider('.doc-card-slider', {
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 1,
      autoplay: true,
      autoplaySpeed: 4500,
      arrows: true,
      slidesToScroll: 1,
      fade: true,
      cssEase: 'linear',
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: true,
            arrows: false
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
          }
        }
      ]
    });

    // Feature card slider
    initSlickSlider('.feature-card-slider', {
      arrows: true,
      dots: false,
      infinite: false,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            dots: true,
            arrows: false
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false
          }
        }
      ]
    });

    // marketings Testimonials slider
    initSlickSlider('.marketing-testimonials-slider', {
      centerMode: true,
      centerPadding: '80px',
      autoplay: false,
      autoplaySpeed: 2000,
      arrows: false,
      slidesToShow: 3,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            arrows: false,
            centerMode: false,
            autoplay: false,
            slidesToShow: 2
          }
        },
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            centerMode: false,
            centerPadding: '40px',
            slidesToShow: 1
          }
        }
      ]
    })



    // Brokers logo slider
    initSlickSlider('.stolo-brokers', {
      arrows: false,
      dots: false,
      infinite: true,
      autoplay: true,
      autoplaySpeed: 2000,
      lazyLoad: 'ondemand',
      slidesToShow: 7,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            // dots: true,
            arrows: false
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: false
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: false
          }
        }
      ]
    });

    // Trade feedback slider
    initSlickSlider('.trade_feedback', {
      dots: true,
      infinite: false,
      speed: 300,
      slidesToShow: 1,
      arrows: true,
      slidesToScroll: 1
    });

    // YouTube close button behavior
    $('.close').click(function () {
      const iframe = $('.embed-responsive-item')[0];
      if (iframe) iframe.src += "?autoplay=0";
    });

    // Floating footer "Reach out to us" button
    const $floatingFooterCta = $('.floating-footer-cta');
    if ($floatingFooterCta.length) {
      $floatingFooterCta.on('click', function (e) {
        e.preventDefault();
        const footerEl = document.querySelector('footer.site-footer');
        if (footerEl && typeof footerEl.scrollIntoView === 'function') {
          footerEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      });

      const floatingBtn = $floatingFooterCta.get(0);
      const footerEl = document.querySelector('footer.site-footer');

      if (floatingBtn && footerEl) {
        const setHidden = (isHidden) => {
          floatingBtn.style.display = isHidden ? 'none' : 'inline-flex';
        };

        const updateVisibility = () => {
          const rect = footerEl.getBoundingClientRect();
          // When footer top enters the viewport (i.e., user is already at footer),
          // hide the floating button to avoid overlap.
          const isAtFooter = rect.top <= window.innerHeight;
          setHidden(isAtFooter);
        };

        let ticking = false;
        const onScroll = () => {
          if (ticking) return;
          ticking = true;
          window.requestAnimationFrame(() => {
            updateVisibility();
            ticking = false;
          });
        };

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', updateVisibility);
        updateVisibility();
      }
    }
  });
</script>
<!-- EOF -->



<!-- <script defer>
  jQuery(".close").click(function () {
    jQuery(".embed-responsive-item")[0].src += "?autoplay=0";
  });

  function playYT(vidid) {
    let html = "<iframe loading='lazy' class='embed-responsive-item' src='https://www.youtube.com/embed/YGVYEh9aMUw?si=P0UJXOTYpKYyGBMp' allowfullscreen></iframe>";
    document.getElementById('video_' + vidid).innerHTML = html;
  }
</script> -->

<script defer>
  jQuery(".close").click(function () {
    let iframe = jQuery(".embed-responsive-item");
    if (iframe.length) {
      let src = iframe.attr("src").split("?")[0]; // Remove previous query params
      iframe.attr("src", src + "?autoplay=0");
    }
  });

  function playYT(vidid) {
    let videoContainer = document.getElementById('video_' + vidid);
    if (videoContainer) {
      let html = `<iframe loading='lazy' class='embed-responsive-item'
                  src='https://www.youtube.com/embed/${vidid}?autoplay=1'
                  allowfullscreen></iframe>`;
      videoContainer.innerHTML = html;
    } else {
      console.error("Element with ID 'video_" + vidid + "' not found.");
    }
  }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const blogRightCorner = document.getElementById('recent-blog');
    const footer = document.querySelector('footer');
    const videoCta = document.getElementById('video-cta');

    if (!blogRightCorner || !footer || !videoCta) {
      console.log('Required elements not found.');
      return;
    }

    const offsetTop = 150;

    function updateLogic() {
      const screenWidth = window.innerWidth;

      if (screenWidth > 992) {
        window.addEventListener('scroll', handleScroll);
        handleScroll(); // Trigger on load in case user is already scrolled
      } else {
        blogRightCorner.style.position = 'static';
        window.removeEventListener('scroll', handleScroll);
      }
    }

    function handleScroll() {
      const scrollY = window.scrollY;
      const footerTop = footer.getBoundingClientRect().top + window.scrollY;
      const videoCtaTop = videoCta.getBoundingClientRect().top + window.scrollY;
      const blogHeight = blogRightCorner.offsetHeight;

      if (scrollY < videoCtaTop - blogHeight - 100) {
        // Allow fixed positioning before reaching the video CTA
        if (scrollY > offsetTop && scrollY + blogHeight + 100 < footerTop) {
          blogRightCorner.style.position = 'fixed';
          blogRightCorner.style.top = '100px';
        } else {
          blogRightCorner.style.position = 'static';
        }
      } else {
        // Make it static before the video CTA section
        blogRightCorner.style.position = 'static';
      }
    }

    updateLogic();
    window.addEventListener('resize', updateLogic);
  });
</script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
    const shareButton = document.getElementById("shareButton");

    if (!shareButton) {
      return;
    }

    shareButton.addEventListener("click", function (event) {
      event.preventDefault();

      if (navigator.share) {
        navigator.share({
          title: document.title,
          text: "Check out this article!",
          url: window.location.href
        })
          .then(() => {
            console.log("Successfully shared");
          })
          .catch((error) => {
            console.log("Error sharing:", error);
          });
      } else {
        alert("Sharing is not supported on this browser. Please copy the link manually.");
      }
    });
  });
</script>

<script type="text/javascript" defer>
  document.querySelectorAll('.mindset-card-div').forEach(card => {
    const video = card.querySelector('video');
    const playButton = card.querySelector('.play-button');

    playButton.addEventListener('click', () => {
      video.play();
      playButton.style.display = 'none'; // Hide play button after clicking
    });

    video.addEventListener('pause', () => {
      playButton.style.display = 'flex'; // Show play button when video is paused
    });

    video.addEventListener('click', () => {
      if (video.paused) {
        video.play();
        playButton.style.display = 'none';
      } else {
        video.pause();
      }
    });
  });
</script>

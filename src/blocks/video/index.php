<section class="video-block">
  <div class="video-block__wrapper">
      <div class="video-block__bg" style="background-image: url('<?php echo Helper::getImagePath('team/CDPI_Team.jpg'); ?>')"></div>
      
      <!-- Play Button -->
    <button class="video-block__play" data-video-url="https://www.youtube.com/embed/n0EFybZwC3g?si=MNh-VOOHK316PJWV" aria-label="Play video">
      <img src="<?php echo Helper::getImagePath('icons/video-play.svg'); ?>" alt="" class="video-block__play-icon" aria-hidden="true" />
    </button>
  </div>

  <!-- Video Modal -->
  <div class="video-modal" id="videoModal">
    <div class="video-modal__backdrop"></div>
    <div class="video-modal__content">
      <button class="video-modal__close" aria-label="Close video">
        <i class="fa-solid fa-xmark"></i>
      </button>
      <div class="video-modal__frame">
        <iframe src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      </div>
    </div>
  </div>
</section>

<style>
.video-block {
  position: relative;
}

.video-block__wrapper {
  position: relative;
  height: 40.25rem;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.video-block__bg {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-size: cover;
  background-position: center;
  background-color: #f7f4f4;
  filter: none;
}



.video-block__play {
  position: relative;
  z-index: 2;
  width: 8.75rem;
  height: 6.125rem;
  background: rgba(0, 0, 0, 0.30);
  border: none;
  border-radius: 0.375rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.video-block__play:hover {
  background: rgba(0, 0, 0, 0.40);
}

.video-block__play-icon {
  width: 1.05rem;
  height: 1.35rem;
  margin-left: 0.25rem;
}

/* Video Modal */
.video-modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 9999;
  display: none;
  align-items: center;
  justify-content: center;
}

.video-modal.active {
  display: flex;
}

.video-modal__backdrop {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.9);
}

.video-modal__content {
  position: relative;
  width: 90%;
  max-width: 1000px;
  z-index: 1;
}

.video-modal__close {
  position: absolute;
  top: -50px;
  right: 0;
  background: none;
  border: none;
  color: #ffffff;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 10px;
  transition: opacity 0.2s ease;
}

.video-modal__close:hover {
  opacity: 0.7;
}

.video-modal__frame {
  position: relative;
  padding-bottom: 56.25%;
  height: 0;
  overflow: hidden;
  border-radius: 12px;
}

.video-modal__frame iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

/* Responsive */
@media (max-width: 991px) {
  .video-block__wrapper {
    height: 16.0625rem;
  }
  
  .video-block__play {
    width: 9rem;
    height: 6.57425rem;
    border-radius: 0.3523125rem;
  }
  
  .video-block__play-icon {
    width: 1.076875rem;
    height: 1.434375rem;
  }
}

@media (max-width: 575px) {
  .video-block__wrapper {
    height: 16.0625rem;
  }
  
  .video-block__play {
    width: 9rem;
    height: 6.57425rem;
    border-radius: 0.3523125rem;
  }
  
  .video-block__play-icon {
    width: 1.076875rem;
    height: 1.434375rem;
  }
}
</style>

<script>
(function() {
  function initVideoBlock() {
    if (typeof jQuery === 'undefined') {
      setTimeout(initVideoBlock, 100);
      return;
    }
    
    jQuery(function($) {
      var $modal = $('#videoModal');
      var $iframe = $modal.find('iframe');
      
      // Open modal
      $('.video-block__play').on('click', function() {
        var videoUrl = $(this).data('video-url');
        $iframe.attr('src', videoUrl + '?autoplay=1');
        $modal.addClass('active');
        $('body').css('overflow', 'hidden');
      });
      
      // Close modal
      function closeModal() {
        $modal.removeClass('active');
        $iframe.attr('src', '');
        $('body').css('overflow', '');
      }
      
      $modal.find('.video-modal__close, .video-modal__backdrop').on('click', closeModal);
      
      $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && $modal.hasClass('active')) {
          closeModal();
        }
      });
    });
  }
  
  initVideoBlock();
})();
</script>

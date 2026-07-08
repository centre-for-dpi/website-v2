<section class="blog-single-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 text-center">
        <!-- Category Label -->
        <span class="blog-single-hero__label text-uppercase"><?php echo $post['categories'][0]['title'] ?? ''; ?></span>
        
        <!-- Title -->
        <h1 class="blog-single-hero__title"><?php echo $post['title']; ?></h1>
      </div>
    </div>
  </div>
  
</section>

<style>
.blog-single-hero {
  background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
  padding: 120px 0 80px;
  position: relative;
  overflow: hidden;
}

.blog-single-hero__label {
  font-size: 0.8rem;
  font-weight: 500;
  background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: 0.15em;
  display: block;
  margin-bottom: 32px;
}

.blog-single-hero__title {
  font-size: 3rem;
  font-weight: 400;
  color: #1a1a2e;
  line-height: 1.3;
  margin: 0;
  max-width: 900px;
  margin-left: auto;
  margin-right: auto;
}

.blog-single-hero__pattern {
  position: absolute;
  left: 0px;
  bottom: 0px;
  width: 300px;
  height: auto;
  opacity: 0.6;
  pointer-events: none;
}

.blog-single-hero__pattern img {
  width: 100%;
  height: auto;
}

/* Responsive */
@media (max-width: 991px) {
  .blog-single-hero {
    padding: 100px 0 60px;
  }
  
  .blog-single-hero__title {
    font-size: 2.25rem;
  }
  
  .blog-single-hero__pattern {
    width: 140px;
    left: -20px;
  }
}

@media (max-width: 767px) {
  .blog-single-hero {
    padding: 80px 0 50px;
  }
  
  .blog-single-hero__title {
    font-size: 1.75rem;
  }
  
  .blog-single-hero__pattern {
    display: none;
  }
}
</style>

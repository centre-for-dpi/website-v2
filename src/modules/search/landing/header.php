<section class="learnBanner text-center">
    <div class="learn-banner-img">
    <div class="banner-linear-gradient">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <img class="img-fluid learnHeadIcon"
                         src="<?php echo Helper::getImagePath('learn&explore.svg'); ?>" alt="Learn and explore" width="44" height="44">
                    <h1>Search Results<br>
                        <span>Results for "<?php echo esc_html(get_search_query()); ?>"</span>
                    </h1>                    
                    <?php get_template_part('searchform'); ?>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
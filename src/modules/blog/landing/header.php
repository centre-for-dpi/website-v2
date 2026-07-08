<?php 
if($current_term) {
    $sub_title = $current_term->name;
} else {
    $sub_title = 'Our latest insights and updates';
}
?>

<section class="learnBanner text-center">
    <div class="learn-banner-img">
        <div class="banner-linear-gradient">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <img class="img-fluid learnHeadIcon"
                            src="<?php echo Helper::getImagePath('learn&explore.svg'); ?>" alt="Learn and explore"
                            width="44" height="44">
                        <h1>Stolo Blog & Articles<br>
                            <span><?php echo $sub_title; ?></span>
                        </h1>
                        <p>Stay updated with the latest news, analysis, and insights.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
/**
 * Our Approach Block
 */

$approaches = [
    [
        'image' => 'images/our-work/advisory.png',
        'title' => 'Advisory',
        'description' => 'From strategy design to implementation support, our advisory ensures solutions are inclusive, sustainable, and aligned with global best practices.',
        'link' => '#'
    ],
    [
        'image' => 'images/our-work/daas.png',
        'title' => 'DaaS',
        'description' => 'Our DaaS model accelerates implementation, reduces costs, and enables countries to benefit from proven digital building blocks without starting from scratch.',
        'link' => '#'
    ],
    [
        'image' => 'images/our-work/approach.png',
        'title' => '+1 Approach',
        'description' => 'The +1 ensures that alongside technical solutions, countries gain the skills, institutions, and partnerships needed for long-term success.',
        'link' => '#'
    ],
];
?>

<section class="redlof-block work-our-approach py-5">
    <div class="container">
        <!-- Header -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <span class="approach-label text-uppercase mb-2 d-block">How We Help</span>
                <h2 class="approach-title mb-3">Our Approach</h2>
                <p class="approach-subtitle">Delivering solutions that create lasting impact for people, communities, and systems.</p>
            </div>
        </div>

        <!-- Cards -->
        <div class="row g-4">
            <?php foreach ($approaches as $approach) : ?>
                <div class="col-lg-4 col-md-6">
                    <div class="approach-card">
                        <div class="approach-image">
                            <img src="<?php echo Helper::getImagePath($approach['image']); ?>" alt="<?php echo esc_attr($approach['title']); ?>" loading="lazy">
                        </div>
                        <div class="approach-card-content">
                            <h3 class="approach-card-title text-uppercase mb-3"><?php echo esc_html($approach['title']); ?></h3>
                            <p class="approach-card-desc mb-3"><?php echo esc_html($approach['description']); ?></p>
                            <a href="<?php echo esc_url($approach['link']); ?>" class="approach-link">Learn more</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.work-our-approach {
    background-color: #fff;
}

.approach-label {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    color: #4f46e5;
}

.approach-title {
    font-size: 36px;
    font-weight: 600;
    color: #1a1a2e;
}

.approach-subtitle {
    font-size: 16px;
    color: #6c757d;
    max-width: 500px;
    margin: 0 auto;
}

.approach-card {
    height: 100%;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}

.approach-image {
    position: relative;
    overflow: hidden;
    height: 200px;
}

.approach-image::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    z-index: 1;
}

.approach-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.approach-card-content {
    padding: 24px;
}

.approach-card-title {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
    color: #1a1a2e;
}

.approach-card-desc {
    font-size: 14px;
    line-height: 1.7;
    color: #6c757d;
}

.approach-link {
    font-size: 14px;
    color: #4f46e5;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.approach-link:hover {
    color: #3730a3;
}
</style>

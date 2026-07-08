<?php
/**
 * Voices We Serve Block
 */

$voices = [
    ['icon' => 'sprout.svg', 'label' => 'Agriculture'],
    ['icon' => 'heart-pulse.svg', 'label' => 'Health'],
    ['icon' => 'graduation-cap.svg', 'label' => 'Education'],
    ['icon' => 'landmark.svg', 'label' => 'Govt. Services'],
    ['icon' => 'financial_service.svg', 'label' => 'Financial Services'],
    ['icon' => 'gender.svg', 'label' => 'Gender'],
    ['icon' => 'foundational_id.svg', 'label' => 'Foundational ID'],
    ['icon' => 'social_benefits.svg', 'label' => 'Social Benefits'],
];

$icon_path = get_template_directory_uri() . '/public/img/icons/';
?>

<section class="redlof-block voices-we-serve py-5">
    <div class="container">
        <div class="row align-items-start">
            <!-- Left Content -->
            <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                <h2 class="voices-title text-uppercase mb-3">Voices We Serve</h2>
                <p class="voices-description text-muted">Stories, insights, and experiences from the people and communities we've supported.</p>
            </div>

            <!-- Right Grid -->
            <div class="col-lg-9 col-md-8">
                <div class="row g-3">
                    <?php foreach ($voices as $voice) : ?>
                        <div class="col-md-6">
                            <a href="#" class="voice-card d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="voice-icon">
                                        <img src="<?php echo esc_url($icon_path . $voice['icon']); ?>" alt="<?php echo esc_attr($voice['label']); ?>">
                                    </span>
                                    <span class="voice-label"><?php echo esc_html($voice['label']); ?></span>
                                </div>
                                <span class="voice-arrow">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14M12 5l7 7-7 7"/>
                                    </svg>
                                </span>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

     <!-- Divider -->
     <div class="voices-we-serve__divider mt-5"></div>
</section>

<style>
.voices-we-serve {
    background-color: #fff;
}

.voices-title {
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 1px;
    color: #1a1a2e;
}

.voices-description {
    font-size: 14px;
    line-height: 1.6;
    color: #6c757d;
}

.voice-card {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    text-decoration: none;
    color: #1a1a2e;
    transition: all 0.2s ease;
    background: #fff;
    height: 105px;
}

.voice-card:hover {
    border-color: #4f46e5;
    box-shadow: 0 2px 8px rgba(79, 70, 229, 0.1);
    color: #1a1a2e;
}

.voice-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.voice-icon img {
    width: 22px;
    height: 22px;
    object-fit: contain;
}

.voice-label {
    font-size: 17px;
    font-weight: 400;
}

.voice-arrow {
    color: #9ca3af;
    display: flex;
    align-items: center;
}

.voice-card:hover .voice-arrow {
    color: #4f46e5;
}

.voices-we-serve__divider {
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
}
</style>

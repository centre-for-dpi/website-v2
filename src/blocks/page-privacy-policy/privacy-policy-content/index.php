<?php
/**
 * Privacy Policy Content Block
 */

$sections = [
    [
        'title' => 'Information We Collect',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
    [
        'title' => 'How We Use Your Information',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
    [
        'title' => 'Sharing of Information',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
    [
        'title' => 'Cookies and Analytics',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
    [
        'title' => 'Data Security',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
    [
        'title' => 'Your Rights',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
    [
        'title' => 'Third-Party Links',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
    [
        'title' => 'Changes to This Privacy Policy',
        'intro' => 'We may collect the following types of information:',
        'items' => [
            'Personal Information you voluntarily provide (e.g., name, email address, organization, role) when you sign up for newsletters, events, or resources.',
            'Usage Data such as your IP address, browser type, device information, pages visited, and interaction patterns.',
            'Cookies and Tracking Technologies to enhance your browsing experience and analyze website performance.',
        ]
    ],
];
?>

<section class="redlof-block privacy-content py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Intro Paragraph -->
                <div class="privacy-content__intro mb-5">
                    <p>The Centre for Digital Public Infrastructure ("CDPI," "we," "our," or "us") respects your privacy and is committed to protecting your personal information.</p>
                    <p>This Privacy Policy explains how we collect, use, store, and protect information when you visit our website or engage with our services.</p>
                </div>

                <!-- Sections -->
                <?php foreach ($sections as $index => $section) : ?>
                    <div class="privacy-content__section mb-5">
                        <h2 class="privacy-content__title text-uppercase"><?php echo ($index + 1) . '. ' . esc_html($section['title']); ?></h2>
                        <p class="privacy-content__text"><?php echo esc_html($section['intro']); ?></p>
                        <ul class="privacy-content__list">
                            <?php foreach ($section['items'] as $item) : ?>
                                <li><?php echo esc_html($item); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<style>
.privacy-content {
    background-color: #fff;
}

.privacy-content__intro p {
    font-size: 15px;
    line-height: 1.8;
    color: #4b5563;
    margin-bottom: 12px;
}

.privacy-content__intro p:last-child {
    margin-bottom: 0;
}

.privacy-content__section {
    padding-bottom: 32px;
    border-bottom: 1px solid #e5e7eb;
}

.privacy-content__section:last-child {
    border-bottom: none;
}

.privacy-content__title {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a2e;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
}

.privacy-content__text {
    font-size: 15px;
    line-height: 1.7;
    color: #4b5563;
    margin-bottom: 16px;
}

.privacy-content__list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.privacy-content__list li {
    position: relative;
    font-size: 14px;
    line-height: 1.7;
    color: #6b7280;
    padding-left: 16px;
    margin-bottom: 12px;
}

.privacy-content__list li::before {
    content: '•';
    position: absolute;
    left: 0;
    color: #9ca3af;
}

.privacy-content__list li:last-child {
    margin-bottom: 0;
}

/* Responsive */
@media (max-width: 767px) {
    .privacy-content__title {
        font-size: 14px;
    }

    .privacy-content__text,
    .privacy-content__intro p {
        font-size: 14px;
    }

    .privacy-content__list li {
        font-size: 13px;
    }
}
</style>

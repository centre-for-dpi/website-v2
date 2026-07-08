<section class="redlof-block key-resources">
    <div class="container-fluid">
        <!-- Key Resources & Publications section -->
        <div class="key-resources__section">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h3 class="key-resources__category text-uppercase">Key Resources &<br>Publications</h3>
                </div>
                <div class="col">
                    <!-- Video Card 1 -->
                    <div class="key-resources__card mb-4">
                        <div class="key-resources__card__content">
                            <span class="fw-normal key-resources__card__date text-uppercase">Jul 2025</span>
                            <h4 class="fw-normal key-resources__card__title">Digital Public Infrastructure and
                                Digital Financial
                                Services -
                                CCAF</h4>
                            <span class="fw-light key-resources__links">Download Publication</span>
                        </div>
                        <div class="key-resources__card__thumbnail">
                            <img src="<?php echo Helper::getImagePath('temp/video-thumbnail.png'); ?>"
                                alt="Nandan Nilekani" loading="lazy" />
                            <button class="key-resources__card__play" aria-label="Play video">
                                <i class="fa-solid fa-play"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Video Card 2 -->
                    <div class="key-resources__card">
                        <div class="key-resources__card__content">
                            <span class=" fw-normal key-resources__card__date text-uppercase">Jun 2025</span>
                            <h4 class="fw-normal key-resources__card__title">Digital Credentials | DaaS: DPI as a
                                Packaged
                                Solution</h4>
                            <span class="fw-light key-resources__links">Download Publication</span>

                        </div>
                        <div class="key-resources__card__thumbnail">
                            <img src="<?php echo Helper::getImagePath('temp/video-thumbnail2.png'); ?>"
                                alt="Nandan Nilekani" loading="lazy" />
                            <button class="key-resources__card__play" aria-label="Play video">
                                <i class="fa-solid fa-play"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="key-resources__section-divider mt-5"></div>
</section>


<style>
    .key-resources {
        background-color: #ffffff;
    }

    .key-resources__divider-top {
        margin-bottom: 20px;
    }

    .key-resources__divider-top,
    .key-resources__divider-bottom {
        height: 1px;
        background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    }


    /* Section */
    .key-resources__section {
        padding: 40px 0px;
    }

    .key-resources__section-divider {
        height: 1px;
        background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    }


    .key-resources__category {
        color: #1a1a2e;
        line-height: 1.5;
        font-size: 20px;
        line-height: 170%;
        letter-spacing: 8%;
    }


    /* Video Card */
    .key-resources__card {
        display: flex;
        overflow: hidden;
        height: 272px;
        gap: 24px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }

    .key-resources__card__content {
        flex: 1;
        padding: 40px 0px 48px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .key-resources__card__date {
        font-weight: 500;
        font-size: 12px;
        line-height: 170%;
        letter-spacing: 1.2px;
        background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
        background-clip: text;
        color: transparent;
    }


    .key-resources__card__title {
        color: #1a1a2e;
        line-height: 1.4px;
        margin: 0;
        font-weight: 300;
        font-size: 24px;
        line-height: 160%;
        letter-spacing: 2%;
    }


    .key-resources__card__thumbnail {
        flex: 0 0 280px;
        position: relative;
        height: 100%;
        overflow: hidden;
        border-radius: 0 12px 12px 0;
    }

    .key-resources__card__thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }


    .key-resources__card__play {
        position: absolute;
        bottom: 0px;
        right: 0px;
        background-color: #0F0F0F;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 70px;
        width: 70px;
    }


    .key-resources__card__play i {
        color: #ffffff;
    }


    .key-resources__card__play:hover {
        background-color: #4948E1;
    }

    .key-resources__links {
        cursor: pointer;
        color: #6564DB;
        font-size: 17px;
        line-height: 170%;
        letter-spacing: 2%;
        text-decoration: underline;
        text-underline-offset: 5px;
    }

    .key-resources__links:hover {
        color: #1C1AE4;
    }

    @media (width<=425px) {
        .key-resources {
            padding: 0px 5px;
        }

        .key-resources__section {
            padding: 72px 0px;
        }

        .key-resources__card {
            display: flex;
            flex-direction: column-reverse;
            justify-content: space-between;
            gap: 24px;
            border-radius: 10px;
            border-width: 1px;
            width: 100%;
            height: 465px;
            padding-bottom: 32px;
        }

        .key-resources__card__thumbnail {
            width: 100%;
            height: 317px;
        }

        .key-resources__card__thumbnail>img {
            max-width: 100%;
            height: auto;
            object-fit: cover;
        }

        .key-resources__card__date {
            font-weight: 400;
        }
         .key-resources__card__content {
            justify-content: space-around;
            align-items: flex-start;
            gap: 16px;
            padding: 10px 20px;
        }

        
    }

    @media (width<=768px) {
        .key-resources__card__title {
            font-weight: 400;
            font-size: 17px;
            line-height: 149%;
            letter-spacing: 2%;
        }
       
    }
</style>
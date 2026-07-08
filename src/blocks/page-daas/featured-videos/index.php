<section class="redlof-block featured-videos">
    <div class="container-fluid">
        <!-- Featured Videos on DaaS Section -->
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h3 class="featured-videos__category text-uppercase">Featured Videos on<br>DAAS</h3>
                <div>
                    <a href="/watch" class="btn featured-videos__btn">
                        Watch all DaaS videos
                    </a>
                </div>
            </div>
            <div class="col">
                <!-- Video Card 1 -->
                <div class="featured-videos__card mb-4">
                    <div class="featured-videos__card__content">
                        <span class="fw-normal featured-videos__card__author text-uppercase">PRAMOD VARMA</span>
                        <h4 class="fw-normal featured-videos__card__title">Digital Authentication | DaaS:
                            DPI as a Packaged
                            Solution</h4>
                    </div>
                    <div class="featured-videos__card__thumbnail">
                        <img src="<?php echo Helper::getImagePath('temp/video-thumbnail.png'); ?>" alt="Pramod Varma"
                            loading="lazy" />
                        <button class="featured-videos__card__play" aria-label="Play video">
                            <i class="fa-solid fa-play"></i>
                        </button>
                    </div>
                </div>

                <!-- Video Card 2 -->
                <div class="featured-videos__card">
                    <div class="featured-videos__card__content">
                        <span class="fw-normal featured-videos__card__author text-uppercase">PRAMOD VARMA</span>
                        <h4 class="fw-normal featured-videos__card__title">Digital Credentials | DaaS: DPI
                            as a
                            Packaged Solution
                        </h4>
                    </div>
                    <div class="featured-videos__card__thumbnail">
                        <img src="<?php echo Helper::getImagePath(Path: 'temp/video-thumbnail2.png'); ?>"
                            alt="Pramod Varma" loading="lazy" />
                        <button class="featured-videos__card__play" aria-label="Play video">
                            <i class="fa-solid fa-play"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="featured-videos__section-divider mt-5"></div>

</section>

<style>
    .featured-videos {
        background-color: #ffffff;
    }

    .featured-videos__divider-top {
        margin-bottom: 20px;
    }

    .featured-videos__divider-top,
    .featured-videos__divider-bottom {
        height: 1px;
        background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    }

    .featured-videos__section-divider {
        height: 1px;
        background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    }

    .featured-videos__category {
        color: #1a1a2e;
        line-height: 1.5;
        line-height: 170%;
        letter-spacing: 8%;
    }

    .featured-videos__card {
        display: flex;
        overflow: hidden;
        height: 272px;
        gap: 24px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }

    .featured-videos__card__content {
        flex: 1;
        padding: 40px 0px 48px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .featured-videos__card__author {
        font-weight: 500;
        font-size: 12px;
        line-height: 170%;
        letter-spacing: 1.2px;
        background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
        background-clip: text;
        color: transparent;
    }

    .featured-videos__card__title {
        color: #1a1a2e;
        line-height: 1.4px;
        margin: 0;
        font-weight: 300;
        font-size: 24px;
        line-height: 160%;
        letter-spacing: 2%;
    }

    .featured-videos__card__thumbnail {
        flex: 0 0 266px;
        position: relative;
        overflow: hidden;
        border-radius: 0 12px 12px 0;
    }

    .featured-videos__card__thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .featured-videos__card__play {
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

    .featured-videos__card__play i {
        color: #ffffff;
    }

    .featured-videos__card__play:hover {
        background-color: #4948E1;
    }

    .featured-videos__btn {
        font-size: 0.9rem;
        font-weight: 500;
        padding: 14px 24px;
        border-radius: 8px;
        transition: all 0.2s ease;
        white-space: nowrap;
        margin-top: 60px;
        background-color: #4f46e5;
        border: 1px solid #4f46e5;
        color: #ffffff;
    }

    .featured-videos__btn:hover {
        color: white;
        background-color: #1C1AE4;
    }

    @media (width<=425px) {
        .featured-videos {
            padding: 72px 5px 0px;
        }

        .featured-videos__btn {
            display: none;
        }

        .featured-videos__card {
            display: flex;
            flex-direction: column-reverse;
            gap: 32px;
            border-radius: 10px;
            border-width: 1px;
            width: 100%;
            height: 465px;
            padding-bottom: 32px;
        }

        .featured-videos__card__content {
            justify-content: space-around;
            align-items: flex-start;
            padding: 10px 20px;
        }

        .featured-videos__card__thumbnail>img {
            max-width: 100%;
            height: auto;
            object-fit: cover;
        }

        .featured-videos__card__author {
            font-weight: 400;
            font-size: 11px;
        }
    }

    @media (width<=768px) {
        .featured-videos__card__title {
            font-weight: 400;
            font-size: 17px;
            line-height: 149%;
            letter-spacing: 2%;
        }
    }
</style>
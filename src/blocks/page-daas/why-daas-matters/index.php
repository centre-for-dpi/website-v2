<section class="why-dass-matters redlof-block position-relative">

    <!-- Background Imgae -->
    <div class="why-dass-matters__bg">
        <img src=" <?php echo Helper::getImagePath('images/daas-why-daas-matters-bg.png'); ?>"
            alt="Why-daas-matters-background-image" loading="lazy">
    </div>

    <!-- Content -->
    <div class=" why-dass-matters__content text-center d-flex justify-content-center align-items-center">
        <!-- Left Content -->
        <h2 class="col-4 text-start align-self-start text-uppercase">Why DaaS <br> Matters</h2>

        <!-- Right Content -->
        <div class="col why-dass-matters__right__content">

            <!-- Card Containers -->
            <div class="why-dass-matters__cards row">

                <!-- Card 1 -->
                <div class="col why-dass-matters__single__card">
                    <img class="row g-0" src=" <?php echo Helper::getImagePath('patterns/circle-gauge.svg'); ?>"
                        alt="Quickness" loading="lazy">
                    <h3 class="row g-0 text-start">Quickness</h3>
                    <p class="row g-0 text-start">Implementation can be completed in just a few
                        months, rather than taking years.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="col why-dass-matters__single__card">
                    <img class="row g-0" src=" <?php echo Helper::getImagePath('patterns/circle-gauge.svg'); ?>"
                        alt="Rapidness" loading="lazy">
                    <h3 class="row g-0 text-start">Rapidness</h3>
                    <p class="row g-0 text-start">Setup can occur within a few months, not stretched over years.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="col why-dass-matters__single__card">
                    <img class="row g-0" src=" <?php echo Helper::getImagePath('patterns/circle-gauge.svg'); ?>"
                        alt="Swiftness" loading="lazy">
                    <h3 class="row g-0 text-start">Swiftness</h3>
                    <p class="row g-0 text-start">Rollout can be achieved in a few months, instead of years.
                    </p>
                </div>

                <!-- Card 4 -->
                <div class="col why-dass-matters__single__card">
                    <img class="row g-0" src=" <?php echo Helper::getImagePath('patterns/circle-gauge.svg'); ?>"
                        alt="Swiftness" loading="lazy">
                    <h3 class="row g-0 text-start">Pace</h3>
                    <p class="row g-0 text-start">Launch can happen in just a few months, not years.
                    </p>
                </div>

                <!-- Card 5 -->
                <div class="col why-dass-matters__single__card">
                    <img class="row g-0" src=" <?php echo Helper::getImagePath('patterns/circle-gauge.svg'); ?>"
                        alt="Swiftness" loading="lazy">
                    <h3 class="row g-0 text-start">Velocity</h3>
                    <p class="row g-0 text-start">Execution can be done in a matter of months, not years.
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>

<style>
    .why-dass-matters__bg {
        position: absolute;
        z-index: 0;
        inset: 0;
        width: 100%;
        height: 100%;
    }

    .why-dass-matters__bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .why-dass-matters__content {
        position: relative;
        z-index: 1;
        color: white;
        gap: 50px;
        max-width: 1440px;
        padding: 160px 376px 160px 90px;
    }

    .why-dass-matters__content>h2 {
        font-weight: 500;
        font-size: 20px;
        line-height: 170%;
        letter-spacing: 8%;
        width: auto;
    }

    .why-dass-matters__right__content {
        max-width: 704px;
        gap: 7px;

    }

    .why-dass-matters__cards {
        gap: 22px;
    }


    .why-dass-matters__single__card {
        max-width: 220px;
        min-width: 220px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 24px;
    }

    .why-dass-matters__single__card>img {
        width: 40px;
        height: 40px;
    }

    .why-dass-matters__single__card>h3 {
        font-weight: 600;
        font-size: 17px;
        line-height: 170%;
        letter-spacing: 2%;
    }

    .why-dass-matters__single__card>p {
        font-weight: 300;
        font-size: 15px;
        line-height: 170%;
        letter-spacing: 2%;
    }

    @media (width<=425px) {
        .why-dass-matters__content {
            display: flex;
            flex-direction: column;
            padding: 74px 24px;
        }

        .why-dass-matters__single__card {
            max-width: 220px;
            min-width: 220px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 24px;
        }
    }

    @media (width<=1440px) {
        .why-dass-matters__content {
            padding: 74px 0px;
            gap: 50px;
        }
    }
</style>
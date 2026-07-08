<section class="how-it-works redlof-block text-center d-flex justify-content-center align-items-center">


    <!-- Left Content -->
    <h2 class="col text-start align-self-start text-uppercase">How It Works</h2>

    <!-- Right Content -->
    <div class="col how-it-works__content">
        <p class="how-it-works__subtext text-start">Instead of building systems from scratch, DaaS helps countries
            upgrade their
            existing infrastructure with open-source components, pre-trained service providers, and ready-to-use policy
            and programme artefacts.</p>

        <!-- Card Containers -->

        <div class="how-it-works__cards row g-0">

            <!-- Card 1 -->
            <div class="col how-it-works__single__card">
                <img class="row g-0" src=" <?php echo Helper::getImagePath('images/daas-block.svg'); ?>"
                    alt="Full Package" loading="lazy">
                <h3 class="row g-0 text-start">1.</h3>
                <p class="row g-0 text-start">Choose your DPI bloc<span>e.g., authentication, digital credentials, ID
                        &ndash; haccount mapper.</span>
                </p>
            </div>


            <!-- Card 2 -->
            <div class="col how-it-works__single__card">
                <img class="row g-0" src=" <?php echo Helper::getImagePath('images/daas-package.svg'); ?>"
                    alt="Full Package" loading="lazy">
                <h3 class="row g-0 text-start">2.</h3>
                <p class="row g-0 text-start">Access the package <span>via CDPI, Digital Public Good (DPG) owners,
                        and certified service providers.</span>
                </p>
            </div>


            <!-- Card 3 -->
            <div class="col how-it-works__single__card">
                <img class="row g-0" src=" <?php echo Helper::getImagePath('images/daas-deploy.svg'); ?>"
                    alt="Full Package" loading="lazy">
                <h3 class="row g-0 text-start">3.</h3>
                <p class="row g-0 text-start">Deploy rapidly<span>pre-packaged kits & reusable artefacts help
                        countries configure, rollout, and scale.</span>
                </p>

            </div>
        </div>
        <div class="text-md-start text-center">
            <a href="https://docs.cdpi.dev/initiatives/dpi-as-a-packaged-solution-daas" target="blank"
                class="row how-it-works__cta btn justify-content-start" role="button"
                aria-label="Go to home page">Download Playbook </a>
        </div>

    </div>
</section>


<style>
    .how-it-works {
        padding: 88px 0px 90px;
    }


    .how-it-works>h2 {
        font-weight: 500;
        font-size: 20px;
        line-height: 170%;
        letter-spacing: 8%;
        max-width: 270px;
    }

    .how-it-works__content {
        max-width: 720px;
        gap: 7px;
    }


    .how-it-works__subtext {
        color: #5E6979;
        font-weight: 400;
        font-size: 17px;
        line-height: 170%;
        letter-spacing: 2%;
    }


    .how-it-works__cards {
        gap: 22px;
        margin-top: 80px;
        margin-bottom: 75px;
    }


    .how-it-works__single__card {
        max-width: 220px;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        gap: 8px;
    }

    .how-it-works__single__card>img {
        background: #FDFCFF;
        width: 220px;
        height: 116px;
        padding-top: 38px;
        padding-right: 90px;
        padding-bottom: 38px;
        padding-left: 90px;
        border-width: 1px;
        border-radius: 10px;
        border: 1px solid #D6E1F1;
    }

    .how-it-works__single__card>h3 {
        font-weight: 500;
        font-size: 20px;
        line-height: 170%;
        letter-spacing: 8%;
    }

    .how-it-works__single__card>p {
        font-weight: 600;
        font-size: 17px;
        line-height: 170%;
        letter-spacing: 2%;
    }

    .how-it-works__single__card>p>span {
        font-weight: 400;
        font-size: 16px;
        line-height: 170%;
        letter-spacing: 2%;
    }

    .how-it-works__cta {
        max-width: 239px;
        padding: 16px 24px;
        border-width: 1px;
        border-radius: 7px;
        cursor: pointer;
        color: white;
        background-color: #4B4AEA;
        font-weight: 400;
        font-size: 14px;
        line-height: 160%;
        letter-spacing: 1%;
    }

    .how-it-works__cta:hover {
        color: white;
        background-color: #1C1AE4;
    }


    @media (width<=425px) {

        .how-it-works__cards {
            gap: 22px;
            margin: 56px 0px;
            padding: 0px 61px;
        }
    }

    @media (width<=768px) {
        .how-it-works {
            padding: 72px 24px;
            display: flex;
            flex-direction: column;
        }

        .how-it-works__cards {
            justify-content: center;
        }
    }
</style>
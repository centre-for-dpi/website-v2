<?php
/**
 * DaaS - Approach section (renamed from comprehensive-approch).
 */
?>

<section
        class="daas-approach redlof-block text-center d-flex flex-column justify-content-center align-items-center border-bottom">

        <!-- Header Content -->
        <div class="daas-approach__pattern">
                <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-3.svg'); ?>" alt="" loading="lazy">
        </div>
        <p class="daas-approach__subheading text-uppercase">Approach</p>
        <h2 class="daas-approach__title">The DaaS way</h2>
  <p class="daas-approach__p1">
        Using robust open-source DPGs with pre-qualified technology service providers, and ready-to-deploy program and policy packages helps governments avoid design errors and reduce deployment time to weeks! This is the DPI-as-packaged Solution (DaaS) way!  
        <span class="daas-approach__p1-note">Co-created and supported by Co-Develop Foundation.</span>
        </p>

        <!-- Card Containers -->

        <div class="daas-approach__cards row">


                <!-- Card 1 -->

                <div class="col daas-approach__single__card">
                        <img class="daas-approach__image" src=" <?php echo Helper::getImagePath('images/daas-approach1.jpeg'); ?>"
                                alt="Technical Scope templates" loading="lazy">
                        <h3 class="text-uppercase">Technical Scope templates</h3>
                        <p>Technical specifications in line with DPI principles and best practices, avoiding cloud / data / vendor lock-ins

                </div>

                <!-- Card 2 -->

                <div class="col daas-approach__single__card">
                        <img class="daas-approach__image" src=" <?php echo Helper::getImagePath('images/daas-approach2.jpeg'); ?>"
                                alt="Legal Arrangements" loading="lazy">
                        <h3 class="text-uppercase">Legal Arrangements</h3>
                        <p> Pre-negotiated legal contracts protecting sovereignty, aligned to global DPI safeguards

                </div>

                <!-- Card 3 -->

                <div class="col daas-approach__single__card">
                        <img class="daas-approach__image" src=" <?php echo Helper::getImagePath('images/daas-approach3.jpeg'); ?>"
                                alt="Program and Adoption playbooks" loading="lazy">
                        <h3 class="text-uppercase">Program and Adoption playbooks</h3>
                        <p>Uncovering use-cases, go-to-market plan, governance approaches to ensure citizens see value right from day&nbsp;1 
                </div>

        </div>
</section>

<style>
        .daas-approach {
                padding: 120px 92px 152px;
        }

        .daas-section-separator {
                height: 1px;
                width: 100%;
                background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
                border: none;
                margin: 0;
        }

        .daas-approach__subheading {
                background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
                -webkit-background-clip: text;
                color: transparent;
                -webkit-background-clip: text;
                /* Ensures support for older or specific Safari versions*/
                background-clip: text;
                -webkit-text-fill-color: transparent;
                color: transparent;
                font-family: "Outfit", system-ui, sans-serif;
                font-weight: 400;
                font-size: 12px;
                line-height: 170%;
                letter-spacing: 1.2px;
                text-align: center;
                text-transform: uppercase;
                margin-bottom: 20px;
        }

        .daas-approach__pattern {
                margin-bottom: 1.25rem;
        }

        .daas-approach__pattern img {
                display: block;
                max-width: 100%;
                height: auto;
        }

        .daas-approach__title {
                font-family: "Outfit", system-ui, sans-serif;
                font-weight: 400;
                font-size: 40px;
                line-height: 130%;
                letter-spacing: -0.02em;
                text-align: center;
                color: #0F0F0F;
                margin-bottom: 20px;
        }

        .daas-approach__p1 {
                color: #5E6979;
                font-weight: 400;
                font-size: 15px;
                line-height: 170%;
                letter-spacing: 2%;
                text-align: center;
                max-width: 617px;
                margin-left: auto;
                margin-right: auto;
                margin-bottom: 80px;
        }

        .daas-approach__p1-note {
                display: block;
                margin-top: 30px;
        }

        .daas-approach__cards {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                gap: 42px;
                margin-left: 0;
                margin-right: 0;
        }

        .daas-approach__cards .col {
                padding-left: 0;
                padding-right: 0;
        }

        .daas-approach__single__card {
                width: 100%;
                max-width: 24.5rem;    /* 392px */
                height: 26.875rem;     /* 430px */
                display: flex;
                flex-direction: column;
                text-align: left;
                border: 1px solid #E3E0E0;
                border-radius: 10px;
                overflow: hidden;
                background-color: #FFFFFF;
        }

        .daas-approach__single__card h3 {
                font-family: "Outfit", system-ui, sans-serif;
                font-weight: 600;
                font-size: 20px;
                line-height: 170%;
                letter-spacing: 8%;
                text-transform: uppercase;
                color: #0F0F0F;
                margin-top: 40px;
                margin-bottom: 20px;
                padding-left: 32px;
                padding-right: 48px;
        }


        .daas-approach__single__card p {
                color: #5E6979;
                font-weight: 400;
                font-size: 15px;
                line-height: 170%;
                letter-spacing: 2%;
                padding-left: 32px;
                padding-right: 48px;
                margin-bottom: 32px;
        }

        .daas-approach__image {
                width: 100%;
                height: 11.25rem; /* 180px */
                min-height: 11.25rem;
                max-height: 11.25rem;
                display: block;
                object-fit: cover;
                object-position: center;
                flex-shrink: 0;
        }


        .daas-approach__p2 {
                color: #5E6979;
                font-weight: 400;
                font-size: 15px;
                line-height: 170%;
                letter-spacing: 2%;
                text-align: center;
                margin-top: 69px;
        }

        .daas-approach__p2 span {
                font-weight: 600;
        }


        @media screen and (width<=1024px) {
                .daas-approach {
                        padding: 66px 54px 102px;
                }

                .daas-approach__cards {
                        flex-direction: column;
                        align-items: center;
                }

                .daas-approach__single__card img {
                        max-width: 100%;
                }

                .daas-approach__single__card {
                        max-width: 100%;
                }
        }

        @media screen and (width<=768px) {

                .daas-approach {
                        padding: 60px 1.5rem 66px;
                        justify-content: start;
                        /* margin-bottom: 43px; */
                }

                .daas-approach__cards {
                        gap: 48px;
                }

                .daas-approach__title {
                        font-size: 28px;
                }

                .daas-approach__p2 {
                        text-align: left;
                }
        }

        @media (width<=425px) {

                .daas-approach {
                        padding: 49px 1.5rem 62px;
                }

                .daas-approach__title {
                        font-size: 24px;
                }
        }
</style>

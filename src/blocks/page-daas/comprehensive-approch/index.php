<section
        class="comprehensive-approach redlof-block text-center d-flex flex-column justify-content-center align-items-center border-bottom">

        <!-- Header Content -->
        <p class="comprehensive-approach__subheading text-uppercase">comprehensive approach</p>
        <h2>DaaS at a Glance</h2>
        <p class="comprehensive-approach__p1">DaaS creates multiple pathways for countries to accelerate DPI adoption
                below.</p>

        <!-- Card Containers -->

        <div class="comprehensive-approach__cards row">

                <!-- Card 1 -->

                <div class="col comprehensive-approach__single__card">
                        <img class="row" src=" <?php echo Helper::getImagePath('images/daas-approach1.png'); ?>"
                                alt="Full Package" loading="lazy">
                        <h3 class="text-uppercase">Full Package</h3>
                        <p>Pre-trained service providers + open-source DPI components + policy and
                                programme complete kits.
                </div>

                <!-- Card 2 -->

                <div class="col comprehensive-approach__single__card">
                        <img class="row" src=" <?php echo Helper::getImagePath('images/daas-approach2.png'); ?>"
                                alt="Full Package" loading="lazy">
                        <h3 class="text-uppercase">Artefacts Only</h3>
                        <p>Countries can reuse legal templates, costing models, or technical modules to
                                upgrade existing systems with their own vendors.
                </div>

                <!-- Card 3 -->

                <div class="col comprehensive-approach__single__card">
                        <img class="row" src=" <?php echo Helper::getImagePath('images/daas-approach3.png'); ?>"
                                alt="Full Package" loading="lazy">
                        <h3 class="text-uppercase">Service Provider Only</h3>
                        <p>Pre-trained service providers + open-source DPI components + policy and
                                programme complete kits.

                </div>
        </div>
        <p class="comprehensive-approach__p2">
                Unlike pilots or proof-of-concepts, DaaS is <span>designed for population scale from day one.</span> It
                focuses on minimalist DPI blocks (like authentication or verifiable credentials) that integrate with
                existing systems and can reach full coverage in months.
        </p>
</section>

<style>
        .comprehensive-approach {
                padding: 125px 92px 157px;
        }

        .comprehensive-approach__subheading {
                background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
                -webkit-background-clip: text;
                color: transparent;
                -webkit-background-clip: text;
                /* Ensures support for older or specific Safari versions*/
                background-clip: text;
                -webkit-text-fill-color: transparent;
                color: transparent;
                font-size: 12px;
                font-weight: 400;
                line-height: 170%;
                letter-spacing: 1.2px;
        }

        .comprehensive-approach>h2 {
                font-size: 48px;
                text-align: center;
                max-width: 617px;
                font-weight: 400;
                font-size: 40px;
                line-height: 130%;
                letter-spacing: -2%;
                text-align: center;
        }

        .comprehensive-approach__p1 {
                color: #5E6979;
                font-weight: 400;
                font-size: 15px;
                line-height: 170%;
                letter-spacing: 2%;
                text-align: center;
                max-width: 392px;
        }

        .comprehensive-approach__cards {
                gap: 40px;
                margin-top: 80px;
                margin-bottom: 72px;
        }

        .comprehensive-approach__single__card {
                max-width: 392px;
                border-radius: 10px;
                border: 1px solid #E3E0E0;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                gap: 30px;
                padding: 0px;
        }

        .comprehensive-approach__single__card>img {
                max-width: 100%;
                height: auto;
                object-fit: cover;
        }

        .comprehensive-approach__single__card>h3 {
                font-weight: 600;
                font-size: 19px;
                line-height: 170%;
                letter-spacing: 8%;
                max-width: 392px;
                gap: 20px;
                padding-right: 32px;
                padding-left: 32px;
        }

        .comprehensive-approach__single__card>p {
                color: #5E6979;
                font-weight: 400;
                font-size: 15px;
                line-height: 170%;
                letter-spacing: 2%;
                max-width: 392px;
                gap: 20px;
                padding-right: 32px;
                padding-bottom: 12px;
                padding-left: 32px;
                text-align: left;

        }

        .comprehensive-approach__p2 {
                color: #5E6979;
                font-weight: 400;
                font-size: 17px;
                line-height: 170%;
                letter-spacing: 2%;
                text-align: center;
                max-width: 764px;
        }

        .comprehensive-approach__p2>span {
                color: #0F0F0F;
                font-weight: 600;
                font-size: 17px;
                line-height: 170%;
                letter-spacing: 2%;
                text-align: center;
        }

        @media (width<=425px) {

                .comprehensive-approach>h2 {
                        font-size: 32px;
                }

                .comprehensive-approach__cards {
                        gap: 40px;
                        margin-top: 64px;
                        margin-bottom: 72px;
                        margin-left: auto;
                        margin-right: auto;
                }

        }

        @media (width<=1024px) {

                .comprehensive-approach {
                        padding: 72px 24px;
                }

                .comprehensive-approach__cards {
                        flex-direction: column;
                        justify-content: center;
                }

        }
</style>
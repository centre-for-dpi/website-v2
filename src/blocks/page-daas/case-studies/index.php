<section class="case-studies redlof-block text-center d-flex flex-column justify-content-center align-items-center">

    <!-- Header Content -->
    <h2>In Action</h2>

    <!-- Card Containers -->
    <div class="case-studies__cards row g-0">

        <!-- Card 1 -->
        <div class="col case-studies__single__card">

            <img class="row g-0" src=" <?php echo Helper::getImagePath('images/daas-case-study1.png'); ?>"
                alt="Full Package" loading="lazy">
            <div class="text-start">
                <span class=" case-studies__blog__category text-uppercase">DAAS</span>
                <span class="mx-1">|</span>
                <span class=" case-studies__blog__date text-uppercase">11 June 2025</span>
            </div>
            <p class="text-start">Trinidad & Tobago &ndash; First country to implement DaaS at population scale</p>
        </div>

        <!-- Card 2 -->
        <div class="col case-studies__single__card">

            <img class="row g-0" src=" <?php echo Helper::getImagePath('images/daas-case-study2.png'); ?>"
                alt="Full Package" loading="lazy">
            <div class=" text-start">
                <span class="case-studies__blog__category text-uppercase">DAAS</span>
                <span class="mx-1">|</span>
                <span class=" case-studies__blog__date text-uppercase">12 June 2025</span>
            </div>
            <p class="text-start">Brazil &ndash; DaaS launched for verifiable credentials in social benefits</p>
        </div>

        <!-- Card 3 -->
        <div class="col case-studies__single__card">

            <img class="row g-0" src=" <?php echo Helper::getImagePath('images/daas-case-study3.png'); ?>"
                alt="Full Package" loading="lazy">
            <div class="text-start">
                <span class="case-studies__blog__category text-uppercase">DAAS</span>
                <span class="mx-1">|</span>
                <span class="case-studies__blog__date text-uppercase">13 June 2025</span>
            </div>
            <p class="text-start">7&ndash;8 more countries across Latin America, Africa, and Asia preparing for rollout
            </p>
        </div>


    </div>
    <a href="https://cdpi.think201.xyz/news/" target="blank" role="button" aria-label="Go to home page"
        class="case-studies__cta px-4 py-3 mt-4">Read news & case studies</a>
</section>


<style>
    .case-studies {
        padding: 162px 155px 138px;
    }

    .case-studies>h2 {
        max-width: 617px;
        font-size: 2.5rem;
        line-height: 130%;
        letter-spacing: -2%;
        font-weight: 400;
    }

    .case-studies__cards {
        gap: 40px;
        margin: 80px 0px;
    }

    .case-studies__single__card {
        max-width: 350px;
    }

    .case-studies__single__card img {
        margin: 0px 0px 16px;
    }

    .case-studies__blog__date,
    .case-studies__blog__category {
        color: #5E6979;
        font-weight: 500;
        font-size: .6875rem;
        line-height: 170%;
        letter-spacing: 8%;
    }

    .case-studies__single__card>p {
        font-weight: 500;
        font-size: 17px;
        line-height: 170%;
        letter-spacing: 2%;
        text-decoration: underline;
        margin-top: 16px;
    }

    .case-studies__cta {
        cursor: pointer;
        color: white;
        background-color: #4B4AEA;
        border-radius: 7px;
        font-weight: 400;
        font-size: 14px;
        line-height: 160%;
        letter-spacing: 1%;
        max-width: 342px;
        height: 54px;
        padding: 16px 96px;
        border-radius: 7px;
    }

    .case-studies__cta:hover {
        color: white;
        background-color: #1C1AE4;
    }

    @media (width<=425px) {
       
    }

    @media (width<=1024px) {
        .case-studies {
            padding: 40px 24px;
        }

        .case-studies__cards {
            /* margin-top: 40px 0px; */
            justify-content: center;
        }
    }
</style>
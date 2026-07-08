<?php get_header(); ?>

<section class="not-found-page redlof-block d-flex flex-column justify-content-center align-items-center text-center">
	<h1>404</h1>
	<p class="text">
		Something went wrong
	</p>
	<p class="subtext">
		The requested URL cannot be found or might be temporarily unavailable.
	</p>
	<a href="/" class="home_cta btn px-4 py-3 mt-4" role="button" aria-label="Go to home page">Back
		to home</a>
	<div class="position-absolute start-0 bottom-0">
		<img src="<?php echo Helper::getImagePath('patterns/hero-pattern-2.svg'); ?>" alt="Rotating cube" class="cube">
	</div>
</section>

<?php get_footer(); ?>

<style>
	.not-found-page {
		background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
		height: 89vh;
	}

	.not-found-page>h1 {
		font-size: 126px;
		font-weight: 600;
	}

	.not-found-page>.text {
		font-size: 32px;
		font-weight: 500;
		line-height: 140%;
		letter-spacing: 0%;
	}

	.not-found-page>.subtext {
		font-size: 21px;
		font-weight: 300;
		max-width: 472px;
		line-height: 160%;
		letter-spacing: -1%;
	}

	.not-found-page>.home_cta {
		cursor: pointer;
		color: white;
		background-color: #4B4AEA;
		border-radius: 7px;
		font-size: 16px;
		font-weight: 500;
		letter-spacing: 1%;
	}

	@media (width<=1140px) {
		.not-found-page .cube {
			width: 400px;
		}
	}

	@media (width<=950px) {
		.not-found-page .cube {
			width: 300px;
		}
	}

	@media (width<=768px) {
		.not-found-page .cube {
			display: none;
		}

		.not-found-page>h1 {
			font-size: 77px;
		}

		.not-found-page>text {
			font-size: 24px;
		}

		.not-found-page>.subtext {
			font-weight: 400;
			font-size: 15px;
			line-height: 170%;
			letter-spacing: 2%;
		}
	}
</style>
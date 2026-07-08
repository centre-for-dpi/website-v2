<header id="header" class="site-header gradient-surface shadow-sm">
  <nav class="navbar navbar-expand-xl py-0">
    <div class="container align-items-center">
      <a class="navbar-brand d-flex align-items-center gap-3" href="/">
        <span class="brand-mark rounded-3 d-inline-flex align-items-center justify-content-center">
          <img
            src="<?php echo Helper::getImagePath('logos/cdpi-logo.svg'); ?>"
            alt="CDPI monogram"
            width="204"
            height="55"
            loading="lazy"
          />
        </span>
        <span class="brand-copy lh-sm fw-semibold text-dark">
          <span class="d-block text-uppercase small text-muted">Centre for Digital</span>
          <span class="d-block fs-6 text-uppercase">Public Infrastructure</span>
        </span>
      </a>

      <div class="d-flex align-items-center gap-2 d-xl-none">
        <a class="btn header-btn header-btn--ghost" href="https://dpi.gov.in/wiki" target="_blank" rel="noreferrer">
          DPI Wiki <i class="fa-solid fa-arrow-up-right ms-1"></i>
        </a>
        <a class="btn header-btn header-btn--solid" href="/contact-us">
          Contact Us
        </a>
      </div>

      <button
        class="px-2 navbar-toggler ms-2"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <i class="fa-solid fa-bars-staggered text-dark"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mb-3 mb-xl-0 ms-xl-5 gap-xl-4">
          <li class="nav-item">
            <a class="nav-link" href="/about">About</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="/our-work" id="ourWorkDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Our Work
            </a>
            <ul class="dropdown-menu" aria-labelledby="ourWorkDropdown">
              <li><a class="dropdown-item" href="/our-work/coalitions">Coalitions</a></li>
              <li><a class="dropdown-item" href="/our-work/capabilities">Capabilities</a></li>
              <li><a class="dropdown-item" href="/our-work/showcase">Showcase</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/daas">DaaS</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="/resources" id="resourcesDropdown" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
              Resources
            </a>
            <ul class="dropdown-menu" aria-labelledby="resourcesDropdown">
              <li><a class="dropdown-item" href="/resources/library">Library</a></li>
              <li><a class="dropdown-item" href="/resources/guides">Guides</a></li>
              <li><a class="dropdown-item" href="/resources/events">Events</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="/news" id="newsDropdown" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
              News
            </a>
            <ul class="dropdown-menu" aria-labelledby="newsDropdown">
              <li><a class="dropdown-item" href="/news/press">Press</a></li>
              <li><a class="dropdown-item" href="/news/updates">Updates</a></li>
              <li><a class="dropdown-item" href="/news/opinion">Opinion</a></li>
            </ul>
          </li>
        </ul>

        <div class="navbar-cta ms-xl-auto d-flex flex-column flex-xl-row gap-2">
          <a class="btn header-btn header-btn--ghost" href="https://dpi.gov.in/wiki" target="_blank" rel="noreferrer">
            DPI Wiki <i class="fa-solid fa-arrow-up-right ms-1"></i>
          </a>
          <a class="btn header-btn header-btn--solid" href="/contact-us">
            Contact Us
          </a>
        </div>
      </div>
    </div>
  </nav>
</header>

<style>
  .site-header {
    background: transparent;
    border: none;
    position: absolute;
    width: 100%;
    z-index: 10;
  }

  .site-header .navbar {
    height: 6.375rem;
  }

  .navbar-brand {
    text-decoration: none;
    min-height: 3.4375rem;
  }

  .brand-mark {
    width: 12.75rem;
    height: 3.4375rem;
  }

  .navbar-brand img {
    width: 100%;
    height: 100%;
    display: block;
    max-width: none;
  }

  .brand-copy .small {
    font-size: 0.6875rem;
    letter-spacing: 0.08em;
  }

  .brand-copy .fs-6 {
    font-size: 1rem;
    letter-spacing: 0.02em;
  }

  .site-header .nav-link {
    color: #101828;
    padding: 0.5rem 1rem;
    font-weight: 400;
    font-size: 0.875rem;
    line-height: 160%;
    letter-spacing: 0%;
    transition: color 0.2s ease;
  }

  .site-header .nav-link:hover,
  .site-header .nav-link:focus {
    color: #4948E1;
  }

  .site-header .dropdown-menu {
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 30px rgba(16, 24, 40, 0.08);
    border-radius: 0.75rem;
    padding: 0.75rem 0.5rem;
  }

  .site-header .dropdown-menu[aria-labelledby="resourcesDropdown"],
  .site-header .dropdown-menu[aria-labelledby="newsDropdown"] {
    position: absolute;
    width: auto;
    min-width: 12rem;
    padding: 0.5rem;
    margin-top: 0.5rem;
    left: auto;
    right: auto;
    top: 100%;
    border-radius: 0.75rem;
    transform: none;
  }

  .site-header .dropdown-toggle::after {
    transition: transform 0.2s ease;
  }

  .site-header .dropdown-toggle[aria-expanded="true"]::after {
    transform: rotate(180deg);
  }

  .site-header .dropdown-item {
    font-size: 0.875rem;
    line-height: 160%;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
  }

  .header-btn {
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: 0.5rem;
    line-height: 160%;
    letter-spacing: 0%;
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    transition: all 0.2s ease;
  }

  .header-btn--ghost {
    background-color: #ffffff;
    color: #101828;
    border: 1px solid #CEDBEE;
  }

  .header-btn--ghost:hover {
    background-color: #f9fafb;
    border-color: #b6c6e3;
    color: #101828;
  }

  .header-btn--solid {
    background-color: #4948E1;
    color: #ffffff;
    border: 1px solid #4948E1;
  }

  .header-btn--solid:hover {
    background-color: #4338ca;
    border-color: #4338ca;
    color: #ffffff;
  }

  .navbar-toggler {
    border: none;
    padding: 0.5rem 0.75rem;
    width: 2.5rem;
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    line-height: 1;
  }

  .navbar-toggler i {
    line-height: 1;
    display: block;
  }

  .navbar-toggler:focus {
    box-shadow: none;
  }

  @media (max-width: 1199px) {
    .site-header .navbar {
      height: 5.5rem;
      position: relative;
    }

    .site-header .navbar-collapse {
      background: #ffffff;
      padding: 1.25rem;
      margin-top: 0;
      border-radius: 0.75rem;
      box-shadow: 0 10px 30px rgba(16, 24, 40, 0.08);
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 20;
    }

    .site-header .nav-link {
      padding: 0.75rem 0;
    }

    .navbar-cta {
      padding-top: 1rem;
      border-top: 1px solid #e5e7eb;
    }
  }
</style>
<?php include Helper::getBlock("html-header-footer/header.php") ?>

<?php
$current_slug = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$current_page = explode('/', $current_slug)[0] ?: 'home';
?>

<header id="header" class="site-header">
  <nav class="navbar navbar-expand-xl p-0">
    <div class="container align-items-center">

      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center" href="<?php echo Helper::getPageUrl('/'); ?>">
        <img src="<?php echo Helper::getImagePath('logos/cdpi-logo-black.svg'); ?>"
          alt="Centre for Digital Public Infrastructure" width="204" height="54" loading="lazy" />
      </a>

      <!-- Hamburger menu -->
      <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
        aria-label="Toggle navigation">
        <img class="navbar-toggler-icon-custom" src="<?php echo Helper::getImagePath('icons/menu.svg'); ?>"
          alt="" width="24" height="24" loading="lazy" />
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- Mobile close button -->
        <button class="navbar-close d-xl-none" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true"
          aria-label="Close navigation">
          <span class="navbar-close-icon" aria-hidden="true"></span>
        </button>
        <ul class="navbar-nav ms-auto navbar-nav--spaced">

          <!-- About -->
          <li class="nav-item">
            <a class="nav-link <?php echo ($current_page === 'about' || $current_page === 'about-us') ? 'active' : ''; ?>"
              href="<?php echo Helper::getPageUrl('about'); ?>">About</a>
          </li>

          <!-- Our Work -->
          <li class="nav-item">
            <a class="nav-link <?php echo ($current_page === 'our-work') ? 'active' : ''; ?>"
              href="<?php echo Helper::getPageUrl('our-work'); ?>">
              Our Work
            </a>
          </li>


          <!-- DaaS -->
          <li class="nav-item">
            <a class="nav-link <?php echo ($current_page === 'daas') ? 'active' : ''; ?>"
              href="<?php echo Helper::getPageUrl('daas'); ?>">DaaS</a>
          </li>

          <!-- Resources -->
          <li class="nav-item">
            <a class="nav-link <?php echo ($current_page === 'resources') ? 'active' : ''; ?>"
              href="<?php echo Helper::getPageUrl('resources'); ?>">Resources</a>
          </li>
        </ul>
        <div class="navbar-cta navbar-cta--spaced d-flex">
          <a class="header-btn header-btn--outline header-btn--wiki" href="https://docs.cdpi.dev" target="_blank" rel="noreferrer">
            DPI Wiki
            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>
</header>

<style>
  /* Header Styles */
  .site-header {
    background: transparent;
    border: none;
    position: absolute;
    /* so it floats on top of hero */
    width: 100%;
    z-index: 10;
  }

  .site-header .navbar {
    width: 100%;
    height: 6.375rem;
  }

  .site-header .navbar > .container {
    margin-left: 5.625rem;  /* 90px */
    margin-right: 5.625rem;  /* 90px */
    padding-left: 0;
    padding-right: 0;
    max-width: none;  /* full width between margins */
    width: calc(100% - 11.25rem);  /* 100% minus 90px*2 */
  }

  /* Nav items and CTA: 26px gap, aligned in one row */
  .site-header .navbar-collapse {
    gap: 1.625rem;  /* 26px */
    align-items: center;
  }

  .site-header .navbar-nav.navbar-nav--spaced {
    gap: 1.625rem;  /* 26px */
  }

  .site-header .navbar-cta.navbar-cta--spaced {
    gap: 1.625rem;  /* 26px */
  }

  /* Brand Styles */
  .navbar-brand {
    text-decoration: none;
  }

  .navbar-brand img {
    max-height: 3rem;
    width: auto;
  }

  /* Nav Links */
  .site-header .nav-link,
  .site-header .nav-link.active {
    color: #101828;
    padding-top: 0.5rem;     /* 8px block only */
    padding-bottom: 0.5rem;
    padding-left: 0 !important;
    padding-right: 0 !important;

    font-weight: 400;
    font-size: 0.875rem;
    line-height: 160%;
    letter-spacing: 0%;

    transition: color 0.2s ease;
    position: relative;
  }

  .site-header .nav-link.active {
    color: #9810FA;
  }

  .site-header .nav-link::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #9810FA;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.2s ease;
  }

  .site-header .nav-link:hover,
  .site-header .nav-link:focus-visible {
    color: #9810FA;
  }

  .site-header .nav-link:hover::after,
  .site-header .nav-link:focus-visible::after,
  .site-header .nav-link.active::after {
    transform: scaleX(1);
  }

  .site-header .dropdown-toggle::after {
    content: none;
    /* disables Bootstrap’s default arrow */
  }

  .dropdown-icon {
    transition: transform 0.2s ease;
  }

  .site-header .dropdown-toggle[aria-expanded="true"] .dropdown-icon {
    transform: rotate(180deg);
  }

  /* Dropdown Menu */

  .site-header .navbar,
  .site-header .navbar>.container,
  .site-header .navbar-collapse,
  .site-header .nav-item.dropdown:not(.dropdown--simple) {
    position: static;
  }

  .site-header .dropdown-menu {
    border: 1px solid #e5e7eb;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    padding: 2.5rem 3.75rem;
    margin-top: 3.125rem;
    position: absolute;
    left: 0;
    right: 0;
    top: 2.875rem;
    width: 100vw;
    border-radius: 0px;
  }

  .site-header .dropdown--simple {
    position: relative;
  }

  .site-header .dropdown--simple .dropdown-menu {
    position: absolute;
    width: auto;
    min-width: 12rem;
    padding: 1.125rem 1.125rem 0.75rem 1.125rem;
    margin-top: 0.25rem;
    left: 0;
    right: auto;
    top: 100%;
    border-radius: 0.75rem;
    transform: none;
  }

  .site-header .dropdown--simple .world-map {
    display: none;
  }

  .site-header .dropdown--simple .dropdown-menu .row {
    margin: 0;
  }

  .site-header .dropdown--simple .dropdown-menu .col-3 {
    display: none;
  }

  .site-header .dropdown--simple .dropdown-menu .col {
    flex: 0 0 100%;
    max-width: 100%;
  }

  .site-header .dropdown-item {
    color: #4948E1;
    margin: 0 0 1.3125rem 0;
    border-radius: 6px;
    transition: all 0.2s ease;
    padding: 0px;

    font-weight: 500;
    font-style: Medium;
    font-size: 0.75rem;
    line-height: 170%;
    letter-spacing: 0.075rem;
  }

  .site-header .dropdown-menu .dropdown-item:hover,
  .site-header .dropdown-menu .dropdown-item:focus {
    background-color: transparent;
  }

  .site-header .dropdown-location-heading {
    cursor: pointer;
    pointer-events: none;
  }

  .site-header .dropdown-item-links {
    color: #424242;

    font-weight: 300;
    font-size: 0.9375rem;
    line-height: 170%;

    display: inline-block;
    margin: 1rem 0;
  }

  .site-header .dropdown-item-links:last-child {
    margin-bottom: 0px;
  }

  .site-header .dropdown-menu .world-map {
    width: 17.875rem;
    height: 14.875rem;
  }

  /* Header Buttons */
  .header-btn {
    font-size: 0.875rem;
    font-weight: 400;
    border-radius: 8px;
    transition: all 0.2s ease;
    line-height: 160%;
    letter-spacing: 0%;

    height: 2.375rem;
  }

  .header-btn--outline {
    background-color: #FFFFFF;
    color: #101828;

    border: 1px solid #CEDBEE;
    padding: 0.5rem 0.5rem 0.5rem 1rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    width: 6.125rem;  /* 98px */
    white-space: nowrap;
  }

  .header-btn--wiki {
    width: auto;
    padding: 0 24px;
    border: 1px solid #B9C7DD;
    border-radius: 9px;
    font-size: 14px;
    line-height: 160%;
    color: #101828;
    gap: 8px;
  }

  .header-btn--wiki i {
    color: #5E6979;
    font-size: 0.55em;
  }

  .header-btn--solid {
    background-color: #4948E1;
    color: #ffffff;

    border-radius: 5px;
    padding: 0.5rem 1rem;
    width: 6.3125rem;  /* 101px */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
  }

  .header-btn--solid:hover {
    color: #ffffff;
  }

  .dropdown-icon {
    width: 1.125rem;
    height: 1.125rem;
    margin-left: 0.3125rem;
  }

  .arrow-up-right-icon-header {
    width: 1.125rem;
    height: 1.125rem;
  }

  /* Close (X) icon base */
  .navbar-close-icon {
    position: relative;
    width: 1.25rem;
    height: 1.25rem;
    display: inline-block;
  }

  .navbar-close-icon::before,
  .navbar-close-icon::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 1.25rem;
    height: 2px;
    background-color: #5E6979;
    transform-origin: center;
  }

  .navbar-close-icon::before {
    transform: translate(-50%, -50%) rotate(45deg);
  }

  .navbar-close-icon::after {
    transform: translate(-50%, -50%) rotate(-45deg);
  }

  /* Mobile Styles */
  @media (max-width: 1199px) {
    .navbar-close {
      border: none;
      background: transparent;
      padding: 0;
      width: 2.5rem;
      height: 2.5rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      top: 0.75rem;
      right: 0.75rem;
      z-index: 25;
    }

    .site-header .navbar > .container {
      margin-left: 1.5rem;
      margin-right: 1.5rem;
      width: calc(100% - 3rem); /* full width minus 24px*2 margins */
      max-width: none;
    }

    .site-header .navbar {
      height: 5.5rem;
      position: relative;
    }

    .site-header .navbar-collapse {
      background: #ffffff;
      padding: 1.25rem;
      margin-top: 0;
      border-radius: 0.75rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      width: 100%;
      z-index: 20;
    }

    .site-header .dropdown-menu {
      position: static;
      width: 100%;
      margin-top: 0;
      padding: 0.5rem 0 0.75rem;
      border: 0;
      box-shadow: none;
      background: transparent;
    }

    .site-header .dropdown--simple .dropdown-menu {
      position: static;
      width: 100%;
      margin-top: 0.25rem;
    }

    .site-header .dropdown-item {
      margin: 0;
      padding: 0.5rem 0;
    }

    .site-header .dropdown-item-links {
      display: block;
      margin: 0.5rem 0;
    }

    .site-header .dropdown-menu .world-map {
      display: none;
    }

    .site-header .navbar-nav {
      margin: 2.5rem 0 1rem; /* 40px top gap from close button, 16px (~1rem) bottom */
    }

    /* Mobile menu: vertical list, 24px spacing like Figma */
    .site-header .navbar-nav.navbar-nav--spaced {
      gap: 1.5rem; /* 24px */
    }

    .site-header .nav-link,
    .site-header .nav-link.active {
      display: block;
      width: 100%;                /* full-width tap target */
      padding: 0.75rem 0;
      font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      font-weight: 500;
      font-size: 1.5rem;          /* 24px */
      line-height: 160%;
      letter-spacing: 0;
      color: #5E6979;
    }

    .site-header .nav-link.active {
      color: #9810FA;
    }

    /* Mobile CTA buttons full width, 62px height, 16px Outfit */
    .navbar-cta .header-btn--outline,
    .navbar-cta .header-btn--solid {
      width: 100%;
      max-width: none;
      justify-content: center;
      height: 3.875rem; /* 62px */
      font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
      font-weight: 500;
      font-size: 1rem;   /* 16px */
      line-height: 160%;
      letter-spacing: 0;
      text-align: center;
    }

    .navbar-cta .header-btn--outline {
      color: #5E6979; /* DPI Wiki label color */
    }

    .navbar-cta {
      padding-top: 1rem;
      border-top: 1px solid #e5e7eb;
    }

    .header-btn--wiki {
      min-width: 100%;
      width: 100%;
      height: 62px;
      padding: 0 20px;
      border-radius: 12px;
      font-size: 16px;
      justify-content: center;
    }
  }

  .navbar-toggler {
    border: none;
    padding: 0.5rem 0.75rem;
    width: 2.5rem;
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: transparent;
  }

  .navbar-toggler-icon-custom {
    display: block;
    width: 1.5rem;
    height: 1.5rem;
  }

  .navbar-toggler:focus {
    box-shadow: none;
  }
</style>

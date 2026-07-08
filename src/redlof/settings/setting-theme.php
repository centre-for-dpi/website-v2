<?php

// Disablin Theme & Plugin Editor
define('DISALLOW_FILE_EDIT', true);

// Image Edits
define( 'IMAGE_EDIT_OVERWRITE', true );


function redlof_theme_setup() 
{

  //Allow editor style.
  add_editor_style();

  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'redlof_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'redlof_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'redlof_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'redlof_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'redlof_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  redlof_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'redlof_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'redlof_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'redlof_excerpt_more' );

}

// let's get this party started
add_action( 'after_setup_theme', 'redlof_theme_setup' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function redlof_register_sidebars() {

  register_sidebar(array(
    'id' => 'main-sidebar',
    'name' => 'Main Sidebar',
    'description' => 'The main sidebar below the image & title of the page',
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
    ));

} 


// Adding WP 3+ Functions & Theme Support
function redlof_theme_support() {

  // wp thumbnails (sizes handled in functions.php)
  add_theme_support( 'post-thumbnails' );

  // default thumb size
  set_post_thumbnail_size(125, 125, true);

  // rss thingy
  add_theme_support('automatic-feed-links');

  // wp menus
  add_theme_support( 'menus' );

  // registering wp3+ menus
  register_nav_menus(
    array(
      'main-nav' => __( 'The Main Menu', 'redloftheme' ),   // main nav in header
      'footer-links' => __( 'Footer Links', 'redloftheme' ) // secondary nav in footer
      )
    );
} 


function rw_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ) {
    $title .= get_bloginfo( 'name' );
  } else {
    $title = get_bloginfo( 'name' ) . $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title .= " {$sep} {$site_description}";
  }

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
  }

  return $title;

}


// remove WP version from RSS
function redlof_rss_version() { return ''; }


// remove injected CSS for recent comments widget
function redlof_remove_wp_widget_recent_comments_style() {
  if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
    remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
  }
}

// remove injected CSS from recent comments widget
function redlof_remove_recent_comments_style() {
  global $wp_widget_factory;
  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
    remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
  }
}

// remove injected CSS from gallery
function redlof_gallery_style($css) {
  return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}

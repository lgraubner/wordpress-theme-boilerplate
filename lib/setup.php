<?php

namespace Boilerplate\Setup;

use Boilerplate\Assets;
use Boilerplate\Helpers;

/**
 * Theme setup
 */
function setup() {
    // Enable features from Soil when plugin is activated
    // https://roots.io/plugins/soil/
    add_theme_support('soil-clean-up');
    add_theme_support('soil-nav-walker');
    add_theme_support('soil-nice-search');
    add_theme_support('soil-jquery-cdn');
    add_theme_support('soil-relative-urls');

    // Make theme available for translation
    // Community translations can be found at https://github.com/roots/sage-translations
    load_theme_textdomain('boilerplate', get_template_directory() . '/lang');

    // Enable plugins to manage the document title
    // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
    add_theme_support('title-tag');

    // Register wp_nav_menu() menus
    // http://codex.wordpress.org/Function_Reference/register_nav_menus
    register_nav_menus([
        'header_navigation' => __('Header Navigation', 'boilerplate'),
        'footer_navigation' => __('Footer Navigation', 'boilerplate'),
    ]);

    // Enable post thumbnails
    // http://codex.wordpress.org/Post_Thumbnails
    // http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size
    // http://codex.wordpress.org/Function_Reference/add_image_size
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(280, 280, true);

    // default
    // add_image_size('post-default', 610, 9999);

    // Enable post formats
    // http://codex.wordpress.org/Post_Formats
    add_theme_support('post-formats', ['aside', 'gallery', 'link', 'image', 'quote', 'video', 'audio']);

    // Enable HTML5 markup support
    // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
    add_theme_support('html5', ['caption', 'comment-form', 'comment-list', 'gallery', 'search-form']);

    // Use main stylesheet for visual editor
    // To add custom styles edit /assets/styles/layouts/_tinymce.scss
    // add_editor_style(Assets\asset_path('styles/main.css'));
}
add_action('after_setup_theme', __NAMESPACE__ . '\\setup');

function adjust_image_sizes_attr( $sizes, $size ) {
     $sizes = '(max-width: 1360px) 100vw, 1360px';
     return $sizes;
}
add_filter( 'wp_calculate_image_sizes', __NAMESPACE__ . '\\adjust_image_sizes_attr', 10 , 2 );

/**
 * Register sidebars
 */
function widgets_init() {
    register_sidebar([
        'name' => __('Footer', 'boilerplate'),
        'id' => 'sidebar-footer',
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ]);
}
add_action('widgets_init', __NAMESPACE__ . '\\widgets_init');

/**
 * Determine which pages should NOT display the sidebar
 */
function display_sidebar() {
    static $display;

    isset($display) || $display = !in_array(true, [
        // The sidebar will NOT be displayed if ANY of the following return true.
        // @link https://codex.wordpress.org/Conditional_Tags
        is_404(),
        is_single(),
        is_front_page(),
        is_page_template('template-custom.php'),
    ]);

    // disabled
    return apply_filters('boilerplate/display_sidebar', false);
}

/**
 * Theme assets
 */
function assets() {
    wp_enqueue_style('boilerplate', Assets\asset_path('styles/main.css'), false, null);

    // remove jQuery
    wp_deregister_script('jquery');

    wp_enqueue_script('boilerplate/js', Assets\asset_path('scripts/main.js'), [], null, true);
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\assets', 100);

/**
 * Add js detection
 */
function js_detection() {
    echo "<script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>";
}
add_action('wp_head', __NAMESPACE__ . '\\js_detection');

/**
 * Redirect attachments to parent post
 */
function redirect_attachment_page() {
    if (is_attachment()) {
        global $post;
        if ($post && $post->post_parent) {
            wp_redirect(esc_url(get_permalink($post->post_parent)), 301);
            exit;
        } else {
            wp_redirect(esc_url(home_url('/')), 301);
            exit;
        }
    }
}
add_action( 'template_redirect', __NAMESPACE__ . '\\redirect_attachment_page' );

/**
 * Add link to rss2 feed
 */
function add_rss_head() {
    echo '<link rel="alternate" type="application/rss+xml" title="RSS 2.0 Feed" href="'.get_bloginfo('rss2_url').'" />';
}
add_action('wp_head', __NAMESPACE__ . '\\add_rss_head');

function add_async_attribute($tag, $handle) {
    if ('boilerplate/js' !== $handle)
        return $tag;
    return str_replace(' src', ' async src', $tag);
}
add_filter('script_loader_tag', __NAMESPACE__ . '\\add_async_attribute', 10, 2);

function search_posttypes($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('product'));
    }
}
add_filter('pre_get_posts', __NAMESPACE__ . '\\search_posttypes');

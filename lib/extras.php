<?php
namespace Boilerplate\Extras;
use Boilerplate\Setup;

/**
 * Add <body> classes
 */
function body_class($classes) {
    // Add page slug if it doesn't exist
    if (is_single() || is_page() && !is_front_page()) {
        if (!in_array(basename(get_permalink()), $classes)) {
            $classes[] = basename(get_permalink());
        }
    }
    // Add class if sidebar is active
    if (Setup\display_sidebar()) {
        $classes[] = 'sidebar-primary';
    }
    return $classes;
}
add_filter('body_class', __NAMESPACE__ . '\\body_class');

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
    return '';
}
add_filter('excerpt_more', __NAMESPACE__ . '\\excerpt_more');

/**
 * Get pagination nav
 */
function pagination_nav() {
    global $wp_query;

    if ( $wp_query->max_num_pages > 1 ) { ?>
        <nav class="pagination" role="navigation">
            <div class="inner">
                <div class="pagination__previous"><?php next_posts_link('Older posts'); ?></div>
                <div class="pagination__next"><?php previous_posts_link('Newer posts'); ?></div>
            </div>
        </nav>
<?php }
}

/**
 * Prettify search url
 */
function search_url_rewrite_rule() {
    if ( is_search() && !empty($_GET['s'])) {
        wp_redirect(home_url("/search/") . urlencode(get_query_var('s')));
        exit();
    }
}
add_action('template_redirect', __NAMESPACE__ . '\\search_url_rewrite_rule');

/**
 * Remove wp emoji dns prefetch and add own prefetch url's
 */
function dns_prefetch_list( $hints, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        return array(
            '//fonts.googleapis.com',
        );
    }

    return $hints;
}
add_filter( 'wp_resource_hints', __NAMESPACE__ . '\\dns_prefetch_list', 10, 2 );

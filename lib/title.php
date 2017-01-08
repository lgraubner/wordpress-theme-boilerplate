<?php
namespace Boilerplate\Titles;

/**
 * Page titles
 */
function title() {
    if (is_front_page()) {
        return 'TITLE';
    } else {
        return get_the_title();
    }
}

<?php
/**
 * Template header
 */
?>
<header role="banner" class="header">
    <div class="inner">
        <?php if (is_front_page()): ?>
            <h1 class="logo">
        <?php else: ?>
            <div class="logo">
        <?php endif; ?>
                <a href="<?= esc_url(home_url('/')); ?>">
                        <?php echo bloginfo('name'); ?>
                </a>
        <?php if (is_front_page()): ?>
            </h1>
        <?php else: ?>
                </div>
        <?php endif; ?>
        <a id="skip-navigation" class="invisible" href="#content">Skip navigation</a>
        <nav role="navigation" class="menu-wrapper">
            <?php
            if (has_nav_menu('header_navigation')) :
                wp_nav_menu(['theme_location' => 'header_navigation']);
            endif;
            ?>
        </nav>
    </div>
</header>

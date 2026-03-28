<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Neue+Haas+Grotesk+Display+Pro:wght@400;500&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- Custom cursor -->
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>

<!-- Navigation -->
<nav>
  <a href="<?php echo home_url(); ?>" class="nav-logo">
    <?php if (ws_get_option('site_logo')): ?>
      <img src="<?php echo ws_get_option('site_logo'); ?>" alt="<?php echo get_bloginfo('name'); ?>" class="site-logo-img">
    <?php
else: ?>
      <?php echo get_bloginfo('name'); ?>
    <?php
endif; ?>
  </a>

    <?php
wp_nav_menu([
  'theme_location' => 'menu-1',
  'menu_class' => 'nav-links',
  'container' => false, // No wrapping <div>
  'walker' => new Custom_Nav_Walker(),
  'fallback_cb' => '__return_false', // Don't show anything if no menu assigned
]);
?>

  <a href="<?php echo home_url('/contact'); ?>" class="nav-cta">Start a Project</a>
</nav>
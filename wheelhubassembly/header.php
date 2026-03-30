<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@300;400;500;600;700;800&family=Barlow:wght@300;400;500;600&family=DM+Mono:wght@300;400;500&display=swap"
    rel="stylesheet">
</head>

<body <?php body_class(); ?>>

  <!-- ============================================
     NAVIGATION
============================================ -->
  <nav class="nav" id="mainNav" aria-label="Primary navigation">
    <div class="nav__inner">
      <a href="<?php echo home_url('/'); ?>" class="nav__logo" aria-label="<?php bloginfo('name') ?>">
        
        
          <?php if(!empty(ws_get_option('site_logo'))) : ?>
            <img src="<?php echo ws_get_option('site_logo'); ?>" alt="<?php bloginfo('name') ?>">

      <?php else : ?>
        <div class="nav__logo-mark" aria-hidden="true"></div>
       <div> <?php bloginfo( 'name' ); ?>
          <span class="nav__logo-sub"><?php bloginfo('description'); ?></span></div>
      <?php  endif; ?>
          
        
      </a>
       <?php
    wp_nav_menu( array(
        'theme_location' => 'menu-1',
        'container'      => false,
        'menu_class'     => 'nav__links',
        'menu_id'        => '',
        'items_wrap'     => '<ul id="%1$s" class="%2$s" role="list">%3$s</ul>',
        'depth'          => 2,            // Fetch up to 2 levels (parent + children)
        'walker'         => new Mega_Menu_Walker(),
        'fallback_cb'    => false,        // Don't show page list if no menu assigned
    ) );
    ?>
      <a href="#contact" class="nav__cta">Request Quote</a>
    </div>
    <button class="nav__hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <!-- Mobile Menu -->
  <div class="mobile-menu" id="mobileMenu" aria-hidden="true" role="dialog" aria-label="Mobile navigation">
    <nav class="mobile-menu__links">
      <a href="#capabilities" class="mobile-menu__link">Capabilities <svg class="icon icon--sm" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg></a>
      <a href="#products" class="mobile-menu__link">Products <svg class="icon icon--sm" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg></a>
      <a href="images-showcase.html" class="mobile-menu__link">Showcase <svg class="icon icon--sm" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg></a>
      <a href="#engineering" class="mobile-menu__link">Engineering <svg class="icon icon--sm" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg></a>
      <a href="#quality" class="mobile-menu__link">Quality <svg class="icon icon--sm" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg></a>
      <a href="#contact" class="mobile-menu__link">Contact <svg class="icon icon--sm" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg></a>
    </nav>
    <div class="mobile-menu__cta">
      <a href="#contact" class="btn-primary">
        <span>Request Technical Quote</span>
        <svg class="icon icon--sm" viewBox="0 0 24 24">
          <line x1="5" y1="12" x2="19" y2="12" />
          <polyline points="12 5 19 12 12 19" />
        </svg>
      </a>
    </div>
  </div>
<?php get_header(); ?>

<!-- Hero -->
<section id="hero" class="hero-slider">
  <div class="hero-grid-overlay"></div>
  
  <div class="hero-slides">
    <?php
$sliders = ws_get_post_meta(23, '_csp_slides');

var_dump($sliders);
$i = 0;
foreach ($sliders as $slider) {
  $background_image = $slider['image_url'];
  $headline = $slider['name'];
  $subheadline = $slider['description'];
  $button_link = $slider['link'];
  $i++;
?>
  <div class="hero-slide <?php echo $i == 1 ? 'active' : ''; ?>">
    <div class="hero-bg" style="background-image: url('<?php echo $background_image; ?>');"></div>
      <div class="hero-bg-overlay"></div>
      <div class="hero-content">
        <div class="hero-index"><?php echo $i; ?></div>
        <p class="hero-label">this is the label</p>
        <h1 class="hero-headline"><?php echo $headline; ?></h1>
        <div class="hero-bottom">
          <p class="hero-sub"><?php echo $subheadline; ?></p>
          <div class="hero-actions">
            <a href="<?php echo $button_link; ?>" class="btn-primary">View Work</a>
            <a href="<?php echo home_url('/contact'); ?>" class="btn-ghost">Start a Project</a>
          </div>
        </div>
      </div>
    </div>
      <?php
}
?>
  </div>

  <!-- Slider Controls -->
  <div class="hero-slider-controls">
    <button class="hero-slider-btn prev-btn" aria-label="Previous Slide">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <div class="hero-slider-dots">
      <span class="hero-dot active" data-slide="0"></span>
      <span class="hero-dot" data-slide="1"></span>
      <span class="hero-dot" data-slide="2"></span>
    </div>
    <button class="hero-slider-btn next-btn" aria-label="Next Slide">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
  </div>

  <div class="scroll-indicator">
    <div class="scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- Marquee -->
<div class="marquee-wrap">
  <div class="marquee-track">
    <?php
$marquee = ws_get_option('marqueue');
foreach ($marquee as $item) {
?>
      <span class="marquee-item"><?php echo $item; ?> <span>✦</span></span>
      <?php
}
?>
    
  </div>
</div>

<!-- Work -->
<section id="work">
  <div class="work-header reveal">
    <div>
      <div class="section-label">Selected Work</div>
      <h2 class="work-title">
        Craft that<br><em>moves</em> culture
      </h2>
    </div>
    <a href="<?php echo get_post_type_archive_link('portfolio'); ?>" class="work-link">All Projects →</a>
  </div>

  <div class="projects-grid">
    <?php
$args = array(
  'post_type' => 'portfolio',
  'posts_per_page' => 5,
  'orderby' => 'menu_order',
  'order' => 'ASC',
);
$portfolio_query = new WP_Query($args);

if ($portfolio_query->have_posts()):
  $count = 0;
  while ($portfolio_query->have_posts()):
    $portfolio_query->the_post();
    $count++;
    $delay_class = '';
    if ($count == 2 || $count == 4)
      $delay_class = 'reveal-delay-1';
    if ($count == 3 || $count == 5)
      $delay_class = 'reveal-delay-2';

    $terms = get_the_terms(get_the_ID(), 'project_type');
    $term_names = array();
    if ($terms && !is_wp_error($terms)) {
      foreach ($terms as $term) {
        $term_names[] = $term->name;
      }
    }
    $category_text = implode(' — ', $term_names);
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
    $display_name = get_the_title();

    // Brand name for the small detail text - first word of title
    $brand_name = strtok($display_name, " ");
?>
            <a href="<?php the_permalink(); ?>" class="project-card reveal <?php echo esc_attr($delay_class); ?>">
              <div class="proj-inner <?php echo !$thumbnail_url ? 'proj-bg-' . (($count - 1) % 5 + 1) : ''; ?>" 
                   <?php if ($thumbnail_url): ?>style="background-image: url('<?php echo esc_url($thumbnail_url); ?>'); background-size: cover; background-position: center;"<?php
    endif; ?>>
                <div class="proj-accent"></div>
                <div class="proj-detail"><span class="proj-detail-text"><?php echo esc_html($brand_name); ?></span></div>
              </div>
              <div class="project-overlay">
                <div class="project-cat"><?php echo esc_html($category_text); ?></div>
                <div class="project-name"><?php the_title(); ?></div>
              </div>
            </a>
        <?php
  endwhile;
  wp_reset_postdata();
else:
  // Fallback static cards if no posts found
?>
        <!-- Project 1 -->
        <a href="#" class="project-card reveal">
          <div class="proj-inner proj-bg-1">
            <div class="proj-accent"></div>
            <div class="proj-detail"><span class="proj-detail-text">Aurèle</span></div>
          </div>
          <div class="project-overlay">
            <div class="project-cat">Luxury Retail — Brand &amp; Web</div>
            <div class="project-name">Aurèle Paris</div>
          </div>
        </a>
        <!-- Project 2 -->
        <a href="#" class="project-card reveal reveal-delay-1">
          <div class="proj-inner proj-bg-2">
            <div class="proj-accent"></div>
            <div class="proj-detail"><span class="proj-detail-text">Nové</span></div>
          </div>
          <div class="project-overlay">
            <div class="project-cat">FinTech — Digital Platform</div>
            <div class="project-name">Nové Capital</div>
          </div>
        </a>
        <!-- Project 3 -->
        <a href="#" class="project-card reveal reveal-delay-2">
          <div class="proj-inner proj-bg-3">
            <div class="proj-accent"></div>
            <div class="proj-detail"><span class="proj-detail-text">Maev</span></div>
          </div>
          <div class="project-overlay">
            <div class="project-cat">Architecture — Identity</div>
            <div class="project-name">Maev Studio</div>
          </div>
        </a>
        <!-- Project 4 -->
        <a href="#" class="project-card reveal reveal-delay-1">
          <div class="proj-inner proj-bg-4">
            <div class="proj-accent"></div>
            <div class="proj-detail"><span class="proj-detail-text">Grén</span></div>
          </div>
          <div class="project-overlay">
            <div class="project-cat">Sustainable Tech — Experience</div>
            <div class="project-name">Grén Systems</div>
          </div>
        </a>
        <!-- Project 5 -->
        <a href="#" class="project-card reveal reveal-delay-2">
          <div class="proj-inner proj-bg-5">
            <div class="proj-accent"></div>
            <div class="proj-detail"><span class="proj-detail-text">Sōl</span></div>
          </div>
          <div class="project-overlay">
            <div class="project-cat">Wellness — Campaign</div>
            <div class="project-name">Sōl Collective</div>
          </div>
        </a>
    <?php
endif; ?>
  </div>
</section>

<!-- Philosophy -->
<section id="philosophy">
  <div class="phil-left reveal">
    <div class="section-label">Our Philosophy</div>
    <p class="phil-statement">
      Design is not<br>decoration.<br>It is <em>strategy</em><br>made visible.
    </p>
  </div>
  <div class="phil-right reveal reveal-delay-2">
    <p class="phil-text">
      We believe the most powerful design work exists at the intersection of culture, technology, and human behaviour. Every pixel, every interaction, every word carries intent.
    </p>
    <p class="phil-text">
      We work with a select number of clients each year — not because we can't scale, but because depth requires focus. The brands we shape with you should outlast trends and define categories.
    </p>
    <div class="phil-divider"></div>
    <div class="phil-stats">
      <div>
        <div class="phil-stat-num">140+</div>
        <div class="phil-stat-label">Projects Delivered</div>
      </div>
      <div>
        <div class="phil-stat-num">9yr</div>
        <div class="phil-stat-label">In Practice</div>
      </div>
      <div>
        <div class="phil-stat-num">28</div>
        <div class="phil-stat-label">Industry Awards</div>
      </div>
      <div>
        <div class="phil-stat-num">100%</div>
        <div class="phil-stat-label">Client Retention</div>
      </div>
    </div>
  </div>
</section>

<!-- Services -->
<section id="services">
  <div class="services-header reveal">
    <div>
      <div class="section-label">Capabilities</div>
      <h2 class="services-title">
        What we<br><em>do best</em>
      </h2>
    </div>
    <p class="services-intro">From foundational brand thinking to immersive digital environments — we operate across the full spectrum of creative and technical disciplines.</p>
  </div>
  <div class="services-list">
    <div class="service-item reveal">
      <div class="service-num">01</div>
      <div class="service-name">Brand<br>Strategy</div>
      <div class="service-desc">Positioning, identity architecture, verbal and visual systems that earn premium perception.</div>
      <div class="service-arrow">↗</div>
    </div>
    <div class="service-item reveal reveal-delay-1">
      <div class="service-num">02</div>
      <div class="service-name">Web<br>Design</div>
      <div class="service-desc">Editorial-grade digital experiences. Considered, purposeful, and built to convert.</div>
      <div class="service-arrow">↗</div>
    </div>
    <div class="service-item reveal reveal-delay-2">
      <div class="service-num">03</div>
      <div class="service-name">Digital<br>Experience</div>
      <div class="service-desc">Immersive interfaces, campaigns, and interactive storytelling at cultural scale.</div>
      <div class="service-arrow">↗</div>
    </div>
    <div class="service-item reveal reveal-delay-3">
      <div class="service-num">04</div>
      <div class="service-name">Interactive<br>Development</div>
      <div class="service-desc">Performance-driven, technically precise front-end engineering that honours the design.</div>
      <div class="service-arrow">↗</div>
    </div>
  </div>
</section>

<!-- Process -->
<section id="process">
  <div class="process-header reveal">
    <div class="section-label">How We Work</div>
    <h2 class="process-title">
      A process<br>built for <em>precision</em>
    </h2>
  </div>
  <div class="process-steps">
    <div class="process-step reveal">
      <div class="process-step-num">I</div>
      <div class="process-step-title">Discover</div>
      <div class="process-step-text">Deep immersion into your brand, market, and audience. We don't assume — we investigate. Research, audits, and strategic alignment.</div>
    </div>
    <div class="process-step reveal reveal-delay-1">
      <div class="process-step-num">II</div>
      <div class="process-step-title">Define</div>
      <div class="process-step-text">We crystallise ambition into strategy. Creative direction, information architecture, and a singular point of view that guides all decisions.</div>
    </div>
    <div class="process-step reveal reveal-delay-2">
      <div class="process-step-num">III</div>
      <div class="process-step-title">Design</div>
      <div class="process-step-text">Craft at every scale. Visual systems, motion, interaction — built with obsessive attention to the relationship between form and feeling.</div>
    </div>
    <div class="process-step reveal reveal-delay-3">
      <div class="process-step-num">IV</div>
      <div class="process-step-title">Deliver</div>
      <div class="process-step-text">Precision engineering, performance optimisation, and seamless handover. We stay engaged long after launch.</div>
    </div>
  </div>
</section>

<!-- Clients & Testimonials -->
<section id="clients">
  <div class="clients-header reveal">
    <div class="section-label">Trusted By</div>
    <h2 class="clients-title">Brands that <em>shape</em> culture</h2>
    <p class="clients-sub">Global companies, luxury houses, and high-growth ventures</p>
  </div>
  <div class="client-logos reveal">
    <div class="client-logo"><span class="client-logo-text">Maison</span></div>
    <div class="client-logo"><span class="client-logo-text">Orbis</span></div>
    <div class="client-logo"><span class="client-logo-text">Stratum</span></div>
    <div class="client-logo"><span class="client-logo-text">Velour</span></div>
    <div class="client-logo"><span class="client-logo-text">Nocturne</span></div>
    <div class="client-logo"><span class="client-logo-text">Aldeia</span></div>
    <div class="client-logo"><span class="client-logo-text">Fenn</span></div>
    <div class="client-logo"><span class="client-logo-text">Claros</span></div>
    <div class="client-logo"><span class="client-logo-text">Remis</span></div>
    <div class="client-logo"><span class="client-logo-text">Caste</span></div>
    <div class="client-logo"><span class="client-logo-text">Koya</span></div>
    <div class="client-logo"><span class="client-logo-text">Draven</span></div>
  </div>
  <div class="testimonials-grid">
    <div class="testimonial-card reveal">
      <div class="testimonial-quote">Working with Vaux was unlike any agency experience we'd had. They challenged our thinking and delivered something far beyond what we imagined possible.</div>
      <div class="testimonial-author">Camille Renaud</div>
      <div class="testimonial-company">CMO — Maison Orbis</div>
    </div>
    <div class="testimonial-card reveal reveal-delay-1">
      <div class="testimonial-quote">The level of craft, attention to detail, and strategic clarity they brought to our rebrand fundamentally changed how the market sees us.</div>
      <div class="testimonial-author">James Whitfield</div>
      <div class="testimonial-company">Founder — Stratum Capital</div>
    </div>
    <div class="testimonial-card reveal reveal-delay-2">
      <div class="testimonial-quote">A rare studio that understands both aesthetics and conversion. Our new site performs better on every metric that matters.</div>
      <div class="testimonial-author">Sena Otieno</div>
      <div class="testimonial-company">CEO — Velour Labs</div>
    </div>
  </div>
</section>

<section id="news">
  <div class="news-header reveal">
    <div>
      <div class="section-label">Latest News</div>
      <h2 class="news-title">
        Updates from the <em>studio</em>
      </h2>
    </div>
    <p class="news-intro">A short view into recent launches, thinking, and studio milestones worth sharing.</p>
  </div>

  <div class="news-list">
    <article class="news-item reveal">
      <div class="news-meta">
        <span class="news-date">March 2026</span>
        <span class="news-tag">Launch</span>
      </div>
      <h3 class="news-item-title">We launched a new editorial system for a luxury retail brand.</h3>
      <p class="news-item-copy">The site pairs sharper storytelling with a faster path to product detail pages and booking flows.</p>
      <a href="portfolio.html" class="news-link">Read more</a>
    </article>

    <article class="news-item reveal reveal-delay-1">
      <div class="news-meta">
        <span class="news-date">February 2026</span>
        <span class="news-tag">Insight</span>
      </div>
      <h3 class="news-item-title">Our latest thinking on premium brand systems is now in the archive.</h3>
      <p class="news-item-copy">A concise breakdown of how structure, tone, and motion can make complex brands feel calm.</p>
      <a href="about.html" class="news-link">Read more</a>
    </article>

    <article class="news-item reveal reveal-delay-2">
      <div class="news-meta">
        <span class="news-date">January 2026</span>
        <span class="news-tag">Studio</span>
      </div>
      <h3 class="news-item-title">We expanded capacity for select web and identity projects this quarter.</h3>
      <p class="news-item-copy">The studio is taking on a small number of new collaborations where depth, craft, and speed all matter.</p>
      <a href="contact.html" class="news-link">Get in touch</a>
    </article>
  </div>
</section>
<!-- Contact CTA -->
<section id="contact">
  <div class="cta-bg-text">VAUX</div>
  <div class="cta-label reveal">Available for new projects</div>
  <h2 class="cta-headline reveal">
    Let's build<br>something<br><em>exceptional.</em>
  </h2>
  <div class="cta-actions reveal">
    <a href="mailto:hello@vaux.studio" class="btn-large">Start a Project</a>
    <a href="mailto:hello@vaux.studio" class="cta-email">hello@vaux.studio</a>
  </div>
</section>

<?php get_footer(); ?>
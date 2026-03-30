<?php get_header(); ?>
 <!-- ============================================
     HERO SECTION
============================================ -->
  <section class="hero swiper" id="hero" aria-label="Hero">
    <div class="swiper-wrapper">
    <?php
$sliders = ws_get_post_meta(7, '_csp_slides');
if ($sliders) {
  foreach ($sliders as $slider) {
?>
        <div class="swiper-slide">
          <div class="hero__bg">
            <?php if (!empty($slider['link'])): ?>
              <a href="<?php echo esc_url($slider['link']); ?>" class="hero__bg-link">
                <img src="<?php echo esc_url($slider['image_url']); ?>" alt="<?php echo esc_attr($slider['name']); ?>" class="hero__bg-img">
              </a>
            <?php
    else: ?>
              <img src="<?php echo esc_url($slider['image_url']); ?>" alt="<?php echo esc_attr($slider['name']); ?>" class="hero__bg-img">
            <?php
    endif; ?>
          </div>
        </div>
        <?php
  }
}
?>
     
    </div>
    
    <!-- Slider Navigation -->
    <div class="swiper-pagination"></div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
  </section>

  <?php 
  $blocks = parse_blocks(get_the_content());

  foreach ($blocks as $block) {
    if (isset($block['blockName']) && $block['blockName'] === 'custom/about-hero') {

      echo render_block($block);
      break;
      
    }
  }



  ?>

  <!-- ============================================
     CORE CAPABILITIES
============================================ -->
  <?php 
  $blocks = parse_blocks(get_the_content());

  foreach ($blocks as $block) {
    if (isset($block['blockName']) && $block['blockName'] === 'axiom/capabilities') {

      echo render_block($block);
      break;
      
    }
  }



  ?>

  <!-- ============================================
     PRODUCT PORTFOLIO
============================================ -->
  <section class="products" id="products" aria-labelledby="products-title">
    <div class="container">
      <div class="products__header">
        <div>
          <div class="tag reveal" style="margin-bottom:20px">Product Portfolio</div>
          <h2 class="section-title reveal reveal-delay-1" id="products-title">Hub <span>Assembly</span><br>Systems</h2>
        </div>
        <div class="products__filter reveal" role="group" aria-label="Filter products">
          <button class="filter-btn active" data-filter="all">All</button>
          <button class="filter-btn" data-filter="gen1">Gen 1</button>
          <button class="filter-btn" data-filter="gen2">Gen 2</button>
          <button class="filter-btn" data-filter="gen3">Gen 3</button>
          <button class="filter-btn" data-filter="heavy">Heavy Duty</button>
        </div>
      </div>

      <div class="products__grid">

        <!-- Product 1 -->
        <div class="product-card reveal" data-category="gen2">
          <div class="product-card__image">
            <!-- IMAGE: Gen 2 hub assembly — front view, studio lit on dark background -->
            <span class="product-card__img-label">512px × 400px</span>
            <div class="product-card__overlay">
              <div class="product-card__specs">
                <div class="spec-item">
                  <div class="spec-item__label">Bolt Pattern</div>
                  <div class="spec-item__value">5×114.3</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Material</div>
                  <div class="spec-item__value">SAE 52100</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Load Rating</div>
                  <div class="spec-item__value">42kN</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Lifespan</div>
                  <div class="spec-item__value">200k km</div>
                </div>
              </div>
              <a href="#contact" class="product-card__quick-view">
                Request Datasheet
                <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg>
              </a>
            </div>
          </div>
          <div class="product-card__info">
            <div class="product-card__gen">Generation II · ABS Integrated</div>
            <div class="product-card__name">AX-2240 Hub Assembly</div>
            <div class="product-card__app">Passenger Vehicle · Sedan / SUV</div>
          </div>
          <div class="product-card__footer">
            <span class="product-card__part">P/N: AX-2240-F5</span>
            <span class="product-card__badge">ABS Ready</span>
          </div>
        </div>

        <!-- Product 2 -->
        <div class="product-card reveal reveal-delay-1" data-category="gen3">
          <div class="product-card__image">
            <!-- IMAGE: Gen 3 EV-optimised hub — angled 3/4 view, dark background with blue accent light -->
            <span class="product-card__img-label">512px × 400px</span>
            <div class="product-card__overlay">
              <div class="product-card__specs">
                <div class="spec-item">
                  <div class="spec-item__label">Bolt Pattern</div>
                  <div class="spec-item__value">5×120</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Material</div>
                  <div class="spec-item__value">SAE 8620</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Load Rating</div>
                  <div class="spec-item__value">56kN</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Lifespan</div>
                  <div class="spec-item__value">240k km</div>
                </div>
              </div>
              <a href="#contact" class="product-card__quick-view">
                Request Datasheet
                <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg>
              </a>
            </div>
          </div>
          <div class="product-card__info">
            <div class="product-card__gen">Generation III · EV Optimised</div>
            <div class="product-card__name">AX-3560 EV Hub Unit</div>
            <div class="product-card__app">Battery Electric Vehicle · BEV Platform</div>
          </div>
          <div class="product-card__footer">
            <span class="product-card__part">P/N: AX-3560-EV</span>
            <span class="product-card__badge">EV Ready</span>
          </div>
        </div>

        <!-- Product 3 -->
        <div class="product-card reveal reveal-delay-2" data-category="heavy">
          <div class="product-card__image">
            <!-- IMAGE: Heavy duty commercial hub — large format, forged flanges visible, textured surface detail -->
            <span class="product-card__img-label">512px × 400px</span>
            <div class="product-card__overlay">
              <div class="product-card__specs">
                <div class="spec-item">
                  <div class="spec-item__label">Bolt Pattern</div>
                  <div class="spec-item__value">10×335</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Material</div>
                  <div class="spec-item__value">Alloy 42CrMo4</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Load Rating</div>
                  <div class="spec-item__value">160kN</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Lifespan</div>
                  <div class="spec-item__value">500k km</div>
                </div>
              </div>
              <a href="#contact" class="product-card__quick-view">
                Request Datasheet
                <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg>
              </a>
            </div>
          </div>
          <div class="product-card__info">
            <div class="product-card__gen">Heavy Duty · Commercial</div>
            <div class="product-card__name">AX-HD8100 Drive Hub</div>
            <div class="product-card__app">Heavy Commercial Vehicle · Truck / Bus</div>
          </div>
          <div class="product-card__footer">
            <span class="product-card__part">P/N: AX-HD8100</span>
            <span class="product-card__badge">HD Series</span>
          </div>
        </div>

        <!-- Product 4 -->
        <div class="product-card reveal" data-category="gen1">
          <div class="product-card__image">
            <!-- IMAGE: Gen 1 basic hub assembly — clean side-profile, neutral background -->
            <span class="product-card__img-label">512px × 400px</span>
            <div class="product-card__overlay">
              <div class="product-card__specs">
                <div class="spec-item">
                  <div class="spec-item__label">Bolt Pattern</div>
                  <div class="spec-item__value">4×100</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Material</div>
                  <div class="spec-item__value">SAE 52100</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Load Rating</div>
                  <div class="spec-item__value">28kN</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Lifespan</div>
                  <div class="spec-item__value">150k km</div>
                </div>
              </div>
              <a href="#contact" class="product-card__quick-view">
                Request Datasheet
                <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg>
              </a>
            </div>
          </div>
          <div class="product-card__info">
            <div class="product-card__gen">Generation I · Standard</div>
            <div class="product-card__name">AX-1100 Base Hub</div>
            <div class="product-card__app">Passenger Vehicle · Entry / Mid Segment</div>
          </div>
          <div class="product-card__footer">
            <span class="product-card__part">P/N: AX-1100-R4</span>
            <span class="product-card__badge">Standard</span>
          </div>
        </div>

        <!-- Product 5 -->
        <div class="product-card reveal reveal-delay-1" data-category="gen2">
          <div class="product-card__image">
            <!-- IMAGE: Gen 2 ABS integrated hub with encoder ring visible — macro/detail shot -->
            <span class="product-card__img-label">512px × 400px</span>
            <div class="product-card__overlay">
              <div class="product-card__specs">
                <div class="spec-item">
                  <div class="spec-item__label">Bolt Pattern</div>
                  <div class="spec-item__value">5×108</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">ABS Encoder</div>
                  <div class="spec-item__value">48-pole</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Load Rating</div>
                  <div class="spec-item__value">38kN</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Lifespan</div>
                  <div class="spec-item__value">200k km</div>
                </div>
              </div>
              <a href="#contact" class="product-card__quick-view">
                Request Datasheet
                <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg>
              </a>
            </div>
          </div>
          <div class="product-card__info">
            <div class="product-card__gen">Generation II · Sensor Integrated</div>
            <div class="product-card__name">AX-2480S Sensor Hub</div>
            <div class="product-card__app">Passenger Vehicle · ADAS Enabled</div>
          </div>
          <div class="product-card__footer">
            <span class="product-card__part">P/N: AX-2480S</span>
            <span class="product-card__badge">ADAS</span>
          </div>
        </div>

        <!-- Product 6 -->
        <div class="product-card reveal reveal-delay-2" data-category="gen3">
          <div class="product-card__image">
            <!-- IMAGE: Gen 3 lightweight hub assembly — exploded or cross-section view showing lightweight design -->
            <span class="product-card__img-label">512px × 400px</span>
            <div class="product-card__overlay">
              <div class="product-card__specs">
                <div class="spec-item">
                  <div class="spec-item__label">Bolt Pattern</div>
                  <div class="spec-item__value">5×130</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Weight</div>
                  <div class="spec-item__value">3.2kg</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Load Rating</div>
                  <div class="spec-item__value">48kN</div>
                </div>
                <div class="spec-item">
                  <div class="spec-item__label">Lifespan</div>
                  <div class="spec-item__value">250k km</div>
                </div>
              </div>
              <a href="#contact" class="product-card__quick-view">
                Request Datasheet
                <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg>
              </a>
            </div>
          </div>
          <div class="product-card__info">
            <div class="product-card__gen">Generation III · Lightweight</div>
            <div class="product-card__name">AX-3200 LiteCore Hub</div>
            <div class="product-card__app">Hybrid / EV · Weight-Optimised Platform</div>
          </div>
          <div class="product-card__footer">
            <span class="product-card__part">P/N: AX-3200-LC</span>
            <span class="product-card__badge">Lightweight</span>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- ============================================
     ENGINEERING & R&D
============================================ -->
  <section class="engineering" id="engineering" aria-labelledby="engineering-title">
    <div class="container">
      <div class="engineering__layout">
        <div class="engineering__visual reveal">
          <!-- IMAGE: Engineering — CAD model render, FEA stress simulation, exploded hub assembly diagram, or cross-section technical drawing. Dark/blue-tinted background. -->
          <div class="engineering__visual-overlay"></div>
          <div class="data-callout">
            <div class="data-callout__val">FEA</div>
            <div class="data-callout__lbl">Simulation Validated</div>
          </div>
          <div class="data-callout">
            <div class="data-callout__val">1.2B</div>
            <div class="data-callout__lbl">Load Cycles Tested</div>
          </div>
          <div class="engineering__visual-label">
            <div class="engineering__visual-tag">Model · AX-3560-EV</div>
            <div class="engineering__visual-title">FEA Structural Analysis — Bearing Race Interface</div>
          </div>
        </div>

        <div class="engineering__content">
          <div class="tag reveal">Engineering & R&D</div>
          <h2 class="section-title reveal reveal-delay-1" id="engineering-title">
            Engineered<br>for the <span>Next</span><br>Generation
          </h2>
          <p class="engineering__desc reveal reveal-delay-2">
            Our 120-strong engineering team operates dedicated simulation, prototyping, and validation labs. From
            initial topology optimisation to NVH-tuned final geometry, every Axiom Drive product is designed with
            performance, longevity, and manufacturability as core parameters.
          </p>
          <div class="engineering__pillars reveal reveal-delay-3">
            <div class="pillar">
              <div class="pillar__num">01</div>
              <div>
                <div class="pillar__title">FEA & Topology Optimisation</div>
                <p class="pillar__text">Multi-body dynamic simulation using ANSYS and NASTRAN. Weight reduction targets
                  achieved without compromising structural margin.</p>
              </div>
            </div>
            <div class="pillar">
              <div class="pillar__num">02</div>
              <div>
                <div class="pillar__title">NVH Tuning & Seal Design</div>
                <p class="pillar__text">Acoustic engineering integrated at design stage. Grease-filled multi-lip seals
                  engineered for low drag torque and lifetime lubrication.</p>
              </div>
            </div>
            <div class="pillar">
              <div class="pillar__num">03</div>
              <div>
                <div class="pillar__title">EV Compatibility & EMC</div>
                <p class="pillar__text">Shielded encoder ring designs compliant with EMC requirements. Validated for
                  high-voltage EV environments and regen braking loads.</p>
              </div>
            </div>
            <div class="pillar">
              <div class="pillar__num">04</div>
              <div>
                <div class="pillar__title">Lightweighting & Materials</div>
                <p class="pillar__text">Selective material substitution with advanced high-strength steels. 18% average
                  mass reduction on Gen III versus Gen II designs.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     MANUFACTURING PROCESS
============================================ -->
  <section class="manufacturing" id="manufacturing" aria-labelledby="manufacturing-title">
    <div class="container">
      <div class="manufacturing__header">
        <div class="tag reveal" style="margin-bottom:20px">Manufacturing Excellence</div>
        <h2 class="section-title reveal reveal-delay-1" id="manufacturing-title">
          From Raw <span>Steel</span><br>to Shipped Unit
        </h2>
      </div>

      <div class="manufacturing__flow">
        <div class="process-step reveal">
          <div class="process-step__icon" aria-hidden="true">
            <svg class="icon" viewBox="0 0 24 24">
              <rect x="3" y="3" width="7" height="7" />
              <rect x="14" y="3" width="7" height="7" />
              <rect x="14" y="14" width="7" height="7" />
              <rect x="3" y="14" width="7" height="7" />
            </svg>
          </div>
          <div class="process-step__num">01</div>
          <div class="process-step__title">Raw Material</div>
          <p class="process-step__text">Certified bearing-grade steel billets, source-verified and spectrally analysed
          </p>
        </div>
        <div class="process-step reveal reveal-delay-1">
          <div class="process-step__icon" aria-hidden="true">
            <svg class="icon" viewBox="0 0 24 24">
              <path d="M12 2L2 7l10 5 10-5-10-5z" />
              <path d="M2 17l10 5 10-5" />
              <path d="M2 12l10 5 10-5" />
            </svg>
          </div>
          <div class="process-step__num">02</div>
          <div class="process-step__title">Forging</div>
          <p class="process-step__text">Closed-die hot forging under 4,500T pressure. Grain flow optimised for fatigue
          </p>
        </div>
        <div class="process-step reveal reveal-delay-2">
          <div class="process-step__icon" aria-hidden="true">
            <svg class="icon" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="3" />
              <path
                d="M19.07 4.93l-1.41 1.41M6.34 17.66l-1.41 1.41M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M12 2v2M12 20v2" />
            </svg>
          </div>
          <div class="process-step__num">03</div>
          <div class="process-step__title">CNC Machining</div>
          <p class="process-step__text">5-axis turning centres with sub-2µm bearing race tolerances, 24/7 operation</p>
        </div>
        <div class="process-step reveal reveal-delay-3">
          <div class="process-step__icon" aria-hidden="true">
            <svg class="icon" viewBox="0 0 24 24">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <path d="M12 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </div>
          <div class="process-step__num">04</div>
          <div class="process-step__title">Heat Treatment</div>
          <p class="process-step__text">Carburizing and induction hardening to 62 HRC. Precision case depth profiles</p>
        </div>
        <div class="process-step reveal">
          <div class="process-step__icon" aria-hidden="true">
            <svg class="icon" viewBox="0 0 24 24">
              <polyline points="23 4 23 10 17 10" />
              <polyline points="1 20 1 14 7 14" />
              <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
            </svg>
          </div>
          <div class="process-step__num">05</div>
          <div class="process-step__title">Assembly</div>
          <p class="process-step__text">Automated bearing press, ABS encoder fitting, and grease fill in cleanroom
            conditions</p>
        </div>
        <div class="process-step reveal reveal-delay-1">
          <div class="process-step__icon" aria-hidden="true">
            <svg class="icon" viewBox="0 0 24 24">
              <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
              <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
          </div>
          <div class="process-step__num">06</div>
          <div class="process-step__title">QC & Dispatch</div>
          <p class="process-step__text">100% EOL testing, CMM dimensional check, packaging, and global shipment</p>
        </div>
      </div>

      <!-- Manufacturing image strip -->
      <div class="manufacturing__imagery reveal">
        <div class="process-image">
          <!-- IMAGE: Steel billet / raw material stockyard — industrial overhead lighting -->
          <div class="process-image__label">Raw Material</div>
        </div>
        <div class="process-image">
          <!-- IMAGE: Hot forging press in action — sparks, red-hot steel, dramatic industrial shot -->
          <div class="process-image__label">Forging</div>
        </div>
        <div class="process-image">
          <!-- IMAGE: CNC machining center close-up — cutting tool on hub blank, coolant streams -->
          <div class="process-image__label">CNC Machining</div>
        </div>
        <div class="process-image">
          <!-- IMAGE: Heat treatment furnace — glowing orange parts in controlled atmosphere oven -->
          <div class="process-image__label">Heat Treatment</div>
        </div>
        <div class="process-image">
          <!-- IMAGE: Automated assembly robot — robotic arm pressing bearing into hub flange -->
          <div class="process-image__label">Assembly</div>
        </div>
        <div class="process-image">
          <!-- IMAGE: CMM inspection / quality lab — coordinate measuring machine probing finished hub -->
          <div class="process-image__label">Quality & Dispatch</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     QUALITY ASSURANCE
============================================ -->
  <section class="quality" id="quality" aria-labelledby="quality-title">
    <div class="container">
      <div class="quality__layout">
        <div>
          <div class="tag reveal" style="margin-bottom:20px">Quality Assurance</div>
          <h2 class="section-title reveal reveal-delay-1" id="quality-title">
            Zero<br><span>Defect</span><br>Standard
          </h2>

          <div class="quality__certifications reveal reveal-delay-2">
            <div class="cert-card">
              <div class="cert-card__badge">IATF 16949</div>
              <div class="cert-card__name">Automotive QMS</div>
              <p class="cert-card__desc">Automotive-specific quality management system for manufacturing excellence and
                continual improvement.</p>
            </div>
            <div class="cert-card">
              <div class="cert-card__badge">ISO 9001</div>
              <div class="cert-card__name">Quality Management</div>
              <p class="cert-card__desc">International standard for quality management systems ensuring consistent
                product and service quality.</p>
            </div>
            <div class="cert-card">
              <div class="cert-card__badge">ISO 14001</div>
              <div class="cert-card__name">Environmental Mgmt</div>
              <p class="cert-card__desc">Environmental management certification confirming sustainable and responsible
                manufacturing operations.</p>
            </div>
            <div class="cert-card">
              <div class="cert-card__badge">TS 16949</div>
              <div class="cert-card__name">OEM Supply Chain</div>
              <p class="cert-card__desc">Global OEM supplier qualification supporting Tier-1 supply chain integration
                requirements.</p>
            </div>
          </div>

          <div class="quality__tests reveal reveal-delay-3">
            <div class="test-row">
              <div class="test-row__name">Radial Load Fatigue Test</div>
              <div class="test-bar">
                <div class="test-bar__fill" data-width="95"></div>
              </div>
              <div class="test-row__val">1.2B cycles</div>
            </div>
            <div class="test-row">
              <div class="test-row__name">NVH Noise Floor</div>
              <div class="test-bar">
                <div class="test-bar__fill" data-width="88"></div>
              </div>
              <div class="test-row__val">&lt;38 dB(A)</div>
            </div>
            <div class="test-row">
              <div class="test-row__name">Corrosion Resistance (Salt Spray)</div>
              <div class="test-bar">
                <div class="test-bar__fill" data-width="100"></div>
              </div>
              <div class="test-row__val">1,200 hrs</div>
            </div>
            <div class="test-row">
              <div class="test-row__name">ABS Encoder Accuracy</div>
              <div class="test-bar">
                <div class="test-bar__fill" data-width="92"></div>
              </div>
              <div class="test-row__val">±0.1°</div>
            </div>
            <div class="test-row">
              <div class="test-row__name">Dimensional CMM Inspection</div>
              <div class="test-bar">
                <div class="test-bar__fill" data-width="100"></div>
              </div>
              <div class="test-row__val">100% coverage</div>
            </div>
          </div>
        </div>

        <div class="quality__visual reveal reveal-delay-2">
          <div class="quality__visual-box">
            <!-- IMAGE: Quality inspection — CMM coordinate measuring machine with probe touching hub surface, or torque testing rig, or noise test bench -->
            <div class="quality__visual-info">
              <div class="quality__visual-title">CMM Dimensional Inspection</div>
              <div class="quality__visual-sub">Zeiss Contura G2 · Sub-micron accuracy · 100% critical dimensions</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     INDUSTRIES & APPLICATIONS
============================================ -->
  <section class="industries" id="industries" aria-labelledby="industries-title">
    <div class="container">
      <div class="tag reveal" style="margin-bottom:20px">Industries & Applications</div>
      <h2 class="section-title reveal reveal-delay-1" id="industries-title">
        Global <span>Applications</span>
      </h2>

      <div class="industries__grid">
        <div class="industry-card reveal">
          <div class="industry-card__image">
            <!-- IMAGE: Passenger vehicle — premium sedan or SUV on road, wheel area detail -->
            <span class="industry-card__number">01</span>
          </div>
          <div class="industry-card__content">
            <div class="industry-card__title">Passenger Vehicles</div>
            <p class="industry-card__desc">Gen I, II & III hub assemblies for sedan, SUV, and crossover platforms.
              Covering 85% of global PV bolt patterns.</p>
            <a href="#contact" class="industry-card__link">Learn more <svg class="icon icon--sm" viewBox="0 0 24 24">
                <line x1="5" y1="12" x2="19" y2="12" />
                <polyline points="12 5 19 12 12 19" />
              </svg></a>
          </div>
        </div>
        <div class="industry-card reveal reveal-delay-1">
          <div class="industry-card__image">
            <!-- IMAGE: Commercial vehicle — heavy truck front wheel, industrial/logistics environment -->
            <span class="industry-card__number">02</span>
          </div>
          <div class="industry-card__content">
            <div class="industry-card__title">Commercial Vehicles</div>
            <p class="industry-card__desc">Heavy-duty hub systems for trucks, buses, and trailers. Engineered for
              maximum axle loads and extended service intervals.</p>
            <a href="#contact" class="industry-card__link">Learn more <svg class="icon icon--sm" viewBox="0 0 24 24">
                <line x1="5" y1="12" x2="19" y2="12" />
                <polyline points="12 5 19 12 12 19" />
              </svg></a>
          </div>
        </div>
        <div class="industry-card reveal reveal-delay-2">
          <div class="industry-card__image">
            <!-- IMAGE: Electric vehicle — EV undercarriage or wheel well showing hub unit, modern clean aesthetic -->
            <span class="industry-card__number">03</span>
          </div>
          <div class="industry-card__content">
            <div class="industry-card__title">EV Platforms</div>
            <p class="industry-card__desc">EMC-shielded, lightweight Gen III hubs for battery electric platforms.
              Optimised for regen braking loads and low NVH.</p>
            <a href="#contact" class="industry-card__link">Learn more <svg class="icon icon--sm" viewBox="0 0 24 24">
                <line x1="5" y1="12" x2="19" y2="12" />
                <polyline points="12 5 19 12 12 19" />
              </svg></a>
          </div>
        </div>
        <div class="industry-card reveal reveal-delay-3">
          <div class="industry-card__image">
            <!-- IMAGE: Aftermarket — stacked hub assemblies in distribution warehouse or parts catalogue layout -->
            <span class="industry-card__number">04</span>
          </div>
          <div class="industry-card__content">
            <div class="industry-card__title">Aftermarket</div>
            <p class="industry-card__desc">OEM-equivalent aftermarket assemblies for global distributors. Branded or
              white-label supply with 120+ active SKUs.</p>
            <a href="#contact" class="industry-card__link">Learn more <svg class="icon icon--sm" viewBox="0 0 24 24">
                <line x1="5" y1="12" x2="19" y2="12" />
                <polyline points="12 5 19 12 12 19" />
              </svg></a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     GLOBAL SUPPLY & LOGISTICS
============================================ -->
  <section class="global" id="global" aria-labelledby="global-title">
    <div class="container">
      <div class="global__layout">
        <div class="global__map-wrapper">
          <div class="global__map reveal">
            <!-- IMAGE: World map — stylized dot or outline map showing export regions. SVG or image. Highlight: Europe, North America, Southeast Asia, Middle East, South America -->
            <div class="global__map-overlay"></div>
            <div class="global__marker"></div>
            <div class="global__marker"></div>
            <div class="global__marker"></div>
            <div class="global__marker"></div>
            <div class="global__marker"></div>
          </div>
          <div class="global__regions reveal reveal-delay-1">
            <div class="region-cell">
              <div class="region-cell__name">Europe</div>
              <div class="region-cell__count">18</div>
            </div>
            <div class="region-cell">
              <div class="region-cell__name">Americas</div>
              <div class="region-cell__count">14</div>
            </div>
            <div class="region-cell">
              <div class="region-cell__name">Asia-Pacific</div>
              <div class="region-cell__count">22</div>
            </div>
            <div class="region-cell">
              <div class="region-cell__name">Middle East</div>
              <div class="region-cell__count">6</div>
            </div>
            <div class="region-cell">
              <div class="region-cell__name">Africa</div>
              <div class="region-cell__count">8</div>
            </div>
            <div class="region-cell">
              <div class="region-cell__name">CIS</div>
              <div class="region-cell__count">4</div>
            </div>
          </div>
        </div>

        <div class="global__content">
          <div class="tag reveal">Global Supply & Logistics</div>
          <h2 class="section-title reveal reveal-delay-1" id="global-title">
            62 Countries.<br><span>One</span><br>Standard.
          </h2>
          <p class="global__desc reveal reveal-delay-2">
            Axiom Drive maintains dedicated logistics partnerships with six global freight operators, enabling
            consistent lead times regardless of destination. Our regional warehousing hubs in Rotterdam, Dubai, and
            Singapore ensure rapid replenishment for key distribution partners.
          </p>
          <div class="logistics-grid reveal reveal-delay-3">
            <div class="logistics-item">
              <div class="logistics-item__icon">
                <svg class="icon" viewBox="0 0 24 24">
                  <rect x="1" y="3" width="15" height="13" />
                  <polygon points="16 8 20 8 23 11 23 16 16 16 16 8" />
                  <circle cx="5.5" cy="18.5" r="2.5" />
                  <circle cx="18.5" cy="18.5" r="2.5" />
                </svg>
              </div>
              <div class="logistics-item__title">14-Day Lead Time</div>
              <p class="logistics-item__text">Standard SKUs dispatched within 14 business days. Express 7-day available
                for prioritised OEM orders.</p>
            </div>
            <div class="logistics-item">
              <div class="logistics-item__icon">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M20 7H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z" />
                  <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                </svg>
              </div>
              <div class="logistics-item__title">Regional Warehousing</div>
              <p class="logistics-item__text">Strategic stock positions in EU, UAE, and APAC. Bonded warehouse
                capability for duty management.</p>
            </div>
            <div class="logistics-item">
              <div class="logistics-item__icon">
                <svg class="icon" viewBox="0 0 24 24">
                  <circle cx="12" cy="12" r="10" />
                  <polyline points="12 6 12 12 16 14" />
                </svg>
              </div>
              <div class="logistics-item__title">99.4% OTIF</div>
              <p class="logistics-item__text">On-time in-full delivery performance across all channels. Tracked and
                reported per OEM programme.</p>
            </div>
            <div class="logistics-item">
              <div class="logistics-item__icon">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
              </div>
              <div class="logistics-item__title">Traceability</div>
              <p class="logistics-item__text">Full material traceability from billet heat to shipped unit. QR-coded
                packaging and digital mill certificates.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     SUSTAINABILITY
============================================ -->
  <section class="sustainability" id="sustainability" aria-labelledby="sustainability-title">
    <div class="container">
      <div class="sustainability__grid">
        <div>
          <div class="tag reveal" style="margin-bottom:20px">Sustainability</div>
          <h2 class="section-title reveal reveal-delay-1" id="sustainability-title"
            style="font-size:clamp(32px,3.5vw,52px)">
            Clean<br>Manufacturing
          </h2>
        </div>
        <div class="sustainability__pillars reveal reveal-delay-2">
          <div class="sustain-card">
            <div class="sustain-card__icon">
              <svg class="icon icon--lg" viewBox="0 0 24 24">
                <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2zm0 0v10l6 3" />
              </svg>
            </div>
            <div class="sustain-card__title">Energy Reduction</div>
            <p class="sustain-card__text">32% reduction in kWh per unit since 2020. Variable-frequency drives on all
              press and machining centre spindles.</p>
          </div>
          <div class="sustain-card">
            <div class="sustain-card__icon">
              <svg class="icon icon--lg" viewBox="0 0 24 24">
                <polyline points="23 4 23 10 17 10" />
                <polyline points="1 20 1 14 7 14" />
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15" />
              </svg>
            </div>
            <div class="sustain-card__title">Closed-Loop Steel</div>
            <p class="sustain-card__text">96% of forging flash and CNC swarf returned to certified steel recyclers. Zero
              landfill target achieved for metallic waste streams.</p>
          </div>
          <div class="sustain-card">
            <div class="sustain-card__icon">
              <svg class="icon icon--lg" viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
              </svg>
            </div>
            <div class="sustain-card__title">RoHS & REACH</div>
            <p class="sustain-card__text">Full RoHS and REACH compliance. No restricted substances in coatings,
              lubricants, or seal compounds. Documented substance-of-concern register.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     CLIENTS / TRUST SIGNALS
============================================ -->
  <section class="trust" id="trust" aria-label="Trust signals and clients">
    <div class="container">
      <div class="tag reveal" style="margin-bottom:20px">Global Partners</div>
      <h2 class="section-title reveal reveal-delay-1">
        Trusted by <span>OEMs</span><br>Worldwide
      </h2>

      <div class="trust__stats reveal reveal-delay-2">
        <div class="trust-stat">
          <div class="trust-stat__value">24<span>+</span></div>
          <div class="trust-stat__label">Years in Production</div>
        </div>
        <div class="trust-stat">
          <div class="trust-stat__value">8<span>M</span></div>
          <div class="trust-stat__label">Units / Year</div>
        </div>
        <div class="trust-stat">
          <div class="trust-stat__value">62</div>
          <div class="trust-stat__label">Export Countries</div>
        </div>
        <div class="trust-stat">
          <div class="trust-stat__value"><span>&lt;</span>1</div>
          <div class="trust-stat__label">PPM Field Defect Rate</div>
        </div>
      </div>

      <!-- Client logo marquee -->
      <div class="trust__clients" aria-label="Partner and client logos">
        <div class="trust__clients-track">
          <!-- IMAGE PLACEHOLDERS: OEM / partner brand logos, greyscale -->
          <div class="client-logo">OEM Partner</div>
          <div class="client-logo">Global Auto</div>
          <div class="client-logo">Euro Tier-1</div>
          <div class="client-logo">Asia OEM</div>
          <div class="client-logo">EV Brand Co</div>
          <div class="client-logo">TruckCo</div>
          <div class="client-logo">DistributorA</div>
          <div class="client-logo">OEM Partner</div>
          <div class="client-logo">Global Auto</div>
          <div class="client-logo">Euro Tier-1</div>
          <div class="client-logo">Asia OEM</div>
          <div class="client-logo">EV Brand Co</div>
          <div class="client-logo">TruckCo</div>
          <div class="client-logo">DistributorA</div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     NEWS / INSIGHTS
============================================ -->
  <section class="news" id="news" aria-labelledby="news-title">
    <div class="container">
      <div style="display:flex;justify-content:space-between;align-items:flex-end">
        <div>
          <div class="tag reveal" style="margin-bottom:20px">News & Insights</div>
          <h2 class="section-title reveal reveal-delay-1" id="news-title">Latest from<br><span>Axiom Drive</span></h2>
        </div>
        <a href="#" class="btn-outline reveal" style="white-space:nowrap">All Articles <svg class="icon icon--sm"
            viewBox="0 0 24 24">
            <line x1="5" y1="12" x2="19" y2="12" />
            <polyline points="12 5 19 12 12 19" />
          </svg></a>
      </div>

      <div class="news__grid">
        <!-- Featured -->
        <div class="news-card reveal">
          <div class="news-card__image">
            <!-- IMAGE: Gen 3 EV hub product launch visual — dramatic product photography or factory event photo -->
          </div>
          <div class="news-card__content">
            <div class="news-card__category">Product Launch</div>
            <h3 class="news-card__title">AX-3560 EV Hub Unit Enters Series Production</h3>
            <p class="news-card__excerpt">Our third-generation EV-optimised hub assembly begins series supply to a
              global BEV platform, delivering 18% weight reduction and full EMC compliance for high-voltage
              environments.</p>
            <div class="news-card__meta">
              <span class="news-card__date">March 2025</span>
              <span class="news-card__read-more">Read more <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg></span>
            </div>
          </div>
        </div>
        <!-- Article 2 -->
        <div class="news-card reveal reveal-delay-1">
          <div class="news-card__image">
            <!-- IMAGE: Trade show booth or Automechanika exhibition floor -->
          </div>
          <div class="news-card__content">
            <div class="news-card__category">Industry</div>
            <h3 class="news-card__title">Axiom Drive at Automechanika Frankfurt 2025</h3>
            <p class="news-card__excerpt">Showcasing the complete Gen III portfolio and new heavy-duty commercial
              vehicle hub range to global buyers.</p>
            <div class="news-card__meta">
              <span class="news-card__date">September 2025</span>
              <span class="news-card__read-more">Read more <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg></span>
            </div>
          </div>
        </div>
        <!-- Article 3 -->
        <div class="news-card reveal reveal-delay-2">
          <div class="news-card__image">
            <!-- IMAGE: Engineering insight — FEA visualization, cross-section diagram, or engineer at workstation -->
          </div>
          <div class="news-card__content">
            <div class="news-card__category">Engineering Insight</div>
            <h3 class="news-card__title">NVH Engineering in Next-Gen Hub Assemblies</h3>
            <p class="news-card__excerpt">How seal geometry and grease viscosity selection drive noise reduction below
              38 dB(A) — a technical deep dive.</p>
            <div class="news-card__meta">
              <span class="news-card__date">January 2025</span>
              <span class="news-card__read-more">Read more <svg class="icon icon--sm" viewBox="0 0 24 24">
                  <line x1="5" y1="12" x2="19" y2="12" />
                  <polyline points="12 5 19 12 12 19" />
                </svg></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================================
     CONTACT / RFQ
============================================ -->
  <section class="contact" id="contact" aria-labelledby="contact-title">
    <div class="container">
      <div class="contact__layout">
        <div class="contact__info">
          <div class="tag reveal" style="margin-bottom:20px">Contact & RFQ</div>
          <h2 class="section-title reveal reveal-delay-1" id="contact-title" style="font-size:clamp(36px,4vw,64px)">
            Start a<br><span>Technical</span><br>Conversation
          </h2>
          <p class="contact__desc reveal reveal-delay-2">
            Whether you're qualifying a new supplier, sourcing specific hub assemblies, or exploring OEM partnership,
            our engineering and commercial teams are ready to respond within 24 hours.
          </p>
          <div class="contact__details reveal reveal-delay-3">
            <div class="contact-detail">
              <div class="contact-detail__label">Headquarters</div>
              <div class="contact-detail__value">Axiom Drive Industrial Park, Block A, Sector 7, Global Manufacturing
                Zone</div>
            </div>
            <div class="contact-detail">
              <div class="contact-detail__label">OEM Sales</div>
              <div class="contact-detail__value">oem@axiomdrive.com<br>+1 (800) AXI-DRIVE</div>
            </div>
            <div class="contact-detail">
              <div class="contact-detail__label">Aftermarket</div>
              <div class="contact-detail__value">aftermarket@axiomdrive.com</div>
            </div>
            <div class="contact-detail">
              <div class="contact-detail__label">Engineering</div>
              <div class="contact-detail__value">engineering@axiomdrive.com</div>
            </div>
            <div class="contact-detail">
              <div class="contact-detail__label">Certifications</div>
              <div class="contact-detail__value">IATF 16949 · ISO 9001 · ISO 14001</div>
            </div>
          </div>
        </div>

        <div class="contact__form reveal reveal-delay-2">
          <div class="form-title">Request Technical Quote</div>
          <form id="rfqForm" novalidate>
            <div class="form-grid">
              <div class="form-group">
                <label class="form-label" for="firstName">First Name *</label>
                <input class="form-input" type="text" id="firstName" name="firstName" placeholder="John" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="lastName">Last Name *</label>
                <input class="form-input" type="text" id="lastName" name="lastName" placeholder="Smith" required>
              </div>
              <div class="form-group">
                <label class="form-label" for="company">Company / Organisation *</label>
                <input class="form-input" type="text" id="company" name="company" placeholder="Acme Automotive GmbH"
                  required>
              </div>
              <div class="form-group">
                <label class="form-label" for="title">Job Title</label>
                <input class="form-input" type="text" id="title" name="title" placeholder="Procurement Engineer">
              </div>
              <div class="form-group">
                <label class="form-label" for="email">Email Address *</label>
                <input class="form-input" type="email" id="email" name="email" placeholder="john.smith@acme.com"
                  required>
              </div>
              <div class="form-group">
                <label class="form-label" for="phone">Phone Number</label>
                <input class="form-input" type="tel" id="phone" name="phone" placeholder="+49 XXX XXXXXX">
              </div>
              <div class="form-group form-group--full">
                <label class="form-label" for="inquiry">Inquiry Type *</label>
                <select class="form-select" id="inquiry" name="inquiry" required>
                  <option value="" disabled selected>Select inquiry type…</option>
                  <option>OEM Supply Qualification</option>
                  <option>Aftermarket Distribution</option>
                  <option>Technical / Engineering Query</option>
                  <option>Custom / Development Programme</option>
                  <option>Quality / Certification Documents</option>
                  <option>General Enquiry</option>
                </select>
              </div>
              <div class="form-group form-group--full">
                <label class="form-label" for="product">Product / Part Number (if known)</label>
                <input class="form-input" type="text" id="product" name="product"
                  placeholder="e.g. AX-2240-F5 or 5×114.3 hub, passenger sedan">
              </div>
              <div class="form-group form-group--full">
                <label class="form-label" for="message">Technical Requirements & Volumes *</label>
                <textarea class="form-textarea" id="message" name="message"
                  placeholder="Please describe your application, required specifications, annual volume estimates, and any OEM programme details…"
                  required></textarea>
              </div>
            </div>
            <button type="submit" class="form-submit">
              <svg class="icon icon--sm" viewBox="0 0 24 24">
                <line x1="22" y1="2" x2="11" y2="13" />
                <polygon points="22 2 15 22 11 13 2 9 22 2" />
              </svg>
              Submit Technical RFQ
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>

  <?php get_footer(); ?>
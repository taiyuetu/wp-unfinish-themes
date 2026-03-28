<?php
/**
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

$services = $attributes['services'] ?? [];
?>
<section <?php echo get_block_wrapper_attributes(['id' => 'services']); ?>>
    <div class="services-header reveal">
        <div>
            <div class="section-label"><?php echo wp_kses_post($attributes['label']); ?></div>
            <h2 class="services-title">
                <?php echo wp_kses_post($attributes['titleTop']); ?><br>
                <em><?php echo wp_kses_post($attributes['titleEm']); ?></em>
            </h2>
        </div>
        <p class="services-intro"><?php echo wp_kses_post($attributes['intro']); ?></p>
    </div>
    
    <div class="services-list">
        <?php foreach ($services as $index => $service): ?>
            <div class="service-item reveal reveal-delay-<?php echo esc_attr($index); ?>">
                <div class="service-num"><?php echo wp_kses_post($service['num']); ?></div>
                <div class="service-name"><?php echo wp_kses_post($service['name']); ?></div>
                <div class="service-desc"><?php echo wp_kses_post($service['desc']); ?></div>
                <div class="service-arrow">↗</div>
            </div>
        <?php
endforeach; ?>
    </div>
</section>
<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$section_label = $attributes['sectionLabel'] ?? '';
$philosophy_statement = $attributes['philosophyStatement'] ?? '';
$philosophy_text1 = $attributes['philosophyText1'] ?? '';
$philosophy_text2 = $attributes['philosophyText2'] ?? '';
$stats = $attributes['stats'] ?? [];

$wrapper_attributes = get_block_wrapper_attributes([
    'id' => 'philosophy',
    'class' => 'wp-block-philosophy-section'
]);
?>

<section <?php echo $wrapper_attributes; ?>>
    <div class="phil-left reveal">
        <div class="section-label"><?php echo wp_kses_post($section_label); ?></div>
        <div class="phil-statement">
            <?php echo wp_kses_post($philosophy_statement); ?>
        </div>
    </div>
    <div class="phil-right reveal reveal-delay-2">
        <p class="phil-text">
            <?php echo wp_kses_post($philosophy_text1); ?>
        </p>
        <p class="phil-text">
            <?php echo wp_kses_post($philosophy_text2); ?>
        </p>
        <div class="phil-divider"></div>
        <div class="phil-stats">
            <?php foreach ($stats as $stat): ?>
                <div>
                    <div class="phil-stat-num"><?php echo esc_html($stat['num']); ?></div>
                    <div class="phil-stat-label"><?php echo esc_html($stat['label']); ?></div>
                </div>
            <?php
endforeach; ?>
        </div>
    </div>
</section>
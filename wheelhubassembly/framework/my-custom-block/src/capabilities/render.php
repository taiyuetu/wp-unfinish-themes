<?php
/**
 * render.php
 *
 * Server-side rendering for the Axiom Capabilities block.
 * $attributes is provided automatically by the block renderer.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner blocks HTML (unused – dynamic block).
 * @var WP_Block $block      Block instance.
 */

$tag           = isset( $attributes['tag'] )          ? $attributes['tag']          : 'Core Capabilities';
$section_title = isset( $attributes['sectionTitle'] ) ? $attributes['sectionTitle'] : '';
$intro         = isset( $attributes['intro'] )         ? $attributes['intro']         : '';
$cards         = isset( $attributes['cards'] )         ? $attributes['cards']         : [];

/**
 * Card SVG icons indexed to match the editor.
 */
$icons = [
	// 01
	'<svg class="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>',
	// 02
	'<svg class="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="2"/><path d="M7 7h10M7 12h10M7 17h6"/></svg>',
	// 03
	'<svg class="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 100 20A10 10 0 0012 2z"/><path d="M12 6v6l4 2"/></svg>',
	// 04
	'<svg class="icon icon--lg" viewBox="0 0 24 24" aria-hidden="true"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>',
];

$wrapper_attrs = get_block_wrapper_attributes( [ 'class' => 'capabilities' ] );
?>

<section <?php echo $wrapper_attrs; ?>>
	<div class="container">

		<div class="capabilities__header">
			<div>
				<div class="tag reveal"><?php echo wp_kses_post( $tag ); ?></div>
				<h2 class="section-title reveal reveal-delay-1">
					<?php echo wp_kses_post( $section_title ); ?>
				</h2>
			</div>
			<p class="capabilities__intro reveal reveal-delay-2">
				<?php echo wp_kses_post( $intro ); ?>
			</p>
		</div>

		<div class="capabilities__grid">
			<?php foreach ( $cards as $index => $card ) :
				$number       = isset( $card['number'] )       ? esc_html( $card['number'] )       : '';
				$title        = isset( $card['title'] )        ? esc_html( $card['title'] )        : '';
				$text         = isset( $card['text'] )         ? wp_kses_post( $card['text'] )     : '';
				$metric       = isset( $card['metric'] )       ? esc_html( $card['metric'] )       : '';
				$metric_sfx   = isset( $card['metricSuffix'] ) ? esc_html( $card['metricSuffix'] ) : '';
				$metric_label = isset( $card['metricLabel'] )  ? esc_html( $card['metricLabel'] )  : '';
				$icon         = $icons[ $index % count( $icons ) ];

				$delay_class = $index > 0 ? ' reveal-delay-' . $index : '';
			?>
			<div class="capability-card reveal<?php echo esc_attr( $delay_class ); ?>">

				<div class="capability-icon" aria-hidden="true">
					<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG ?>
				</div>

				<div class="capability-card__number"><?php echo $number; ?></div>
				<div class="capability-card__title"><?php echo $title; ?></div>
				<p class="capability-card__text"><?php echo $text; ?></p>

				<div class="capability-card__metric">
					<?php echo $metric; ?>
					<?php if ( $metric_sfx ) : ?>
						<span style="font-size:16px;color:var(--c-chrome)"><?php echo $metric_sfx; ?></span>
					<?php endif; ?>
				</div>
				<div class="capability-card__metric-label"><?php echo $metric_label; ?></div>

			</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
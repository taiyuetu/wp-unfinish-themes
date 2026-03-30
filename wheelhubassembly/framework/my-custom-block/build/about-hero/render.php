<?php
/**
 * Server-side render for the About Hero block.
 *
 * Available variables:
 *   $attributes  (array)  – block attributes saved in the editor
 *   $content     (string) – inner blocks HTML (unused here)
 *   $block       (object) – WP_Block instance
 */

$tag         = ! empty( $attributes['tag'] )         ? $attributes['tag']         : '';
$title       = ! empty( $attributes['title'] )       ? $attributes['title']       : '';
$description = ! empty( $attributes['description'] ) ? $attributes['description'] : '';
$button_text = ! empty( $attributes['buttonText'] )  ? $attributes['buttonText']  : '';
$button_url  = ! empty( $attributes['buttonUrl'] )   ? esc_url( $attributes['buttonUrl'] ) : '#';
$image_url   = ! empty( $attributes['imageUrl'] )    ? esc_url( $attributes['imageUrl'] )  : '';
$image_alt   = ! empty( $attributes['imageAlt'] )    ? esc_attr( $attributes['imageAlt'] ) : '';
$badge_value = ! empty( $attributes['badgeValue'] )  ? $attributes['badgeValue']  : '';
$badge_label = ! empty( $attributes['badgeLabel'] )  ? $attributes['badgeLabel']  : '';
?>
<section class="about-hero">
	<div class="container">
		<div class="about-hero__layout">

			<div class="about-hero__content">

				<?php if ( $tag ) : ?>
					<div class="tag reveal"><?php echo wp_kses_post( $tag ); ?></div>
				<?php endif; ?>

				<?php if ( $title ) : ?>
					<h2 class="about-hero__title reveal reveal-delay-1">
						<?php echo wp_kses_post( $title ); ?>
					</h2>
				<?php endif; ?>

				<?php if ( $description ) : ?>
					<p class="about-hero__desc reveal reveal-delay-2">
						<?php echo wp_kses_post( $description ); ?>
					</p>
				<?php endif; ?>

				<?php if ( $button_text ) : ?>
					<a href="<?php echo $button_url; ?>" class="btn-primary reveal reveal-delay-3">
						<span><?php echo wp_kses_post( $button_text ); ?></span>
						<svg class="icon icon--sm" viewBox="0 0 24 24" aria-hidden="true">
							<line x1="5" y1="12" x2="19" y2="12"></line>
							<polyline points="12 5 19 12 12 19"></polyline>
						</svg>
					</a>
				<?php endif; ?>

			</div>

			<div class="about-hero__visual reveal">

				<?php if ( $image_url ) : ?>
					<img
						src="<?php echo $image_url; ?>"
						alt="<?php echo $image_alt; ?>"
						class="about-hero__visual-img"
						loading="lazy"
					/>
				<?php endif; ?>

				<?php if ( $badge_value || $badge_label ) : ?>
					<div class="about-hero__visual-badge">
						<?php if ( $badge_value ) : ?>
							<span class="about-hero__visual-badge-value">
								<?php echo esc_html( $badge_value ); ?>
							</span>
						<?php endif; ?>
						<?php if ( $badge_label ) : ?>
							<span class="about-hero__visual-badge-label">
								<?php echo esc_html( $badge_label ); ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>

		</div>
	</div>
</section>
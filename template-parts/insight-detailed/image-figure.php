<?php
/**
 * Image and chart component for the detailed Insight report
 *
 * Alt text comes from the Media Library so it stays editable per image and the
 * existing WP-Optimize pipeline can serve webp with lazy loading.
 *
 * @var array $component
 */

if ( empty( $component['image'] ) ) {
	return;
}

$image_id  = $component['image']['ID'];
$caption   = ! empty( $component['caption'] ) ? $component['caption'] : '';
$source    = ! empty( $component['source'] ) ? $component['source'] : '';
$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

if ( ! $image_alt ) {
	$image_alt = $caption;
}
?>
<figure class="report-figure">
	<?php
	echo wp_get_attachment_image( $image_id, 'full', false, array(
		'alt'     => esc_attr( $image_alt ),
		'sizes'   => '(min-width: 992px) 780px, (min-width: 768px) 672px, 100vw',
		'loading' => 'lazy',
	) );
	?>
	<?php if ( $caption || $source ) { ?>
        <figcaption>
			<?php if ( $caption ) { ?>
                <span class="report-figure-caption"><?php echo esc_html( $caption ); ?></span>
			<?php } ?>
			<?php if ( $source ) { ?>
                <span class="report-figure-source"><?php echo esc_html( $source ); ?></span>
			<?php } ?>
        </figcaption>
	<?php } ?>
</figure>

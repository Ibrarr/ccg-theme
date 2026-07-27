<?php
/**
 * Pull quote component for the detailed Insight report
 *
 * @var array $component
 */

if ( empty( $component['quote'] ) ) {
	return;
}
?>
<figure class="report-quote">
    <blockquote>
        <p>“<?php echo esc_html( $component['quote'] ); ?>”</p>
    </blockquote>
	<?php if ( ! empty( $component['attribution'] ) ) { ?>
        <figcaption><?php echo esc_html( $component['attribution'] ); ?></figcaption>
	<?php } ?>
</figure>

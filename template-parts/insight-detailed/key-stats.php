<?php
/**
 * Key stat callout component for the detailed Insight report
 *
 * Stat colours cycle coral, dark green then amber by position.
 *
 * @var array $component
 */

if ( empty( $component['stats'] ) ) {
	return;
}

$stat_colours = array( 'coral', 'green', 'amber' );
?>
<div class="report-stats">
    <dl>
		<?php foreach ( $component['stats'] as $index => $stat ) { ?>
			<?php if ( empty( $stat['value'] ) ) {
				continue;
			} ?>
            <div class="report-stat is-<?php echo esc_attr( $stat_colours[ $index % 3 ] ); ?>">
                <dt><?php echo esc_html( $stat['value'] ); ?></dt>
                <dd><?php echo esc_html( $stat['label'] ); ?></dd>
            </div>
		<?php } ?>
    </dl>
</div>

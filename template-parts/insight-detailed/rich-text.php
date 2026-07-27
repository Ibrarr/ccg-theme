<?php
/**
 * Rich text component for the detailed Insight report
 *
 * @var array $component
 */

if ( empty( $component['text'] ) ) {
	return;
}
?>
<div class="report-text"><?php echo wp_kses_post( $component['text'] ); ?></div>

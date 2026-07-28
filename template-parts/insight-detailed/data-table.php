<?php
/**
 * Data table component for the detailed Insight report
 *
 * Semantic table with scoped headers, wrapped in Bootstrap's .table-responsive
 * so wide tables scroll rather than breaking the layout.
 *
 * The caption is rendered twice on purpose. A <caption>'s containing block is
 * the table, not the scroll wrapper, so on a table with a min-width the visible
 * text would lay out wider than the screen and have to be scrolled to read. The
 * visible copy therefore sits outside the scroll container where it wraps at
 * container width, and the real <caption> stays in the table, visually hidden,
 * so the table keeps its accessible name. The visible copy is hidden from
 * assistive technology so the text is not announced twice.
 *
 * @var array       $component
 * @var string|null $variant Optional modifier, e.g. 'on-dark' for the
 *                           methodology panel.
 */

$headings = ! empty( $component['headings'] ) ? $component['headings'] : array();
$rows     = ! empty( $component['rows'] ) ? $component['rows'] : array();
$columns  = count( $headings );
$caption  = ! empty( $component['table_caption'] ) ? $component['table_caption'] : '';

// Read once and cleared immediately: this partial is included in a loop, so a
// leftover value would style the next table too.
$variant = isset( $variant ) ? $variant : '';
$classes = 'report-table' . ( $variant ? ' report-table--' . $variant : '' );

if ( ! $columns || ! $rows ) {
	return;
}
?>
<figure class="<?php echo esc_attr( $classes ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
	<?php if ( $caption ) { ?>
        <p class="report-table-title" aria-hidden="true"><?php echo esc_html( $caption ); ?></p>
	<?php } ?>
    <div class="report-table-scroll table-responsive">
        <table class="table">
			<?php if ( $caption ) { ?>
                <caption><?php echo esc_html( $caption ); ?></caption>
			<?php } ?>
            <thead>
            <tr>
				<?php foreach ( $headings as $heading ) { ?>
                    <th scope="col"><?php echo esc_html( $heading['heading'] ); ?></th>
				<?php } ?>
            </tr>
            </thead>
            <tbody>
			<?php foreach ( $rows as $row ) { ?>
				<?php
				$cells = ccg_insight_detailed_table_cells( $row, $columns );

				if ( ! strlen( trim( implode( '', $cells ) ) ) ) {
					continue;
				}
				?>
                <tr>
					<?php foreach ( $cells as $index => $cell ) { ?>
						<?php if ( 0 === $index ) { ?>
                            <th scope="row"><?php echo esc_html( $cell ); ?></th>
						<?php } else { ?>
                            <td><?php echo esc_html( $cell ); ?></td>
						<?php } ?>
					<?php } ?>
                </tr>
			<?php } ?>
            </tbody>
        </table>
    </div>
</figure>
<?php
// The including scope is reused for the next component, so leaving this set
// would carry the modifier onto a table that should not have it.
$variant = '';

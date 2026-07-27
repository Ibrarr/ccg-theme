<?php
/**
 * Data table component for the detailed Insight report
 *
 * Semantic table with a caption and scoped headers, wrapped in Bootstrap's
 * .table-responsive so wide tables scroll rather than breaking the layout.
 *
 * @var array $component
 */

$headings = ! empty( $component['headings'] ) ? $component['headings'] : array();
$rows     = ! empty( $component['rows'] ) ? $component['rows'] : array();
$columns  = count( $headings );

if ( ! $columns || ! $rows ) {
	return;
}
?>
<div class="report-table table-responsive" data-columns="<?php echo esc_attr( $columns ); ?>">
    <table class="table">
		<?php if ( ! empty( $component['table_caption'] ) ) { ?>
            <caption><?php echo esc_html( $component['table_caption'] ); ?></caption>
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

<?php
/**
 * The template for displaying insight posts
 *
 */

$post_type = 'insight';
$taxonomy  = 'type';
$term_slug = 'insight-reports';
// article-card.php prints $term_name, so it holds the readable label while the
// slug drives the queries.
$term         = get_term_by( 'slug', $term_slug, $taxonomy );
$term_name    = $term ? $term->name : '';
$linkedin_url = get_the_author_meta( 'linkedin' );

$post_thumbnail_id  = get_post_thumbnail_id( get_the_ID() );
$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'header-image' )[0];
?>
    <section class="post-header" style="background-image: url('<?php echo $post_thumbnail_url; ?>');">
        <div class="container px-4">
            <div class="row">
                <div class="col-md-8 header-content">
                    <p class="term">Insight Report</p>
                    <h1 class="title"><?php the_title(); ?></h1>
                    <div class="intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>' ); ?></h2></div>
                </div>
            </div>
        </div>
    </section>

    <section class="form-section">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4">
			<?php if ( get_field( 'form_header' ) ) { ?>
                <div class="row">
                    <p class="form-header"><?php the_field( 'form_header' ); ?></p>
                </div>
			<?php } ?>
            <div class="row">
				<?php if ( get_field( 'form_body_text' ) ) { ?>
                    <div class="col-md-6">
                        <div class="insight-reports-form"><?php echo do_shortcode( '[gravityform id="' . get_field( 'select_gform_form' ) . '" title="false" description="false" ajax="true"]' ); ?></div>
                    </div>
                    <div class="col-md-6 col-lg-5 offset-lg-1 form-body-text">
						<?php the_field( 'form_body_text' ); ?>
                    </div>
				<?php } else { ?>
                    <div class="col-md-9">
                        <div class="insight-reports-form"><?php echo do_shortcode( '[gravityform id="' . get_field( 'select_gform_form' ) . '" title="false" description="false" ajax="true"]' ); ?></div>
                    </div>
				<?php } ?>
            </div>
        </div>
    </section>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <section class="post-content">
                <div class="row gx-5">
					<?php if ( get_field( 'column_amount' ) === 'two' ) { ?>
						<?php if ( get_field( 'points_heading' ) && get_field( 'points_heading' ) ) { ?>
                            <div class="col-lg-8 col-md-6">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="content-heading"><?php the_field( 'heading' ); ?></h3>
                                        <p class="content-sub-heading"><?php the_field( 'sub_heading' ); ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="content-body"><?php the_field( 'body' ); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 points-section">
                                <h3><?php the_field( 'points_heading' ); ?></h3>
								<?php the_field( 'points' ); ?>
                            </div>
						<?php } else { ?>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <h3 class="content-heading"><?php the_field( 'heading' ); ?></h3>
                                        <p class="content-sub-heading"><?php the_field( 'sub_heading' ); ?></p>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="content-body"><?php the_field( 'body' ); ?></div>
                                    </div>
                                </div>
                            </div>
						<?php } ?>
					<?php } else { ?>
						<?php if ( get_field( 'points_heading' ) && get_field( 'points_heading' ) ) { ?>
                            <div class="col-lg-8 col-md-6">
                                <h3 class="content-heading"><?php the_field( 'heading' ); ?></h3>
                                <p class="content-sub-heading"><?php the_field( 'sub_heading' ); ?></p>
                                <div class="content-body"><?php the_field( 'body' ); ?></div>
                            </div>
                            <div class="col-lg-4 col-md-6 points-section">
                                <h3><?php the_field( 'points_heading' ); ?></h3>
								<?php the_field( 'points' ); ?>
                            </div>
						<?php } else { ?>
                            <div class="col-12">
                                <h3 class="content-heading"><?php the_field( 'heading' ); ?></h3>
                                <p class="content-sub-heading"><?php the_field( 'sub_heading' ); ?></p>
                                <div class="content-body"><?php the_field( 'body' ); ?></div>
                            </div>
						<?php } ?>
					<?php } ?>
                </div>
            </section>
        </div>
    </article>

<?php
/**
 * Related content: pinned Insight Reports first, topped up with the most recent.
 * The IDs are collected before anything renders, so the section still appears
 * when four or more reports are pinned.
 */
$related_limit = 4;
$related_tax   = array(
	array(
		'taxonomy'         => $taxonomy,
		'field'            => 'slug',
		'terms'            => $term_slug,
		'include_children' => false
	),
);

$pinned_posts = new WP_Query( array(
	'post_type'      => $post_type,
	'posts_per_page' => $related_limit,
	'fields'         => 'ids',
	'post__not_in'   => array( get_the_ID() ),
	'tax_query'      => $related_tax,
	'meta_query'     => array(
		array(
			'key'     => 'pinned',
			'value'   => '1',
			'compare' => '='
		)
	)
) );

$related_ids = $pinned_posts->posts;

if ( count( $related_ids ) < $related_limit ) {
	$related_ids = array_merge( $related_ids, ( new WP_Query( array(
		'post_type'      => $post_type,
		'posts_per_page' => $related_limit - count( $related_ids ),
		'fields'         => 'ids',
		'post__not_in'   => array_merge( array( get_the_ID() ), $related_ids ),
		'tax_query'      => $related_tax,
	) ) )->posts );
}

if ( $related_ids ) {
	$related_posts = new WP_Query( array(
		'post_type'      => $post_type,
		'post__in'       => $related_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => $related_limit,
	) );

	if ( $related_posts->have_posts() ) {
		echo '<section class="related-content">';
		echo '<div class="parallax-bars" id="bar-three">' . file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) . '</div>';
		echo '<div class="container px-4">';
		echo '<h3>Related content</h3>';
		echo '<div class="row mb-3">';

		while ( $related_posts->have_posts() ) {
			$related_posts->the_post();
			require( 'article-card.php' );
		}

		echo '</div>';
		echo '<div class="row"><a class="global-button" href="/insight-hub?types=insight-reports">See More</a></div>';
		echo '</div>';
		echo '</section>';
	}

	wp_reset_postdata();
}

include( 'bottom-cta.php' );
?>
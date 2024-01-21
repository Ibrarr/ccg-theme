<?php
/**
 * The template for displaying insight posts
 *
 */

$post_type    = 'insight';
$taxonomy     = 'type';
$term_name    = 'insight-reports';
$linkedin_url = get_the_author_meta( 'linkedin' );

$post_thumbnail_id  = get_post_thumbnail_id( get_the_ID() );
$post_thumbnail_url = wp_get_attachment_image_src( $post_thumbnail_id, 'header-image' )[0];
?>
    <section class="post-header" style="background-image: url('<?php echo $post_thumbnail_url; ?>');">
        <div class="container px-4">
            <div class="row">
                <div class="col-md-8 header-content">
                    <p class="term">Webinar</p>
                    <h1 class="title"><?php the_title(); ?></h1>
                    <div class="intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>' ); ?></h2></div>
                </div>
            </div>
        </div>
    </section>

    <section class="form-section">
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
$pinned_posts = new WP_Query( array(
	'post_type'      => $post_type,
	'posts_per_page' => 4,
	'tax_query'      => array(
		array(
			'taxonomy'         => $taxonomy,
			'field'            => 'slug',
			'terms'            => $term_name,
			'include_children' => false
		),
	),
	'meta_query'     => array(
		array(
			'key'     => 'pinned',
			'value'   => '1',
			'compare' => '='
		)
	)
) );

$remaining_posts_to_fetch = 4;

if ( $pinned_posts->have_posts() ) {
	$remaining_posts_to_fetch -= $pinned_posts->post_count;
}

if ( $remaining_posts_to_fetch > 0 ) {
	$exclude_post_ids = wp_list_pluck( $pinned_posts->posts, 'ID' );
	$args             = array(
		'post_type'      => $post_type,
		'posts_per_page' => $remaining_posts_to_fetch,
		'post__not_in'   => array_merge( array( get_the_ID() ), $exclude_post_ids ),
		'tax_query'      => array(
			array(
				'taxonomy'         => $taxonomy,
				'field'            => 'slug',
				'terms'            => $term_name,
				'include_children' => false
			),
		),
	);

	$query = new WP_Query( $args );

	if ( $pinned_posts->have_posts() || $query->have_posts() ) {
		echo '<section class="related-content">';
		echo '<div class="parallax-bars" id="bar-three">' . file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) . '</div>';
		echo '<div class="container px-4">';
		echo '<h3>Related content</h3>';
		echo '<div class="row mb-3">';

		while ( $pinned_posts->have_posts() ) {
			$pinned_posts->the_post();
			require( 'article-card.php' );
		}

		while ( $query->have_posts() ) {
			$query->the_post();
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
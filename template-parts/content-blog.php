<?php
/**
 * The template for displaying posts
 *
 */

$post_type = 'post';
$taxonomy  = 'category';
$terms     = get_the_terms( get_the_ID(), $taxonomy );
// A blog post with no category would otherwise fatal on $terms[0].
$term         = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0] : null;
$term_name    = $term ? $term->name : '';
$linkedin_url = get_the_author_meta( 'linkedin' );

$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
$image_srcset = wp_get_attachment_image_srcset( $thumbnail_id );
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/green.svg' ) ?></div>
        <div class="container px-4">
            <section class="post-header">
                <p class="term"><?php echo $term_name; ?></p>
                <h1 class="title"><?php the_title(); ?></h1>
                <div class="row">
                    <div class="col-lg-8 intro"><p class="statement"><?php echo ccg_editor_html( get_field( 'intro' ) ); ?></p></div>
                </div>
            </section>
            <section class="post-content">
                <div class="row gx-5">
                    <div class="col-lg-7 content"><?php the_field( 'body' ); ?></div>
                    <div class="col-lg-5 image social">
                        <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_title(); ?>"
                             srcset="<?php echo esc_attr( $image_srcset ); ?>" sizes="(min-width: 391px) 1024px, 100vw">
                        <?php get_template_part( 'template-parts/components/share' ); ?>
                    </div>
                </div>
            </section>
            <div class="author">
                <p><span class="byline-label">Written by</span> <?php the_author(); ?></p>
				<?php
				if ( $linkedin_url ) {
					echo '<a href="' . esc_url( $linkedin_url ) . '" target="_blank">LinkedIn</a>';
				}
				?>
            </div>
        </div>
    </article>

<?php
/**
 * Related content: pinned posts first, topped up with others sharing this
 * post's category. The IDs are collected before anything renders, so the
 * section still appears when four or more posts are pinned.
 */
$related_limit = 4;

$pinned_posts = new WP_Query( array(
	'post_type'      => $post_type,
	'posts_per_page' => $related_limit,
	'fields'         => 'ids',
	'post__not_in'   => array( get_the_ID() ),
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
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $related_limit - count( $related_ids ),
		'fields'         => 'ids',
		'post__not_in'   => array_merge( array( get_the_ID() ), $related_ids ),
	);

	if ( $term ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'         => $taxonomy,
				'field'            => 'slug',
				'terms'            => $term->slug,
				'include_children' => false
			),
		);
	}

	$related_ids = array_merge( $related_ids, ( new WP_Query( $args ) )->posts );
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

		// article-card.php prints $term_name, so it has to be each card's own
		// term rather than the host page's. Inheriting the host's value labelled
		// every related card with the host page's term: register #11, where two
		// case studies showed the sector of the page they were listed on. The
		// host value is restored afterwards because the "See More" link below
		// still needs it. This is the same per-post idiom the listing loops in
		// ajax-calls.php and front-page.php already use.
		$host_term_name = $term_name;

		while ( $related_posts->have_posts() ) {
			$related_posts->the_post();
			$card_terms = get_the_terms( get_the_ID(), $taxonomy );
			$term_name  = ( $card_terms && ! is_wp_error( $card_terms ) ) ? $card_terms[0]->name : '';
			require( 'article-card.php' );
		}

		$term_name = $host_term_name;

		echo '</div>';
		echo '<div class="row"><a class="global-button global-button--index" href="/our-blog' . ( $term_name ? '?categories=' . sanitize_title( $term_name ) : '' ) . '">See More</a></div>';
		echo '</div>';
		echo '</section>';
	}

	wp_reset_postdata();
}

include( 'bottom-cta.php' );
?>
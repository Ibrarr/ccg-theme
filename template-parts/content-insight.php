<?php
/**
 * The template for displaying insight posts
 *
 */

$post_type = 'insight';
$taxonomy  = 'type';
$terms     = get_the_terms( get_the_ID(), $taxonomy );
// An Insight with no type term would otherwise fatal on $terms[0].
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
                    <div class="col-lg-8 intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>' ); ?></h2></div>
                </div>
            </section>
            <section class="post-content">
                <div class="row gx-5">
                    <div class="col-lg-7 content"><?php the_field( 'body' ); ?></div>
                    <div class="col-lg-5 image social">
                        <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_title(); ?>"
                             srcset="<?php echo esc_attr( $image_srcset ); ?>" sizes="(min-width: 391px) 1024px, 100vw">
                        <div class="social-icons">
                            <a class="mail-icon"
                               href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&body=Check out this insight from CCGroup <?php echo rawurlencode( get_permalink() ); ?>"
                               target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/mail-icon.svg' ) ?></a>
                            <a class="linkedin-icon" rel="nofollow"
                               href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode( get_permalink() ); ?>&title=<?php echo rawurlencode( get_the_title() ); ?>"
                               target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/linkedin-icon.svg' ) ?></a>
                            <a class="x-icon" rel="nofollow"
                               href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>/&text='<?php echo rawurlencode( get_the_title() ); ?>'&via=<?php the_field( 'xtwitter_username', 'option' ); ?>"
                               title="Tweet this insight"
                               target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/x-icon.svg' ) ?></a>
                        </div>
                    </div>
                </div>
            </section>
            <div class="author">
                <p>Written by <?php the_author(); ?></p>
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
 * Related content: pinned Insights first, topped up with others sharing this
 * post's type. The IDs are collected before anything renders, so the section
 * still appears when four or more Insights are pinned.
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

		while ( $related_posts->have_posts() ) {
			$related_posts->the_post();
			require( 'article-card.php' );
		}

		echo '</div>';
		echo '<div class="row"><a class="global-button" href="/insight-hub' . ( $term_name ? '?types=' . sanitize_title( $term_name ) : '' ) . '">See More</a></div>';
		echo '</div>';
		echo '</section>';
	}

	wp_reset_postdata();
}

include( 'bottom-cta.php' );
?>
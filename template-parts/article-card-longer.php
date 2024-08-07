<div class="col-lg-4 col-6 mb-4 article-card">
    <a href="<?php the_permalink(); ?>">
        <div class="image-wrapper">
			<?php
			$thumbnail_id = get_post_thumbnail_id( $post->ID );
			$image_srcset = wp_get_attachment_image_srcset( $thumbnail_id );
            $intro_content = get_field('intro', $post->ID);
            ?>
            <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_title(); ?>"
                 srcset="<?php echo esc_attr( $image_srcset ); ?>" sizes="(min-width: 391px) 1024px, 100vw">
        </div>
        <p class="term"><?php echo $term_name; ?></p>
        <p class="title"><strong><?php the_title(); ?></strong></p>
        <p class="intro"><?php echo $intro ?></p>
        <p class="find-out-more"><a href="<?php the_permalink(); ?>">Find out more here</a></p>
        <p></p>
    </a>
</div>
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
        <?php
            $intro_paragraphs = explode('</p>', $intro_content);
            if (isset($intro_paragraphs[0])) {
                echo '<p class="intro">' . strip_tags($intro_paragraphs[0]) . '</p>';
            }
        ?>
        <p class="find-out-more">Find out more here</p>
        <p></p>
    </a>
</div>
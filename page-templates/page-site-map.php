<?php
/*
Template Name: Site Map
*/
get_header();
?>

    <section class="privacy-header">
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
        </div>
    </section>

    <section class="link-section">
        <div class="container px-4">
            <div class="row">
				<?php
				if ( have_rows( 'list_section' ) ):
					while ( have_rows( 'list_section' ) ) : the_row();
						echo '<div class="col-lg-4 col-md-6">';
						echo '<h3>' . get_sub_field( 'heading' ) . '</h3>';
						if ( have_rows( 'links' ) ):
							echo '<ul>';
							while ( have_rows( 'links' ) ) : the_row();
								$link = get_sub_field( 'link' );
								?>
                                <li>
                                    <a href="<?php echo $link['url']; ?>"
                                       target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a>
                                </li>
							<?php
							endwhile;
							echo '</ul>';
						endif;
						echo '</div>';
					endwhile;
				endif;
				?>
            </div>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>
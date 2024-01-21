<?php
/*
Template Name: Awards
*/
get_header();
?>

    <section class="awards-header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro"><h2><?php the_field( 'intro' ) ?></h2></div>
            </div>
        </div>
    </section>

    <section class="awards">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/green.svg' ) ?></div>
        <div class="container px-4">
			<?php
			if ( have_rows( 'award_year' ) ):
				while ( have_rows( 'award_year' ) ) :
					the_row();
					echo '<div class="award-year">';
					echo '<h4>' . get_sub_field( 'year' ) . '</h4>';
					if ( have_rows( 'winning_awards' ) ):
						echo '<div class="all-awards-container">';
						echo '<h5>Winning Awards</h5>';
						echo '<div class="row justify-content-center winning-awards">';
						echo '<div class="col-md-10">';
						echo '<div class="row gx-5">';
						while ( have_rows( 'winning_awards' ) ) : the_row();
							?>
                            <div class="col-md-4 col-6 d-flex justify-content-center">
                                <div class="winning-award">
                                    <img class="award-image" src="<?php the_sub_field( 'image' ); ?>"
                                         alt="<?php the_sub_field( 'award_name' ); ?>">
                                    <p class="award-details"><?php the_sub_field( 'details' ); ?></p>
                                    <p class="award-name"><?php the_sub_field( 'award_name' ); ?></p>
                                </div>
                            </div>
						<?php
						endwhile;
						echo '</div>';
						echo '</div>';
						echo '</div>';
					endif;
					if ( have_rows( 'shortlisted' ) ):
						echo '<h5 class="shortlisted-header">Shortlisted</h5>';
						echo '<div class="row justify-content-center shortlisted-awards">';
						echo '<div class="col-md-10">';
						echo '<div class="row gx-5">';
						while ( have_rows( 'shortlisted' ) ) : the_row();
							?>
                            <div class="col-md-4 col-6 d-flex justify-content-center">
                                <div class="shortlisted-award">
                                    <img class="award-image" src="<?php the_sub_field( 'image' ); ?>"
                                         alt="<?php the_sub_field( 'award_name' ); ?>">
                                    <p class="award-details"><?php the_sub_field( 'details' ); ?></p>
                                    <p class="award-name"><?php the_sub_field( 'award_name' ); ?></p>
                                </div>
                            </div>
						<?php
						endwhile;
						echo '</div>';
						echo '</div>';
						echo '</div>';
						echo '</div>';
					endif;
					echo '</div>';
				endwhile;
			endif;
			?>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>
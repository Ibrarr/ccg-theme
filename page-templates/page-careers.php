<?php
/*
Template Name: Careers
*/
get_header();

$header_button = get_field( 'header_button' );
$talent_link   = get_field( 'talent_manager_button' );
?>

    <section class="careers-header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro">
                    <h2><?php the_field( 'intro' ); ?></h2>
                    <a class="global-button" href="<?php echo $header_button['url']; ?>"
                       target="<?php echo $header_button['target']; ?>"><?php echo $header_button['title']; ?></a>
                </div>
            </div>
        </div>
    </section>

    <section class="diversity">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/green.svg' ) ?></div>
        <div class="container px-4">
            <div class="row gx-5">
                <div class="col-lg-7">
                    <img class="main-image slide-in-image" src="<?php the_field( 'divsersity_main_image' ); ?>"
                         alt="Diversity main image">
                </div>
                <div class="col-lg-4 content-blueprint">
                    <div class="content"><?php the_field( 'divsersity_content' ); ?></div>

                    <img class="divsersity-blueprint-image" src="<?php the_field( 'divsersity_blueprint_image' ); ?>"
                         alt="Blueprint logo">
                </div>
            </div>
        </div>
    </section>

    <section class="values">
        <div class="container px-4">
            <div class="row gx-5">
                <div class="col-lg-3 col-md-4">
                    <h3><?php the_field( 'values_heading' ); ?></h3>
                    <p><?php the_field( 'values_intro' ); ?></p>
                </div>
                <div class="col-lg-8 col-md-8">
                    <div class="value-cards">
                        <div class="headings">
							<?php
							if ( have_rows( 'values_values' ) ):
								while ( have_rows( 'values_values' ) ) : the_row();
									$heading             = get_sub_field( 'heading' );
									$heading_lowercase   = strtolower( $heading );
									$heading_with_dashes = str_replace( ' ', '-', $heading_lowercase );
									?>
                                    <h4 class="<?php echo $heading_with_dashes ?>"><?php the_sub_field( 'heading' ); ?></h4>
								<?php
								endwhile;
							endif;
							?>
                        </div>

                        <div class="cards">
							<?php
							if ( have_rows( 'values_values' ) ):
								$index = 1;
								while ( have_rows( 'values_values' ) ) :
									the_row();
									$heading             = get_sub_field( 'heading' );
									$heading_lowercase   = strtolower( $heading );
									$heading_with_dashes = str_replace( ' ', '-', $heading_lowercase );

									if ( $index === 1 ) {
										$card_image = CCG_TEMPLATE_DIR . '/assets/images/cards/green-card.svg';
									} elseif ( $index === 2 ) {
										$card_image = CCG_TEMPLATE_DIR . '/assets/images/cards/light-grey-card.svg';
									} elseif ( $index === 3 ) {
										$card_image = CCG_TEMPLATE_DIR . '/assets/images/cards/dark-grey-card.svg';
									} elseif ( $index === 4 ) {
										$card_image = CCG_TEMPLATE_DIR . '/assets/images/cards/red-card.svg';
									} elseif ( $index === 5 ) {
										$card_image = CCG_TEMPLATE_DIR . '/assets/images/cards/mustard-card.svg';
									} else {
										$card_image = CCG_TEMPLATE_DIR . '/assets/images/cards/green-card.svg';
									}
									?>
                                    <div class="<?php echo $heading_with_dashes ?>">
                                        <div class="contents">
                                            <p class="heading"><?php the_sub_field( 'heading' ); ?></p>
                                            <p class="description"><?php the_sub_field( 'description' ); ?></p>
                                        </div>
                                        <div class="value-card"><?php echo file_get_contents( $card_image ); ?></div>
                                    </div>
									<?php
									$index ++;
								endwhile;
							endif;
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="life-here">
        <div class="container px-4">
            <div class="row">
                <h3><?php the_field( 'life_here_heading' ); ?></h3>
            </div>
            <div class="row gx-5">
                <div class="col-lg-4">
                    <p><?php the_field( 'life_here_intro' ); ?></p>
                </div>
                <div class="col-lg-8">
                    <div class="row">
						<?php
						$life_here_images = get_field( 'life_here_images' );
						if ( $life_here_images ): ?>
							<?php foreach ( $life_here_images as $image_url ): ?>
                                <div class="col-md-6">
                                    <img class="life-here-images slide-in-image" src="<?php echo $image_url; ?>"
                                         alt="Life at CCgroup">
                                </div>
							<?php endforeach; ?>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="culture">
        <div class="container px-4">
            <h3><?php the_field( 'culture_heading' ); ?></h3>
            <div class="row">
                <div class="col-lg-9"><p class="intro"><?php the_field( 'culture_intro' ); ?></p></div>
            </div>
            <div class="row gx-5 content">
                <div class="col-lg-6">
					<?php the_field( 'culture_body_column_1' ); ?>
                </div>
                <div class="col-lg-6">
					<?php the_field( 'culture_body_column_2' ); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="other-info">
        <div class="container px-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="benefits-perks">
                        <div class="parallax-bars"
                             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
                        <h3 class="heading"><?php the_field( 'benefits_perks_heading' ); ?></h3>
						<?php the_field( 'benefits_perks_benefits_perks' ); ?>
                        <button class="global-button-benefit">See More Perks</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="talent-awards">
                        <div class="talent">
                            <div class="row">
                                <div class="col-7 info">
                                    <h3 class="heading"><?php the_field( 'talent_manager_header' ); ?></h3>
                                    <div class="content"><?php the_field( 'talent_manager_content' ); ?></div>
                                    <a class="global-button" href="<?php echo $talent_link['url']; ?>"
                                       target="<?php echo $talent_link['target']; ?>"><?php echo $talent_link['title']; ?></a>
                                </div>
                                <div class="col-5 headshot">
                                    <img src="<?php the_field( 'talent_manager_headshot' ); ?>" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="awards">
                            <div class="row"><h3 class="heading"><?php the_field( 'employer_awards_heading' ); ?></h3>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="content"><?php the_field( 'employer_awards_content' ); ?></div>
                                </div>
                                <div class="col-6">
									<?php
									$employer_awards_awards = get_field( 'employer_awards_awards' );
									if ( $employer_awards_awards ): ?>
										<?php foreach ( $employer_awards_awards as $image_url ): ?>
                                            <img src="<?php echo $image_url; ?>"
                                                 alt="Awards">
										<?php endforeach; ?>
									<?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="vacancies" id="vacancies">
        <div class="container px-4">
            <h3><?php the_field( 'vacancies_heading' ); ?></h3>
			<?php
			if ( have_rows( 'vacancies_current_vacancies' ) ):
				while ( have_rows( 'vacancies_current_vacancies' ) ) :
					the_row();
					?>
                    <div class="vacancy">
                        <h4><?php the_sub_field( 'job_title' ); ?></h4>
                        <div class="vacancy-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5><?php the_sub_field( 'heading' ); ?></h5>
									<?php the_sub_field( 'description' ); ?>
                                </div>
                                <div class="col-md-6 d-flex justify-content-center align-items-center">
                                    <div class="download-spec">
                                        <a href="<?php the_sub_field( 'job_specification' ); ?>" download>
                                            <p><?php the_sub_field( 'download_text' ); ?></p>
											<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/word-download.svg' ) ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php
				endwhile;
			else:
				echo "<p class='no-vacancies'>We don’t have any vacancies currently but we are always interested to hear from ambitious and curious experts, if you think you fit the bill then please get in touch.</p>";
			endif;
			?>
        </div>
    </section>


<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>
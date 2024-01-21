<?php
/*
Template Name: Services
*/
get_header();
?>

    <section class="services-header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>, <p>' ); ?></h2></div>
            </div>
        </div>
    </section>

    <section class="services">
        <div class="parallax-bars"
             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/navy.svg' ) ?></div>
        <div class="container px-4">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="analytics">
                        <h3 class="service-header">Analytics</h3>
                        <div class="service-intro"><?php the_field( 'analytics_intro' ) ?></div>
                        <div class="service-body"><?php the_field( 'analytics_body' ) ?></div>
                        <div class="accordion-container">
                            <h4 class="parent-term">Analytics</h4>
							<?php
							$analytic_terms = get_terms( array(
								'taxonomy'   => 'service',
								'hide_empty' => false,
								'parent'     => 0,
							) );

							foreach ( $analytic_terms as $analytic_term ) {
								if ( $analytic_term->name === 'Analytics' ) {
									$child_terms = get_term_children( $analytic_term->term_id, 'service' );

									if ( ! empty( $child_terms ) ) {
										foreach ( $child_terms as $child_term_id ) {
											$child_term = get_term( $child_term_id, 'service' );
											?>
                                            <div class="service-item">
                                                <div class="service-name">
                                                    <h4>
                                                        <a href="<?php echo get_term_link( $child_term ); ?>"><?php echo $child_term->name ?></a>
                                                    </h4>
                                                    <div class="open-close-accordion">
														<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/plus.svg' ) ?>
                                                    </div>
                                                </div>
												<?php if ( $child_term->description ) { ?>
                                                    <div class="service-description">
                                                        <p><?php echo $child_term->description ?></p>
                                                    </div>
												<?php } ?>
                                            </div>
											<?php
										}
									}
								}
							}
							?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="strategy">
                        <h3 class="service-header">Strategy</h3>
                        <div class="service-intro"><?php the_field( 'strategy_intro' ) ?></div>
                        <div class="service-body"><?php the_field( 'strategy_body' ) ?></div>
                        <div class="accordion-container">
                            <h4 class="parent-term">Strategy</h4>
							<?php
							$strategy_terms = get_terms( array(
								'taxonomy'   => 'service',
								'hide_empty' => false,
								'parent'     => 0,
							) );

							foreach ( $strategy_terms as $strategy_term ) {
								if ( $strategy_term->name === 'Strategy' ) {
									$child_terms = get_term_children( $strategy_term->term_id, 'service' );

									if ( ! empty( $child_terms ) ) {
										foreach ( $child_terms as $child_term_id ) {
											$child_term = get_term( $child_term_id, 'service' );
											?>
                                            <div class="service-item">
                                                <div class="service-name">
                                                    <h4>
                                                        <a href="<?php echo get_term_link( $child_term ); ?>"><?php echo $child_term->name ?></a>
                                                    </h4>
                                                    <div class="open-close-accordion">
														<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/plus.svg' ) ?>
                                                    </div>
                                                </div>
												<?php if ( $child_term->description ) { ?>
                                                    <div class="service-description">
                                                        <p><?php echo $child_term->description ?></p>
                                                    </div>
												<?php } ?>
                                            </div>
											<?php
										}
									}
								}
							}
							?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="activation">
                        <div class="col-lg-12 col-md-6">
                            <h3 class="service-header">Activation</h3>
                            <div class="service-intro"><?php the_field( 'activation_intro' ) ?></div>
                            <div class="service-body"><?php the_field( 'activation_body' ) ?></div>
                        </div>
                        <div class="col-lg-12 col-md-6">
                            <div class="accordion-container">
                                <h4 class="parent-term">Activation</h4>
								<?php
								$activation_terms = get_terms( array(
									'taxonomy'   => 'service',
									'hide_empty' => false,
									'parent'     => 0,
								) );

								foreach ( $activation_terms as $activation_term ) {
									if ( $activation_term->name === 'Activation' ) {
										$child_terms = get_term_children( $activation_term->term_id, 'service' );

										if ( ! empty( $child_terms ) ) {
											foreach ( $child_terms as $child_term_id ) {
												$child_term = get_term( $child_term_id, 'service' );
												?>
                                                <div class="service-item">
                                                    <div class="service-name">
                                                        <h4>
                                                            <a href="<?php echo get_term_link( $child_term ); ?>"><?php echo $child_term->name ?></a>
                                                        </h4>
                                                        <div class="open-close-accordion">
															<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/plus.svg' ) ?>
                                                        </div>
                                                    </div>
													<?php if ( $child_term->description ) { ?>
                                                        <div class="service-description">
                                                            <p><?php echo $child_term->description ?></p>
                                                        </div>
													<?php } ?>
                                                </div>
												<?php
											}
										}
									}
								}
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>
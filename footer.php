</main>
<footer id="footer" role="contentinfo">
    <div class="container px-4">
        <div class="row">
            <div class="col-md-3 footer-logo">
                <a href="/"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/logos/footer-logo.svg' ) ?></a>
            </div>
            <div class="col-md-9 logo-text">
                <p><?php the_field( 'footer_text', 'option' ) ?></p>
            </div>
        </div>
        <div class="footer-sections">
            <div class="row">
                <div class="col-md-4 offset-md-3 main-footer-menu">
					<?php wp_nav_menu( array(
						'theme_location' => 'footer-menu',
					) ); ?>
                </div>
                <div class="col-md-5 social-contact">
                    <div class="row">
                        <div class="col-8 col-md-7">
                            <div class="contact">
                                <h4>Get in touch</h4>
                                <a href="tel:<?php the_field( 'ccg_phone', 'option' ) ?>"><?php the_field( 'ccg_phone', 'option' ) ?></a><br>
                                <a href="mailto:<?php the_field( 'ccg_email', 'option' ) ?>"><?php the_field( 'ccg_email', 'option' ) ?></a>
                                <br><br>

                                <h4>Find us</h4>
								<?php the_field( 'ccg_address', 'option' ) ?>
                            </div>
                        </div>
                        <div class="col-4 col-md-5">
                            <div class="social-container">
                                <div class="social">
                                    <h4>Follow</h4>
                                    <a href="https://www.linkedin.com/company/<?php the_field( 'linkedin_username', 'option' ) ?>"
                                       target="_blank">LinkedIn</a>
                                    <a href="<?php the_field( 'instagram', 'option' ) ?>" target="_blank">Instagram</a>
                                    <a href="https://twitter.com/<?php the_field( 'xtwitter_username', 'option' ) ?>"
                                       target="_blank">X
                                        (Twitter)</a>
                                    <a href="<?php the_field( 'youtube', 'option' ) ?>" target="_blank">YouTube</a>
                                    <a href="<?php the_field( 'facebook', 'option' ) ?>" target="_blank">Facebook</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 credit">
                    <p><a href="https://www.todaycreative.co.uk/">Design - Today Creative</a></p>
                </div>
                <div class="col-md-4 legal">
                    <p class="company-name">© CCGroup Communications Limited <br><?php echo date( "Y" ); ?></p>
                </div>
                <div class="col-md-5 legal footer-info">
                    <div class="row align-items-end">
                        <div class="col-8 col-md-7">
                            <div class="legal-text">
                                <p><a href="<?php the_field( 'cms_link', 'option' ) ?>">Communications Management
                                        Standard (CMS)</a></p>
                                <p class="company-name">© CCGroup Communications Limited <?php echo date( "Y" ); ?></p>
                                <p class="credit"><a href="https://www.todaycreative.co.uk/">Design - Today Creative</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-4 col-md-5">
                            <div class="info-container">
                                <div class="info">
									<?php wp_nav_menu( array(
										'theme_location' => 'info-menu',
									) ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</div>
<?php wp_footer(); ?>
<script type="text/javascript"> _linkedin_partner_id = "6223058"; window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || []; window._linkedin_data_partner_ids.push(_linkedin_partner_id); </script><script type="text/javascript"> (function(l) { if (!l){window.lintrk = function(a,b){window.lintrk.q.push([a,b])}; window.lintrk.q=[]} var s = document.getElementsByTagName("script")[0]; var b = document.createElement("script"); b.type = "text/javascript";b.async = true; b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js"; s.parentNode.insertBefore(b, s);})(window.lintrk); </script> <noscript> <img height="1" width="1" style="display:none;" alt="" src="https://px.ads.linkedin.com/collect/?pid=6223058&fmt=gif" /> </noscript>
</body>
</html>
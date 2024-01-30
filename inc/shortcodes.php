<?php

/**
 * Shortcode to let editors embed videos into posts
 */
add_shortcode( 'embed_iframe', 'iframe_shortcode' );
function iframe_shortcode( $atts ) {
    $a = shortcode_atts( array(
        'src' => ''
    ), $atts );

    return '<iframe src="' . esc_url( $a['src'] ) . '" allowfullscreen="allowfullscreen" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}
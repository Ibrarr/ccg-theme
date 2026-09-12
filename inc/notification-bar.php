<?php
/**
 * Site-wide notification bar.
 *
 * A slim strip above the header, editable from Site Settings. Content is
 * server-rendered so it is identical for crawlers and visitors, it introduces no
 * heading tags, and its height is reserved in CSS from first paint.
 *
 * Why the dismissal is not handled server-side: the obvious approach is to read
 * the cookie in PHP and skip rendering. This install runs WP-Optimize page cache
 * and sits behind Cloudflare in production, so a cookie-varied response would
 * either be cached and served to people who had dismissed it, or fragment the
 * cache. The bar is therefore always rendered and a pre-paint script in the head
 * hides it before the first frame, which costs nothing and cannot go stale.
 */

/**
 * The bar's content, or null when it should not render.
 *
 * @return array{kicker:string,message:string,link:array|false}|null
 */
function ccg_notification_bar() {
	if ( ! function_exists( 'get_field' ) || ! get_field( 'enable_notification_bar', 'option' ) ) {
		return null;
	}

	$message = trim( (string) get_field( 'notification_bar_message', 'option' ) );

	// An empty message means an empty strip, so treat it as off.
	if ( '' === $message ) {
		return null;
	}

	return [
		'kicker'  => trim( (string) get_field( 'notification_bar_kicker', 'option' ) ),
		'message' => $message,
		'link'    => get_field( 'notification_bar_link', 'option' ),
	];
}

/**
 * Flags the bar on <body> so the stylesheet can reserve its height.
 *
 * The height lives on the body class rather than :root because the server is the
 * only thing that knows whether the bar rendered, and body sits above every
 * element that reads --ccg-topbar-height.
 */
add_filter( 'body_class', 'ccg_notification_bar_body_class' );
function ccg_notification_bar_body_class( $classes ) {
	if ( ccg_notification_bar() ) {
		$classes[] = 'has-notification-bar';
	}

	return $classes;
}

/**
 * Hides a dismissed bar before the first paint.
 *
 * Runs in the head, so it cannot touch document.body yet: it flags <html>
 * instead and the stylesheet does the rest. Priority 0 to get in ahead of
 * everything else in wp_head, including the stylesheet.
 */
add_action( 'wp_head', 'ccg_notification_bar_prepaint', 0 );
function ccg_notification_bar_prepaint() {
	if ( ! ccg_notification_bar() ) {
		return;
	}

	echo '<script>if(/(?:^|;\s*)ccgNoticeDismissed=1/.test(document.cookie)){document.documentElement.className+=" notice-dismissed";}</script>' . "\n";
}

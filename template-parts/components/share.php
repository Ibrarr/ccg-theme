<?php
/**
 * The share block: a serif italic "Share" label over the three share links.
 *
 * A8 (v0.12) asks for this on standard blog posts and on every Our Work page,
 * matching what the insight templates already carry. It is one part rather than
 * three copies so the next change lands everywhere at once; the detailed report
 * keeps its own `.report-share` markup because that template scopes its whole
 * sidebar off the $rd-* token block.
 *
 * The icons ship with no fill of their own (the mail glyph strokes acid, which
 * was a dark-ground colour on a light ground), so `.post-share` paints them.
 */

$ccg_share_url   = rawurlencode( get_permalink() );
$ccg_share_title = rawurlencode( get_the_title() );
$ccg_twitter     = get_field( 'xtwitter_username', 'option' );
?>
<div class="post-share">
    <p class="post-share-label">Share</p>
    <div class="social-icons">
        <a class="mail-icon"
           href="mailto:?subject=<?php echo $ccg_share_title; ?>&body=<?php echo rawurlencode( 'Take a look at this from CCGroup ' ); ?><?php echo $ccg_share_url; ?>"
           target="_blank"><span class="screen-reader-text">Share by email</span><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/mail-icon.svg' ); ?></a>
        <a class="linkedin-icon" rel="nofollow"
           href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $ccg_share_url; ?>&title=<?php echo $ccg_share_title; ?>"
           target="_blank"><span class="screen-reader-text">Share on LinkedIn</span><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/linkedin-icon.svg' ); ?></a>
        <a class="x-icon" rel="nofollow"
           href="https://twitter.com/intent/tweet?url=<?php echo $ccg_share_url; ?>&text=<?php echo $ccg_share_title; ?><?php echo $ccg_twitter ? '&via=' . esc_attr( $ccg_twitter ) : ''; ?>"
           target="_blank"><span class="screen-reader-text">Share on X</span><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/x-icon.svg' ); ?></a>
    </div>
</div>

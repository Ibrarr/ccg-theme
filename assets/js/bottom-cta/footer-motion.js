/**
 * F2 (v0.13): the footer logo lockup glides in (Movement 1) when the footer
 * scrolls into view, once per visit. The one named exception to E5: everything
 * else in the footer stays still.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { glide, onReveal } from '../shared/text-motion';

gsap.registerPlugin( ScrollTrigger );

const lockup = document.querySelector( 'footer .footer-logo' );
const marks = lockup ? [ ...lockup.querySelectorAll( 'svg' ) ] : [];

// The SVG is an atomic inline box, so unlike an inline span it can be moved.
onReveal( ScrollTrigger, lockup, marks, () => glide( marks ), { line: 0.95 } );

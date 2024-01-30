<?php

// Define Globals
define( 'CCG_THEME_PREFIX', 'ccg' );
define( 'CCG_TEMPLATE_URI', get_template_directory_uri() );
define( 'CCG_TEMPLATE_DIR', get_template_directory() );
define( 'CCG_INC_PATH', CCG_TEMPLATE_DIR . '/inc' );

define( 'DISALLOW_FILE_EDIT', true );

// Actions
require CCG_INC_PATH . '/actions.php';

// Filters
require CCG_INC_PATH . '/filters.php';

// Remove Functions
require CCG_INC_PATH . '/remove.php';

// Style and Scripts
require CCG_INC_PATH . '/styles-scripts.php';

// Template Functions
require CCG_INC_PATH . '/template-functions.php';

// Custom Post Types
require CCG_INC_PATH . '/custom-post-types.php';

// Custom Taxonomies
require CCG_INC_PATH . '/custom-taxonomies.php';

// ACF
require CCG_INC_PATH . '/acf.php';

// Shortcodes
require CCG_INC_PATH . '/shortcodes.php';

// Ajax Calls
require CCG_INC_PATH . '/ajax-calls.php';
<?php
/**
 * USA States functions and definitions
 *
 * @package USA States
 */

if ( ! function_exists( 'states_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function states_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on USA States, use a find and replace
	 * to change 'states' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'states', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'states' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'states_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // states_setup
add_action( 'after_setup_theme', 'states_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function states_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'states_content_width', 640 );
}
add_action( 'after_setup_theme', 'states_content_width', 0 );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function states_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'states' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'states_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function states_scripts() {
	wp_enqueue_style( 'states-style', get_stylesheet_uri() );

	wp_enqueue_script( 'states-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'states-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'states_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';


//not working anymore since update to api v2
function statesoftheusa_remove_extra_data( $response, $post, $request ) {
	// We only want to modify the 'view' context, for reading posts
	if ( $context !== 'view' || is_wp_error( $response->data ) ) {
		return $response;
	}

	$data = $response->data;

	if ( $data['type'] === 'state' ) {
		// Here, we unset any data we don't want to see on the front end:
		unset( $data['author'] );
		unset( $data['status'] );
		unset( $data['link'] );
		unset( $data['type'] );
		unset( $data['meta'] );
		unset( $data['content'] );
		unset( $data['parent'] );
		unset( $data['date'] );
		unset( $data['modified'] );
		unset( $data['format'] );
		unset( $data['guid'] );
		unset( $data['excerpt'] );
		unset( $data['menu_order'] );
		unset( $data['comment_status'] );
		unset( $data['ping_status'] );
		unset( $data['sticky'] );
		unset( $data['date_tz'] );
		unset( $data['date_gmt'] );
		unset( $data['modified_tz'] );
		unset( $data['modified_gmt'] );
		unset( $data['terms'] );


		unset( $data['acf']['bordering_states'] );

		$data['acf']['abbr'] = strtolower( $data['acf']['abbreviation'] );
	}

	$response->data = $data;

	return $response;
}

// add_filter( 'json_prepare_post', 'statesoftheusa_remove_extra_data', 12, 3 );
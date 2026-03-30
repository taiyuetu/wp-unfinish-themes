<?php
/**
 * wheelhubassembly functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wheelhubassembly
 */

if (!defined('_S_VERSION')) {
	// Replace the version number of the theme on each release.
	define('_S_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function wheelhubassembly_setup()
{
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on wheelhubassembly, use a find and replace
	 * to change 'wheelhubassembly' to the name of your theme in all the template files.
	 */
	load_theme_textdomain('wheelhubassembly', get_template_directory() . '/languages');

	// Add default posts and comments RSS feed links to head.
	add_theme_support('automatic-feed-links');

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support('title-tag');

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support('post-thumbnails');

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
		'menu-1' => esc_html__('Primary', 'wheelhubassembly'),
	)
	);

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
		'wheelhubassembly_custom_background_args',
		array(
		'default-color' => 'ffffff',
		'default-image' => '',
	)
	)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support('customize-selective-refresh-widgets');

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
		'height' => 250,
		'width' => 250,
		'flex-width' => true,
		'flex-height' => true,
	)
	);
}
add_action('after_setup_theme', 'wheelhubassembly_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wheelhubassembly_content_width()
{
	$GLOBALS['content_width'] = apply_filters('wheelhubassembly_content_width', 640);
}
add_action('after_setup_theme', 'wheelhubassembly_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wheelhubassembly_widgets_init()
{
	register_sidebar(
		array(
		'name' => esc_html__('Sidebar', 'wheelhubassembly'),
		'id' => 'sidebar-1',
		'description' => esc_html__('Add widgets here.', 'wheelhubassembly'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget' => '</section>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	)
	);
}
add_action('widgets_init', 'wheelhubassembly_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function wheelhubassembly_scripts()
{
	wp_enqueue_style('wheelhubassembly-style', get_stylesheet_uri(), array(), _S_VERSION);
	wp_style_add_data('wheelhubassembly-style', 'rtl', 'replace');

	wp_enqueue_script('wheelhubassembly-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'wheelhubassembly_scripts');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';


//enqueue scripts
require_once get_template_directory() . '/framework/functions/enqueue.php';

//include wp rapid fields
require_once get_template_directory() . '/framework/vendor/wp-rapid-fields.php';

//init wp rapid fields
require_once get_template_directory() . '/framework/admin/wrf-init.php';

// custom slider 
require_once get_template_directory() . '/framework/admin/custom-sliders.php';

//custom image collection 
require_once get_template_directory() . '/framework/admin/image-collection.php';

//clean up 
require_once get_template_directory() . '/framework/functions/clean-up.php';

//clean url 
require_once get_template_directory() . '/framework/functions/clean-url.php';

//user avatar 
require_once get_template_directory() . '/framework/functions/user-avatar.php';

//post type module 
require_once get_template_directory() . '/framework/functions/videos-module.php';

//custom blocks 
require_once get_template_directory() . '/framework/my-custom-block/my-custom-block.php';

//custom mega menu walker 
require_once get_template_directory() . '/framework/functions/class-mega-menu-walker.php';

function wps_enable_svg_upload($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'wps_enable_svg_upload');

/**
 * Fix SVG display in WordPress Media Library
 */
function wps_fix_svg_thumb_display()
{
    echo '<style>
		td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail {
			width: 100% !important;
			height: auto !important;
		}
	</style>';
}
add_action('admin_head', 'wps_fix_svg_thumb_display');

/**
 * Fix SVG display in the media library and bypass security checks that might block them.
 */
function mjagency_fix_svg_upload($data, $file, $filename, $mimes)
{
	$ext = isset($data['ext']) ? $data['ext'] : '';
	if (empty($ext)) {
		$exploded = explode('.', $filename);
		$ext = strtolower(end($exploded));
	}

	if ($ext === 'svg') {
		$data['type'] = 'image/svg+xml';
		$data['ext'] = 'svg';
	}
	return $data;
}
add_filter('wp_check_filetype_and_ext', 'mjagency_fix_svg_upload', 10, 4);

/**
 * Ensures SVG images are visible in the WordPress admin/media library.
 */
function mjagency_admin_svg_css()
{
	echo '<style>
		.attachment-266x266, .thumbnail img[src$=".svg"] { 
		width: 100% !important; 
		height: auto !important; 
		}
	</style>';
}
add_action('admin_head', 'mjagency_admin_svg_css');
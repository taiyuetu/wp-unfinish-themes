<?php
/**
 * WP Rapid Fields Pro — Comprehensive Init Example
 *
 * Drop this file into your theme's /inc/ directory and require it from functions.php:
 *
 *   require_once get_template_directory() . '/inc/wp-rapid-fields-pro.php';
 *   require_once get_template_directory() . '/inc/wp-rapid-fields-example.php';
 *
 * Each section below is a standalone example. Enable only what you need.
 */

if (!defined('ABSPATH'))
    exit;

// ===========================================================================
// 1. POST META — Page / Post custom fields
// ===========================================================================

add_action('init', function () {

    $config = [
        'id' => 'page_options', // Unique slug for this field group
        'title' => 'Page Options', // Meta box heading
        'context' => 'post', // 'post' context = post meta box
        'post_types' => ['page', 'post'], // Register on these post types
    ];

    $tabs = [

        // -----------------------------------------------------------------------
        // Tab 1: Hero Section
        // -----------------------------------------------------------------------
        'hero' => [
            'label' => 'Hero',
            'fields' => [

                // Visual section heading (no ID, saves nothing)
                [
                    'type' => 'heading',
                    'label' => 'Hero Content',
                ],

                // Plain text
                [
                    'id' => 'hero_title',
                    'type' => 'text',
                    'label' => 'Hero Title',
                    'placeholder' => 'Enter a compelling headline...',
                    'default' => 'Welcome',
                ],

                // Textarea
                [
                    'id' => 'hero_subtitle',
                    'type' => 'textarea',
                    'label' => 'Hero Subtitle',
                    'desc' => 'Keep it under 160 characters for best results.',
                ],

                // Image (returns URL)
                [
                    'id' => 'hero_image',
                    'type' => 'image',
                    'label' => 'Hero Background Image',
                    'return' => 'url',
                ],

                // Color picker
                [
                    'id' => 'hero_overlay_color',
                    'type' => 'color',
                    'label' => 'Overlay Color',
                    'default' => '#000000',
                ],

                // Select (single)
                [
                    'id' => 'hero_layout',
                    'type' => 'select',
                    'label' => 'Hero Layout',
                    'options' => [
                        'centered' => 'Centered',
                        'left' => 'Left Aligned',
                        'right' => 'Right Aligned',
                        'fullscreen' => 'Fullscreen',
                    ],
                    'default' => 'centered',
                ],

                // Radio buttons
                [
                    'id' => 'hero_text_color',
                    'type' => 'radio',
                    'label' => 'Text Color',
                    'options' => [
                        'light' => 'Light (White)',
                        'dark' => 'Dark (Black)',
                    ],
                    'default' => 'light',
                ],

                // Checkbox
                [
                    'id' => 'hero_show_cta',
                    'type' => 'checkbox',
                    'label' => 'Show CTA Button',
                    'desc' => 'Display a call-to-action button in the hero section',
                ],

                [
                    'id' => 'hero_cta_text',
                    'type' => 'text',
                    'label' => 'CTA Button Text',
                    'placeholder' => 'Get Started',
                ],

                [
                    'id' => 'hero_cta_url',
                    'type' => 'url',
                    'label' => 'CTA Button URL',
                    'placeholder' => 'https://',
                ],
            ],
        ],

        // -----------------------------------------------------------------------
        // Tab 2: Content & Media
        // -----------------------------------------------------------------------
        'content' => [
            'label' => 'Content',
            'fields' => [

                [
                    'type' => 'heading',
                    'label' => 'Rich Content',
                ],

                // WP Editor (TinyMCE)
                [
                    'id' => 'extra_content',
                    'type' => 'wp_editor',
                    'label' => 'Additional Content Block',
                    'desc' => 'Rendered below the main content area.',
                ],

                [
                    'type' => 'heading',
                    'label' => 'Media',
                ],

                // Gallery (comma-separated attachment IDs)
                [
                    'id' => 'page_gallery',
                    'type' => 'gallery',
                    'label' => 'Image Gallery',
                    'desc' => 'Images will be displayed in a grid below the content.',
                ],

                // File upload
                [
                    'id' => 'download_file',
                    'type' => 'file',
                    'label' => 'Downloadable File',
                    'desc' => 'PDF, ZIP, or any file type.',
                ],

                // Image with ID return (useful for responsive srcset)
                [
                    'id' => 'og_image',
                    'type' => 'image',
                    'label' => 'Social Share Image',
                    'return' => 'id',
                    'desc' => 'Recommended: 1200×630px. Saves attachment ID.',
                ],
            ],
        ],

        // -----------------------------------------------------------------------
        // Tab 3: Relationships
        // -----------------------------------------------------------------------
        'relations' => [
            'label' => 'Relations',
            'fields' => [

                // Post select — single
                [
                    'id' => 'related_post',
                    'type' => 'post_select',
                    'label' => 'Related Post',
                    'post_type' => 'post',
                ],

                // Post select — multiple (any post type)
                [
                    'id' => 'related_pages',
                    'type' => 'post_select',
                    'label' => 'Related Pages',
                    'post_type' => 'page',
                    'multiple' => true,
                    'desc' => 'Select one or more pages to link from this page.',
                ],
            ],
        ],

        // -----------------------------------------------------------------------
        // Tab 4: Repeater Fields
        // -----------------------------------------------------------------------
        'repeaters' => [
            'label' => 'Repeaters',
            'fields' => [

                [
                    'type' => 'heading',
                    'label' => 'Team Members (Group Repeater)',
                ],

                // Group — repeatable rows of multiple sub-fields
                [
                    'id' => 'team_members',
                    'type' => 'group',
                    'label' => 'Team Members',
                    'button' => 'Add Team Member',
                    'sub_fields' => [
                        [
                            'id' => 'name',
                            'type' => 'text',
                            'label' => 'Full Name',
                            'placeholder' => 'Jane Doe',
                        ],
                        [
                            'id' => 'role',
                            'type' => 'text',
                            'label' => 'Job Title',
                            'placeholder' => 'Lead Designer',
                        ],
                        [
                            'id' => 'bio',
                            'type' => 'textarea',
                            'label' => 'Short Bio',
                        ],
                        [
                            'id' => 'photo',
                            'type' => 'image',
                            'label' => 'Profile Photo',
                            'return' => 'url',
                        ],
                        [
                            'id' => 'linkedin',
                            'type' => 'url',
                            'label' => 'LinkedIn URL',
                            'placeholder' => 'https://linkedin.com/in/...',
                        ],
                    ],
                ],

                [
                    'type' => 'heading',
                    'label' => 'Feature List (Set Repeater)',
                ],

                // Set — repeatable plain text items
                [
                    'id' => 'features_list',
                    'type' => 'set',
                    'label' => 'Key Features',
                    'button' => 'Add Feature',
                    'placeholder' => 'e.g. Fast performance',
                ],

                [
                    'id' => 'faq_topics',
                    'type' => 'set',
                    'label' => 'FAQ Topics',
                    'button' => 'Add Topic',
                    'placeholder' => 'Enter a topic...',
                ],
            ],
        ],

        // -----------------------------------------------------------------------
        // Tab 5: SEO & Advanced
        // -----------------------------------------------------------------------
        'seo' => [
            'label' => 'SEO',
            'fields' => [

                [
                    'type' => 'heading',
                    'label' => 'SEO Meta',
                ],

                [
                    'id' => 'seo_title',
                    'type' => 'text',
                    'label' => 'SEO Title',
                    'placeholder' => 'Leave blank to use post title',
                ],

                [
                    'id' => 'seo_description',
                    'type' => 'textarea',
                    'label' => 'Meta Description',
                    'desc' => 'Recommended: 120–160 characters.',
                ],

                [
                    'id' => 'seo_noindex',
                    'type' => 'checkbox',
                    'label' => 'No Index',
                    'desc' => 'Tell search engines not to index this page.',
                ],

                [
                    'type' => 'heading',
                    'label' => 'Advanced',
                ],

                // Raw HTML — admin only, unfiltered
                [
                    'id' => 'custom_scripts',
                    'type' => 'raw_html',
                    'label' => 'Custom Scripts / Embed Codes',
                ],

                // Select multiple
                [
                    'id' => 'page_tags',
                    'type' => 'select',
                    'label' => 'Internal Tags',
                    'multiple' => true,
                    'options' => [
                        'featured' => 'Featured',
                        'promo' => 'Promo',
                        'seasonal' => 'Seasonal',
                        'internal' => 'Internal Only',
                    ],
                ],

                // Number input
                [
                    'id' => 'sort_order',
                    'type' => 'number',
                    'label' => 'Sort Order',
                    'placeholder' => '0',
                    'default' => 0,
                ],

                // Date
                [
                    'id' => 'publish_date_override',
                    'type' => 'date',
                    'label' => 'Display Date Override',
                    'desc' => 'Overrides the displayed publish date if set.',
                ],

                // Email
                [
                    'id' => 'contact_email',
                    'type' => 'email',
                    'label' => 'Contact Email',
                    'placeholder' => 'you@example.com',
                ],
            ],
        ],
    ];

    new WP_Rapid_Fields_Pro($config, $tabs);

});


// ===========================================================================
// 2. TERM META — Category extra fields
// ===========================================================================

add_action('init', function () {

    $config = [
        'id' => 'category_fields',
        'title' => 'Category Options',
        'context' => 'term',
        'taxonomy' => 'category', // or: ['category', 'product_cat']
    ];

    $tabs = [
        'display' => [
            'label' => 'Display',
            'fields' => [
                [
                    'id' => 'cat_banner_image',
                    'type' => 'image',
                    'label' => 'Category Banner Image',
                    'return' => 'url',
                ],
                [
                    'id' => 'cat_description_extended',
                    'type' => 'wp_editor',
                    'label' => 'Extended Description',
                    'desc' => 'Displayed at the top of the category archive page.',
                ],
                [
                    'id' => 'cat_color',
                    'type' => 'color',
                    'label' => 'Category Accent Color',
                    'default' => '#0073aa',
                ],
                [
                    'id' => 'cat_hide_from_menu',
                    'type' => 'checkbox',
                    'label' => 'Hide from Navigation',
                    'desc' => 'Exclude this category from auto-generated navigation.',
                ],
            ],
        ],
    ];

    new WP_Rapid_Fields_Pro($config, $tabs);

});


// ===========================================================================
// 3. USER META — Author profile extra fields
// ===========================================================================

add_action('init', function () {

    $config = [
        'id' => 'author_profile',
        'title' => 'Author Profile',
        'context' => 'user',
    ];

    $tabs = [
        'profile' => [
            'label' => 'Profile',
            'fields' => [
                [
                    'id' => 'author_avatar',
                    'type' => 'image',
                    'label' => 'Custom Avatar',
                    'return' => 'url',
                    'desc' => 'Overrides the Gravatar.',
                ],
                [
                    'id' => 'author_tagline',
                    'type' => 'text',
                    'label' => 'Tagline / Role',
                    'placeholder' => 'Senior Editor, Product Reviews',
                ],
                [
                    'id' => 'author_bio_extended',
                    'type' => 'textarea',
                    'label' => 'Extended Bio',
                ],
            ],
        ],
        'social' => [
            'label' => 'Social Links',
            'fields' => [
                [
                    'id' => 'social_twitter',
                    'type' => 'url',
                    'label' => 'X (Twitter)',
                    'placeholder' => 'https://x.com/username',
                ],
                [
                    'id' => 'social_linkedin',
                    'type' => 'url',
                    'label' => 'LinkedIn',
                    'placeholder' => 'https://linkedin.com/in/username',
                ],
                [
                    'id' => 'social_github',
                    'type' => 'url',
                    'label' => 'GitHub',
                    'placeholder' => 'https://github.com/username',
                ],
                [
                    'id' => 'social_website',
                    'type' => 'url',
                    'label' => 'Personal Website',
                    'placeholder' => 'https://yourwebsite.com',
                ],
            ],
        ],
    ];

    new WP_Rapid_Fields_Pro($config, $tabs);

});


// ===========================================================================
// 4. OPTIONS PAGE — Theme-wide global settings
// ===========================================================================

add_action('init', function () {

    $config = [
        'id' => 'theme_options',
        'title' => 'Theme Options',
        'context' => 'option',
    ];

    $tabs = [
        'general' => [
            'label' => 'General',
            'fields' => [
                [
                    'type' => 'heading',
                    'label' => 'Branding',
                ],
                [
                    'id' => 'site_logo',
                    'type' => 'image',
                    'label' => 'Logo',
                    'return' => 'url',
                ],
                [
                    'id' => 'site_logo_retina',
                    'type' => 'image',
                    'label' => 'Logo (Retina @2x)',
                    'return' => 'url',
                ],
                [
                    'id' => 'primary_color',
                    'type' => 'color',
                    'label' => 'Primary Brand Color',
                    'default' => '#0073aa',
                ],
                [
                    'id' => 'secondary_color',
                    'type' => 'color',
                    'label' => 'Secondary Brand Color',
                    'default' => '#23282d',
                ],
                [
                    'type' => 'heading',
                    'label' => 'Contact Info',
                ],
                [
                    'id' => 'contact_phone',
                    'type' => 'text',
                    'label' => 'Phone Number',
                    'placeholder' => '+1 (800) 000-0000',
                ],
                [
                    'id' => 'contact_email_global',
                    'type' => 'email',
                    'label' => 'General Contact Email',
                    'placeholder' => 'hello@yoursite.com',
                ],
                [
                    'id' => 'contact_address',
                    'type' => 'textarea',
                    'label' => 'Office Address',
                ],
            ],
        ],

        'header' => [
            'label' => 'Header',
            'fields' => [
                [
                    'id' => 'header_style',
                    'type' => 'select',
                    'label' => 'Header Style',
                    'options' => [
                        'standard' => 'Standard',
                        'transparent' => 'Transparent',
                        'sticky' => 'Sticky',
                        'centered' => 'Centered Logo',
                    ],
                    'default' => 'standard',
                ],
                [
                    'id' => 'header_cta_enabled',
                    'type' => 'checkbox',
                    'label' => 'Show Header CTA Button',
                    'desc' => 'Display a button in the top navigation bar.',
                ],
                [
                    'id' => 'header_cta_text',
                    'type' => 'text',
                    'label' => 'Header CTA Text',
                    'placeholder' => 'Get a Quote',
                ],
                [
                    'id' => 'header_cta_url',
                    'type' => 'url',
                    'label' => 'Header CTA URL',
                    'placeholder' => 'https://',
                ],
            ],
        ],

        'footer' => [
            'label' => 'Footer',
            'fields' => [
                [
                    'id' => 'footer_about_text',
                    'type' => 'textarea',
                    'label' => 'Footer About Text',
                    'desc' => 'Short description shown in the first footer column.',
                ],
                [
                    'id' => 'footer_copyright',
                    'type' => 'text',
                    'label' => 'Copyright Text',
                    'placeholder' => '© 2026 My Company. All rights reserved.',
                ],
                [
                    'id' => 'footer_social_links',
                    'type' => 'group',
                    'label' => 'Social Media Links',
                    'button' => 'Add Social Link',
                    'sub_fields' => [
                        [
                            'id' => 'platform',
                            'type' => 'select',
                            'label' => 'Platform',
                            'options' => [
                                'facebook' => 'Facebook',
                                'twitter' => 'X (Twitter)',
                                'instagram' => 'Instagram',
                                'linkedin' => 'LinkedIn',
                                'youtube' => 'YouTube',
                                'github' => 'GitHub',
                            ],
                        ],
                        [
                            'id' => 'url',
                            'type' => 'url',
                            'label' => 'Profile URL',
                            'placeholder' => 'https://',
                        ],
                    ],
                ],
            ],
        ],

        'scripts' => [
            'label' => 'Scripts',
            'fields' => [
                [
                    'id' => 'head_scripts',
                    'type' => 'raw_html',
                    'label' => 'Head Scripts',
                    'desc' => 'Injected inside <head>. Admins only.',
                ],
                [
                    'id' => 'footer_scripts',
                    'type' => 'raw_html',
                    'label' => 'Footer Scripts',
                    'desc' => 'Injected before </body>. Admins only.',
                ],
            ],
        ],
    ];

    new WP_Rapid_Fields_Pro($config, $tabs);

});


// ===========================================================================
// USAGE EXAMPLES — Retrieving saved values in templates
// ===========================================================================

/**
 * Example 1: Render the hero section (post meta)
 *
 * Usage: echo render_hero_section();
 */
function render_hero_section()
{
    $post_id = get_the_ID();
    $title = get_post_meta($post_id, 'hero_title', true);
    $sub = get_post_meta($post_id, 'hero_subtitle', true);
    $image = get_post_meta($post_id, 'hero_image', true);
    $layout = get_post_meta($post_id, 'hero_layout', true) ?: 'centered';
    $cta = get_post_meta($post_id, 'hero_show_cta', true);
    $cta_txt = get_post_meta($post_id, 'hero_cta_text', true);
    $cta_url = get_post_meta($post_id, 'hero_cta_url', true);

    ob_start(); ?>
    <section class="hero hero--<?php echo esc_attr($layout); ?>"
        <?php if ($image): ?>style="background-image:url(<?php echo esc_url($image); ?>)"<?php
    endif; ?>>
        <?php if ($title): ?><h1><?php echo esc_html($title); ?></h1><?php
    endif; ?>
        <?php if ($sub): ?><p><?php echo esc_html($sub); ?></p><?php
    endif; ?>
        <?php if ($cta && $cta_url): ?>
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn-primary">
                <?php echo esc_html($cta_txt ?: 'Learn More'); ?>
            </a>
        <?php
    endif; ?>
    </section>
    <?php
    return ob_get_clean();
}


/**
 * Example 2: Display team members (group repeater)
 */
function render_team_members()
{
    $members = get_post_meta(get_the_ID(), 'team_members', true);
    if (!is_array($members) || empty($members))
        return '';

    ob_start(); ?>
    <div class="team-grid">
        <?php foreach ($members as $member): ?>
            <div class="team-card">
                <?php if (!empty($member['photo'])): ?>
                    <img src="<?php echo esc_url($member['photo']); ?>" alt="<?php echo esc_attr($member['name'] ?? ''); ?>">
                <?php
        endif; ?>
                <h3><?php echo esc_html($member['name'] ?? ''); ?></h3>
                <p class="role"><?php echo esc_html($member['role'] ?? ''); ?></p>
                <?php if (!empty($member['bio'])): ?>
                    <p><?php echo esc_html($member['bio']); ?></p>
                <?php
        endif; ?>
                <?php if (!empty($member['linkedin'])): ?>
                    <a href="<?php echo esc_url($member['linkedin']); ?>" target="_blank">LinkedIn</a>
                <?php
        endif; ?>
            </div>
        <?php
    endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}


/**
 * Example 3: Output a set field (plain list)
 */
function render_features_list()
{
    $features = get_post_meta(get_the_ID(), 'features_list', true);
    if (!is_array($features) || empty($features))
        return '';

    $output = '<ul class="features-list">';
    foreach ($features as $feature) {
        if (trim($feature)) {
            $output .= '<li>' . esc_html($feature) . '</li>';
        }
    }
    $output .= '</ul>';
    return $output;
}


/**
 * Example 4: Retrieve theme options (option context)
 */
function get_theme_opt($key, $fallback = '')
{
    static $opts = null;
    if ($opts === null) {
        $opts = get_option('theme_options_opts', []);
        if (!is_array($opts))
            $opts = [];
    }
    return isset($opts[$key]) && $opts[$key] !== '' ? $opts[$key] : $fallback;
}

// Usage:
// $logo         = get_theme_opt( 'site_logo' );
// $brand_color  = get_theme_opt( 'primary_color', '#0073aa' );
// $phone        = get_theme_opt( 'contact_phone' );
// $footer_links = get_theme_opt( 'footer_social_links', [] ); // group field → array


/**
 * Example 5: Render the page gallery
 */
function render_page_gallery()
{
    $ids = get_post_meta(get_the_ID(), 'page_gallery', true);
    if (!$ids)
        return '';

    $id_array = array_filter(explode(',', $ids));
    if (empty($id_array))
        return '';

    $output = '<div class="gallery-grid">';
    foreach ($id_array as $img_id) {
        $img_id = (int)$img_id;
        $full = wp_get_attachment_image_url($img_id, 'full');
        $output .= '<a href="' . esc_url($full) . '">';
        $output .= wp_get_attachment_image($img_id, 'medium');
        $output .= '</a>';
    }
    $output .= '</div>';
    return $output;
}


/**
 * Example 6: SEO meta tags (output in wp_head)
 *
 * add_action('wp_head', 'output_custom_seo_tags');
 */
function output_custom_seo_tags()
{
    if (!is_singular())
        return;

    $post_id = get_the_ID();
    $seo_title = get_post_meta($post_id, 'seo_title', true);
    $seo_desc = get_post_meta($post_id, 'seo_description', true);
    $noindex = get_post_meta($post_id, 'seo_noindex', true);
    $og_img_id = get_post_meta($post_id, 'og_image', true);

    if ($seo_title) {
        echo '<title>' . esc_html($seo_title) . '</title>' . "\n";
    }
    if ($seo_desc) {
        echo '<meta name="description" content="' . esc_attr($seo_desc) . '">' . "\n";
    }
    if ($noindex) {
        echo '<meta name="robots" content="noindex,nofollow">' . "\n";
    }
    if ($og_img_id && is_numeric($og_img_id)) {
        $og_url = wp_get_attachment_image_url((int)$og_img_id, 'full');
        if ($og_url) {
            echo '<meta property="og:image" content="' . esc_url($og_url) . '">' . "\n";
        }
    }
}


/**
 * Get theme option using WP Rapid Fields framework.
 *
 * @param string $option_name
 * @param mixed $default
 * @return mixed
 */

function ws_get_option($option_name, $default = '')
{
    $lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
    $default_lang = function_exists('pll_default_language') ? pll_default_language('slug') : '';

    if ($lang && $lang === $default_lang) {
        // Default language: Try base options first, then suffixed options
        $options = get_option('theme_options_opts', []);
        if (is_array($options) && isset($options[$option_name]) && $options[$option_name] !== '') {
            return $options[$option_name];
        }

        $options = get_option('theme_options_opts_' . $lang, []);
        if (is_array($options) && isset($options[$option_name]) && $options[$option_name] !== '') {
            return $options[$option_name];
        }
    }
    elseif ($lang) {
        // Non-default language: Try suffixed options first, then base options
        $options = get_option('theme_options_opts_' . $lang, []);
        if (is_array($options) && isset($options[$option_name]) && $options[$option_name] !== '') {
            return $options[$option_name];
        }

        $options = get_option('theme_options_opts', []);
        if (is_array($options) && isset($options[$option_name]) && $options[$option_name] !== '') {
            return $options[$option_name];
        }
    }
    else {
        // No Polylang: use base options
        $options = get_option('theme_options_opts', []);
        if (is_array($options) && isset($options[$option_name]) && $options[$option_name] !== '') {
            return $options[$option_name];
        }
    }

    return $default;
}

/**
 * Get Polylang-aware post meta value.
 * Tries language-specific meta key first, falls back to base key.
 *
 * @param int    $post_id   Post ID
 * @param string $meta_key  Base meta key (without language suffix)
 * @param bool   $single    Whether to return a single value
 * @return mixed
 */
function ws_get_post_meta($post_id, $meta_key, $single = true)
{
    $lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
    $default_lang = function_exists('pll_default_language') ? pll_default_language('slug') : '';

    if ($lang && $lang === $default_lang) {
        // Default language: Try base meta first, then suffixed meta
        $val = get_post_meta($post_id, $meta_key, $single);
        if (!empty($val)) {
            return $val;
        }

        $lang_key = $meta_key . '_' . $lang;
        if (metadata_exists('post', $post_id, $lang_key)) {
            $val = get_post_meta($post_id, $lang_key, $single);
            if (!empty($val)) {
                return $val;
            }
        }
    }
    elseif ($lang) {
        // Non-default language: Try suffixed meta first, then base meta
        $lang_key = $meta_key . '_' . $lang;
        if (metadata_exists('post', $post_id, $lang_key)) {
            $val = get_post_meta($post_id, $lang_key, $single);
            if (!empty($val)) {
                return $val;
            }
        }

        return get_post_meta($post_id, $meta_key, $single);
    }

    return get_post_meta($post_id, $meta_key, $single);
}

/**
 * Get Polylang-aware term meta value.
 * Tries language-specific meta key first, falls back to base key.
 *
 * @param int    $term_id   Term ID
 * @param string $meta_key  Base meta key (without language suffix)
 * @param bool   $single    Whether to return a single value
 * @return mixed
 */
function ws_get_term_meta($term_id, $meta_key, $single = true)
{
    $lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
    $default_lang = function_exists('pll_default_language') ? pll_default_language('slug') : '';

    if ($lang && $lang === $default_lang) {
        // Default language: Try base meta first, then suffixed meta
        $val = get_term_meta($term_id, $meta_key, $single);
        if (!empty($val)) {
            return $val;
        }

        $lang_key = $meta_key . '_' . $lang;
        if (metadata_exists('term', $term_id, $lang_key)) {
            $val = get_term_meta($term_id, $lang_key, $single);
            if (!empty($val)) {
                return $val;
            }
        }
    }
    elseif ($lang) {
        // Non-default language: Try suffixed meta first, then base meta
        $lang_key = $meta_key . '_' . $lang;
        if (metadata_exists('term', $term_id, $lang_key)) {
            $val = get_term_meta($term_id, $lang_key, $single);
            if (!empty($val)) {
                return $val;
            }
        }

        return get_term_meta($term_id, $meta_key, $single);
    }

    return get_term_meta($term_id, $meta_key, $single);
}

/**
 * Get Polylang-aware user meta value.
 * Tries language-specific meta key first, falls back to base key.
 *
 * @param int    $user_id   User ID
 * @param string $meta_key  Base meta key (without language suffix)
 * @param bool   $single    Whether to return a single value
 * @return mixed
 */
function ws_get_user_meta($user_id, $meta_key, $single = true)
{
    $lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
    $default_lang = function_exists('pll_default_language') ? pll_default_language('slug') : '';

    if ($lang && $lang === $default_lang) {
        // Default language: Try base meta first, then suffixed meta
        $val = get_user_meta($user_id, $meta_key, $single);
        if (!empty($val)) {
            return $val;
        }

        $lang_key = $meta_key . '_' . $lang;
        if (metadata_exists('user', $user_id, $lang_key)) {
            $val = get_user_meta($user_id, $lang_key, $single);
            if (!empty($val)) {
                return $val;
            }
        }
    }
    elseif ($lang) {
        // Non-default language: Try suffixed meta first, then base meta
        $lang_key = $meta_key . '_' . $lang;
        if (metadata_exists('user', $user_id, $lang_key)) {
            $val = get_user_meta($user_id, $lang_key, $single);
            if (!empty($val)) {
                return $val;
            }
        }

        return get_user_meta($user_id, $meta_key, $single);
    }

    return get_user_meta($user_id, $meta_key, $single);
}



add_action('admin_bar_menu', 'add_theme_options_to_admin_bar', 100);


function add_theme_options_to_admin_bar($admin_bar)
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $admin_bar->add_node([
        'id' => 'mjagency-theme-options',
        'title' => 'Theme Options',
        'href' => admin_url('admin.php?page=theme_options'),
        'meta' => [
            'title' => 'Theme Options'

        ]

    ]);
}

<?php
/**
 * Register Custom Post Type: Portfolio
 * Register Custom Taxonomy: Project Type
 */

function custom_portfolio_init()
{

    // 1. Portfolio Custom Post Type Labels & Args
    $portfolio_labels = array(
        'name' => 'Portfolios',
        'singular_name' => 'Portfolio',
        'add_new' => 'Add New Project',
        'add_new_item' => 'Add New Portfolio Project',
        'edit_item' => 'Edit Project',
        'new_item' => 'New Project',
        'view_item' => 'View Project',
        'search_items' => 'Search Portfolios',
        'not_found' => 'No projects found',
        'not_found_in_trash' => 'No projects found in Trash',
        'menu_name' => 'Portfolio',
    );

    $portfolio_args = array(
        'labels' => $portfolio_labels,
        'public' => true,
        'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true, // Essential for Gutenberg/Block Editor support
        'query_var' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-portfolio', // Official WP icon
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
    );

    register_post_type('portfolio', $portfolio_args);

    // 2. Project Type Taxonomy (Categories for Portfolio)
    $taxonomy_labels = array(
        'name' => 'Project Types',
        'singular_name' => 'Project Type',
        'search_items' => 'Search Project Types',
        'all_items' => 'All Project Types',
        'parent_item' => 'Parent Project Type',
        'parent_item_colon' => 'Parent Project Type:',
        'edit_item' => 'Edit Project Type',
        'update_item' => 'Update Project Type',
        'add_new_item' => 'Add New Project Type',
        'new_item_name' => 'New Project Type Name',
        'menu_name' => 'Project Types',
    );

    $taxonomy_args = array(
        'hierarchical' => true, // Set to true to act like Categories, false for Tags
        'labels' => $taxonomy_labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'project-type'),
    );

    register_taxonomy('project_type', array('portfolio'), $taxonomy_args);
}

add_action('init', 'custom_portfolio_init');
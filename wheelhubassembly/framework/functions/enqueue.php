<?php

add_action('wp_enqueue_scripts', 'hub_assembly_enqueue_scripts');
function hub_assembly_enqueue_scripts()
{
    wp_enqueue_style('swiper-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_style('hub-assembly-style', get_template_directory_uri() . '/assets/assets/css/style.css');
    wp_enqueue_style('hub-assembly-add-on', get_template_directory_uri() . '/css/add-on.css');
    wp_enqueue_script('swiper-script', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    wp_enqueue_script('hub-assembly-script', get_template_directory_uri() . '/assets/assets/js/main.js', array(), '1.0.0', true);
    wp_enqueue_script('hub-assembly-add-on', get_template_directory_uri() . '/js/add-on.js', array(), '1.0.0', true);
}
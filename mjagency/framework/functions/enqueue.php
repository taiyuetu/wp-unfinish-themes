<?php

add_action('wp_enqueue_scripts', 'mjagency_enqueue_scripts');

function mjagency_enqueue_scripts()
{
    wp_enqueue_style('mjagency-theme-style', get_template_directory_uri() . '/assets/css/styles.css');
    wp_enqueue_script('mjagency-theme-script', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
}
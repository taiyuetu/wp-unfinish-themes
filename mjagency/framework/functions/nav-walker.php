<?php
/**
 * Custom Nav Walker for Primary Navigation
 * Replicates: nav-links with has-dropdown & dropdown-menu support
 */

class Custom_Nav_Walker extends Walker_Nav_Menu
{

    // Opening wrapper for each menu item <li>
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? [] : (array)$item->classes;
        $has_children = in_array('menu-item-has-children', $classes);

        // Add custom class for items with dropdowns
        if ($has_children && $depth === 0) {
            $classes[] = 'has-dropdown';
        }

        $class_names = implode(' ', array_filter(array_map('sanitize_html_class', $classes)));
        $class_attr = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        // Build <li>
        $output .= $indent . '<li' . $class_attr . '>';

        // Attributes for <a>
        $atts = [];
        $atts['href'] = !empty($item->url) ? $item->url : '#';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
        $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';

        // Build attribute string
        $attr_string = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $attr_string .= ' ' . $attr . '="' . esc_attr($value) . '"';
            }
        }

        $link_before = isset($args->link_before) ? $args->link_before : '';
        $link_after = isset($args->link_after) ? $args->link_after : '';
        $item_output = '';

        $item_output .= '<a' . $attr_string . '>';
        $item_output .= $link_before;
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= $link_after;

        // Add dropdown arrow span for parent items
        if ($has_children && $depth === 0) {
            $item_output .= ' <span class="dropdown-arrow"></span>';
        }

        $item_output .= '</a>';

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    // Opening <ul> for sub-menus
    public function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
    }

    // Closing </ul> for sub-menus
    public function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
}
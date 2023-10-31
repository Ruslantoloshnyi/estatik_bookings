<?php
/*
Plugin Name: estatik-bookings
Description: This is a plugin for managing bookings.
Version:  1.0
Author: Ruslan Toloshnyi
*/

defined('ABSPATH') || exit; // Ensure that WordPress is loaded, or exit

// Register a custom post type for bookings
function register_booking_post_type()
{
    $args = array(
        'public' => true,
        'label'  => 'Bookings',
    );
    register_post_type('booking', $args);
}
add_action('init', 'register_booking_post_type');

// Include the metabox template file
function include_metabox_template()
{
    include plugin_dir_path(__FILE__) . 'templates/metabox-template.php';
}

include_metabox_template();

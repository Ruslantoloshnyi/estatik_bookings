<?php
/*
Plugin Name: estatik-bookings
Description: bookings.
Version:  1.0
Author: Ruslan Toloshnyi
*/
?>

<?php
defined('ABSPATH') || exit;

function register_booking_post_type()
{
    $args = array(
        'public' => true,
        'label'  => 'Bookings',
        // Добавьте другие параметры по необходимости
    );
    register_post_type('booking', $args);
}
add_action('init', 'register_booking_post_type');

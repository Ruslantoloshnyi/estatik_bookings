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
    );
    register_post_type('booking', $args);
}
add_action('init', 'register_booking_post_type');


function add_booking_metabox()
{
    add_meta_box(
        'booking_details',
        'Booking Details',
        'render_booking_metabox',
        'booking',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_booking_metabox');


function render_booking_metabox($post)
{
    $start_date = get_post_meta($post->ID, 'start_date', true);
    $end_date = get_post_meta($post->ID, 'end_date', true);
    $address = get_post_meta($post->ID, 'address', true);

    // Вывод HTML для полей
    echo '<label for="start_date" style="display: block; margin-bottom: 5px;">Start Date:</label>';
    echo '<input type="text" id="start_date" name="start_date" class="datepicker" placeholder="Start date" value="' . date('d M Y H:i', $start_date) . '" /><br />';

    echo '<label for="end_date" style="display: block; margin-bottom: 5px;">End Date:</label>';
    echo '<input type="text" id="end_date" name="end_date" class="datepicker" value="' . date('d M Y H:i', $end_date) . '" /><br />';

    echo '<label for="address" style="display: block; margin-bottom: 5px;">Address:</label>';
    echo '<input type="text" id="address" name="address" class="address-field" value="' . $address . '" /><br />';
}

function enqueue_datepicker()
{
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_add_inline_script('jquery-ui-datepicker', 'jQuery(document).ready(function() { jQuery("#start_date, #end_date").datepicker(); });');
}
add_action('admin_enqueue_scripts', 'enqueue_datepicker');


function save_booking_metabox($post_id)
{
    if (isset($_POST['start_date']))
    {
        update_post_meta($post_id, 'start_date', strtotime($_POST['start_date']));
    }
    if (isset($_POST['end_date']))
    {
        update_post_meta($post_id, 'end_date', strtotime($_POST['end_date']));
    }
    if (isset($_POST['address']))
    {
        update_post_meta($post_id, 'address', sanitize_text_field($_POST['address']));
    }
}
add_action('save_post_booking', 'save_booking_metabox');

<?php
// Function to add a metabox to the booking custom post type
function add_booking_metabox_callback()
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
add_action('add_meta_boxes', 'add_booking_metabox_callback');

// Render the content of the metabox
function render_booking_metabox($post)
{
    // Retrieve saved values or set defaults
    $start_date = get_post_meta($post->ID, 'start_date', true);
    $end_date = get_post_meta($post->ID, 'end_date', true);
    $address = get_post_meta($post->ID, 'address', true);

    // Output HTML for fields
    echo '<label for="start_date" style="display: block; margin-bottom: 5px;">Start Date:</label>';
    echo '<input type="text" id="start_date" name="start_date" class="datepicker" placeholder="Start date" value="' . date('d M Y H:i', $start_date) . '" /><br />';

    echo '<label for="end_date" style="display: block; margin-bottom: 5px;">End Date:</label>';
    echo '<input type="text" id="end_date" name="end_date" class="datepicker" value="' . date('d M Y H:i', $end_date) . '" /><br />';

    echo '<label for="address" style="display: block; margin-bottom: 5px;">Address:</label>';
    echo '<input type="text" id="address" name="address" class="address-field" value="' . $address . '" /><br />';
}

// Enqueue datepicker scripts and styles
function enqueue_datepicker()
{
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');
    wp_add_inline_script('jquery-ui-datepicker', 'jQuery(document).ready(function() { jQuery("#start_date, #end_date").datepicker(); });');
}
add_action('admin_enqueue_scripts', 'enqueue_datepicker');

// Save metabox data when the post is saved
function save_booking_metabox($post_id)
{
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE')) return;

    // Check if the necessary fields are set
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

// Display Google map using a shortcode
function display_google_map_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'address' => '',
    ), $atts);

    return '<iframe width="100%" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' . urlencode($atts['address']) . '&output=embed"></iframe>';
}
add_shortcode('map', 'display_google_map_shortcode');

// Add booking data to the content of the post
function add_booking_data_to_content($content)
{
    // Check if this is a single booking post and in the main query
    if (is_singular('booking') && in_the_loop() && is_main_query())
    {
        $start_date = get_post_meta(get_the_ID(), 'start_date', true);
        $end_date = get_post_meta(get_the_ID(), 'end_date', true);
        $address = get_post_meta(get_the_ID(), 'address', true);

        // Check if all necessary data is available
        if ($start_date && $end_date && $address)
        {
            $booking_data = '<div class="booking-data">';
            $booking_data .= '<p>Booking Dates: ' . date('d M Y', $start_date) . ' - ' . date('d M Y', $end_date) . '</p>';
            $booking_data .= do_shortcode('[map address="' . $address . '"]');
            $booking_data .= '</div>';
            $content .= $booking_data;
        }
    }

    return $content;
}
add_filter('the_content', 'add_booking_data_to_content');

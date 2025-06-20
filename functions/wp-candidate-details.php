<?php

add_submenu_page(
    null, // Hidden from menu
    'Candidate Details',
    'Candidate Details',
    'manage_options',
    'candidate_details',
    'render_candidate_details_page'
);

function render_candidate_details_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidates';

    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if (!$id) {
        echo '<div class="notice notice-error"><p>Invalid candidate ID.</p></div>';
        return;
    }

    $candidate = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));
    if (!$candidate) {
        echo '<div class="notice notice-error"><p>Candidate not found.</p></div>';
        return;
    }

    echo '<div id="wpCandidateDetails" class="wrap"><h1>Candidate Details</h1>';
    echo '<table class="widefat fixed striped">';

    foreach ($candidate as $key => $value) {
        echo '<tr>';
        echo '<th>' . esc_html(ucwords(str_replace('_', ' ', $key))) . '</th>';
        echo '<td>' . esc_html($value) . '</td>';
        echo '</tr>';
    }

    echo '</table>';
    echo '<p><a href="' . esc_url(admin_url('admin.php?page=candidates')) . '" class="button">Back to Candidates</a></p>';
    echo '</div>';
}
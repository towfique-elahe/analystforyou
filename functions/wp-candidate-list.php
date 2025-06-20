<?php

add_action('admin_menu', 'register_candidates_page');

function register_candidates_page() {
    add_menu_page(
        'Candidates',
        'Candidates',
        'manage_options',
        'candidates',
        'render_candidates_page',
        'dashicons-groups',
        25
    );
}

function render_candidates_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'candidates';

    // Get filter & search values
    $status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';
    $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    // Build query conditions
    $conditions = [];
    if ($status_filter !== 'all') {
        $conditions[] = $wpdb->prepare("status = %s", $status_filter);
    }
    if (!empty($search_term)) {
        $search_like = '%' . $wpdb->esc_like($search_term) . '%';
        $conditions[] = $wpdb->prepare("(first_name LIKE %s OR last_name LIKE %s OR email LIKE %s)", $search_like, $search_like, $search_like);
    }

    $where_clause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
    $query = "SELECT * FROM $table_name $where_clause ORDER BY created_at DESC";
    $candidates = $wpdb->get_results($query);

    // Count totals
    $total_all = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_public = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'Public'");
    $total_private = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'Private'");

    // Output wrapper
    echo '<div id="wpCandidateList" class="wrap">';
    echo '<h1 class="wp-heading-inline">Candidates</h1>';

    // Inline filter + search bar
    echo '<div class="filter-bar">';

    // Filters
    $base_url = admin_url('admin.php?page=candidates');
    echo '<div class="subsubsub">';
    echo '<a href="' . esc_url($base_url) . '" class="' . ($status_filter === 'all' ? 'current' : '') . '">All (' . $total_all . ')</a> | ';
    echo '<a href="' . esc_url(add_query_arg('status', 'Public', $base_url)) . '" class="' . ($status_filter === 'Public' ? 'current' : '') . '">Public (' . $total_public . ')</a> | ';
    echo '<a href="' . esc_url(add_query_arg('status', 'Private', $base_url)) . '" class="' . ($status_filter === 'Private' ? 'current' : '') . '">Private (' . $total_private . ')</a>';
    echo '</div>';

    // Search form
    echo '<form method="get">';
    echo '<input type="hidden" name="page" value="candidates" />';
    if ($status_filter !== 'all') {
        echo '<input type="hidden" name="status" value="' . esc_attr($status_filter) . '" />';
    }
    echo '<input type="search" name="s" value="' . esc_attr($search_term) . '" />';
    echo '<input type="submit" class="button" value="Search Candidates" />';
    echo '</form>';

    echo '</div>'; // End flex wrapper

    // Candidate Table
    if ($candidates) {
        echo '<table class="widefat fixed striped">';
        echo '<thead>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Name</th>
                <th>Email</th>
                <th>Country</th>
                <th>Phone</th>
                <th>Specialization</th>
                <th>Experience</th>
                <th>Availability</th>
                <th>Registered At</th>
                <th>Action</th>
            </tr>
        </thead><tbody>';

        foreach ($candidates as $candidate) {
            echo '<tr>';
            echo '<td>' . esc_html($candidate->id) . '</td>';
            echo '<td>' . esc_html($candidate->status) . '</td>';
            echo '<td>' . esc_html($candidate->first_name . ' ' . $candidate->last_name) . '</td>';
            echo '<td>' . esc_html($candidate->email) . '</td>';
            echo '<td>' . esc_html($candidate->country) . '</td>';
            echo '<td>' . esc_html($candidate->phone) . '</td>';
            echo '<td>' . esc_html($candidate->specialization) . '</td>';
            echo '<td>' . esc_html($candidate->experience) . '</td>';
            echo '<td>' . esc_html($candidate->availability) . '</td>';
            echo '<td>' . esc_html($candidate->created_at) . '</td>';
            $details_url = admin_url('admin.php?page=candidate_details&id=' . $candidate->id);
            echo '<td><a href="' . esc_url($details_url) . '" title="View Details"><span class="dashicons dashicons-info"></span></a></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No candidates found.</p>';
    }

    echo '</div>';
}
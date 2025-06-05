<?php
/**
 * Roles Management
 */

 // Remove Existing Custom Roles
function customtheme_remove_custom_roles() {
    remove_role('parent');
    remove_role('teacher');
    remove_role('student');
    remove_role('hr');
    remove_role('employer');
    remove_role('subscriber');
    remove_role('contributor');
    remove_role('author');
    remove_role('editor');
}
add_action('init', 'customtheme_remove_custom_roles', 9);

// Custom User Roles with Base Capabilities
function customtheme_add_custom_roles() {
    // Define base capabilities for all roles
    $base_caps = [
        'read'            => true,
        'edit_posts'      => false,
        'delete_posts'    => false,
        'upload_files'    => false,
    ];

    // Define admin capabilities
    $admin_caps = array_merge($base_caps, [
        'manage_options'  => true,
        'edit_users'      => true,
        'delete_users'    => true,
        'create_users'    => true,
        'list_users'      => true,
        'promote_users'   => true,
    ]);

    // Add Admin Role
    add_role(
        'admin',
        __('Admin', 'customtheme'),
        $admin_caps
    );

    // Add Candidate Role
    add_role(
        'candidate',
        __('Candidate', 'customtheme'),
        $base_caps
    );

    // Add Recruiter Role
    add_role(
        'recruiter',
        __('Recruiter', 'customtheme'),
        $base_caps
    );
}
add_action('init', 'customtheme_add_custom_roles');

// Hide Admin Toolbar for Specific Roles
function customtheme_hide_admin_toolbar($show_toolbar) {
    $roles_to_hide_toolbar = ['admin', 'candidate', 'recruiter'];

    foreach ($roles_to_hide_toolbar as $role) {
        if (current_user_can($role)) {
            return false;
        }
    }

    return $show_toolbar;
}
add_filter('show_admin_bar', 'customtheme_hide_admin_toolbar');

// Restrict Non-Admin Users from Accessing the WordPress Admin Area
function customtheme_restrict_admin_dashboard() {
    if (!current_user_can('manage_options') && is_admin() && !defined('DOING_AJAX')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'customtheme_restrict_admin_dashboard');
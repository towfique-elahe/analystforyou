<?php
/**
 * Custom Database Tables Setup
**/

function create_custom_tables() {
    global $wpdb;

    $custom_tables_version = '1.0.3';
    $installed_version = get_option('custom_tables_version');

    if ($installed_version === $custom_tables_version) {
        return;
    }

    $charset_collate = $wpdb->get_charset_collate();
    $candidates_table = $wpdb->prefix . 'candidates';

    // SQL to create candidates table
    $candidates_sql = "CREATE TABLE $candidates_table (
        id BIGINT(20) UNSIGNED NOT NULL,
        status ENUM('Private', 'Public') NOT NULL DEFAULT 'Private',
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NULL,
        date_of_birth DATE NULL,
        email VARCHAR(255) NOT NULL,
        country VARCHAR(255) NULL,
        address TEXT NULL,
        city VARCHAR(255) NULL,
        postal_code VARCHAR(50) NULL,
        phone VARCHAR(20) NULL,
        bio VARCHAR(255) NULL,
        specialization VARCHAR(255) NULL,
        sub_role VARCHAR(255) NULL,
        lang_tools VARCHAR(255) NULL,
        experience VARCHAR(255) NULL,
        availability VARCHAR(255) NULL,
        sector VARCHAR(255) NULL,
        education VARCHAR(255) NULL,
        languages VARCHAR(255) NULL,
        cv VARCHAR(255) NULL,
        image VARCHAR(255) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_user_id (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($candidates_sql);

    // Add foreign key constraint manually (dbDelta does not support it)
    $fk_name = 'fk_candidates_user_id';
    $fk_check = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT CONSTRAINT_NAME 
             FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = %s 
             AND CONSTRAINT_NAME = %s",
            $candidates_table,
            $fk_name
        )
    );

    if (!$fk_check) {
        $wpdb->query("
            ALTER TABLE $candidates_table
            ADD CONSTRAINT $fk_name FOREIGN KEY (id) REFERENCES {$wpdb->prefix}users(ID) ON DELETE CASCADE
        ");
    }

    update_option('custom_tables_version', $custom_tables_version);
}
add_action('init', 'create_custom_tables', 20);
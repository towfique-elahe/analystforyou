<?php
/**
 * Template Name: Candidate Dashboard
 */

get_header();

// Restrict access to candidates
if (!is_user_logged_in()) {
    wp_redirect(site_url('/login'));
    exit;
}

$current_user = wp_get_current_user();
if (!in_array('candidate', (array) $current_user->roles)) {
    wp_redirect(site_url('/'));
    exit;
}
?>

<div id="candidateDashboard" class="portal">
    <div class="container">
        <?php get_template_part('template-parts/candidate/sidebar'); ?>
        <div class="main">
            <?php get_template_part('template-parts/candidate/topbar'); ?>
            <div class="content">
                <p>This is your dashboard. You can manage your account from here.</p>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
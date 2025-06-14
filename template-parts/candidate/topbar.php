<?php
global $wpdb;
$user_id = get_current_user_id();
$table   = $wpdb->prefix . 'candidates';

$image_url = $wpdb->get_var(
    $wpdb->prepare("SELECT image FROM $table WHERE id = %d", $user_id)
);

if (empty($image_url)) {
    $image_url = get_template_directory_uri() . '/assets/media/user.png';
}
?>
<div class="topbar">
    <h1 class="page-heading"><?php echo esc_html(get_the_title()); ?></h1>
    <div class="candidate">
        <img src="<?php echo esc_url($image_url); ?>" alt="Candidate Image" class="candidate-image">
    </div>
</div>
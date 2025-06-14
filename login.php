<?php
/**
 * Template Name: Candidate Login
 */

get_header();

// Redirect logged-in users with candidate role
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();
    if (in_array('candidate', (array) $current_user->roles)) {
        wp_redirect(site_url('/candidate-dashboard'));
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['candidate_login'])) {
    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];

    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true,
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        echo '<p style="color:crimson;">Login failed: ' . esc_html($user->get_error_message()) . '</p>';
    } else {
        if (in_array('candidate', (array) $user->roles)) {
            wp_redirect(site_url('/candidate-dashboard'));
            exit;
        } else {
            wp_logout();
            echo '<p style="color:crimson;">Access denied. Only candidates can log in here.</p>';
        }
    }
}
?>

<div id="login">
    <div class="container row">
        <div class="col">
            <img class="login-banner"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/login.svg'); ?>"
                alt="Login Illustration">
        </div>
        <div class="col">
            <form method="post" class="candidate-login-form">
                <div class="form-group">
                    <label for="username">Email / Username <span class="required">*</span></label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" name="password" required>
                </div>
                <div class="button-group">
                    <input type="submit" name="candidate_login" value="Login" class="login-button">
                </div>
                <div class="links">
                    <a href="<?php echo esc_url(site_url('/forget-password')); ?>">Forgot Password?</a> |
                    <a href="<?php echo esc_url(site_url('/register')); ?>">Register</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php get_footer(); ?>
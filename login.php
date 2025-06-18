<?php
/**
 * Template Name: Candidate Login
 */

get_header();

// Redirect logged-in users
if (is_user_logged_in()) {
    $current_user = wp_get_current_user();

    if (in_array('candidate', (array) $current_user->roles)) {
        wp_redirect(site_url('/candidate-dashboard'));
        exit;
    } elseif (!in_array('administrator', (array) $current_user->roles)) {
        wp_redirect(site_url('/'));
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

<div id="auth">
    <div class="container row">
        <div class="col auth-banner-container">
            <img class="auth-banner"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/login.svg'); ?>"
                alt="Login Illustration">
        </div>
        <div class="col auth-form-container">
            <form method="post" class="auth-form" autocomplete="off">
                <h3 class="heading">Welcome Back</h3>
                <p class="sub-heading">Login to access your candidate dashboard</p>
                <div class="form-group">
                    <label for="username">Email or Username <span class="required">*</span></label>
                    <input type="text" name="username" id="username" placeholder="Enter your email or username"
                        required>
                </div>
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <ion-icon name="eye-off-outline"></ion-icon>
                    </button>
                </div>
                <div class="button-group">
                    <input type="submit" name="candidate_login" value="Login" class="action-button">
                </div>
                <div class="links">
                    <p>Forgot password? <a href="<?php echo esc_url(site_url('/forget-password')); ?>">Reset</a></p>
                    <p>Don't have an account? <a href="<?php echo esc_url(site_url('/register')); ?>">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php get_footer(); ?>
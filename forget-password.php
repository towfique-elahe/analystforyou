<?php
/**
 * Template Name: Candidate Forgot Password
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

$reset_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['candidate_reset'])) {
    $user_login = sanitize_text_field($_POST['user_login']);

    if (empty($user_login)) {
        $reset_message = '<p class="error">Please enter your username or email.</p>';
    } else {
        $user = get_user_by('email', $user_login);
        if (!$user) {
            $user = get_user_by('login', $user_login);
        }

        if ($user) {
            if (in_array('candidate', (array) $user->roles)) {
                // Valid candidate user, send reset email
                $reset_result = retrieve_password($user_login);

                if ($reset_result === true) {
                    $reset_message = '<p class="success">Check your email for the password reset link.</p>';
                } else {
                    $reset_message = '<p class="error">Failed to send reset email. Please try again later.</p>';
                }
            } else {
                // User exists but has a different role
                $reset_message = '<p class="error">Only candidate accounts can reset their password here.</p>';
            }
        } else {
            $reset_message = '<p  class="error">No account found with that email or username.</p>';
        }
    }
}

?>

<div id="auth">
    <div class="container row">
        <div class="col">
            <img class="auth-banner"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/forgot-password.svg'); ?>"
                alt="Forgot Password Illustration">
        </div>
        <div class="col">
            <form method="post" class="auth-form" autocomplete="off">
                <h3 class="heading">Forgot Your Password?</h3>
                <p class="sub-heading">Enter your email or username and we’ll send a reset link.</p>

                <?php if (!empty($reset_message)) echo $reset_message; ?>

                <div class="form-group">
                    <label for="user_login">Email or Username <span class="required">*</span></label>
                    <input type="text" name="user_login" id="user_login" placeholder="you@example.com" required>
                </div>

                <div class="button-group">
                    <input type="submit" name="candidate_reset" value="Send Reset Link" class="action-button">
                </div>

                <div class="links">
                    <p>Remembered your password? <a href="<?php echo esc_url(site_url('/login')); ?>">Login</a></p>
                    <p>Need an account? <a href="<?php echo esc_url(site_url('/register')); ?>">Register</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php get_footer(); ?>
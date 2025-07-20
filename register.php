<?php
/**
 * Template Name: Candidate Register
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['candidate_register'])) {
    global $wpdb;

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $username = sanitize_user($_POST['username']);

    $register_error = '';
    if ($password !== $confirm_password) {
        $register_error = '<div class="error">Passwords do not match.</div>';
    } else {
        if (empty($username)) {
            $base_username = sanitize_title($first_name . '_' . $last_name);
            $username = $base_username;
            $count = 1;
            while (username_exists($username)) {
                $username = $base_username . '_' . $count++;
            }
        }

        if (!username_exists($username) && !email_exists($email)) {
            $user_id = wp_create_user($username, $password, $email);
            if (!is_wp_error($user_id)) {
                wp_update_user([
                    'ID' => $user_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                ]);
                wp_update_user(['ID' => $user_id, 'role' => 'candidate']);

                // Insert into custom candidate table
                $table = $wpdb->prefix . 'candidates';
                $wpdb->insert($table, [
                    'id' => $user_id,
                    'status' => 'Private',
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql'),
                ]);

                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);

                $user = wp_get_current_user();
                if (in_array('candidate', (array) $user->roles)) {
                    wp_redirect(site_url('/candidate-dashboard'));
                    exit;
                }
            } else {
                $register_error = '<div class="error">' . $user_id->get_error_message() . '</div>';
            }
        } else {
            $register_error = '<div class="error">Username or email already exists.</div>';
        }
    }
}

?>

<div id="auth">
    <div class="container row">
        <div class="col auth-banner-container">
            <img class="auth-banner"
                src="<?php echo esc_url(get_template_directory_uri() . '/assets/media/register.svg'); ?>"
                alt="Login Illustration">
        </div>
        <div class="col auth-form-container">
            <form method="post" class="auth-form" autocomplete="off">
                <h3 class="heading">Create Your Candidate Profile</h3>
                <p class="sub-heading">It only takes a minute to register. Start your journey with us!</p>
                <?php if (!empty($register_error)) {
                    echo $register_error;
                } ?>
                <div class="form-group">
                    <label for="first_name">First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" id="first_name" placeholder="John" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" id="last_name" placeholder="Doe" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Leave blank to auto-generate">
                </div>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" name="email" id="email" placeholder="you@example.com" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" id="phone" placeholder="e.g. +1 555 123 4567">
                </div>
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" name="password" id="password" placeholder="Enter a strong password" required>
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <ion-icon name="eye-off-outline"></ion-icon>
                    </button>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                    <input type="password" name="confirm_password" id="confirm_password"
                        placeholder="Re-enter your password" required>
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility">
                        <ion-icon name="eye-off-outline"></ion-icon>
                    </button>
                </div>
                <div class="button-group">
                    <input type="submit" name="candidate_register" value="Register" class="action-button">
                </div>
                <div class="links">
                    <p>Already have an account? <a href="<?php echo esc_url(site_url('/login')); ?>">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<?php get_footer(); ?>
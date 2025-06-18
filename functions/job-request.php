<?php
/**
 * Job Request Modal with AJAX Submission
 * Usage:
 * - [job_request_modal] auto-loading via footer
 * - Use Elementor button with custom attribute: data-request-job="1"
 */

// ===== 1. Modal Shortcode Output ===== //
function render_job_request_modal() {
    $skills        = get_terms(['taxonomy' => 'skills', 'hide_empty' => false]);
    $experience    = get_terms(['taxonomy' => 'years-of-experience', 'hide_empty' => false]);
    $location_pref = get_terms(['taxonomy' => 'location-preference', 'hide_empty' => false]);
    $availability  = get_terms(['taxonomy' => 'availability', 'hide_empty' => false]);
    $industry      = get_terms(['taxonomy' => 'industry-expertise', 'hide_empty' => false]);
    $lang_tools    = get_terms(['taxonomy' => 'l-and-t', 'hide_empty' => false]);

    ob_start(); ?>
<div id="job-request-modal" style="display:none;">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2 class="heading">Request a Job Post</h2>
        <form id="job-request-form">
            <h3 class="sub-heading">Recruiter Details</h3>

            <div class="form-group">
                <label>Name <span class="required">*<span></label>
                <input type="text" name="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label>Email <span class="required">*<span></label>
                <input type="email" name="email" placeholder="Enter your email address" required>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Include phone number">
            </div>

            <h3 class="sub-heading">Job Details</h3>

            <div class="form-group">
                <label>Title <span class="required">*<span></label>
                <input type="text" name="job_title" placeholder="e.g. Senior Data Engineer" required>
            </div>

            <div class="form-group">
                <label>Description <span class="required">*<span></label>
                <textarea name="description" placeholder="Describe the role, responsibilities, and requirements"
                    required></textarea>
            </div>

            <?php
                    $fields = [
                        'specialization' => ['label' => 'Specialization', 'options' => $skills],
                        'experience'     => ['label' => 'Years of Experience', 'options' => $experience],
                        'location'       => ['label' => 'Location Preference', 'options' => $location_pref],
                        'availability'   => ['label' => 'Availability', 'options' => $availability],
                        'industry'       => ['label' => 'Industry Expertise', 'options' => $industry],
                        'tools'          => ['label' => 'Languages & Tools', 'options' => $lang_tools, 'multiple' => true],
                    ];

                    foreach ($fields as $name => $data) {
                        $label = $data['label'] . ' <span class="required">*</span>';

                        if ($name === 'tools') {
                            echo "<div class='checkbox-group'>";
                            echo "<label>{$label}</label>";
                            foreach ($data['options'] as $term) {
                                $value = esc_attr($term->name);
                                $term_label = esc_html($term->name);
                                echo "<div class='checkbox-item'>";
                                echo "<label><input type='checkbox' name='tools[]' value='{$value}'> {$term_label}</label>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } else {
                            $name_attr = !empty($data['multiple']) ? "{$name}[]" : $name;
                            $multiple_attr = !empty($data['multiple']) ? 'multiple' : '';
                            echo "<div class='select-group'>";
                            echo "<label>{$label}</label>";
                            echo "<select name='{$name_attr}' {$multiple_attr} required>";
                            echo "<option value='' disabled selected>Select {$data['label']}</option>";
                            foreach ($data['options'] as $term) {
                                echo "<option value='" . esc_attr($term->name) . "'>" . esc_html($term->name) . "</option>";
                            }
                            echo "</select>";
                            echo "</div>";
                        }
                    }

                ?>

            <div class="loader" style="display:none;">Submitting...</div>
            <div class="confirmation" style="display:none; color:green;">Submitted successfully!</div>
            <button type="submit">Request Job Post</button>
        </form>
    </div>
</div>
<?php
    return ob_get_clean();
}
add_shortcode('job_request_modal', 'render_job_request_modal');

// Auto-inject modal into footer
add_action('wp_footer', function () {
    echo do_shortcode('[job_request_modal]');
});

// ===== 2. Enqueue JS & Localize ===== //
add_action('wp_enqueue_scripts', function () {
    $version = wp_get_theme()->get('Version');
    wp_enqueue_script('customtheme-job-request', get_template_directory_uri() . '/assets/js/job-request.js', ['jquery'], $version, true);
    wp_localize_script('customtheme-job-request', 'jobRequestAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('job_request_nonce'),
    ]);
});

// ===== 3. AJAX Handler for Email ===== //
add_action('wp_ajax_submit_job_request', 'handle_job_request_email');
add_action('wp_ajax_nopriv_submit_job_request', 'handle_job_request_email');

function handle_job_request_email() {
    check_ajax_referer('job_request_nonce', 'nonce');

    $form = [];
    foreach ($_POST['form_data'] as $item) {
        $name = $item['name'];
        $value = $item['value'];

        // Handle multi-value fields like tools[]
        if (str_ends_with($name, '[]')) {
            $base_name = rtrim($name, '[]');
            $form[$base_name][] = sanitize_text_field($value);
        } else {
            $form[$name] = sanitize_text_field($value);
        }
    }

    $admin_email = get_option('admin_email');

    // Sanitize and fallback
    $name = $form['name'] ?? 'N/A';
    $email = $form['email'] ?? 'N/A';
    $phone = $form['phone'] ?? 'N/A';
    $job_title = $form['job_title'] ?? 'N/A';
    $description = nl2br($form['description'] ?? 'N/A');
    $specialization = $form['specialization'] ?? 'N/A';
    $experience = $form['experience'] ?? 'N/A';
    $location = $form['location'] ?? 'N/A';
    $availability = $form['availability'] ?? 'N/A';
    $industry = $form['industry'] ?? 'N/A';
    $tools = isset($form['tools']) ? implode(', ', array_map('sanitize_text_field', $form['tools'])) : 'N/A';

    $subject = "New Job Post Request from {$name}";

    $message = <<<EOT
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; color: #333; }
    h2 { color: #2c3e50; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; vertical-align: top; }
    th { background-color: #f4f4f4; width: 200px; }
    p { margin: 0 0 10px; }
  </style>
</head>
<body>
  <h2>New Job Post Request</h2>
  <p>Hello <strong>Admin</strong>,</p>
  <p>A recruiter has submitted a new job posting. Please review the details below.</p>

  <h3>Recruiter Information:</h3>
  <table>
    <tr><th>Name</th><td>{$name}</td></tr>
    <tr><th>Email</th><td>{$email}</td></tr>
    <tr><th>Phone</th><td>{$phone}</td></tr>
  </table>

  <h3>Job Details:</h3>
  <table>
    <tr><th>Title</th><td>{$job_title}</td></tr>
    <tr><th>Description</th><td>{$description}</td></tr>
    <tr><th>Specialization</th><td>{$specialization}</td></tr>
    <tr><th>Experience</th><td>{$experience}</td></tr>
    <tr><th>Location Preference</th><td>{$location}</td></tr>
    <tr><th>Availability</th><td>{$availability}</td></tr>
    <tr><th>Industry</th><td>{$industry}</td></tr>
    <tr><th>Languages & Tools</th><td>{$tools}</td></tr>
  </table>

</body>
</html>
EOT;

    $headers = ['Content-Type: text/html; charset=UTF-8'];

    $success = wp_mail($admin_email, $subject, $message, $headers);

    if ($success) {
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}

// job post request trigger from mobile menu
function add_data_attribute_to_menu_link($atts, $item, $args, $depth) {
    if (in_array('job-request-trigger', $item->classes)) {
        $atts['data-request-job'] = '1';
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_data_attribute_to_menu_link', 10, 4);
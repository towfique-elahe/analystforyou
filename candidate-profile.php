<?php
/**
 * Template Name: Candidate Profile
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

global $wpdb;

$user_id = get_current_user_id();

// Fetch candidate data from custom table
$candidate = $wpdb->get_row(
    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}candidates WHERE id = %d", $user_id),
    ARRAY_A
);

$selected_languages = !empty($candidate['languages']) ? array_map('trim', explode(',', $candidate['languages'])) : [];
$selected_education = $candidate['education'] ?? '';
$selected_specialization = $candidate['specialization'] ?? '';
$selected_sub_role = $candidate['sub_role'] ?? '';
$selected_experience = $candidate['experience'] ?? '';
$selected_availability = $candidate['availability'] ?? '';
$selected_sector = $candidate['sector'] ?? '';
$checked_lang_tools = !empty($candidate['lang_tools']) ? array_map('trim', explode(',', $candidate['lang_tools'])) : [];

// Default fallback avatar
$default_avatar = 'user.png';
$image_url = get_template_directory_uri() . '/assets/media/' . $default_avatar;
$image_file_name = '';

if (!empty($candidate['image'])) {
    if (filter_var($candidate['image'], FILTER_VALIDATE_URL)) {
        $image_url = esc_url($candidate['image']);
    } else {
        $image_url = esc_url(site_url($candidate['image']));
    }
    $image_file_name = basename($candidate['image']);
} else {
    $image_file_name = $default_avatar;
}

// Load CV
$cv_url = '';
$cv_file_name = '';
if (!empty($candidate['cv'])) {
    $cv_raw = $candidate['cv'];
    $cv_url = filter_var($cv_raw, FILTER_VALIDATE_URL) ? $cv_raw : site_url($cv_raw);
    $cv_file_name = basename($cv_raw);
}

if (isset($_GET['updated']) && $_GET['updated'] === '1') {
    $form_message = 'Profile updated successfully.';
    $form_message_type = 'success';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_update_nonce']) && wp_verify_nonce($_POST['profile_update_nonce'], 'candidate_profile_update')) {
    $errors = [];
    $user_id = get_current_user_id();

    // 1. Sanitize fields
    $status = isset($_POST['status']) && $_POST['status'] === 'Public' ? 'Public' : 'Private';
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $date_of_birth = sanitize_text_field($_POST['date_of_birth']);
    $country = sanitize_text_field($_POST['country']);
    $address = sanitize_text_field($_POST['address']);
    $city = sanitize_text_field($_POST['city']);
    $postal_code = sanitize_text_field($_POST['postal_code']);
    $phone = sanitize_text_field($_POST['phone']);
    $languages = !empty($_POST['languages']) ? implode(', ', array_map('sanitize_text_field', $_POST['languages'])) : '';
    $education = sanitize_text_field($_POST['education']);
    $bio = sanitize_textarea_field($_POST['bio']);
    $specialization = sanitize_text_field($_POST['specialization']);
    $sub_role = sanitize_text_field($_POST['sub_role']);
    $experience = sanitize_text_field($_POST['experience']);
    $availability = sanitize_text_field($_POST['availability']);
    $sector = sanitize_text_field($_POST['sector']);
    $lang_tools = !empty($_POST['lang_tools']) ? implode(', ', array_map('sanitize_text_field', $_POST['lang_tools'])) : '';

    // 2. Update user meta
    wp_update_user([
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
    ]);

    // 3. Handle avatar selection
    $selected_avatar = sanitize_text_field($_POST['selected_avatar'] ?? '');
    $valid_avatars = [
        'user-man-1.png',
        'user-man-2.png',
        'user-man-3.png',
        'user-woman-1.png',
        'user-woman-2.png',
        'user-woman-3.png',
    ];
    $image_url = in_array($selected_avatar, $valid_avatars)
        ? get_template_directory_uri() . '/assets/media/' . $selected_avatar
        : get_template_directory_uri() . '/assets/media/' . $default_avatar;

    // 4. Handle CV upload
    // $cv_id = 0;
    // if (!empty($_FILES['cv']['name'])) {
    //     require_once ABSPATH . 'wp-admin/includes/file.php';
    //     require_once ABSPATH . 'wp-admin/includes/media.php';
    //     require_once ABSPATH . 'wp-admin/includes/image.php';

    //     $uploaded_cv = media_handle_upload('cv', 0);

    //     if (!is_wp_error($uploaded_cv)) {
    //         if (!empty($candidate['cv'])) {
    //             $old_cv_id = attachment_url_to_postid($candidate['cv']);
    //             if ($old_cv_id) {
    //                 wp_delete_attachment($old_cv_id, true);
    //             }
    //         }

    //         $cv_id = $uploaded_cv;
    //     } else {
    //         $errors[] = 'CV upload failed.';
    //     }
    // }

    // 5. Update DB
    if (empty($errors)) {
        $result = $wpdb->update(
            "{$wpdb->prefix}candidates",
            [
                'status'          => $status,
                'first_name'      => $first_name,
                'last_name'       => $last_name,
                'date_of_birth'   => $date_of_birth,
                'country'         => $country,
                'address'         => $address,
                'city'            => $city,
                'postal_code'     => $postal_code,
                'phone'           => $phone,
                'languages'       => $languages,
                'education'       => $education,
                'bio'             => $bio,
                'specialization'  => $specialization,
                'sub_role'        => $sub_role,
                'experience'      => $experience,
                'availability'    => $availability,
                'sector'          => $sector,
                'lang_tools'      => $lang_tools,
                'image'           => $image_url,
                'cv'              => !empty($cv_id) ? wp_get_attachment_url($cv_id) : $candidate['cv'],
            ],
            ['id' => $user_id],
            null,
            ['%d']
        );

        if ($result !== false) {
            wp_redirect(add_query_arg('updated', '1', get_permalink()));
            exit;
        } else {
            $form_message = 'No changes made.';
            $form_message_type = 'success';
        }
    } else {
        $form_message = implode('<br>', $errors);
        $form_message_type = 'error';
    }
}

?>

<div id="candidateProfile" class="portal">
    <div class="container">
        <?php get_template_part('template-parts/candidate/sidebar'); ?>
        <div class="main">
            <?php get_template_part('template-parts/candidate/topbar'); ?>
            <div class="content">
                <form action="" method="post" enctype="multipart/form-data" class="row profile-management-form">
                    <input type="hidden" name="profile_update_nonce"
                        value="<?php echo wp_create_nonce('candidate_profile_update'); ?>">
                    <?php if (!empty($form_message)): ?>
                    <div class="form-message <?php echo esc_attr($form_message_type); ?>" style="display: block;">
                        <?php echo esc_html($form_message); ?>
                    </div>
                    <?php endif; ?>
                    <div class="form-message" style="display:none;"></div>
                    <div class="col personal-info">
                        <h3 class="form-heading">Personal Information</h3>

                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <label>
                                    <input type="checkbox" name="status" value="Public" <?php
                                        checked($candidate['status'], 'Public' ); ?>>
                                    If checked, your account will be visible to recruiters.
                                </label>
                            </div>
                        </div>

                        <div class="row avatar-container">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Image</label>
                                    <div class="update-avatar">
                                        <img src="<?php echo $image_url; ?>" alt="Profile Image" class="image">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Select an avatar:</label>
                                    <div class="avatar-selection">
                                        <?php
                                        $avatars = [
                                            'user-man-1.png',
                                            'user-man-2.png',
                                            'user-man-3.png',
                                            'user-woman-1.png',
                                            'user-woman-2.png',
                                            'user-woman-3.png',
                                        ];

                                        foreach ($avatars as $avatar) {
                                            $avatar_url = get_template_directory_uri() . '/assets/media/' . $avatar;
                                            $checked = (basename($candidate['image']) === $avatar) ? 'checked' : '';
                                            echo '
                                            <label class="avatar-option">
                                                <input type="radio" name="selected_avatar" value="' . esc_attr($avatar) . '" ' . $checked . '>
                                                <img src="' . esc_url($avatar_url) . '" alt="' . esc_attr($avatar) . '" class="avatar-thumb">
                                            </label>';
                                        }
                                    ?>
                                    </div>
                                    <small class="file-hint">Choose one avatar as your profile image.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="firstName">First Name <span class="required">*</span></label>
                                    <input type="text" name="first_name" id="firstName"
                                        value="<?php echo esc_attr($current_user->first_name); ?>"
                                        placeholder="Enter your first name" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="lastName">Last Name <span class="required">*</span></label>
                                    <input type="text" name="last_name" id="lastName"
                                        value="<?php echo esc_attr($current_user->last_name); ?>"
                                        placeholder="Enter your last name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="username">Username <span class="required">*</span></label>
                                    <input type="text" name="username" id="username"
                                        value="<?php echo esc_attr($current_user->user_login); ?>" readonly required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="email">Email <span class="required">*</span></label>
                                    <input type="email" name="email" id="email"
                                        value="<?php echo esc_attr($current_user->user_email); ?>" readonly required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="dateOfBirth">Date of Birth <span class="required">*</span></label>
                                    <input type="date" name="date_of_birth" id="dateOfBirth"
                                        value="<?php echo esc_attr($candidate['date_of_birth'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="country">Country <span class="required">*</span></label>
                                    <input type="text" name="country" id="country" placeholder="Enter your country"
                                        required value="<?php echo esc_attr($candidate['country'] ?? ''); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address"
                                        value="<?php echo esc_attr($candidate['address'] ?? ''); ?>"
                                        placeholder="Enter your address">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" name="city" id="city"
                                        value="<?php echo esc_attr($candidate['city'] ?? ''); ?>"
                                        placeholder="Enter your city">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="postalCode">Postal Code</label>
                                    <input type="text" name="postal_code" id="postalCode"
                                        value="<?php echo esc_attr($candidate['postal_code'] ?? ''); ?>"
                                        placeholder="Enter postal code">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="phone">Phone <span class="required">*</span></label>
                                    <input type="text" name="phone" id="phone"
                                        value="<?php echo esc_attr($candidate['phone'] ?? ''); ?>"
                                        placeholder="Enter phone number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="languages">Languages <span class="required">*</span></label>
                                    <select name="languages[]" id="languages" multiple required>
                                        <option value="" disabled <?php selected(in_array($language,
                                            $selected_languages), true, false); ?>>
                                            Select
                                            languages</option>
                                        <?php
                                            $all_languages = [
                                                'English', 'Dutch', 'Spanish', 'French', 'German', 'Italian', 'Portuguese', 'Russian', 'Chinese', 'Japanese',
                                                'Arabic', 'Hindi', 'Swedish', 'Turkish', 'Korean', 'Finnish', 'Polish', 'Czech', 'Danish', 'Norwegian', 'Greek',
                                                'Hungarian', 'Romanian', 'Bulgarian', 'Ukrainian', 'Hebrew', 'Malay', 'Thai', 'Vietnamese', 'Indonesian', 'Filipino',
                                                'Swahili', 'Persian', 'Tamil', 'Bengali', 'Gujarati', 'Punjabi', 'Marathi', 'Urdu', 'Malayalam', 'Telugu', 'Kannada',
                                                'Nepali', 'Sinhala', 'Pashto', 'Khmer', 'Lao', 'Georgian', 'Albanian', 'Serbian', 'Croatian', 'Bosnian', 'Slovak',
                                                'Estonian', 'Latvian', 'Lithuanian', 'Icelandic', 'Maltese', 'Armenian', 'Azerbaijani', 'Kazakh', 'Uzbek', 'Tajik',
                                                'Kyrgyz', 'Turkmen', 'Mongolian', 'Burmese', 'Tigrinya', 'Somali', 'Haitian Creole', 'Catalan', 'Basque', 'Galician',
                                                'Scottish Gaelic', 'Irish', 'Welsh', 'Breton', 'Corsican', 'Sicilian', 'Esperanto', 'Latin', 'Yiddish'
                                            ];
                                            foreach ($all_languages as $language) {
                                                echo '<option value="' . esc_attr($language) . '" ' . selected(in_array($language, $selected_languages), true, false) . '>' . esc_html($language) . '</option>';
                                            }
                                        ?>
                                    </select>
                                    <label for="" style="color: var(--text-color)">(Hold CTRL for multiple
                                        selection)</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="education">Education <span class="required">*</span></label>
                                    <select name="education" id="education" required>
                                        <option value="" disabled <?php selected($selected_education, '' ); ?>>Select
                                            education level</option>
                                        <option value="High School Diploma" <?php
                                            selected($selected_education, 'High School Diploma' ); ?>>High School
                                            Diploma</option>
                                        <option value="Associate Degree" <?php
                                            selected($selected_education, 'Associate Degree' ); ?>>Associate Degree
                                        </option>
                                        <option value="Bachelor’s Degree" <?php
                                            selected($selected_education, 'Bachelor’s Degree' ); ?>>Bachelor’s
                                            Degree</option>
                                        <option value="Bachelor of Science" <?php
                                            selected($selected_education, 'Bachelor of Science' ); ?>>Bachelor of
                                            Science</option>
                                        <option value="Bachelor of Arts" <?php
                                            selected($selected_education, 'Bachelor of Arts' ); ?>>Bachelor of Arts
                                        </option>
                                        <option value="Bachelor of Engineering" <?php
                                            selected($selected_education, 'Bachelor of Engineering' ); ?>>Bachelor
                                            of Engineering</option>
                                        <option value="Master of Science" <?php
                                            selected($selected_education, 'Master of Science' ); ?>>Master of
                                            Science</option>
                                        <option value="Master of Arts" <?php
                                            selected($selected_education, 'Master of Arts' ); ?>>Master of Arts
                                        </option>
                                        <option value="Master of Business Administration" <?php
                                            selected($selected_education, 'Master of Business Administration' ); ?>>
                                            Master of Business Administration</option>
                                        <option value="Master of Engineering" <?php
                                            selected($selected_education, 'Master of Engineering' ); ?>>Master of
                                            Engineering</option>
                                        <option value="Master of Philosophy" <?php
                                            selected($selected_education, 'Master of Philosophy' ); ?>>Master of
                                            Philosophy</option>
                                        <option value="Doctorate / Ph.D." <?php
                                            selected($selected_education, 'Doctorate / Ph.D.' ); ?>>Doctorate /
                                            Ph.D.</option>
                                        <option value="Other" <?php selected($selected_education, 'Other' ); ?>>Other
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="bio">Bio <span class="required">*</span></label>
                            <textarea name="bio" id="bio"
                                required><?php echo esc_textarea($candidate['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="col technical-info">
                        <h3 class="form-heading">Technical Information</h3>

                        <!-- <div class="form-group">
                            <label for="">CV</label>
                            <?php if ($cv_file_name && $cv_url): ?>
                            <p class="current-cv">
                                <?php echo esc_html($cv_file_name); ?> |
                                <a href="<?php echo esc_url($cv_url); ?>" target="_blank">View</a>
                            </p>
                            <?php endif; ?>
                            <input type="file" name="cv" id="cv" accept="application/pdf">
                            <label for="cv" class="update-cv">Upload CV</label>
                            <p class="cv-file-name" style="display: none;"></p>
                            <small class="file-hint">Max 2MB. PDF only.</small>
                        </div> -->

                        <div class="form-group">
                            <label for="specialization">Specialization <span class="required">*</span></label>
                            <select name="specialization" id="specialization" required>
                                <option value="" disabled <?php selected($selected_specialization, '' ); ?>>Select
                                    specialization</option>
                                <option value="Data Analyst" <?php selected($selected_specialization, 'Data Analyst' );
                                    ?>>Data Analyst</option>
                                <option value="Business Analyst" <?php
                                    selected($selected_specialization, 'Business Analyst' ); ?>>Business Analyst
                                </option>
                                <option value="BI Specialist" <?php selected($selected_specialization, 'BI Specialist'
                                    ); ?>>BI Specialist</option>
                                <option value="Data Scientist" <?php selected($selected_specialization, 'Data Scientist'
                                    ); ?>>Data Scientist
                                </option>
                                <option value="Data Engineer" <?php selected($selected_specialization, 'Data Engineer'
                                    ); ?>>Data Engineer</option>
                                <option value="Information Analyst" <?php
                                    selected($selected_specialization, 'Information Analyst' ); ?>>Information
                                    Analyst</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="subRole">Sub Role <span class="required">*</span></label>
                            <select name="sub_role" id="subRole" required>
                                <option value="" disabled selected>Select sub role</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="experience">Experience <span class="required">*</span></label>
                            <select name="experience" id="experience" required>
                                <option value="" disabled <?php selected($selected_experience, '' ); ?>>Select
                                    experience
                                    level</option>
                                <option value="Junior (0–2 years)" <?php
                                    selected($selected_experience, 'Junior (0–2 years)' ); ?>>Junior (0–2 years)
                                </option>
                                <option value="Mid-level (3–5 years)" <?php
                                    selected($selected_experience, 'Mid-level (3–5 years)' ); ?>>Mid-level (3–5
                                    years)</option>
                                <option value="Senior (6+ years)" <?php
                                    selected($selected_experience, 'Senior (6+ years)' ); ?>>Senior (6+ years)
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="availability">Availability <span class="required">*</span></label>
                            <select name="availability" id="availability" required>
                                <option value="" disabled <?php selected($selected_availability, '' ); ?>>Select
                                    availability</option>
                                <option value="Full-time" <?php selected($selected_availability, 'Full-time' ); ?>>
                                    Full-time</option>
                                <option value="Part-time" <?php selected($selected_availability, 'Part-time' ); ?>>
                                    Part-time</option>
                                <option value="Freelancer" <?php selected($selected_availability, 'Freelancer' ); ?>>
                                    Freelancer</option>
                                <option value="Traineeship" <?php selected($selected_availability, 'Traineeship' ); ?>>
                                    Traineeship</option>
                                <option value="Internship" <?php selected($selected_availability, 'Internship' ); ?>>
                                    Internship</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sector">Sector <span class="required">*</span></label>
                            <select name="sector" id="sector" required>
                                <option value="" disabled <?php selected($selected_sector, '' ); ?>>Select sector
                                </option>
                                <option value="Finance" <?php selected($selected_sector, 'Finance' ); ?>>Finance
                                </option>
                                <option value="Healthcare" <?php selected($selected_sector, 'Healthcare' ); ?>>
                                    Healthcare
                                </option>
                                <option value="Government" <?php selected($selected_sector, 'Government' ); ?>>
                                    Government
                                </option>
                                <option value="Retail & E-commerce" <?php
                                    selected($selected_sector, 'Retail & E-commerce' ); ?>>Retail & E-commerce
                                </option>
                                <option value="Logistics" <?php selected($selected_sector, 'Logistics' ); ?>>Logistics
                                </option>
                                <option value="Technology & IT" <?php selected($selected_sector, 'Technology & IT' ); ?>
                                    >
                                    Technology & IT</option>
                                <option value="Energy" <?php selected($selected_sector, 'Energy' ); ?>>Energy</option>
                                <option value="Education" <?php selected($selected_sector, 'Education' ); ?>>Education
                                </option>
                                <option value="Consulting" <?php selected($selected_sector, 'Consulting' ); ?>>
                                    Consulting
                                </option>
                                <option value="Other / Not Relevant" <?php
                                    selected($selected_sector, 'Other / Not Relevant' ); ?>>Other / Not Relevant
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Languages & Tools <span class="required">*</span></label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="SQL" <?php
                                            checked(in_array('SQL', $checked_lang_tools)); ?>> SQL</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Python" <?php
                                            checked(in_array('Python', $checked_lang_tools)); ?>> Python</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="R" <?php
                                            checked(in_array('R', $checked_lang_tools)); ?>> R</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Power BI" <?php
                                            checked(in_array('Power BI', $checked_lang_tools)); ?>> Power
                                        BI</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Tableau" <?php
                                            checked(in_array('Tableau', $checked_lang_tools)); ?>> Tableau</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Excel" <?php
                                            checked(in_array('Excel', $checked_lang_tools)); ?>> Excel</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Google Data Studio" <?php
                                            checked(in_array('Google Data Studio', $checked_lang_tools)); ?>>
                                        Google Data
                                        Studio</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="scikit-learn" <?php
                                            checked(in_array('scikit-learn', $checked_lang_tools)); ?>>
                                        scikit-learn</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="TensorFlow" <?php
                                            checked(in_array('TensorFlow', $checked_lang_tools)); ?>>
                                        TensorFlow</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Apache Airflow" <?php
                                            checked(in_array('Apache Airflow', $checked_lang_tools)); ?>> Apache
                                        Airflow</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Snowflake" <?php
                                            checked(in_array('Snowflake', $checked_lang_tools)); ?>>
                                        Snowflake</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Azure / AWS / GCP" <?php
                                            checked(in_array('Azure / AWS / GCP', $checked_lang_tools)); ?>> Azure
                                        / AWS /
                                        GCP</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Git" <?php
                                            checked(in_array('Git', $checked_lang_tools)); ?>> Git</label>
                                </div>
                            </div>
                        </div>

                        <div class="button-group">
                            <button class="button update" type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-hide PHP-rendered success message and clear ?updated=1 from URL
        const autoHideMessage = document.querySelector('.form-message.success');
        if (autoHideMessage && autoHideMessage.textContent.trim() !== '') {
            setTimeout(() => {
                autoHideMessage.style.display = 'none';
                autoHideMessage.textContent = '';

                const url = new URL(window.location.href);
                url.searchParams.delete('updated');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }, 5000);
        }

        const messageBox = document.querySelector('.form-message');

        function showMessage(msg, type = 'success') {
            messageBox.textContent = msg;
            messageBox.className = `form-message ${type}`;
            messageBox.style.display = 'block';
            messageBox.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            setTimeout(() => {
                messageBox.textContent = '';
                messageBox.style.display = 'none';
            }, 5000);
        }

        // Update profile preview when an avatar is selected
        document.querySelectorAll('input[name="selected_avatar"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const previewImg = document.querySelector('.update-avatar img');
                if (previewImg) {
                    previewImg.src = this.nextElementSibling.src;
                }
            });
        });

        // CV input handler — check if element exists first
        // const cvInput = document.getElementById('cv');
        // const cvFileNameDisplay = document.querySelector('.cv-file-name');

        // if (cvInput && cvFileNameDisplay) {
        //     cvInput.addEventListener('change', function() {
        //         const file = cvInput.files[0];
        //         if (!file) return;

        //         const allowedType = 'application/pdf';
        //         const maxSize = 2 * 1024 * 1024; // 2MB

        //         if (file.type !== allowedType) {
        //             showMessage('Only PDF files are allowed for CV.', 'error');
        //             cvInput.value = '';
        //             cvFileNameDisplay.textContent = '';
        //             cvFileNameDisplay.style.display = 'none';
        //             return;
        //         }

        //         if (file.size > maxSize) {
        //             showMessage('CV must be smaller than 2MB.', 'error');
        //             cvInput.value = '';
        //             cvFileNameDisplay.textContent = '';
        //             cvFileNameDisplay.style.display = 'none';
        //             return;
        //         }

        //         cvFileNameDisplay.textContent = file.name;
        //         cvFileNameDisplay.style.display = 'block';

        //         showMessage('CV selected successfully.', 'success');
        //     });
        // }

        // Sub-role population
        const subrolesMap = {
            "Data Analyst": [
                "Reporting Analyst", "Marketing Data Analyst", "Customer Insights Analyst",
                "Financial Data Analyst", "Operations Analyst", "Supply Chain Analyst",
                "Statistical Analyst"
            ],
            "Business Analyst": [
                "Process Analyst", "Change Analyst", "Functional Business Analyst",
                "Business Process Analyst"
            ],
            "BI Specialist": [
                "BI Analyst", "BI Developer", "Power BI Specialist", "Tableau Specialist",
                "Dashboard Specialist", "Data Visualization Specialist"
            ],
            "Data Scientist": [
                "Predictive Analytics Specialist", "Machine Learning Engineer", "AI Engineer",
                "NLP Specialist", "Quantitative Analyst"
            ],
            "Data Engineer": [
                "ETL Developer", "Analytics Engineer", "Data Platform Engineer",
                "Data Integration Specialist", "Data Architect", "Data Quality Analyst", "Data Steward"
            ],
            "Information Analyst": [
                "Functional Analyst", "Technical Analyst", "Systems Analyst",
                "Application Analyst", "Requirements Analyst"
            ]
        };

        const specializationSelect = document.getElementById('specialization');
        const subRoleSelect = document.getElementById('subRole');

        const selectedSpecialization = "<?php echo esc_js($selected_specialization); ?>";
        const selectedSubRole = "<?php echo esc_js($selected_sub_role); ?>";

        function populateSubRoles(specialization, selected) {
            const roles = subrolesMap[specialization] || [];
            subRoleSelect.innerHTML = '<option value="" disabled>Select sub role</option>';

            roles.forEach(function (role) {
                const opt = document.createElement('option');
                opt.value = role;
                opt.textContent = role;
                if (role === selected) {
                    opt.selected = true;
                }
                subRoleSelect.appendChild(opt);
            });
        }

        if (selectedSpecialization) {
            populateSubRoles(selectedSpecialization, selectedSubRole);
        }

        if (specializationSelect) {
            specializationSelect.addEventListener('change', function () {
                populateSubRoles(this.value, '');
            });
        }
    });
</script>

<?php get_footer(); ?>
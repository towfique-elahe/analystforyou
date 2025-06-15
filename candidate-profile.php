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

$selected_language = $candidate['languages'] ?? '';
$selected_education = $candidate['education'] ?? '';
$selected_specialization = $candidate['specialization'] ?? '';
$selected_sub_role = $candidate['sub_role'] ?? '';
$selected_experience = $candidate['experience'] ?? '';
$selected_availability = $candidate['availability'] ?? '';
$selected_sector = $candidate['sector'] ?? '';
$checked_lang_tools = !empty($candidate['lang_tools']) ? array_map('trim', explode(',', $candidate['lang_tools'])) : [];

// Default fallback image
$image_url = get_template_directory_uri() . '/assets/media/user.png';
$image_file_name = '';

if (!empty($candidate['image'])) {
    // Use as full URL or relative path based on what's stored
    if (filter_var($candidate['image'], FILTER_VALIDATE_URL)) {
        $image_url = esc_url($candidate['image']);
    } else {
        $image_url = esc_url(site_url($candidate['image']));
    }
    $image_file_name = basename($candidate['image']);
}

// Load CV
$cv_url = '';
$cv_file_name = '';
if (!empty($candidate['cv_id'])) {
    $cv_src = wp_get_attachment_url($candidate['cv_id']);
    if ($cv_src) {
        $cv_url = esc_url($cv_src);
        $cv_file_name = basename(get_attached_file($candidate['cv_id']));
    }
}

?>

<div id="candidateProfile" class="portal">
    <div class="container">
        <?php get_template_part('template-parts/candidate/sidebar'); ?>
        <div class="main">
            <?php get_template_part('template-parts/candidate/topbar'); ?>
            <div class="content">
                <form action="" class="row profile-management-form">
                    <div class="col personal-info">
                        <h3 class="form-heading">Personal Information</h3>

                        <div class="form-group">
                            <label for="">Image</label>
                            <input type="file" name="image" id="image">
                            <div class="update-image">
                                <img src="<?php echo $image_url; ?>" alt="Profile Image" class="image">
                                <div class="buttons">
                                    <label for="image" class="button edit">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </label>
                                    <a href="javascript:void()" class="button delete">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </a>
                                </div>
                            </div>
                            <p class="image-file-name"><?php echo esc_html($image_file_name); ?></p>
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
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone"
                                        value="<?php echo esc_attr($candidate['phone'] ?? ''); ?>"
                                        placeholder="Enter phone number">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="languages">Languages <span class="required">*</span></label>
                                    <select name="languages" id="languages" required>
                                        <option value="" disabled <?php selected($selected_language, ''); ?>>Select a
                                            language</option>
                                        <option value="English" <?php selected($selected_language, 'English'); ?>>
                                            English</option>
                                        <option value="Dutch" <?php selected($selected_language, 'Dutch'); ?>>Dutch
                                        </option>
                                        <option value="Spanish" <?php selected($selected_language, 'Spanish'); ?>>
                                            Spanish</option>
                                        <option value="French" <?php selected($selected_language, 'French'); ?>>French
                                        </option>
                                        <option value="German" <?php selected($selected_language, 'German'); ?>>German
                                        </option>
                                        <option value="Italian" <?php selected($selected_language, 'Italian'); ?>>
                                            Italian</option>
                                        <option value="Portuguese" <?php selected($selected_language, 'Portuguese'); ?>>
                                            Portuguese</option>
                                        <option value="Russian" <?php selected($selected_language, 'Russian'); ?>>
                                            Russian</option>
                                        <option value="Chinese" <?php selected($selected_language, 'Chinese'); ?>>
                                            Chinese</option>
                                        <option value="Japanese" <?php selected($selected_language, 'Japanese'); ?>>
                                            Japanese</option>
                                        <option value="Arabic" <?php selected($selected_language, 'Arabic'); ?>>Arabic
                                        </option>
                                        <option value="Hindi" <?php selected($selected_language, 'Hindi'); ?>>Hindi
                                        </option>
                                        <option value="Swedish" <?php selected($selected_language, 'Swedish'); ?>>
                                            Swedish</option>
                                        <option value="Turkish" <?php selected($selected_language, 'Turkish'); ?>>
                                            Turkish</option>
                                        <option value="Korean" <?php selected($selected_language, 'Korean'); ?>>Korean
                                        </option>
                                        <option value="Finnish" <?php selected($selected_language, 'Finnish'); ?>>
                                            Finnish</option>
                                        <option value="Polish" <?php selected($selected_language, 'Polish'); ?>>Polish
                                        </option>
                                        <option value="Czech" <?php selected($selected_language, 'Czech'); ?>>Czech
                                        </option>
                                        <option value="Danish" <?php selected($selected_language, 'Danish'); ?>>Danish
                                        </option>
                                        <option value="Norwegian" <?php selected($selected_language, 'Norwegian'); ?>>
                                            Norwegian</option>
                                        <option value="Greek" <?php selected($selected_language, 'Greek'); ?>>Greek
                                        </option>
                                        <option value="Hungarian" <?php selected($selected_language, 'Hungarian'); ?>>
                                            Hungarian</option>
                                        <option value="Romanian" <?php selected($selected_language, 'Romanian'); ?>>
                                            Romanian</option>
                                        <option value="Bulgarian" <?php selected($selected_language, 'Bulgarian'); ?>>
                                            Bulgarian</option>
                                        <option value="Ukrainian" <?php selected($selected_language, 'Ukrainian'); ?>>
                                            Ukrainian</option>
                                        <option value="Hebrew" <?php selected($selected_language, 'Hebrew'); ?>>Hebrew
                                        </option>
                                        <option value="Malay" <?php selected($selected_language, 'Malay'); ?>>Malay
                                        </option>
                                        <option value="Thai" <?php selected($selected_language, 'Thai'); ?>>Thai
                                        </option>
                                        <option value="Vietnamese" <?php selected($selected_language, 'Vietnamese'); ?>>
                                            Vietnamese</option>
                                        <option value="Indonesian" <?php selected($selected_language, 'Indonesian'); ?>>
                                            Indonesian</option>
                                        <option value="Filipino" <?php selected($selected_language, 'Filipino'); ?>>
                                            Filipino</option>
                                        <option value="Swahili" <?php selected($selected_language, 'Swahili'); ?>>
                                            Swahili</option>
                                        <option value="Persian" <?php selected($selected_language, 'Persian'); ?>>
                                            Persian</option>
                                        <option value="Tamil" <?php selected($selected_language, 'Tamil'); ?>>Tamil
                                        </option>
                                        <option value="Bengali" <?php selected($selected_language, 'Bengali'); ?>>
                                            Bengali</option>
                                        <option value="Gujarati" <?php selected($selected_language, 'Gujarati'); ?>>
                                            Gujarati</option>
                                        <option value="Punjabi" <?php selected($selected_language, 'Punjabi'); ?>>
                                            Punjabi</option>
                                        <option value="Marathi" <?php selected($selected_language, 'Marathi'); ?>>
                                            Marathi</option>
                                        <option value="Urdu" <?php selected($selected_language, 'Urdu'); ?>>Urdu
                                        </option>
                                        <option value="Malayalam" <?php selected($selected_language, 'Malayalam'); ?>>
                                            Malayalam</option>
                                        <option value="Telugu" <?php selected($selected_language, 'Telugu'); ?>>Telugu
                                        </option>
                                        <option value="Kannada" <?php selected($selected_language, 'Kannada'); ?>>
                                            Kannada</option>
                                        <option value="Nepali" <?php selected($selected_language, 'Nepali'); ?>>Nepali
                                        </option>
                                        <option value="Sinhala" <?php selected($selected_language, 'Sinhala'); ?>>
                                            Sinhala</option>
                                        <option value="Pashto" <?php selected($selected_language, 'Pashto'); ?>>Pashto
                                        </option>
                                        <option value="Khmer" <?php selected($selected_language, 'Khmer'); ?>>Khmer
                                        </option>
                                        <option value="Lao" <?php selected($selected_language, 'Lao'); ?>>Lao</option>
                                        <option value="Georgian" <?php selected($selected_language, 'Georgian'); ?>>
                                            Georgian</option>
                                        <option value="Albanian" <?php selected($selected_language, 'Albanian'); ?>>
                                            Albanian</option>
                                        <option value="Serbian" <?php selected($selected_language, 'Serbian'); ?>>
                                            Serbian</option>
                                        <option value="Croatian" <?php selected($selected_language, 'Croatian'); ?>>
                                            Croatian</option>
                                        <option value="Bosnian" <?php selected($selected_language, 'Bosnian'); ?>>
                                            Bosnian</option>
                                        <option value="Slovak" <?php selected($selected_language, 'Slovak'); ?>>Slovak
                                        </option>
                                        <option value="Estonian" <?php selected($selected_language, 'Estonian'); ?>>
                                            Estonian</option>
                                        <option value="Latvian" <?php selected($selected_language, 'Latvian'); ?>>
                                            Latvian</option>
                                        <option value="Lithuanian" <?php selected($selected_language, 'Lithuanian'); ?>>
                                            Lithuanian</option>
                                        <option value="Icelandic" <?php selected($selected_language, 'Icelandic'); ?>>
                                            Icelandic</option>
                                        <option value="Maltese" <?php selected($selected_language, 'Maltese'); ?>>
                                            Maltese</option>
                                        <option value="Armenian" <?php selected($selected_language, 'Armenian'); ?>>
                                            Armenian</option>
                                        <option value="Azerbaijani"
                                            <?php selected($selected_language, 'Azerbaijani'); ?>>Azerbaijani</option>
                                        <option value="Kazakh" <?php selected($selected_language, 'Kazakh'); ?>>Kazakh
                                        </option>
                                        <option value="Uzbek" <?php selected($selected_language, 'Uzbek'); ?>>Uzbek
                                        </option>
                                        <option value="Tajik" <?php selected($selected_language, 'Tajik'); ?>>Tajik
                                        </option>
                                        <option value="Kyrgyz" <?php selected($selected_language, 'Kyrgyz'); ?>>Kyrgyz
                                        </option>
                                        <option value="Turkmen" <?php selected($selected_language, 'Turkmen'); ?>>
                                            Turkmen</option>
                                        <option value="Mongolian" <?php selected($selected_language, 'Mongolian'); ?>>
                                            Mongolian</option>
                                        <option value="Burmese" <?php selected($selected_language, 'Burmese'); ?>>
                                            Burmese</option>
                                        <option value="Tigrinya" <?php selected($selected_language, 'Tigrinya'); ?>>
                                            Tigrinya</option>
                                        <option value="Somali" <?php selected($selected_language, 'Somali'); ?>>Somali
                                        </option>
                                        <option value="Haitian Creole"
                                            <?php selected($selected_language, 'Haitian Creole'); ?>>Haitian Creole
                                        </option>
                                        <option value="Catalan" <?php selected($selected_language, 'Catalan'); ?>>
                                            Catalan</option>
                                        <option value="Basque" <?php selected($selected_language, 'Basque'); ?>>Basque
                                        </option>
                                        <option value="Galician" <?php selected($selected_language, 'Galician'); ?>>
                                            Galician</option>
                                        <option value="Scottish Gaelic"
                                            <?php selected($selected_language, 'Scottish Gaelic'); ?>>Scottish Gaelic
                                        </option>
                                        <option value="Irish" <?php selected($selected_language, 'Irish'); ?>>Irish
                                        </option>
                                        <option value="Welsh" <?php selected($selected_language, 'Welsh'); ?>>Welsh
                                        </option>
                                        <option value="Breton" <?php selected($selected_language, 'Breton'); ?>>Breton
                                        </option>
                                        <option value="Corsican" <?php selected($selected_language, 'Corsican'); ?>>
                                            Corsican</option>
                                        <option value="Sicilian" <?php selected($selected_language, 'Sicilian'); ?>>
                                            Sicilian</option>
                                        <option value="Esperanto" <?php selected($selected_language, 'Esperanto'); ?>>
                                            Esperanto</option>
                                        <option value="Latin" <?php selected($selected_language, 'Latin'); ?>>Latin
                                        </option>
                                        <option value="Yiddish" <?php selected($selected_language, 'Yiddish'); ?>>
                                            Yiddish</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="education">Education <span class="required">*</span></label>
                                    <select name="education" id="education" required>
                                        <option value="" disabled <?php selected($selected_education, ''); ?>>Select
                                            education level</option>
                                        <option value="high_school"
                                            <?php selected($selected_education, 'high_school'); ?>>High School Diploma
                                        </option>
                                        <option value="associate" <?php selected($selected_education, 'associate'); ?>>
                                            Associate Degree</option>
                                        <option value="bachelor" <?php selected($selected_education, 'bachelor'); ?>>
                                            Bachelor’s Degree</option>
                                        <option value="bsc" <?php selected($selected_education, 'bsc'); ?>>Bachelor of
                                            Science</option>
                                        <option value="ba" <?php selected($selected_education, 'ba'); ?>>Bachelor of
                                            Arts</option>
                                        <option value="beng" <?php selected($selected_education, 'beng'); ?>>Bachelor of
                                            Engineering</option>
                                        <option value="msc" <?php selected($selected_education, 'msc'); ?>>Master of
                                            Science</option>
                                        <option value="ma" <?php selected($selected_education, 'ma'); ?>>Master of Arts
                                        </option>
                                        <option value="mba" <?php selected($selected_education, 'mba'); ?>>Master of
                                            Business Administration</option>
                                        <option value="meng" <?php selected($selected_education, 'meng'); ?>>Master of
                                            Engineering</option>
                                        <option value="mphil" <?php selected($selected_education, 'mphil'); ?>>Master of
                                            Philosophy</option>
                                        <option value="phd" <?php selected($selected_education, 'phd'); ?>>Doctorate /
                                            Ph.D.</option>
                                        <option value="other" <?php selected($selected_education, 'other'); ?>>Other
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

                        <div class="form-group">
                            <label for="">CV</label>
                            <p class="current-cv">
                                <?php if ($cv_file_name): ?>
                                <?php echo esc_html($cv_file_name); ?> |
                                <a href="<?php echo $cv_url; ?>" target="_blank">View</a>
                                <?php else: ?>
                                No file uploaded
                                <?php endif; ?>
                            </p>
                            <input type="file" name="cv" id="cv" accept="application/pdf">
                            <label for="cv" class="update-cv">Upload CV</label>
                            <p class="cv-file-name"><?php echo esc_html($cv_file_name); ?></p>
                        </div>

                        <div class="form-group">
                            <label for="specialization">Specialization <span class="required">*</span></label>
                            <select name="specialization" id="specialization" required>
                                <option value="" disabled <?php selected($selected_specialization, ''); ?>>Select
                                    specialization</option>
                                <option value="Data Analyst"
                                    <?php selected($selected_specialization, 'Data Analyst'); ?>>Data Analyst</option>
                                <option value="Business Analyst"
                                    <?php selected($selected_specialization, 'Business Analyst'); ?>>Business Analyst
                                </option>
                                <option value="BI Specialist"
                                    <?php selected($selected_specialization, 'BI Specialist'); ?>>BI Specialist</option>
                                <option value="Data Scientist"
                                    <?php selected($selected_specialization, 'Data Scientist'); ?>>Data Scientist
                                </option>
                                <option value="Data Engineer"
                                    <?php selected($selected_specialization, 'Data Engineer'); ?>>Data Engineer</option>
                                <option value="Information Analyst"
                                    <?php selected($selected_specialization, 'Information Analyst'); ?>>Information
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
                                <option value="" disabled <?php selected($selected_experience, ''); ?>>Select experience
                                    level</option>
                                <option value="junior" <?php selected($selected_experience, 'junior'); ?>>Junior (0–2
                                    years)</option>
                                <option value="mid" <?php selected($selected_experience, 'mid'); ?>>Mid-level (3–5
                                    years)</option>
                                <option value="senior" <?php selected($selected_experience, 'senior'); ?>>Senior (6+
                                    years)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="availability">Availability <span class="required">*</span></label>
                            <select name="availability" id="availability" required>
                                <option value="" disabled <?php selected($selected_availability, ''); ?>>Select
                                    availability</option>
                                <option value="full_time" <?php selected($selected_availability, 'full_time'); ?>>
                                    Full-time</option>
                                <option value="part_time" <?php selected($selected_availability, 'part_time'); ?>>
                                    Part-time</option>
                                <option value="freelancer" <?php selected($selected_availability, 'freelancer'); ?>>
                                    Freelancer</option>
                                <option value="traineeship" <?php selected($selected_availability, 'traineeship'); ?>>
                                    Traineeship</option>
                                <option value="internship" <?php selected($selected_availability, 'internship'); ?>>
                                    Internship</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="sector">Sector <span class="required">*</span></label>
                            <select name="sector" id="sector" required>
                                <option value="" disabled <?php selected($selected_sector, ''); ?>>Select sector
                                </option>
                                <option value="finance" <?php selected($selected_sector, 'finance'); ?>>Finance</option>
                                <option value="healthcare" <?php selected($selected_sector, 'healthcare'); ?>>Healthcare
                                </option>
                                <option value="government" <?php selected($selected_sector, 'government'); ?>>Government
                                </option>
                                <option value="retail_ecommerce"
                                    <?php selected($selected_sector, 'retail_ecommerce'); ?>>Retail & E-commerce
                                </option>
                                <option value="logistics" <?php selected($selected_sector, 'logistics'); ?>>Logistics
                                </option>
                                <option value="technology_it" <?php selected($selected_sector, 'technology_it'); ?>>
                                    Technology & IT</option>
                                <option value="energy" <?php selected($selected_sector, 'energy'); ?>>Energy</option>
                                <option value="education" <?php selected($selected_sector, 'education'); ?>>Education
                                </option>
                                <option value="consulting" <?php selected($selected_sector, 'consulting'); ?>>Consulting
                                </option>
                                <option value="other" <?php selected($selected_sector, 'other'); ?>>Other / Not Relevant
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Languages & Tools <span class="required">*</span></label>
                            <div class="checkbox-group">
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="SQL"
                                            <?php checked(in_array('SQL', $checked_lang_tools)); ?>> SQL</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Python"
                                            <?php checked(in_array('Python', $checked_lang_tools)); ?>> Python</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="R"
                                            <?php checked(in_array('R', $checked_lang_tools)); ?>> R</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Power BI"
                                            <?php checked(in_array('Power BI', $checked_lang_tools)); ?>> Power
                                        BI</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Tableau"
                                            <?php checked(in_array('Tableau', $checked_lang_tools)); ?>> Tableau</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Excel"
                                            <?php checked(in_array('Excel', $checked_lang_tools)); ?>> Excel</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Google Data Studio"
                                            <?php checked(in_array('Google Data Studio', $checked_lang_tools)); ?>>
                                        Google Data
                                        Studio</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="scikit-learn"
                                            <?php checked(in_array('scikit-learn', $checked_lang_tools)); ?>>
                                        scikit-learn</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="TensorFlow"
                                            <?php checked(in_array('TensorFlow', $checked_lang_tools)); ?>>
                                        TensorFlow</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Apache Airflow"
                                            <?php checked(in_array('Apache Airflow', $checked_lang_tools)); ?>> Apache
                                        Airflow</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Snowflake"
                                            <?php checked(in_array('Snowflake', $checked_lang_tools)); ?>>
                                        Snowflake</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Azure / AWS / GCP"
                                            <?php checked(in_array('Azure / AWS / GCP', $checked_lang_tools)); ?>> Azure
                                        / AWS /
                                        GCP</label>
                                </div>
                                <div class="checkbox-item">
                                    <label><input type="checkbox" name="lang_tools[]" value="Git"
                                            <?php checked(in_array('Git', $checked_lang_tools)); ?>> Git</label>
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
document.addEventListener('DOMContentLoaded', function() {
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
            "Data Integration Specialist",
            "Data Architect", "Data Quality Analyst", "Data Steward"
        ],
        "Information Analyst": [
            "Functional Analyst", "Technical Analyst", "Systems Analyst",
            "Application Analyst", "Requirements Analyst"
        ]
    };

    const specializationSelect = document.getElementById('specialization');
    const subRoleSelect = document.getElementById('subRole');

    // Inject saved values from PHP to JS
    const selectedSpecialization = "<?php echo esc_js($selected_specialization); ?>";
    const selectedSubRole = "<?php echo esc_js($selected_sub_role); ?>";

    function populateSubRoles(specialization, selected) {
        const roles = subrolesMap[specialization] || [];
        subRoleSelect.innerHTML = '<option value="" disabled>Select sub role</option>';

        roles.forEach(function(role) {
            const opt = document.createElement('option');
            opt.value = role;
            opt.textContent = role;
            if (role === selected) {
                opt.selected = true;
            }
            subRoleSelect.appendChild(opt);
        });
    }

    // Initial population based on saved specialization
    if (selectedSpecialization) {
        populateSubRoles(selectedSpecialization, selectedSubRole);
    }

    // On specialization change
    specializationSelect.addEventListener('change', function() {
        populateSubRoles(this.value, '');
    });
});
</script>

<?php get_footer(); ?>
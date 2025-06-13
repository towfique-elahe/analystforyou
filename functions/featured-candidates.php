<?php

// Shortcode [featured_candidates ids="id1,id2,id3"]

// Utility: Convert string to color (PHP version)
function string_to_color($str) {
    $hash = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        $hash = ord($str[$i]) + (($hash << 5) - $hash);
    }
    $hue = $hash % 360;
    return "hsl($hue, 70%, 60%)";
}

// Utility: Obfuscate last name
function obfuscate_last_name($name) {
    if (strlen($name) < 2) return '*';
    return strtoupper($name[0]) . '**' . strtoupper($name[strlen($name) - 1]);
}

// Utility: Function to get language flags
function get_language_flags($languages) {
    $language_flags = [
        'English' => '🇬🇧',
        'Dutch' => '🇳🇱',
        'Spanish' => '🇪🇸',
        'French' => '🇫🇷',
        'German' => '🇩🇪',
        'Italian' => '🇮🇹',
        'Portuguese' => '🇵🇹',
        'Russian' => '🇷🇺',
        'Chinese' => '🇨🇳',
        'Japanese' => '🇯🇵',
        'Arabic' => '🇸🇦',
        'Hindi' => '🇮🇳',
        'Swedish' => '🇸🇪',
        'Turkish' => '🇹🇷',
        'Korean' => '🇰🇷',
        'Polish' => '🇵🇱',
        'Finnish' => '🇫🇮',
        'Norwegian' => '🇳🇴',
        'Ukrainian' => '🇺🇦',
        'Romanian' => '🇷🇴',
        'Hebrew' => '🇮🇱',
        // Add more as needed...
    ];

    $flags = [];

    foreach (explode(',', $languages) as $language) {
        $lang = trim($language);
        if (!empty($language_flags[$lang])) {
            $flags[] = $language_flags[$lang];
        }
    }

    return implode(' ', $flags);
}

// Main shortcode function
function render_featured_candidates($atts) {
    global $wpdb;

    $atts = shortcode_atts([
        'ids' => ''
    ], $atts);

    $ids = array_filter(array_map('intval', explode(',', $atts['ids'])));
    if (empty($ids)) return '<p>No candidate IDs specified.</p>';

    $table = $wpdb->prefix . "candidates";
    $placeholders = implode(',', array_fill(0, count($ids), '%d'));
    $query = "SELECT * FROM $table WHERE id IN ($placeholders) AND status = 'Approved'";
    $results = $wpdb->get_results($wpdb->prepare($query, ...$ids), ARRAY_A);

    if (!$results) return '<p>No matching candidates found.</p>';

    ob_start();
    echo '<div class="featured-candidates">';

    foreach ($results as $candidate) {
        $firstInitial = strtoupper(substr($candidate['first_name'], 0, 1));
        $lastObfuscated = obfuscate_last_name($candidate['last_name']);
        $tools = array_map('trim', explode(',', $candidate['lang_tools']));
        $toolBadges = implode('', array_map(fn($tool) => "<span class='tool'>$tool</span>", $tools));

        echo "
        <div class='candidate-card'>
            <div class='row head'>
                <div class='col'>
                    <div class='avatar-container'>
                        <div class='avatar' style='background-color: " . string_to_color($candidate['first_name']) . "'>$firstInitial</div>
                    </div>
                </div>
                <div class='col'>
                    <h4 class='name'>{$candidate['first_name']} $lastObfuscated</h4>
                    <p class='specialization'>{$candidate['specialization']}</p>
                    <div class='flags'>
                        " . get_language_flags($candidate['languages']) . "
                    </div>
                </div>
            </div>
            <div class='body'>
                <div class='tools'>$toolBadges</div>
                <p class='bio'>" . (!empty($candidate['bio']) ? esc_html($candidate['bio']) : "No bio available.") . "</p>
                <p><strong>Sub Role:</strong> {$candidate['sub_role']}</p>
                <p><strong>Experience:</strong> {$candidate['experience']}</p>
                <p><strong>Availability:</strong> {$candidate['availability']}</p>
                <p><strong>Sector:</strong> {$candidate['sector']}</p>
            </div>
        </div>";
    }

    echo '</div>';
    return ob_get_clean();
}
add_shortcode('featured_candidates', 'render_featured_candidates');
<?php

function enqueue_candidate_filter_scripts() {
    wp_enqueue_script('candidate-filter', get_template_directory_uri() . '/assets/js/candidate-filter.js', ['jquery'], null, true);
    wp_localize_script('candidate-filter', 'ajax_vars', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_candidate_filter_scripts');

// AJAX handler
add_action('wp_ajax_filter_candidates', 'handle_candidate_filter');
add_action('wp_ajax_nopriv_filter_candidates', 'handle_candidate_filter');

function handle_candidate_filter() {
    $filters = $_POST['filters'];

    $args = [
        'post_type' => 'candidate',
        'posts_per_page' => -1,
        'tax_query' => ['relation' => 'AND'],
    ];

    // Map keys to real taxonomies
    $taxonomy_map = [
        'skills' => 'skills',
        'experience' => 'years-of-experience',
        'location' => 'location-preference',
        'availability' => 'availability',
        'industry' => 'industry-expertise',
    ];

    foreach ($filters as $key => $terms) {
        if (!empty($terms) && isset($taxonomy_map[$key])) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy_map[$key],
                'field'    => 'slug',
                'terms'    => $terms,
            ];
        }
    }

    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            echo '<div class="candidate">';
            echo '<h4>' . get_the_title() . '</h4>';
            echo '<p>' . get_the_excerpt() . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No candidates found.</p>';
    }

    wp_die();
}

// Generate checkbox filter fields
function get_filter_options($taxonomy) {
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ]);

    $html = '';
    foreach ($terms as $term) {
        $html .= "<label style='display:block;'><input type='checkbox' name='{$taxonomy}[]' value='{$term->slug}'> {$term->name}</label>";
    }
    return $html;
}

function render_candidate_filter_modal() {
    ob_start();
    ?>
<style>
#filter-modal {
    display: none;
    position: fixed;
    top: 5%;
    left: 50%;
    transform: translateX(-50%);
    width: 90%;
    max-width: 600px;
    background: #ffffff;
    z-index: 9999;
    padding: 20px 30px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    border-radius: 10px;
}

#filter-modal h3 {
    margin-bottom: 15px;
    font-size: 1.2rem;
}

#filter-modal label {
    display: block;
    margin-bottom: 8px;
}

#filter-modal button {
    margin-top: 15px;
    padding: 10px 20px;
    background-color: #0073aa;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

#filter-modal .close-modal {
    position: absolute;
    top: 8px;
    right: 12px;
    font-size: 20px;
    color: #333;
    cursor: pointer;
}

#open-filter-btn {
    padding: 10px 20px;
    margin-bottom: 20px;
    background-color: #0073aa;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

@media (max-width: 480px) {
    #filter-modal {
        width: 95%;
        padding: 15px;
    }
}
</style>

<button id="open-filter-btn">🎯 Filter Candidates</button>

<div id="filter-modal">
    <span class="close-modal">❌</span>

    <div class="step step-1">
        <h3>Select Skills</h3>
        <?php echo get_filter_options('skills'); ?>
        <button class="next-step">Next</button>
    </div>
    <div class="step step-2" style="display:none;">
        <h3>Select Experience</h3>
        <?php echo get_filter_options('years-of-experience'); ?>
        <button class="next-step">Next</button>
    </div>
    <div class="step step-3" style="display:none;">
        <h3>Select Location</h3>
        <?php echo get_filter_options('location-preference'); ?>
        <button class="next-step">Next</button>
    </div>
    <div class="step step-4" style="display:none;">
        <h3>Select Availability</h3>
        <?php echo get_filter_options('availability'); ?>
        <button class="next-step">Next</button>
    </div>
    <div class="step step-5" style="display:none;">
        <h3>Select Industry</h3>
        <?php echo get_filter_options('industry-expertise'); ?>
        <button id="apply-filters">Show Candidates</button>
    </div>
</div>

<div id="candidate-list" style="margin-top: 30px;"></div>
<?php
    return ob_get_clean();
}

add_shortcode('candidate_filter_modal', 'render_candidate_filter_modal');
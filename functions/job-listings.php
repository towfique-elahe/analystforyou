<?php

function render_custom_job_listing() {
    ob_start();

    // Fetch filter options
    $skills        = get_terms(['taxonomy' => 'skills', 'hide_empty' => false]);
    $experience    = get_terms(['taxonomy' => 'years-of-experience', 'hide_empty' => false]);
    $location_pref = get_terms(['taxonomy' => 'location-preference', 'hide_empty' => false]);
    $availability  = get_terms(['taxonomy' => 'availability', 'hide_empty' => false]);
    $industry      = get_terms(['taxonomy' => 'industry-expertise', 'hide_empty' => false]);
    ?>
<div id="jobListing" class="custom-job-listing">
    <div class="container">
        <!-- Sidebar Filters -->
        <div class="col sidebar">
            <div class="sidebar-header">
                <h3 class="sidebar-heading">Advance Filter</h3>
                <a href="javascript:void()" class="reset-button">Reset</a>
            </div>
            <div class="filter-group">
                <div class="search">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" name="search" id="search" placeholder="Search jobs...">
                </div>
            </div>
            <?php
                $filters = [
                    'skills' => $skills,
                    'years-of-experience' => $experience,
                    'location-preference' => $location_pref,
                    'availability' => $availability,
                    'industry-expertise' => $industry,
                ];

                foreach ($filters as $key => $terms) {
                    echo '<div class="filter-group">';
                    echo '<h4 class="filter-heading">' . ucwords(str_replace('-', ' ', $key)) . '</h4>';
                    echo '<div class="filter-items">';
                    foreach ($terms as $term) {
                        echo '<div class="filter-item">';
                        echo '<input type="checkbox" class="filter-checkbox" name="' . esc_attr($key) . '[]" value="' . esc_attr($term->slug) . '" id="' . esc_attr($term->slug) . '">';
                        echo '<label for="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</label>';
                        echo '</div>';
                    }
                    echo '</div></div>';
                }
                ?>
        </div>

        <!-- Jobs Content -->
        <div class="col jobs-content">
            <div class="content">
                <!-- header -->
                <div class="row content-header">
                    <div class="col">
                        <p class="showing-text">
                            <!-- Showing <strong>41</strong>-<strong>60</strong> of <strong>944</strong> jobs -->
                        </p>
                    </div>
                    <div class="col">
                        <div class="row">
                            <div class="job-count">
                                Show:
                                <select id="jobs_per_page">
                                    <option value="6">6</option>
                                    <option value="9">9</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="sort-by">
                                Sort by:
                                <select id="sort_order">
                                    <option value="desc">Newest Jobs</option>
                                    <option value="asc">Oldest Jobs</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Job Listings will be inserted here -->
                <div id="job-results" class="jobs"></div>
            </div>
        </div>
    </div>
</div>
<?php
    wp_enqueue_script('job-listing-ajax', plugin_dir_url(__FILE__) . 'job-listing.js', ['jquery'], null, true);
    wp_localize_script('job-listing-ajax', 'job_ajax_obj', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('job_filter_nonce')
    ]);

    return ob_get_clean();
}
add_shortcode('custom_job_listing', 'render_custom_job_listing');

function ajax_filter_jobs() {
    check_ajax_referer('job_filter_nonce', 'nonce');

    $paged          = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $search         = sanitize_text_field($_POST['search']);
    $posts_per_page = intval($_POST['posts_per_page']) ?: 3;
    $order          = sanitize_text_field($_POST['order']) === 'asc' ? 'ASC' : 'DESC';

    $tax_query = [];

    $taxonomies = ['skills', 'years-of-experience', 'location-preference', 'availability', 'industry-expertise'];
    foreach ($taxonomies as $tax) {
        if (!empty($_POST[$tax])) {
            $tax_query[] = [
                'taxonomy' => $tax,
                'field'    => 'slug',
                'terms'    => array_map('sanitize_text_field', $_POST[$tax]),
            ];
        }
    }

    if (count($tax_query) > 1) {
        $tax_query['relation'] = 'AND';
    }

    $args = [
        'post_type'      => 'awsm_job_openings',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'post_status'    => 'publish',
        's'              => $search,
        'order'          => $order,
    ];

    if (!empty($tax_query)) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        echo '<div class="jobs">';
        while ($query->have_posts()) {
            $query->the_post();
            ?>
<div class="job">
    <div class="head">
        <?php if (has_post_thumbnail()) {
        the_post_thumbnail('medium', ['class' => 'featured-image']);
    } ?>
    </div>
    <div class="body">
        <h3><a href="<?php the_permalink(); ?>" class="title">
                <?php the_title(); ?>
            </a></h3>

        <?php
    // Get taxonomies
    $skills        = get_the_terms(get_the_ID(), 'skills');
    $experience    = get_the_terms(get_the_ID(), 'years-of-experience');
    $location_pref = get_the_terms(get_the_ID(), 'location-preference');
    $availability  = get_the_terms(get_the_ID(), 'availability');
    $industry      = get_the_terms(get_the_ID(), 'industry-expertise');

    // Skills display
    if ($skills && !is_wp_error($skills)) {
        echo '<div class="skills">';
        foreach ($skills as $skill) {
            echo '<span class="skill">' . esc_html($skill->name) . '</span>';
        }
        echo '</div>';
    }

    // Specs display
    echo '<div class="specs">';
    if ($experience && !is_wp_error($experience)) {
        echo '<p class="spec"><strong>Experience:</strong> ' . esc_html(join(', ', wp_list_pluck($experience, 'name'))) . '</p>';
    }
    if ($location_pref && !is_wp_error($location_pref)) {
        echo '<p class="spec"><strong>Location Preference:</strong> ' . esc_html(join(', ', wp_list_pluck($location_pref, 'name'))) . '</p>';
    }
    if ($availability && !is_wp_error($availability)) {
        echo '<p class="spec"><strong>Availability:</strong> ' . esc_html(join(', ', wp_list_pluck($availability, 'name'))) . '</p>';
    }
    if ($industry && !is_wp_error($industry)) {
        echo '<p class="spec"><strong>Industry Expertise:</strong> ' . esc_html(join(', ', wp_list_pluck($industry, 'name'))) . '</p>';
    }
    echo '</div>';
    ?>
    </div>
</div>
<?php
        }
        echo '</div>';

        // Pagination
        $total_pages = $query->max_num_pages;
        if ($total_pages > 1) {
            echo '<div class="pagination">';
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<a href="#" class="page ' . ($i == $paged ? 'active' : '') . '" data-page="' . $i . '">' . $i . '</a>';
            }
            echo '</div>';
        }
    } else {
        echo '<p class="jobs-msg">No jobs found.</p>';
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_filter_jobs', 'ajax_filter_jobs');
add_action('wp_ajax_nopriv_filter_jobs', 'ajax_filter_jobs');
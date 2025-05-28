<?php

// Custom job listing section with filter at top
function custom_job_listing_shortcode() {
    ob_start();

    // Fetch dropdown terms for filters
    $skills = get_terms( array( 'taxonomy' => 'skills', 'hide_empty' => false ) );
    $experience = get_terms( array( 'taxonomy' => 'years-of-experience', 'hide_empty' => false ) );
    $location_pref = get_terms( array( 'taxonomy' => 'location-preference', 'hide_empty' => false ) );
    $availability = get_terms( array( 'taxonomy' => 'availability', 'hide_empty' => false ) );
    $industry = get_terms( array( 'taxonomy' => 'industry-expertise', 'hide_empty' => false ) );

    // Handle filtering and pagination
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

    $search_term = isset($_GET['job_search']) ? sanitize_text_field($_GET['job_search']) : '';
    $filters = array(
        'skills' => $_GET['skills'] ?? '',
        'years-of-experience' => $_GET['years-of-experience'] ?? '',
        'location-preference' => $_GET['location-preference'] ?? '',
        'availability' => $_GET['availability'] ?? '',
        'industry-expertise' => $_GET['industry-expertise'] ?? '',
    );

    // Base URL for clear filters
    $base_url = strtok( $_SERVER["REQUEST_URI"], '?' );
    $clear_url = esc_url( home_url( $base_url ) );
    ?>

<form method="get" class="job-filter-form" id="job-filter-form">
    <input type="text" name="job_search" id="job_search" placeholder="Search jobs..."
        value="<?php echo esc_attr($search_term); ?>" />

    <select name="skills">
        <option value="">All Skills</option>
        <?php foreach ( $skills as $term ) : ?>
        <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($filters['skills'], $term->slug); ?>>
            <?php echo esc_html($term->name); ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select name="years-of-experience">
        <option value="">All Experience Levels</option>
        <?php foreach ( $experience as $term ) : ?>
        <option value="<?php echo esc_attr($term->slug); ?>"
            <?php selected($filters['years-of-experience'], $term->slug); ?>>
            <?php echo esc_html($term->name); ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select name="location-preference">
        <option value="">All Locations</option>
        <?php foreach ( $location_pref as $term ) : ?>
        <option value="<?php echo esc_attr($term->slug); ?>"
            <?php selected($filters['location-preference'], $term->slug); ?>>
            <?php echo esc_html($term->name); ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select name="availability">
        <option value="">All Availability</option>
        <?php foreach ( $availability as $term ) : ?>
        <option value="<?php echo esc_attr($term->slug); ?>" <?php selected($filters['availability'], $term->slug); ?>>
            <?php echo esc_html($term->name); ?>
        </option>
        <?php endforeach; ?>
    </select>

    <select name="industry-expertise">
        <option value="">All Industries</option>
        <?php foreach ( $industry as $term ) : ?>
        <option value="<?php echo esc_attr($term->slug); ?>"
            <?php selected($filters['industry-expertise'], $term->slug); ?>>
            <?php echo esc_html($term->name); ?>
        </option>
        <?php endforeach; ?>
    </select>

    <input type="hidden" name="paged" id="paged_input" value="1" />

    <?php if ( array_filter( $filters ) || !empty($search_term) ) : ?>
    <a href="<?php echo $clear_url; ?>" class="clear-filter-button">Clear Filters</a>
    <?php endif; ?>
</form>

<script>
const form = document.getElementById("job-filter-form");
const selects = form.querySelectorAll("select");
const searchInput = document.getElementById("job_search");

selects.forEach(select => {
    select.addEventListener("change", () => {
        document.getElementById("paged_input").value = 1;
        form.submit();
    });
});

searchInput.addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        e.preventDefault();
        document.getElementById("paged_input").value = 1;
        form.submit();
    }
});
</script>

<?php
    // Query args
    $query_args = array(
        'post_type'      => 'awsm_job_openings',
        'posts_per_page' => 3,
        'paged'          => $paged,
        'post_status'    => 'publish',
        's'              => $search_term,
    );

    $tax_query = [];

    foreach ( $filters as $taxonomy => $term_slug ) {
        if ( ! empty( $term_slug ) ) {
            $tax_query[] = array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term_slug,
            );
        }
    }

    if ( ! empty( $tax_query ) ) {
        $query_args['tax_query'] = $tax_query;
    }

    $jobs = new WP_Query( $query_args );

    if ( $jobs->have_posts() ) {
        echo '<div class="job-listings">';
        while ( $jobs->have_posts() ) {
            $jobs->the_post();

            // Taxonomies
            $skills = get_the_terms( get_the_ID(), 'skills' );
            $experience = get_the_terms( get_the_ID(), 'years-of-experience' );
            $location_pref = get_the_terms( get_the_ID(), 'location-preference' );
            $availability = get_the_terms( get_the_ID(), 'availability' );
            $industry = get_the_terms( get_the_ID(), 'industry-expertise' );

            echo '<div class="job-listing">';
            if ( has_post_thumbnail() ) {
                echo '<div class="job-thumbnail">' . get_the_post_thumbnail( get_the_ID(), 'medium' ) . '</div>';
            }

            echo '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';

            if ( $skills && ! is_wp_error( $skills ) ) {
                echo '<div class="skills">';
                foreach ( $skills as $skill ) {
                    echo '<span class="skill">' . esc_html( $skill->name ) . '</span>';
                }
                echo '</div>';
            }
            if ( $experience && ! is_wp_error( $experience ) ) {
                echo '<p><strong>Experience:</strong> ' . esc_html( join( ', ', wp_list_pluck( $experience, 'name' ) ) ) . '</p>';
            }
            if ( $location_pref && ! is_wp_error( $location_pref ) ) {
                echo '<p><strong>Location Preference:</strong> ' . esc_html( join( ', ', wp_list_pluck( $location_pref, 'name' ) ) ) . '</p>';
            }
            if ( $availability && ! is_wp_error( $availability ) ) {
                echo '<p><strong>Availability:</strong> ' . esc_html( join( ', ', wp_list_pluck( $availability, 'name' ) ) ) . '</p>';
            }
            if ( $industry && ! is_wp_error( $industry ) ) {
                echo '<p><strong>Industry Expertise:</strong> ' . esc_html( join( ', ', wp_list_pluck( $industry, 'name' ) ) ) . '</p>';
            }

            echo '</div>';
        }
        echo '</div>';

        // Pagination with filter persistence
        echo '<div class="pagination">';
        echo paginate_links( array(
            'total'     => $jobs->max_num_pages,
            'current'   => $paged,
            'format'    => '?paged=%#%',
            'add_args'  => $_GET,
            'prev_text' => '« Prev',
            'next_text' => 'Next »',
        ) );
        echo '</div>';
    } else {
        echo '<p>No jobs found matching your criteria.</p>';
    }

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'custom_job_listing', 'custom_job_listing_shortcode' );
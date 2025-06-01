jQuery(document).ready(function ($) {
    function fetchJobs(page = 1) {
        let filters = {
            action: 'filter_jobs',
            nonce: job_ajax_obj.nonce,
            search: $('#search').val(),
            paged: page,
            posts_per_page: $('#jobs_per_page').val(),
            order: $('#sort_order').val()
        };

        // Gather all checked filters
        $('.filter-checkbox:checked').each(function () {
            const name = $(this).attr('name').replace('[]', '');
            if (!filters[name]) filters[name] = [];
            filters[name].push($(this).val());
        });

        $.ajax({
            url: job_ajax_obj.ajax_url,
            type: 'POST',
            data: filters,
            beforeSend: function () {
                $('#job-results').html('<p class="jobs-msg">Loading jobs...</p>');
            },
            success: function (res) {
                $('#job-results').html(res);
            }
        });
    }

    // Trigger events
    $('#search, #jobs_per_page, #sort_order').on('change keyup', function () {
        fetchJobs();
    });

    $('.filter-checkbox').on('change', function () {
        fetchJobs();
    });

    $('#job-results').on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).data('page');
        fetchJobs(page);
    });

    $('.reset-button').on('click', function (e) {
        e.preventDefault();
        $('input[type="checkbox"]').prop('checked', false);
        $('#search').val('');
        fetchJobs();
    });

    // Initial load
    fetchJobs();
});

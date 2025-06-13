jQuery(document).ready(function ($) {
    $(document).on('click', '[data-request-job]', function () {
        $('#job-request-modal').fadeIn();
    });

    $('.close-modal, .modal-overlay').on('click', function () {
        $('#job-request-modal').fadeOut();
    });

    $('#job-request-form').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const loader = form.find('.loader');
        const confirm = form.find('.confirmation');

        loader.show();

        $.post(jobRequestAjax.ajax_url, {
            action: 'submit_job_request',
            nonce: jobRequestAjax.nonce,
            form_data: form.serializeArray()
        }, function (res) {
            loader.hide();
            if (res.success) {
                confirm.show();
                form.trigger('reset');
            } else {
                console.error(res);
                alert('Error submitting the form.');
            }
        });
    });
});

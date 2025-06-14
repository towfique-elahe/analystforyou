jQuery(document).ready(function ($) {
    // Open modal
    $(document).on('click', '[data-request-job]', function () {
        $('body').addClass('modal-open');
        $('#job-request-modal').fadeIn();
    });

    // Close modal
    $('.close-modal, .modal-overlay').on('click', function () {
        $('body').removeClass('modal-open');
        $('#job-request-modal').fadeOut();
    });

    // Form submission
    $('#job-request-form').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const loader = form.find('.loader');
        const confirm = form.find('.confirmation');

        loader.show();
        confirm.hide();

        const formData = form.serializeArray();

        $.ajax({
            url: jobRequestAjax.ajax_url,
            type: 'POST',
            data: {
                action: 'submit_job_request',
                nonce: jobRequestAjax.nonce,
                form_data: formData
            },
            success: function (response) {
                loader.hide();
                if (response.success) {
                    confirm.fadeIn();
                    form.trigger('reset');

                    // Auto-hide confirmation and close modal after 3s
                    setTimeout(() => {
                        confirm.fadeOut();
                        $('body').removeClass('modal-open');
                        $('#job-request-modal').fadeOut();
                    }, 3000);
                } else {
                    alert('Submission failed. Please try again.');
                }
            },
            error: function () {
                loader.hide();
                alert('Server error. Please try again later.');
            }
        });
    });
});

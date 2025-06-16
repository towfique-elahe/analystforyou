document.addEventListener('DOMContentLoaded', function () {
    const coverLetter = document.getElementById('awsm-cover-letter');
    if (coverLetter) {
        coverLetter.removeAttribute('required');
        coverLetter.removeAttribute('aria-required');

        // Remove validation error message if visible
        const errorDiv = document.getElementById('awsm-cover-letter-error');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }

        // Remove asterisk from label
        const label = document.querySelector("label[for='awsm-cover-letter']");
        if (label) {
            label.innerHTML = "Cover Letter";
        }
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('awsm-application-form');
    const coverLetter = document.getElementById('awsm-cover-letter');

    if (form && coverLetter) {
        form.addEventListener('submit', function () {
            if (coverLetter.value.trim() === '') {
                // Set a dummy value to pass backend validation
                coverLetter.value = 'No Cover Letter Provided';
            }
        });
    }
});
